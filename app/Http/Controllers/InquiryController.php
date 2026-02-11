<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'property_code' => 'required|string',
            'message' => 'required|string|max:1000',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $property = Property::findOrFail($validated['property_id']);

        // التحقق من كود الوحدة
        if ($property->code !== $validated['property_code']) {
            return back()->withErrors(['property_code' => 'كود الوحدة غير صحيح']);
        }

        Inquiry::create([
            'property_id' => $property->id,
            'user_id' => Auth::id(),
            'name' => $validated['name'] ?? (Auth::check() ? Auth::user()->name : null),
            'phone' => $validated['phone'] ?? (Auth::check() ? Auth::user()->phone : null),
            'property_code' => $validated['property_code'],
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'تم إرسال استفسارك بنجاح');
    }
}
