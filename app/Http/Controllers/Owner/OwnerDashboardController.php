<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // إحصائيات الوحدات
        $totalProperties = Property::where('user_id', $user->id)->count();
        $activeProperties = Property::where('user_id', $user->id)
            ->where('admin_status', 'approved')
            ->where('is_suspended', false)
            ->count();
        $pendingProperties = Property::where('user_id', $user->id)
            ->where('admin_status', 'pending')
            ->count();
        $suspendedProperties = Property::where('user_id', $user->id)
            ->where('is_suspended', true)
            ->count();
        
        // إحصائيات الحجوزات
        $totalBookings = Booking::whereHas('property', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        
        $pendingInspections = Booking::whereHas('property', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'pending')->count();
        
        $confirmedBookings = Booking::whereHas('property', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'confirmed')->count();
        
        // تنبيهات
        $alerts = [
            'pending_inspections' => $pendingInspections,
            'unread_messages' => Message::where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->count(),
            'pending_properties' => $pendingProperties,
            'upcoming_inspections' => Booking::whereHas('property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'confirmed')
            ->where('inspection_date', '>=', now())
            ->where('inspection_date', '<=', now()->addDays(3))
            ->count(),
        ];
        
        // الوحدات الأخيرة
        $recentProperties = Property::where('user_id', $user->id)
            ->with('images')
            ->latest()
            ->take(5)
            ->get();
        
        // الحجوزات الأخيرة
        $recentBookings = Booking::whereHas('property', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('user', 'property')
        ->latest()
        ->take(5)
        ->get();
        
        return view('owner.dashboard', compact(
            'totalProperties',
            'activeProperties',
            'pendingProperties',
            'suspendedProperties',
            'totalBookings',
            'pendingInspections',
            'confirmedBookings',
            'alerts',
            'recentProperties',
            'recentBookings'
        ));
    }
}
