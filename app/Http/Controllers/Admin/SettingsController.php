<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        
        $groups = [
            'pricing' => 'الأسعار والرسوم',
            'general' => 'إعدادات عامة',
            'contact' => 'معلومات التواصل',
        ];
        
        return view('admin.settings.index', compact('settings', 'groups'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم حفظ الإعدادات بنجاح');
    }

    public function pricing()
    {
        $settings = Setting::where('group', 'pricing')->get();
        return view('admin.settings.pricing', compact('settings'));
    }

    public function updatePricing(Request $request)
    {
        $validated = $request->validate([
            'inspection_fee' => 'required|numeric|min:0',
            'reservation_percentage_daily' => 'required|numeric|min:0|max:100',
            'reservation_percentage_monthly' => 'required|numeric|min:0|max:100',
            'reservation_percentage_yearly' => 'required|numeric|min:0|max:100',
            'platform_fee_percentage' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        // مسح كاش الأسعار بشكل صريح
        Setting::clearPricingCache();
        Setting::clearCache();

        return redirect()->back()
            ->with('success', 'تم تحديث الأسعار بنجاح');
    }
}

