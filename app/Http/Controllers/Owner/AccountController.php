<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // حساب نسبة اكتمال الحساب
        $completion = $this->calculateProfileCompletion($user);
        
        return view('owner.account', compact('user', 'completion'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|unique:users,phone,' . $user->id,
        ]);
        
        $user->update($validated);
        
        return redirect()->route('owner.account')->with('success', 'تم تحديث بيانات الحساب بنجاح');
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:id_card,ownership_proof',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        
        $user = Auth::user();
        $path = $request->file('document')->store('documents/' . $user->id, 'public');
        
        // حفظ مسار المستند في قاعدة البيانات
        if ($request->document_type === 'id_card') {
            $user->update(['id_card_path' => $path]);
        } else {
            $user->update(['ownership_proof_path' => $path]);
        }
        
        return redirect()->route('owner.account')->with('success', 'تم رفع المستند بنجاح');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->route('owner.account')->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    private function calculateProfileCompletion($user)
    {
        $fields = [
            'name' => $user->name ? 1 : 0,
            'email' => $user->email ? 1 : 0,
            'phone' => $user->phone ? 1 : 0,
            'id_card' => isset($user->id_card_path) ? 1 : 0,
            'ownership_proof' => isset($user->ownership_proof_path) ? 1 : 0,
        ];
        
        $total = count($fields);
        $completed = array_sum($fields);
        
        return round(($completed / $total) * 100);
    }
}
