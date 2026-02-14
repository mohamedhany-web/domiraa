<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // للصفحة الرئيسية فقط
        if ($request->route()->getName() === 'home') {
            $query = Property::where('admin_status', 'approved')
                ->where('is_suspended', false)
                ->availableForPublic()
                ->with('images', 'propertyType');
            
            // تطبيق الفلترة
            if ($request->filled('address')) {
                $query->where('address', 'like', '%' . $request->address . '%');
            }
            
            if ($request->filled('property_type_id')) {
                $query->where('property_type_id', $request->property_type_id);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('price_range')) {
                $query->where('price', '<=', $request->price_range);
            } elseif ($request->filled('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }
            
            if ($request->filled('price_type')) {
                $query->where('price_type', $request->price_type);
            }
            
            $featuredProperties = $query->latest()->take(12)->get();
            
            return view('home', compact('featuredProperties'));
        }

        // صفحة البحث/العقارات
        $query = Property::where('admin_status', 'approved')
            ->where('is_suspended', false)
            ->availableForPublic()
            ->with('images', 'propertyType');

        // فلترة حسب المنطقة
        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }

        // فلترة حسب نوع الوحدة
        if ($request->filled('property_type_id')) {
            $query->where('property_type_id', $request->property_type_id);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب رنج الأسعار
        if ($request->filled('price_range')) {
            $query->where('price', '<=', $request->price_range);
        } elseif ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // فلترة حسب نوع السعر
        if ($request->filled('price_type')) {
            $query->where('price_type', $request->price_type);
        }

        $properties = $query->latest()->paginate(12);

        return view('tenant.search', compact('properties'));
    }

    public function ajaxSearch(Request $request)
    {
        $query = Property::where('admin_status', 'approved')
            ->where('is_suspended', false)
            ->availableForPublic()
            ->with('images', 'propertyType');

        // فلترة حسب المنطقة
        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }

        // فلترة حسب نوع الوحدة
        if ($request->filled('property_type_id')) {
            $query->where('property_type_id', $request->property_type_id);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب رنج الأسعار
        if ($request->filled('price_range')) {
            $query->where('price', '<=', $request->price_range);
        } elseif ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // فلترة حسب نوع السعر
        if ($request->filled('price_type')) {
            $query->where('price_type', $request->price_type);
        }

        $properties = $query->latest()->paginate(12);

        $html = view('tenant.search-results', compact('properties'))->render();

        return response()->json([
            'html' => $html,
            'count' => $properties->total(),
        ]);
    }

    public function show(Property $property)
    {
        if ($property->admin_status !== 'approved') {
            abort(404);
        }
        // إخفاء صفحة الوحدة من الزوار إذا لديها حجز مؤكد (تبقى البيانات للمالك والإدارة)
        $hasConfirmedBooking = $property->bookings()->where('status', 'confirmed')->exists()
            || $property->roomBookings()->where('status', 'confirmed')->exists();
        if ($hasConfirmedBooking) {
            abort(404);
        }

        // Load all relationships including rooms
        $property->load(['images', 'user', 'propertyType', 'rooms' => function($query) {
            $query->orderBy('room_number');
        }]);
        
        // Ensure rooms relation is loaded
        if (!$property->relationLoaded('rooms')) {
            $property->load('rooms');
        }
        
        return view('tenant.property-details', compact('property'));
    }
}
