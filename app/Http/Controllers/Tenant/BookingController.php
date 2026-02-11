<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function __construct()
    {
        // لا نطبق middleware auth هنا لأننا نريد السماح للزوار أيضاً
    }

    // حجز المعاينة للزوار (بدون تسجيل دخول)
    public function createGuest(Property $property)
    {
        if ($property->admin_status !== 'approved') {
            abort(404);
        }

        // جلب المحافظ من الأدمن (المحافظ العامة للنظام)
        $wallets = Wallet::where('is_active', true)
            ->whereHas('user', function($query) {
                $query->where('role', 'admin');
            })
            ->get();
        
        // جلب سعر المعاينة من الإعدادات
        $inspectionFee = Setting::getInspectionFee();
        
        // جلب نسبة الحجز حسب نوع السعر
        $reservationPercentage = Setting::getReservationPercentageByType($property->price_type);
        
        // إذا لم توجد محافظ، نعرض رسالة تحذيرية في الصفحة بدلاً من redirect
        // حتى يتمكن المستخدم من رؤية الصفحة على الأقل

        return view('tenant.booking.create-guest', compact('property', 'wallets', 'inspectionFee', 'reservationPercentage'));
    }

    public function storeGuest(Request $request, Property $property)
    {
        try {
            // التحقق من أن الوحدة معتمدة
            if ($property->admin_status !== 'approved') {
                return redirect()->back()
                    ->with('error', 'لا يمكن حجز وحدة غير معتمدة')
                    ->withInput();
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'inspection_date' => 'required|date|after:today',
                'inspection_time' => 'required',
                'booking_type' => 'required|in:inspection,reservation',
                'wallet_id' => 'required|exists:wallets,id',
                'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            // البحث عن مستخدم موجود بالبريد أو إنشاء حساب جديد
            $user = User::where('email', $validated['email'])->first();
            
            if (!$user) {
                // إنشاء حساب جديد للزائر
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'password' => Hash::make(uniqid()), // كلمة مرور عشوائية
                    'role' => 'tenant',
                ]);
            } else {
                // تحديث بيانات المستخدم إذا كانت مختلفة
                $user->update([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                ]);
            }

            // تسجيل دخول تلقائي للمستخدم
            Auth::login($user);

            // حساب المبلغ حسب نوع الحجز
            $inspectionFee = Setting::getInspectionFee();
            
            if ($validated['booking_type'] === 'reservation') {
                // التحقق من وجود نوع السعر والسعر
                if (!$property->price_type) {
                    try {
                        Log::error('Property price_type is missing in storeGuest', ['property_id' => $property->id]);
                    } catch (\Exception $e) {
                        // تجاهل أخطاء التسجيل
                    }
                    return redirect()->back()
                        ->with('error', 'نوع السعر غير محدد للوحدة. يرجى التواصل مع الإدارة.')
                        ->withInput();
                }
                
                if (!$property->price || $property->price <= 0) {
                    try {
                        Log::error('Property price is missing or invalid in storeGuest', ['property_id' => $property->id, 'price' => $property->price]);
                    } catch (\Exception $e) {
                        // تجاهل أخطاء التسجيل
                    }
                    return redirect()->back()
                        ->with('error', 'سعر الوحدة غير محدد. يرجى التواصل مع الإدارة.')
                        ->withInput();
                }
                
                // الحصول على نسبة الحجز حسب نوع السعر
                $reservationPercentage = Setting::getReservationPercentageByType($property->price_type) / 100;
                $amount = $property->price * $reservationPercentage;
            } else {
                $amount = $inspectionFee;
            }

            // التحقق من أن المبلغ صحيح
            if (!$amount || $amount <= 0) {
                try {
                    Log::error('Invalid booking amount calculated in storeGuest', [
                        'property_id' => $property->id,
                        'booking_type' => $validated['booking_type'],
                        'amount' => $amount
                    ]);
                } catch (\Exception $e) {
                    // تجاهل أخطاء التسجيل
                }
                return redirect()->back()
                    ->with('error', 'حدث خطأ في حساب المبلغ. يرجى المحاولة مرة أخرى.')
                    ->withInput();
            }

            // إنشاء الحجز مباشرة
            $nextId = Booking::max('id') ? Booking::max('id') + 1 : 1;
            $booking = Booking::create([
                'property_id' => $property->id,
                'user_id' => $user->id,
                'inspection_date' => $validated['inspection_date'],
                'inspection_time' => $validated['inspection_time'],
                'booking_type' => $validated['booking_type'],
                'amount' => $amount,
                'payment_status' => 'pending',
                'status' => 'pending',
                'booking_code' => 'BOOK-' . str_pad($nextId, 8, '0', STR_PAD_LEFT),
            ]);

            // التحقق من أن المحفظة تخص الأدمن (المحافظ العامة للنظام)
            $wallet = Wallet::with('user')->findOrFail($validated['wallet_id']);
            if (!$wallet->user || $wallet->user->role !== 'admin') {
                return redirect()->back()->with('error', 'المحفظة المختارة غير صحيحة')->withInput();
            }

            // رفع الإيصال مع معالجة الأخطاء
            try {
                $receiptPath = $request->file('receipt')->store('receipts/' . $booking->id, 'public');
            } catch (\Exception $e) {
                // إذا فشل رفع الملف، نحاول إنشاء المجلد أولاً
                $receiptDir = storage_path('app/public/receipts/' . $booking->id);
                if (!is_dir($receiptDir)) {
                    @mkdir($receiptDir, 0755, true);
                }
                $receiptPath = $request->file('receipt')->store('receipts/' . $booking->id, 'public');
            }

            // تحديد نوع الدفع
            $paymentType = $validated['booking_type'] === 'inspection' ? 'inspection_fee' : 'reservation_fee';

            // إنشاء سجل الدفع
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $user->id,
                'wallet_id' => $validated['wallet_id'],
                'payment_type' => $paymentType,
                'amount' => $amount,
                'payment_method' => 'bank_transfer',
                'status' => 'pending',
                'receipt_path' => $receiptPath,
                'review_status' => 'pending',
                'payment_date' => now(),
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'تم إرسال طلب الحجز والدفع بنجاح. سيتم مراجعة الإيصال والتأكيد قريباً.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            try {
                Log::error('Validation failed in storeGuest', [
                    'errors' => $e->errors(),
                    'property_id' => $property->id,
                ]);
            } catch (\Exception $logError) {
                // تجاهل أخطاء التسجيل
            }
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // محاولة تسجيل الخطأ
            try {
                Log::error('Store guest booking error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'property_id' => $property->id ?? null,
                ]);
            } catch (\Exception $logError) {
                // إذا فشل التسجيل، نستخدم طريقة بديلة
                error_log('Store guest booking error: ' . $e->getMessage());
            }
            
            // رسالة خطأ آمنة للمستخدم
            $errorMessage = config('app.debug') 
                ? 'حدث خطأ أثناء إنشاء الحجز: ' . $e->getMessage()
                : 'حدث خطأ أثناء إنشاء الحجز. يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني.';
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    // حجز المعاينة للمستخدمين المسجلين
    public function create(Property $property)
    {
        if (!Auth::check()) {
            return redirect()->route('inspection.create', $property);
        }

        if ($property->admin_status !== 'approved') {
            abort(404);
        }

        // جلب سعر المعاينة من الإعدادات
        $inspectionFee = Setting::getInspectionFee();
        
        // جلب نسبة الحجز حسب نوع السعر
        $reservationPercentage = Setting::getReservationPercentageByType($property->price_type);

        return view('tenant.booking.create', compact('property', 'inspectionFee', 'reservationPercentage'));
    }

    public function store(Request $request, Property $property)
    {
        if (!Auth::check()) {
            return redirect()->route('inspection.create', $property);
        }

        try {
            // التحقق من أن الوحدة معتمدة
            if ($property->admin_status !== 'approved') {
                return redirect()->back()
                    ->with('error', 'لا يمكن حجز وحدة غير معتمدة')
                    ->withInput();
            }

            // حساب التاريخ بشكل آمن
            try {
                $tomorrow = date('Y-m-d', strtotime('+1 day'));
            } catch (\Exception $e) {
                $tomorrow = date('Y-m-d', strtotime('tomorrow'));
            }
            
            // تسجيل البيانات الواردة للتشخيص (مع معالجة الأخطاء)
            try {
                Log::info('Booking store request', [
                    'property_id' => $property->id,
                    'user_id' => Auth::id(),
                    'inspection_date' => $request->input('inspection_date'),
                    'inspection_time' => $request->input('inspection_time'),
                    'booking_type' => $request->input('booking_type'),
                ]);
            } catch (\Exception $e) {
                // تجاهل أخطاء التسجيل إذا كان هناك مشكلة في الصلاحيات
            }
            
            // التحقق من أن البيانات موجودة
            if (!$request->has('inspection_date') || !$request->has('inspection_time') || !$request->has('booking_type')) {
                try {
                    Log::error('Missing required fields', [
                        'has_inspection_date' => $request->has('inspection_date'),
                        'has_inspection_time' => $request->has('inspection_time'),
                        'has_booking_type' => $request->has('booking_type'),
                    ]);
                } catch (\Exception $e) {
                    // تجاهل أخطاء التسجيل
                }
                return redirect()->back()
                    ->with('error', 'يرجى ملء جميع الحقول المطلوبة')
                    ->withInput();
            }
            
            $validated = $request->validate([
                'inspection_date' => ['required', 'date', 'after_or_equal:' . $tomorrow],
                'inspection_time' => ['required', 'string'],
                'booking_type' => ['required', 'in:inspection,reservation'],
            ], [
                'inspection_date.required' => 'يرجى اختيار تاريخ المعاينة',
                'inspection_date.date' => 'تاريخ المعاينة غير صحيح',
                'inspection_date.after_or_equal' => 'يجب أن يكون تاريخ المعاينة من الغد (' . date('d/m/Y', strtotime($tomorrow)) . ') فصاعداً',
                'inspection_time.required' => 'يرجى اختيار وقت المعاينة',
                'booking_type.required' => 'يرجى اختيار نوع الحجز',
                'booking_type.in' => 'نوع الحجز المحدد غير صحيح',
            ]);
            
            try {
                Log::info('Validation passed', ['validated' => $validated]);
            } catch (\Exception $e) {
                // تجاهل أخطاء التسجيل
            }

            // حساب المبلغ حسب نوع الحجز
            $inspectionFee = Setting::getInspectionFee();
            
            if ($validated['booking_type'] === 'reservation') {
                // التحقق من وجود نوع السعر والسعر
                if (!$property->price_type) {
                    try {
                        Log::error('Property price_type is missing', ['property_id' => $property->id]);
                    } catch (\Exception $e) {
                        // تجاهل أخطاء التسجيل
                    }
                    return redirect()->back()
                        ->with('error', 'نوع السعر غير محدد للوحدة. يرجى التواصل مع الإدارة.')
                        ->withInput();
                }
                
                if (!$property->price || $property->price <= 0) {
                    try {
                        Log::error('Property price is missing or invalid', ['property_id' => $property->id, 'price' => $property->price]);
                    } catch (\Exception $e) {
                        // تجاهل أخطاء التسجيل
                    }
                    return redirect()->back()
                        ->with('error', 'سعر الوحدة غير محدد. يرجى التواصل مع الإدارة.')
                        ->withInput();
                }
                
                // الحصول على نسبة الحجز حسب نوع السعر
                $reservationPercentage = Setting::getReservationPercentageByType($property->price_type) / 100;
                $amount = $property->price * $reservationPercentage;
            } else {
                $amount = $inspectionFee;
            }

            // التحقق من أن المبلغ صحيح
            if (!$amount || $amount <= 0) {
                try {
                    Log::error('Invalid booking amount calculated', [
                        'property_id' => $property->id,
                        'booking_type' => $validated['booking_type'],
                        'amount' => $amount
                    ]);
                } catch (\Exception $e) {
                    // تجاهل أخطاء التسجيل
                }
                return redirect()->back()
                    ->with('error', 'حدث خطأ في حساب المبلغ. يرجى المحاولة مرة أخرى.')
                    ->withInput();
            }

            // إنشاء الحجز مباشرة
            $nextId = Booking::max('id') ? Booking::max('id') + 1 : 1;
            
            try {
                Log::info('Creating booking', [
                    'property_id' => $property->id,
                    'user_id' => Auth::id(),
                    'amount' => $amount,
                    'booking_type' => $validated['booking_type'],
                ]);
            } catch (\Exception $e) {
                // تجاهل أخطاء التسجيل
            }
            
            $booking = Booking::create([
                'property_id' => $property->id,
                'user_id' => Auth::id(),
                'inspection_date' => $validated['inspection_date'],
                'inspection_time' => $validated['inspection_time'],
                'booking_type' => $validated['booking_type'],
                'amount' => $amount,
                'payment_status' => 'pending',
                'status' => 'pending',
                'booking_code' => 'BOOK-' . str_pad($nextId, 8, '0', STR_PAD_LEFT),
            ]);

            try {
                Log::info('Booking created successfully', ['booking_id' => $booking->id]);
            } catch (\Exception $e) {
                // تجاهل أخطاء التسجيل
            }

            // التحويل لصفحة الدفع
            return redirect()->route('tenant.booking.payment', $booking)
                ->with('info', 'يرجى إتمام عملية الدفع ورفع الإيصال لإكمال الحجز');
        } catch (\Illuminate\Validation\ValidationException $e) {
            try {
                Log::error('Validation failed', [
                    'errors' => $e->errors(),
                    'property_id' => $property->id,
                    'user_id' => Auth::id(),
                ]);
            } catch (\Exception $logError) {
                // تجاهل أخطاء التسجيل
            }
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // محاولة تسجيل الخطأ
            try {
                Log::error('Booking store error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'property_id' => $property->id ?? null,
                    'user_id' => Auth::id(),
                ]);
            } catch (\Exception $logError) {
                // إذا فشل التسجيل، نستخدم طريقة بديلة
                error_log('Booking store error: ' . $e->getMessage());
            }
            
            // رسالة خطأ آمنة للمستخدم (لا نعرض تفاصيل الخطأ في الإنتاج)
            $errorMessage = config('app.debug') 
                ? 'حدث خطأ أثناء إنشاء الحجز: ' . $e->getMessage()
                : 'حدث خطأ أثناء إنشاء الحجز. يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني.';
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    public function payment(Booking $booking)
    {
        try {
            // التحقق من أن المستخدم مسجل دخول
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول للوصول إلى صفحة الدفع');
            }

            // التحقق من أن الحجز يخص المستخدم الحالي
            if ($booking->user_id !== Auth::id()) {
                abort(403);
            }

            // جلب المحافظ من الأدمن (المحافظ العامة للنظام)
            $wallets = Wallet::where('is_active', true)
                ->whereHas('user', function($query) {
                    $query->where('role', 'admin');
                })
                ->get();
            
            if ($wallets->isEmpty()) {
                return redirect()->route('tenant.booking.create', $booking->property)
                    ->with('error', 'لا توجد محافظ متاحة للدفع. يرجى التواصل مع الإدارة.');
            }

            return view('tenant.booking.payment', compact('booking', 'wallets'));
        } catch (\Exception $e) {
            \Log::error('Booking payment error: ' . $e->getMessage());
            return redirect()->route('tenant.booking.create', $booking->property)
                ->with('error', 'حدث خطأ أثناء تحميل صفحة الدفع. يرجى المحاولة مرة أخرى.');
        }
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        try {
            if ($booking->user_id !== Auth::id()) {
                abort(403);
            }

            $validated = $request->validate([
                'wallet_id' => 'required|exists:wallets,id',
                'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            // التحقق من أن المحفظة تخص الأدمن (المحافظ العامة للنظام)
            $wallet = Wallet::with('user')->findOrFail($validated['wallet_id']);
            
            if (!$wallet->user || $wallet->user->role !== 'admin') {
                return redirect()->back()->with('error', 'المحفظة المختارة غير صحيحة');
            }

            // التحقق من وجود المبلغ
            if (!$booking->amount || $booking->amount <= 0) {
                return redirect()->back()->with('error', 'المبلغ غير صحيح. يرجى المحاولة مرة أخرى.');
            }

            // رفع الإيصال مع معالجة الأخطاء
            try {
                $receiptPath = $request->file('receipt')->store('receipts/' . $booking->id, 'public');
            } catch (\Exception $e) {
                // إذا فشل رفع الملف، نحاول إنشاء المجلد أولاً
                $receiptDir = storage_path('app/public/receipts/' . $booking->id);
                if (!is_dir($receiptDir)) {
                    @mkdir($receiptDir, 0755, true);
                }
                $receiptPath = $request->file('receipt')->store('receipts/' . $booking->id, 'public');
            }

            // تحديد نوع الدفع
            $paymentType = $booking->booking_type === 'inspection' ? 'inspection_fee' : 'reservation_fee';

            // إنشاء سجل الدفع
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'wallet_id' => $validated['wallet_id'],
                'payment_type' => $paymentType,
                'amount' => $booking->amount,
                'payment_method' => 'bank_transfer',
                'status' => 'pending',
                'receipt_path' => $receiptPath,
                'review_status' => 'pending',
                'payment_date' => now(),
            ]);

            // تحديث حالة الحجز
            $booking->update([
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'تم إرسال طلب الدفع بنجاح. سيتم مراجعة الإيصال والتأكيد قريباً.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Confirm payment error: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تأكيد الدفع. يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني.')
                ->withInput();
        }
    }

    // حجز الغرفة
    public function createRoomBooking(Property $property, Room $room)
    {
        try {
            // منع حجز غرفة من وحدة غير معتمدة أو مرفوضة
            if ($property->admin_status !== 'approved') {
                if ($property->admin_status === 'rejected') {
                    return redirect()->route('property.show', $property)
                        ->with('error', 'لا يمكن حجز غرفة من وحدة مرفوضة');
                }
                abort(404);
            }

            if ($room->property_id !== $property->id) {
                abort(404);
            }

            // التأكد من وجود room_name و room_number
            if (empty($room->room_name)) {
                $room->room_name = "غرفة " . ($room->room_number ?: $room->id);
                $room->save();
            }
            
            if (empty($room->room_number)) {
                $room->room_number = (string) $room->id;
                $room->save();
            }

            if (!$room->is_available) {
                return redirect()->route('property.show', $property)
                    ->with('error', 'هذه الغرفة غير متاحة للحجز حالياً');
            }

            return view('tenant.booking.room-booking', compact('property', 'room'));
        } catch (\Exception $e) {
            \Log::error('Room booking create error: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return redirect()->route('property.show', $property)
                ->with('error', 'حدث خطأ أثناء تحميل صفحة الحجز. يرجى المحاولة مرة أخرى.');
        }
    }

    public function storeRoomBooking(Request $request, Property $property, Room $room)
    {
        try {
            // Log request data for debugging
            \Log::info('Room booking request', [
                'property_id' => $property->id,
                'room_id' => $room->id,
                'booking_type' => $request->input('booking_type'),
                'check_in_date' => $request->input('check_in_date'),
                'check_out_date' => $request->input('check_out_date'),
                'inspection_date' => $request->input('inspection_date'),
                'inspection_time' => $request->input('inspection_time'),
            ]);
            
            // منع حجز غرفة من وحدة غير معتمدة أو مرفوضة
            if ($property->admin_status !== 'approved') {
                if ($property->admin_status === 'rejected') {
                    return redirect()->route('property.show', $property)
                        ->with('error', 'لا يمكن حجز غرفة من وحدة مرفوضة');
                }
                abort(404);
            }

            if ($room->property_id !== $property->id) {
                abort(404);
            }

            // التأكد من وجود room_name و room_number
            if (empty($room->room_name)) {
                $room->room_name = "غرفة " . ($room->room_number ?: $room->id);
                $room->save();
            }
            
            if (empty($room->room_number)) {
                $room->room_number = (string) $room->id;
                $room->save();
            }

            if (!$room->is_available) {
                return redirect()->back()->with('error', 'هذه الغرفة غير متاحة للحجز حالياً');
            }

        // Validation rules based on booking type
        $bookingType = $request->input('booking_type');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        
        if ($bookingType === 'inspection') {
            $validated = $request->validate([
                'booking_type' => 'required|in:inspection,reservation',
                'inspection_date' => ['required', 'date', 'after_or_equal:' . $tomorrow],
                'inspection_time' => 'required|string',
                'name' => 'required_if:guest,1|nullable|string|max:255',
                'email' => 'required_if:guest,1|nullable|email|max:255',
                'phone' => 'required_if:guest,1|nullable|string|max:20',
                'guest' => 'nullable|boolean',
            ], [
                'inspection_date.required' => 'يرجى اختيار تاريخ المعاينة',
                'inspection_date.after_or_equal' => 'يجب أن يكون تاريخ المعاينة من الغد فصاعداً',
                'inspection_time.required' => 'يرجى اختيار وقت المعاينة',
            ]);
            
            // استخدام inspection_date كـ check_in_date للتوافق
            $validated['check_in_date'] = $validated['inspection_date'];
            $validated['check_out_date'] = null;
        } else {
            // حجز نهائي - التحقق من check_in_date فقط
            $validationRules = [
                'booking_type' => 'required|in:inspection,reservation',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'nullable|date|after:check_in_date',
                'name' => 'required_if:guest,1|nullable|string|max:255',
                'email' => 'required_if:guest,1|nullable|email|max:255',
                'phone' => 'required_if:guest,1|nullable|string|max:20',
                'guest' => 'nullable|boolean',
            ];
            
            $validationMessages = [
                'check_in_date.required' => 'يرجى اختيار تاريخ الدخول',
                'check_in_date.after_or_equal' => 'يجب أن يكون تاريخ الدخول من اليوم فصاعداً',
                'check_out_date.after' => 'يجب أن يكون تاريخ الخروج بعد تاريخ الدخول',
            ];
            
            $validated = $request->validate($validationRules, $validationMessages);
        }

        // البحث عن مستخدم أو إنشاء حساب جديد للزوار
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            if (!$request->has('guest') || !$request->guest) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول لحجز الغرفة');
            }

            $user = User::where('email', $validated['email'])->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'password' => Hash::make(uniqid()),
                    'role' => 'tenant',
                ]);
            } else {
                $user->update([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                ]);
            }
            
            Auth::login($user);
        }

        // حساب المبلغ
        $inspectionFee = Setting::getInspectionFee();
        
        if ($validated['booking_type'] === 'reservation') {
            $reservationPercentage = Setting::getReservationPercentageByType($room->price_type) / 100;
            $amount = $room->price * $reservationPercentage;
        } else {
            $amount = $inspectionFee;
        }

        // إنشاء Booking فقط (يحتوي على room_id للغرف)
        $nextId = Booking::max('id') ? Booking::max('id') + 1 : 1;
        $booking = Booking::create([
            'property_id' => $property->id,
            'user_id' => $user->id,
            'room_id' => $room->id,
            'inspection_date' => $validated['check_in_date'],
            'inspection_time' => ($validated['booking_type'] === 'inspection' && isset($validated['inspection_time'])) 
                ? $validated['inspection_time'] 
                : '00:00',
            'booking_type' => $validated['booking_type'],
            'amount' => $amount,
            'payment_status' => 'pending',
            'status' => 'pending',
            'booking_code' => 'BOOK-' . str_pad($nextId, 8, '0', STR_PAD_LEFT),
        ]);

        // تحديث حالة الغرفة إذا كان حجز نهائي
        if ($validated['booking_type'] === 'reservation') {
            $room->update(['is_available' => false]);
        }

        // التحويل لصفحة الدفع
        return redirect()->route('tenant.booking.payment', $booking)
            ->with('info', 'يرجى إتمام عملية الدفع ورفع الإيصال لإكمال حجز الغرفة');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Room booking validation failed', [
                'property_id' => $property->id,
                'room_id' => $room->id,
                'errors' => $e->errors(),
                'input' => $request->except(['password'])
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Room booking store error', [
                'property_id' => $property->id,
                'room_id' => $room->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الحجز. يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني.')
                ->withInput();
        }
    }
}
