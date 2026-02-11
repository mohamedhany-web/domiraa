<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{
    public function index()
    {
        $propertyTypes = PropertyType::orderBy('sort_order')->get();
        return view('admin.property-types.index', compact('propertyTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:property_types,slug',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        PropertyType::create($validated);

        return redirect()->route('admin.property-types.index')
            ->with('success', 'تم إضافة نوع الوحدة بنجاح');
    }

    public function update(Request $request, PropertyType $propertyType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:property_types,slug,' . $propertyType->id,
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $propertyType->update($validated);

        return redirect()->route('admin.property-types.index')
            ->with('success', 'تم تحديث نوع الوحدة بنجاح');
    }

    public function destroy(PropertyType $propertyType)
    {
        // التحقق من وجود وحدات تستخدم هذا النوع
        if ($propertyType->properties()->count() > 0) {
            return redirect()->route('admin.property-types.index')
                ->with('error', 'لا يمكن حذف نوع الوحدة لأنه مستخدم في وحدات موجودة');
        }

        $propertyType->delete();

        return redirect()->route('admin.property-types.index')
            ->with('success', 'تم حذف نوع الوحدة بنجاح');
    }
}

