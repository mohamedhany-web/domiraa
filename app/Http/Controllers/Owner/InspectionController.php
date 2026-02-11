<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InspectionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // عرض جميع المعاينات (المعلقة والمؤكدة والمكتملة)
        $query = Booking::whereHas('property', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->whereIn('status', ['pending', 'confirmed', 'completed'])
        ->with('user', 'property');
        
        if ($request->has('status') && $request->status) {
            if (in_array($request->status, ['pending', 'confirmed', 'completed'])) {
                $query->where('status', $request->status);
            }
        }
        
        $inspections = $query->latest()->paginate(15);
        
        // إحصائيات لجميع الحالات
        $stats = [
            'pending' => Booking::whereHas('property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'pending')->count(),
            'confirmed' => Booking::whereHas('property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'confirmed')->count(),
            'completed' => Booking::whereHas('property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'completed')->count(),
        ];
        
        return view('owner.inspections', compact('inspections', 'stats'));
    }

    public function accept(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if ($booking->property->user_id != $user->id) {
            abort(403);
        }
        
        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
        
        return redirect()->route('owner.inspections')->with('success', 'تم قبول موعد المعاينة بنجاح');
    }

    public function reject(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if ($booking->property->user_id != $user->id) {
            abort(403);
        }
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->rejection_reason,
            'cancelled_at' => now(),
        ]);
        
        return redirect()->route('owner.inspections')->with('success', 'تم رفض موعد المعاينة');
    }

    public function suggestAlternative(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if ($booking->property->user_id != $user->id) {
            abort(403);
        }
        
        $request->validate([
            'alternative_date' => 'required|date|after:today',
            'alternative_time' => 'required|string',
        ]);
        
        $booking->update([
            'inspection_date' => $request->alternative_date,
            'inspection_time' => $request->alternative_time,
            'status' => 'pending',
        ]);
        
        return redirect()->route('owner.inspections')->with('success', 'تم اقتراح موعد بديل');
    }
}
