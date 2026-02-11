<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $payments = Payment::whereHas('booking.property', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('booking.property', 'user')
        ->latest()
        ->paginate(15);
        
        $stats = [
            'total_earnings' => Payment::whereHas('booking.property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'completed')->sum('amount'),
            'pending' => Payment::whereHas('booking.property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'pending')->count(),
            'completed' => Payment::whereHas('booking.property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'completed')->count(),
        ];
        
        return view('owner.payments', compact('payments', 'stats'));
    }

    public function bookings()
    {
        $user = Auth::user();
        
        $bookings = Booking::whereHas('property', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', '!=', 'pending')
        ->with('user', 'property', 'payment')
        ->latest()
        ->paginate(15);
        
        return view('owner.bookings', compact('bookings'));
    }

    public function uploadContract(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if ($booking->property->user_id != $user->id) {
            abort(403);
        }
        
        $request->validate([
            'contract' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        
        $path = $request->file('contract')->store('contracts/' . $booking->id, 'public');
        
        $booking->update([
            'contract_path' => $path,
            'contract_uploaded_at' => now(),
        ]);
        
        return redirect()->route('owner.bookings')->with('success', 'تم رفع العقد بنجاح');
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if ($booking->property->user_id != $user->id) {
            abort(403);
        }
        
        if ($booking->payment) {
            $booking->payment->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }
        
        return redirect()->route('owner.bookings')->with('success', 'تم تأكيد استلام الدفعة');
    }

    public function reports(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 'monthly');
        
        // Logic for generating reports
        return view('owner.payments-reports', compact('period'));
    }

    // إدارة المحافظ
    public function wallets()
    {
        $user = Auth::user();
        $wallets = Wallet::where('user_id', $user->id)->latest()->get();
        return view('owner.wallets', compact('wallets'));
    }

    public function storeWallet(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'type' => 'required|in:bank,mobile_wallet,other',
            'notes' => 'nullable|string',
        ]);

        Wallet::create([
            'user_id' => Auth::id(),
            ...$validated,
            'is_active' => true,
        ]);

        return redirect()->route('owner.wallets')->with('success', 'تم إضافة المحفظة بنجاح');
    }

    public function updateWallet(Request $request, Wallet $wallet)
    {
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'type' => 'required|in:bank,mobile_wallet,other',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $wallet->update($validated);

        return redirect()->route('owner.wallets')->with('success', 'تم تحديث المحفظة بنجاح');
    }

    public function deleteWallet(Wallet $wallet)
    {
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }

        // التحقق من عدم وجود مدفوعات مرتبطة
        if ($wallet->payments()->count() > 0) {
            return redirect()->route('owner.wallets')->with('error', 'لا يمكن حذف المحفظة لأنها مرتبطة بمدفوعات');
        }

        $wallet->delete();

        return redirect()->route('owner.wallets')->with('success', 'تم حذف المحفظة بنجاح');
    }
}
