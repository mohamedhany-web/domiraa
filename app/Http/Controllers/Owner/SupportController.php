<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $complaints = Complaint::where('user_id', $user->id)
            ->latest()
            ->paginate(15);
        
        return view('owner.support', compact('complaints'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'complaint_type' => 'required|in:property,owner,tenant,other',
        ]);
        
        Complaint::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'complaint_type' => $request->complaint_type,
            'status' => 'new',
        ]);
        
        return redirect()->route('owner.support')->with('success', 'تم إرسال الشكوى بنجاح');
    }

    public function show(Complaint $complaint)
    {
        if ($complaint->user_id != Auth::id()) {
            abort(403);
        }
        
        return view('owner.support-show', compact('complaint'));
    }
}
