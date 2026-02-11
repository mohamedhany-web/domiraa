<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Setting;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function properties(Request $request)
    {
        $query = Property::with('user', 'images');
        
        if ($request->has('status') && $request->status) {
            $query->where('admin_status', $request->status);
        }
        
        if ($request->has('property_type_id') && $request->property_type_id) {
            $query->where('property_type_id', $request->property_type_id);
        }
        
        $properties = $query->latest()->get();
        $totalProperties = Property::count();
        $approvedProperties = Property::where('admin_status', 'approved')->count();
        $pendingProperties = Property::where('admin_status', 'pending')->count();
        $rejectedProperties = Property::where('admin_status', 'rejected')->count();
        
        return view('admin.properties.index', compact('properties', 'totalProperties', 'approvedProperties', 'pendingProperties', 'rejectedProperties'));
    }
    
    public function editProperty(Property $property)
    {
        $property->load('user', 'images');
        return view('admin.properties.edit', compact('property'));
    }
    
    public function updateProperty(Request $request, Property $property)
    {
        $validated = $request->validate([
            'property_type_id' => 'required|exists:property_types,id',
            'address' => 'required|string|max:500',
            'location_lat' => 'nullable|string',
            'location_lng' => 'nullable|string',
            'status' => 'required|in:furnished,unfurnished',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|in:daily,monthly,yearly',
            'contract_duration' => 'nullable|integer|min:1',
            'contract_duration_type' => 'nullable|in:daily,weekly,monthly,yearly',
            'annual_increase' => 'nullable|numeric|min:0|max:100',
            'video_url' => 'nullable|url',
            'special_requirements' => 'nullable|string',
            'admin_status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);
        
        $property->update($validated);
        
        return redirect()->route('admin.properties')
            ->with('success', 'تم تحديث الوحدة بنجاح');
    }
    
    public function destroyProperty(Property $property)
    {
        // حذف الصور
        foreach ($property->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }
        
        // حذف ملف إثبات الملكية
        if ($property->ownership_proof && Storage::disk('public')->exists($property->ownership_proof)) {
            Storage::disk('public')->delete($property->ownership_proof);
        }
        
        $property->delete();
        
        return redirect()->route('admin.properties')
            ->with('success', 'تم حذف الوحدة بنجاح');
    }

    /**
     * Show the form for creating a new property
     */
    public function createProperty()
    {
        $owners = User::where('role', 'owner')->get();
        return view('admin.properties.create', compact('owners'));
    }

    /**
     * Store a newly created property
     */
    public function storeProperty(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'property_type_id' => 'required|exists:property_types,id',
            'address' => 'required|string|max:500',
            'location_lat' => 'nullable|string',
            'location_lng' => 'nullable|string',
            'status' => 'required|in:furnished,unfurnished',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|in:daily,monthly,yearly',
            'contract_duration' => 'nullable|integer|min:1',
            'contract_duration_type' => 'nullable|in:daily,weekly,monthly,yearly',
            'annual_increase' => 'nullable|numeric|min:0|max:100',
            'video_url' => 'nullable|url',
            'special_requirements' => 'nullable|string',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
            'area' => 'nullable|integer|min:1',
            'rooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floor' => 'nullable|string|max:100',
            'amenities' => 'nullable|array',
            'admin_status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        // Handle amenities
        if ($request->has('amenities')) {
            $validated['amenities'] = $request->amenities;
        }

        // Admin approved properties don't need ownership proof
        $validated['ownership_proof'] = null;

        $property = Property::create($validated);

        // Upload images using ImageService
        if ($request->hasFile('images')) {
            $imageService = new ImageService();
            
            foreach ($request->file('images') as $index => $image) {
                $result = $imageService->upload($image, 'properties', true);
                
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $result['path'],
                    'thumbnail_path' => $result['thumbnail'],
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.properties')
            ->with('success', 'تم إضافة الوحدة بنجاح');
    }

    public function reviewProperty(Property $property)
    {
        $property->load('user', 'images', 'rooms');
        return view('admin.properties.review', compact('property'));
    }

    public function approveProperty(Request $request, Property $property)
    {
        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $property->update([
            'admin_status' => 'approved',
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.properties')
            ->with('success', 'تم الموافقة على الوحدة بنجاح');
    }

    public function rejectProperty(Request $request, Property $property)
    {
        try {
            $request->validate([
                'rejection_reason' => 'required|string|min:10',
            ], [
                'rejection_reason.required' => 'يجب كتابة سبب الرفض',
                'rejection_reason.min' => 'سبب الرفض يجب أن يكون على الأقل 10 أحرف',
            ]);

            DB::beginTransaction();

            $updateData = [
                'admin_status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'admin_notes' => $request->rejection_reason, // حفظ في admin_notes أيضاً للتوافق
            ];

            $property->update($updateData);

            DB::commit();

            Log::info('Property rejected successfully', [
                'property_id' => $property->id,
                'rejection_reason' => $request->rejection_reason
            ]);

            return redirect()->route('admin.properties')
                ->with('success', 'تم رفض الوحدة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::warning('Property rejection validation failed', [
                'property_id' => $property->id,
                'errors' => $e->errors()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting property', [
                'property_id' => $property->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء رفض الوحدة. يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني.')
                ->withInput();
        }
    }
    
    public function users()
    {
        $users = \App\Models\User::withCount(['properties', 'bookings'])->latest()->get();
        $owners = \App\Models\User::where('role', 'owner')->count();
        $tenants = \App\Models\User::where('role', 'tenant')->count();
        $admins = \App\Models\User::where('role', 'admin')->count();
        
        return view('admin.users.index', compact('users', 'owners', 'tenants', 'admins'));
    }
    
    public function createUser()
    {
        return view('admin.users.create');
    }
    
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,owner,tenant',
        ]);
        
        $validated['password'] = bcrypt($validated['password']);
        
        \App\Models\User::create($validated);
        
        return redirect()->route('admin.users')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }
    
    public function editUser(\App\Models\User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function updateUser(Request $request, \App\Models\User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|unique:users,phone,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,owner,tenant',
        ]);
        
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.users')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }
    
    public function destroyUser(\App\Models\User $user)
    {
        // منع حذف المستخدم الحالي
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'لا يمكنك حذف حسابك الخاص');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
    
    public function bookings(Request $request)
    {
        $query = \App\Models\Booking::with('user', 'property', 'property.user', 'payments.wallet');
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);
        $pendingBookings = \App\Models\Booking::where('status', 'pending')->count();
        $confirmedBookings = \App\Models\Booking::where('status', 'confirmed')->count();
        $completedBookings = \App\Models\Booking::where('status', 'completed')->count();
        $cancelledBookings = \App\Models\Booking::where('status', 'cancelled')->count();
        
        return view('admin.bookings.index', compact('bookings', 'pendingBookings', 'confirmedBookings', 'completedBookings', 'cancelledBookings'));
    }
    
    public function createBooking()
    {
        $properties = \App\Models\Property::where('admin_status', 'approved')->with('user')->get();
        $users = \App\Models\User::where('role', 'tenant')->get();
        return view('admin.bookings.create', compact('properties', 'users'));
    }
    
    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'user_id' => 'required|exists:users,id',
            'inspection_date' => 'required|date|after:today',
            'inspection_time' => 'required',
            'booking_type' => 'required|in:inspection,reservation',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,refunded',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);
        
        $nextId = \App\Models\Booking::max('id') ? \App\Models\Booking::max('id') + 1 : 1;
        $validated['booking_code'] = 'BOOK-' . str_pad($nextId, 8, '0', STR_PAD_LEFT);
        $booking = \App\Models\Booking::create($validated);
        
        return redirect()->route('admin.bookings')
            ->with('success', 'تم إضافة الحجز بنجاح');
    }
    
    public function editBooking(\App\Models\Booking $booking)
    {
        $booking->load('user', 'property', 'property.user', 'payments');
        $properties = \App\Models\Property::where('admin_status', 'approved')->with('user')->get();
        $users = \App\Models\User::where('role', 'tenant')->get();
        return view('admin.bookings.edit', compact('booking', 'properties', 'users'));
    }
    
    public function updateBooking(Request $request, \App\Models\Booking $booking)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'user_id' => 'required|exists:users,id',
            'inspection_date' => 'required|date',
            'inspection_time' => 'required',
            'booking_type' => 'required|in:inspection,reservation',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,refunded',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);
        
        $booking->update($validated);
        
        return redirect()->route('admin.bookings')
            ->with('success', 'تم تحديث الحجز بنجاح');
    }
    
    public function destroyBooking(\App\Models\Booking $booking)
    {
        // حذف الدفعات المرتبطة
        foreach ($booking->payments as $payment) {
            // حذف إيصال الدفع
            if ($payment->receipt_path && Storage::disk('public')->exists($payment->receipt_path)) {
                Storage::disk('public')->delete($payment->receipt_path);
            }
            $payment->delete();
        }
        
        $booking->delete();
        
        return redirect()->route('admin.bookings')
            ->with('success', 'تم حذف الحجز بنجاح');
    }
    
    public function approveInspection(Request $request, \App\Models\Booking $booking)
    {
        \Log::info('Attempting to approve inspection for booking ID: ' . $booking->id);
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        try {
            \DB::beginTransaction();
            
            // تحديث حالة الحجز
            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'payment_status' => 'paid',
            ]);
            \Log::info('Booking status updated to confirmed and payment_status to paid', ['booking_id' => $booking->id]);
            
            // تحديث حالة الغرفة لتكون غير متاحة بعد تأكيد الحجز
            if ($booking->room_id) {
                $room = \App\Models\Room::find($booking->room_id);
                if ($room) {
                    $room->update(['is_available' => false]);
                    \Log::info('Room availability updated to false after inspection approval', [
                        'room_id' => $room->id,
                        'booking_id' => $booking->id
                    ]);
                }
                
                // تحديث حالة RoomBooking أيضاً إذا كان موجوداً
                $roomBooking = \App\Models\RoomBooking::where('room_id', $booking->room_id)
                    ->where('user_id', $booking->user_id)
                    ->where('status', 'pending')
                    ->first();
                if ($roomBooking) {
                    $roomBooking->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                    ]);
                    \Log::info('RoomBooking status updated to confirmed', ['room_booking_id' => $roomBooking->id]);
                }
            }
            
            // البحث عن الدفعة المرتبطة بالحجز (حتى لو كانت pending)
            $payment = \App\Models\Payment::where('booking_id', $booking->id)->first();
            
            // إذا لم تكن هناك دفعة، ننشئ دفعة تلقائياً
            if (!$payment) {
                \Log::info('No existing payment found for booking, creating new payment.', ['booking_id' => $booking->id]);
                // الحصول على محفظة نشطة للأدمن
                $wallet = \App\Models\Wallet::where('is_active', true)
                    ->whereHas('user', function($query) {
                        $query->where('role', 'admin');
                    })
                    ->first();
                
                if (!$wallet) {
                    \DB::rollBack();
                    \Log::error('No active admin wallet found to create automatic payment for booking.', ['booking_id' => $booking->id]);
                    return redirect()->route('admin.bookings')
                        ->with('error', 'لا توجد محافظ نشطة للأدمن. يرجى إضافة محفظة أولاً.');
                }
                
                // تحديد نوع الدفع
                $paymentType = $booking->booking_type === 'inspection' ? 'inspection_fee' : 'reservation_fee';
                
                // إنشاء دفعة جديدة
                $payment = \App\Models\Payment::create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'wallet_id' => $wallet->id,
                    'payment_type' => $paymentType,
                    'amount' => $booking->amount,
                    'payment_method' => 'bank_transfer',
                    'status' => 'completed', // يتم اعتبارها مكتملة لأن الأدمن وافق عليها
                    'receipt_path' => null, // لا يوجد إيصال مرفوع تلقائياً
                    'review_status' => 'approved',
                    'reviewed_at' => now(),
                    'reviewed_by' => auth()->id(),
                    'payment_date' => now(),
                ]);
                
                \Log::info('Payment created automatically for approved booking', [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount
                ]);
            } else {
                // تحديث حالة الدفعة إلى approved و completed
                $payment->update([
                    'review_status' => 'approved',
                    'status' => 'completed',
                    'reviewed_at' => now(),
                    'reviewed_by' => auth()->id(),
                ]);
                \Log::info('Existing payment updated to approved and completed', ['payment_id' => $payment->id]);
            }
            
            // إنشاء معاملة مالية في المحفظة إذا كانت موجودة
            if ($payment->wallet_id) {
                $wallet = \App\Models\Wallet::find($payment->wallet_id);
                
                if ($wallet) {
                    $description = $payment->payment_type === 'inspection_fee' 
                        ? "رسوم معاينة - حجز #{$booking->id}" 
                        : "رسوم حجز - حجز #{$booking->id}";
                    
                    // التحقق من عدم وجود transaction مسبقاً لهذه الدفعة
                    $existingTransaction = \App\Models\Transaction::where('reference_type', 'App\\Models\\Payment')
                        ->where('reference_id', $payment->id)
                        ->first();
                    
                    if (!$existingTransaction) {
                        try {
                            $transaction = \App\Models\Transaction::record(
                                $wallet,
                                'income',
                                (float)$payment->amount,
                                $description,
                                'App\\Models\\Payment',
                                $payment->id,
                                "دفعة من المستأجر: {$booking->user->name}"
                            );
                            
                            // حفظ transaction_id في الدفعة
                            $payment->update(['transaction_id' => (string)$transaction->id]);
                            
                            \DB::commit();
                            
                            \Log::info('Transaction created for payment in approveInspection', [
                                'payment_id' => $payment->id,
                                'transaction_id' => $transaction->id,
                                'wallet_id' => $wallet->id,
                                'wallet_balance_before' => $wallet->fresh()->balance - $payment->amount,
                                'wallet_balance_after' => $wallet->fresh()->balance,
                                'amount' => $payment->amount
                            ]);
                        } catch (\Exception $e) {
                            \DB::rollBack();
                            \Log::error('Failed to create transaction for payment in approveInspection: ' . $e->getMessage(), [
                                'payment_id' => $payment->id,
                                'booking_id' => $booking->id,
                                'trace' => $e->getTraceAsString()
                            ]);
                            return redirect()->route('admin.bookings')
                                ->with('error', 'حدث خطأ أثناء إنشاء المعاملة المالية: ' . $e->getMessage());
                        }
                    } else {
                        \Log::info('Transaction already exists for payment', [
                            'payment_id' => $payment->id,
                            'existing_transaction_id' => $existingTransaction->id
                        ]);
                        // تحديث transaction_id في الدفعة إذا لم يكن موجوداً
                        if (empty($payment->transaction_id)) {
                            $payment->update(['transaction_id' => (string)$existingTransaction->id]);
                        }
                        \DB::commit();
                    }
                } else {
                    \Log::warning('Wallet not found for payment, cannot create transaction.', ['wallet_id' => $payment->wallet_id, 'payment_id' => $payment->id]);
                    \DB::commit();
                }
            } else {
                \Log::warning('Payment has no associated wallet, cannot create transaction.', ['payment_id' => $payment->id]);
                \DB::commit();
            }
            
            return redirect()->route('admin.bookings')
                ->with('success', 'تم الموافقة على طلب المعاينة بنجاح');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error approving inspection: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.bookings')
                ->with('error', 'حدث خطأ أثناء الموافقة على طلب المعاينة: ' . $e->getMessage());
        }
    }
    
    public function rejectInspection(Request $request, \App\Models\Booking $booking)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['rejection_reason'],
            'cancelled_at' => now(),
        ]);
        
        return redirect()->route('admin.bookings')
            ->with('success', 'تم رفض طلب المعاينة');
    }
    
    public function inquiries()
    {
        $inquiries = \App\Models\Inquiry::with('user', 'property')->latest()->get();
        $pendingInquiries = \App\Models\Inquiry::where('status', 'pending')->count();
        $answeredInquiries = \App\Models\Inquiry::where('status', 'answered')->count();
        
        return view('admin.inquiries.index', compact('inquiries', 'pendingInquiries', 'answeredInquiries'));
    }
    
    public function answerInquiry(Request $request, \App\Models\Inquiry $inquiry)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);
        
        $inquiry->update([
            'status' => 'answered',
            'answer' => $request->answer,
        ]);
        
        return redirect()->route('admin.inquiries')
            ->with('success', 'تم الرد على الاستفسار بنجاح');
    }
    
    public function settings()
    {
        return view('admin.settings.index');
    }
    
    public function updateSettings(Request $request)
    {
        // هنا يمكن حفظ الإعدادات في ملف config أو جدول settings
        return redirect()->route('admin.settings')
            ->with('success', 'تم تحديث الإعدادات بنجاح');
    }
    
    // المدفوعات والفواتير
    public function payments(Request $request)
    {
        try {
            $query = \App\Models\Payment::with(['user', 'booking', 'wallet', 'reviewer']);
            
            // فلترة حسب حالة المراجعة
            if ($request->has('review_status') && $request->review_status != '') {
                $query->where('review_status', $request->review_status);
            }
            
            // فلترة حسب حالة الدفع
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }
            
            // فلترة حسب نوع الدفع
            if ($request->has('payment_type') && $request->payment_type != '') {
                $query->where('payment_type', $request->payment_type);
            }
            
            $payments = $query->orderBy('created_at', 'desc')->paginate(20);
            
            // الإحصائيات
            $totalPayments = \App\Models\Payment::where('status', 'completed')
                ->where('review_status', 'approved')
                ->sum('amount') ?? 0;
            $pendingPayments = \App\Models\Payment::where('review_status', 'pending')->count();
            $approvedPayments = \App\Models\Payment::where('review_status', 'approved')->count();
            $rejectedPayments = \App\Models\Payment::where('review_status', 'rejected')->count();
            $totalCount = \App\Models\Payment::count();
            
            \Log::info('Payments page loaded', [
                'total_count' => $totalCount,
                'payments_count' => $payments->count(),
                'filters' => $request->all()
            ]);
            
            return view('admin.payments.index', compact(
                'payments', 
                'totalPayments', 
                'pendingPayments', 
                'approvedPayments', 
                'rejectedPayments',
                'totalCount'
            ));
        } catch (\Exception $e) {
            \Log::error('Payments page error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('admin.payments.index', [
                'payments' => collect(),
                'totalPayments' => 0,
                'pendingPayments' => 0,
                'approvedPayments' => 0,
                'rejectedPayments' => 0,
                'totalCount' => 0
            ])->with('error', 'حدث خطأ أثناء تحميل المدفوعات: ' . $e->getMessage());
        }
    }
    
    public function reviewPayment(Request $request, \App\Models\Payment $payment)
    {
        \Log::info('Reviewing payment', ['payment_id' => $payment->id, 'request_data' => $request->all()]);
        $validated = $request->validate([
            'review_status' => 'required|in:approved,rejected',
            'review_notes' => 'nullable|string|max:1000',
        ]);

        try {
            \DB::beginTransaction();

            $payment->update([
                'review_status' => $validated['review_status'],
                'review_notes' => $validated['review_notes'] ?? null,
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);
            \Log::info('Payment review status updated', ['payment_id' => $payment->id, 'new_status' => $validated['review_status']]);

            // إذا تم الموافقة، تحديث حالة الدفع والحجز وإنشاء معاملة مالية
            if ($validated['review_status'] === 'approved') {
                $payment->update([
                    'status' => 'completed',
                ]);
                \Log::info('Payment status updated to completed', ['payment_id' => $payment->id]);

                if ($payment->booking) {
                    $payment->booking->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                        'confirmed_at' => now(),
                    ]);
                    \Log::info('Associated booking status updated to confirmed and payment_status to paid', ['booking_id' => $payment->booking->id]);
                    
                    // تحديث حالة الغرفة لتكون غير متاحة بعد تأكيد الحجز
                    if ($payment->booking->room_id) {
                        $room = \App\Models\Room::find($payment->booking->room_id);
                        if ($room) {
                            $room->update(['is_available' => false]);
                            \Log::info('Room availability updated to false after booking confirmation', [
                                'room_id' => $room->id,
                                'booking_id' => $payment->booking->id
                            ]);
                        }
                    }
                    
                    // تحديث حالة RoomBooking أيضاً إذا كان موجوداً
                    $roomBooking = \App\Models\RoomBooking::where('room_id', $payment->booking->room_id)
                        ->where('user_id', $payment->booking->user_id)
                        ->where('status', 'pending')
                        ->first();
                    if ($roomBooking) {
                        $roomBooking->update([
                            'payment_status' => 'paid',
                            'status' => 'confirmed',
                        ]);
                        \Log::info('RoomBooking status updated to confirmed', ['room_booking_id' => $roomBooking->id]);
                    }
                }
                
                // إنشاء معاملة مالية في المحفظة إذا كانت موجودة
                if ($payment->wallet_id) {
                    $wallet = \App\Models\Wallet::find($payment->wallet_id);
                    
                    if ($wallet) {
                        $description = $payment->payment_type === 'inspection_fee' 
                            ? "رسوم معاينة - حجز #{$payment->booking_id}" 
                            : "رسوم حجز - حجز #{$payment->booking_id}";
                        
                        // التحقق من عدم وجود transaction مسبقاً لهذه الدفعة
                        $existingTransaction = \App\Models\Transaction::where('reference_type', 'App\\Models\\Payment')
                            ->where('reference_id', $payment->id)
                            ->first();
                        
                        if (!$existingTransaction) {
                            $transaction = \App\Models\Transaction::record(
                                $wallet,
                                'income',
                                (float)$payment->amount,
                                $description,
                                'App\\Models\\Payment',
                                $payment->id,
                                $payment->booking ? "دفعة من المستأجر: {$payment->booking->user->name}" : "دفعة مالية"
                            );
                            // حفظ transaction_id في الدفعة
                            $payment->update(['transaction_id' => (string)$transaction->id]);
                            \Log::info('Transaction created for payment', ['payment_id' => $payment->id, 'transaction_id' => $transaction->id]);
                        } else {
                            \Log::info('Transaction already exists for payment, skipping creation.', ['payment_id' => $payment->id, 'transaction_id' => $existingTransaction->id]);
                            // تحديث transaction_id في الدفعة إذا لم يكن موجوداً
                            if (empty($payment->transaction_id)) {
                                $payment->update(['transaction_id' => (string)$existingTransaction->id]);
                            }
                        }
                    } else {
                        \Log::warning('Wallet not found for payment, cannot create transaction.', ['wallet_id' => $payment->wallet_id, 'payment_id' => $payment->id]);
                    }
                } else {
                    \Log::warning('Payment has no associated wallet, cannot create transaction.', ['payment_id' => $payment->id]);
                }
            } else {
                // إذا تم الرفض، إعادة الحجز إلى حالة pending
                if ($payment->booking) {
                    $payment->booking->update([
                        'payment_status' => 'pending',
                        'status' => 'pending',
                    ]);
                    \Log::info('Associated booking status reset to pending due to payment rejection', ['booking_id' => $payment->booking->id]);
                }
            }

            \DB::commit();
            return redirect()->route('admin.payments')
                ->with('success', $validated['review_status'] === 'approved' ? 'تم الموافقة على الدفعة بنجاح' : 'تم رفض الدفعة');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error reviewing payment: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.payments')
                ->with('error', 'حدث خطأ أثناء مراجعة الدفعة: ' . $e->getMessage());
        }
    }
    
    public function refundPayment(Request $request, \App\Models\Payment $payment)
    {
        $payment->update([
            'status' => 'refunded',
            'notes' => $request->notes ?? 'تم الاسترداد بواسطة الأدمن',
        ]);
        
        return redirect()->route('admin.payments')
            ->with('success', 'تم استرداد المبلغ بنجاح');
    }
    
    // المحافظ
    public function wallets(Request $request)
    {
        $query = \App\Models\Wallet::with('user');
        
        if ($request->has('property_type_id') && $request->property_type_id) {
            $query->where('property_type_id', $request->property_type_id);
        }
        
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $wallets = $query->latest()->paginate(20);
        
        $stats = [
            'total' => \App\Models\Wallet::count(),
            'bank' => \App\Models\Wallet::where('type', 'bank')->count(),
            'mobile' => \App\Models\Wallet::where('type', 'mobile_wallet')->count(),
            'active' => \App\Models\Wallet::where('is_active', true)->count(),
        ];
        
        return view('admin.wallets.index', compact('wallets', 'stats'));
    }
    
    public function showWallet(\App\Models\Wallet $wallet)
    {
        $wallet->load(['user', 'payments' => function($q) {
            $q->latest()->take(10);
        }]);
        
        return view('admin.wallets.show', compact('wallet'));
    }
    
    public function toggleWalletStatus(\App\Models\Wallet $wallet)
    {
        $wallet->update(['is_active' => !$wallet->is_active]);
        
        return redirect()->back()
            ->with('success', $wallet->is_active ? 'تم تفعيل المحفظة' : 'تم تعطيل المحفظة');
    }
    
    public function createWallet()
    {
        $users = \App\Models\User::whereIn('role', ['owner', 'admin'])->get();
        return view('admin.wallets.create', compact('users'));
    }
    
    public function storeWallet(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:bank,mobile_wallet',
            'name' => 'required|string|max:255',
            'bank_name' => 'required_if:type,bank|nullable|string|max:255',
            'account_number' => 'required_if:type,bank|nullable|string|max:50',
            'account_name' => 'required_if:type,bank|nullable|string|max:255',
            'iban' => 'nullable|string|max:50',
            'phone_number' => 'required_if:type,mobile_wallet|nullable|string|max:20',
            'notes' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        \App\Models\Wallet::create($validated);
        
        \App\Models\ActivityLog::log('wallet_create', 'تم إنشاء محفظة جديدة', null, $validated);
        
        return redirect()->route('admin.wallets')
            ->with('success', 'تم إضافة المحفظة بنجاح');
    }
    
    public function editWallet(\App\Models\Wallet $wallet)
    {
        $users = \App\Models\User::whereIn('role', ['owner', 'admin'])->get();
        return view('admin.wallets.edit', compact('wallet', 'users'));
    }
    
    public function updateWallet(Request $request, \App\Models\Wallet $wallet)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:bank,mobile_wallet',
            'name' => 'required|string|max:255',
            'bank_name' => 'required_if:type,bank|nullable|string|max:255',
            'account_number' => 'required_if:type,bank|nullable|string|max:50',
            'account_name' => 'required_if:type,bank|nullable|string|max:255',
            'iban' => 'nullable|string|max:50',
            'phone_number' => 'required_if:type,mobile_wallet|nullable|string|max:20',
            'notes' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $wallet->update($validated);
        
        \App\Models\ActivityLog::log('wallet_update', 'تم تعديل المحفظة', $wallet);
        
        return redirect()->route('admin.wallets')
            ->with('success', 'تم تحديث المحفظة بنجاح');
    }
    
    public function destroyWallet(\App\Models\Wallet $wallet)
    {
        $wallet->delete();
        
        \App\Models\ActivityLog::log('wallet_delete', 'تم حذف المحفظة');
        
        return redirect()->route('admin.wallets')
            ->with('success', 'تم حذف المحفظة بنجاح');
    }
    
    // الشكاوى والبلاغات
    public function complaints()
    {
        $complaints = \App\Models\Complaint::with('user', 'property', 'reportedUser')->latest()->get();
        $newComplaints = \App\Models\Complaint::where('status', 'new')->count();
        $underReview = \App\Models\Complaint::where('status', 'under_review')->count();
        $resolved = \App\Models\Complaint::where('status', 'resolved')->count();
        
        return view('admin.complaints.index', compact('complaints', 'newComplaints', 'underReview', 'resolved'));
    }
    
    public function showComplaint(\App\Models\Complaint $complaint)
    {
        $complaint->load('user', 'property', 'reportedUser');
        return view('admin.complaints.show', compact('complaint'));
    }
    
    public function updateComplaint(Request $request, \App\Models\Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,under_review,resolved,rejected',
            'admin_response' => 'nullable|string',
            'action_taken' => 'required|in:none,warning,suspend_property,suspend_account',
        ]);
        
        $complaint->update($validated);
        
        // تنفيذ الإجراءات
        if ($request->action_taken === 'suspend_property' && $complaint->property_id) {
            $complaint->property->update(['is_suspended' => true]);
        } elseif ($request->action_taken === 'suspend_account' && $complaint->reported_user_id) {
            $complaint->reportedUser->update(['account_status' => 'suspended']);
        }
        
        return redirect()->route('admin.complaints')
            ->with('success', 'تم تحديث البلاغ بنجاح');
    }
    
    // إدارة المحتوى
    public function content()
    {
        $pages = \App\Models\ContentPage::orderBy('order')->get();
        return view('admin.content.index', compact('pages'));
    }
    
    public function createContent()
    {
        return view('admin.content.create');
    }
    
    public function storeContent(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:content_pages,slug',
            'title' => 'required|string',
            'content' => 'required|string',
            'type' => 'required|in:page,faq,terms,privacy,banner',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);
        
        \App\Models\ContentPage::create($validated);
        
        return redirect()->route('admin.content')
            ->with('success', 'تم إنشاء الصفحة بنجاح');
    }
    
    public function editContent(\App\Models\ContentPage $contentPage)
    {
        return view('admin.content.edit', compact('contentPage'));
    }
    
    public function updateContent(Request $request, \App\Models\ContentPage $contentPage)
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:content_pages,slug,' . $contentPage->id,
            'title' => 'required|string',
            'content' => 'required|string',
            'type' => 'required|in:page,faq,terms,privacy,banner',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);
        
        $contentPage->update($validated);
        
        return redirect()->route('admin.content')
            ->with('success', 'تم تحديث الصفحة بنجاح');
    }
    
    public function destroyContent(\App\Models\ContentPage $contentPage)
    {
        $contentPage->delete();
        
        return redirect()->route('admin.content')
            ->with('success', 'تم حذف الصفحة بنجاح');
    }
    
    // تحسين Dashboard
    public function dashboard()
    {
        $totalProperties = Property::count();
        $approvedProperties = Property::where('admin_status', 'approved')->count();
        $pendingProperties = Property::where('admin_status', 'pending')->count();
        $rejectedProperties = Property::where('admin_status', 'rejected')->count();
        $suspendedProperties = Property::where('is_suspended', true)->count();
        
        $totalUsers = \App\Models\User::count();
        $owners = \App\Models\User::where('role', 'owner')->count();
        $tenants = \App\Models\User::where('role', 'tenant')->count();
        
        $totalBookings = \App\Models\Booking::count();
        $totalPayments = \App\Models\Payment::where('status', 'completed')->sum('amount');
        
        // تنبيهات عاجلة
        $urgentAlerts = [
            'new_complaints' => \App\Models\Complaint::where('status', 'new')->count(),
            'suspended_properties' => Property::where('is_suspended', true)->count(),
            'pending_properties' => $pendingProperties,
            'violations' => \App\Models\User::where('violations_count', '>', 0)->count(),
        ];
        
        $recentProperties = Property::with('user')->latest()->take(10)->get();
        $recentComplaints = \App\Models\Complaint::with('user', 'property')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalProperties',
            'approvedProperties',
            'pendingProperties',
            'rejectedProperties',
            'suspendedProperties',
            'totalUsers',
            'owners',
            'tenants',
            'totalBookings',
            'totalPayments',
            'urgentAlerts',
            'recentProperties',
            'recentComplaints'
        ));
    }
}
