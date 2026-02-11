<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::where('user_id', Auth::id())
            ->with('property')
            ->latest()
            ->get();
        
        return view('tenant.inquiries.index', compact('inquiries'));
    }
    
    public function show(Inquiry $inquiry)
    {
        // التأكد من أن الاستفسار يخص المستخدم الحالي
        if ($inquiry->user_id !== Auth::id()) {
            abort(403);
        }
        
        $inquiry->load('property');
        
        return view('tenant.inquiries.show', compact('inquiry'));
    }
}

