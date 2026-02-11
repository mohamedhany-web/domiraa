<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingTrackingController extends Controller
{
    public function index()
    {
        return view('tenant.booking-tracking.index');
    }
    
    public function track(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);
        
        // البحث عن جميع الطلبات المرتبطة برقم الهاتف
        $bookings = Booking::whereHas('user', function($query) use ($request) {
                $query->where('phone', $request->phone);
            })
            ->with('property', 'user', 'payments')
            ->orderBy('created_at', 'desc')
            ->get();
        
        if ($bookings->isEmpty()) {
            return back()->withErrors(['phone' => 'لم يتم العثور على أي طلبات مرتبطة برقم الهاتف هذا.'])->withInput();
        }
        
        return view('tenant.booking-tracking.result', compact('bookings'));
    }
}

