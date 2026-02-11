<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        return view('owner.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'language' => 'required|in:ar,en',
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
            'preferred_contact' => 'required|in:email,phone,both',
        ]);
        
        $user->update([
            'language' => $validated['language'],
            'notification_email' => $request->has('notification_email'),
            'notification_sms' => $request->has('notification_sms'),
            'preferred_contact' => $validated['preferred_contact'],
        ]);
        
        return redirect()->route('owner.settings')->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
