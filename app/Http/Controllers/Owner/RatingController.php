<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $ratings = Rating::whereHas('property', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('user', 'property')
        ->latest()
        ->paginate(15);
        
        $stats = [
            'average_rating' => Rating::whereHas('property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->avg('rating') ?? 0,
            'total_ratings' => Rating::whereHas('property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'five_star' => Rating::whereHas('property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('rating', 5)->count(),
        ];
        
        return view('owner.ratings', compact('ratings', 'stats'));
    }

    public function reply(Request $request, Rating $rating)
    {
        $user = Auth::user();
        
        if ($rating->property->user_id != $user->id) {
            abort(403);
        }
        
        $request->validate([
            'reply' => 'required|string|max:500',
        ]);
        
        $rating->update([
            'owner_reply' => $request->reply,
            'replied_at' => now(),
        ]);
        
        return redirect()->route('owner.ratings')->with('success', 'تم إرسال الرد بنجاح');
    }
}
