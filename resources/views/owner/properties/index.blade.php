@extends('layouts.owner')

@section('title', 'الوحدات - منصة دوميرا')
@section('page-title', 'الوحدات')

@push('styles')
<style>
    .property-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .property-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .property-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #1F2937; margin-bottom: 0.5rem;">{{ $stats['total'] }}</div>
        <div style="color: #6B7280; font-weight: 600;">إجمالي الوحدات</div>
    </div>
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #6b8980; margin-bottom: 0.5rem;">{{ $stats['active'] }}</div>
        <div style="color: #6B7280; font-weight: 600;">نشطة</div>
    </div>
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #F59E0B; margin-bottom: 0.5rem;">{{ $stats['pending'] }}</div>
        <div style="color: #6B7280; font-weight: 600;">قيد المراجعة</div>
    </div>
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #EF4444; margin-bottom: 0.5rem;">{{ $stats['suspended'] }}</div>
        <div style="color: #6B7280; font-weight: 600;">موقوفة</div>
    </div>
</div>

<!-- Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1F2937;">قائمة الوحدات</h2>
    <a href="{{ route('owner.properties.create') }}" style="background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);">
        <i class="fas fa-plus"></i>
        إضافة وحدة جديدة
    </a>
</div>

<!-- Properties Grid -->
@if($properties->count() > 0)
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
    @foreach($properties as $property)
    <div class="property-card">
        @if($property->images && $property->images->count() > 0)
            @php
                $firstImage = $property->images->first();
            @endphp
            @if($firstImage && $firstImage->image_path)
            <img src="{{ $firstImage->url }}" alt="{{ $property->address }}" class="property-image" loading="lazy" onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'; this.onerror=null;">
            @else
            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #E0E7FF 0%, #F0F9FF 100%); display: flex; align-items: center; justify-content: center; color: #9CA3AF;">
                <div style="text-align: center;">
                    <i class="fas fa-image" style="font-size: 3rem; margin-bottom: 0.5rem; display: block;"></i>
                    <span style="font-size: 0.875rem;">No Image</span>
                </div>
            </div>
            @endif
        @else
        <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #E0E7FF 0%, #F0F9FF 100%); display: flex; align-items: center; justify-content: center; color: #9CA3AF;">
            <div style="text-align: center;">
                <i class="fas fa-image" style="font-size: 3rem; margin-bottom: 0.5rem; display: block;"></i>
                <span style="font-size: 0.875rem;">No Image</span>
            </div>
        </div>
        @endif
        
        <div style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">{{ $property->code }}</h3>
                    <p style="color: #6B7280; font-size: 0.9rem;">{{ Str::limit($property->address, 40) }}</p>
                </div>
                @if($property->admin_status == 'approved' && !$property->is_suspended)
                <span class="stat-badge" style="background: #D1FAE5; color: #536b63;">نشط</span>
                @elseif($property->admin_status == 'pending')
                <span class="stat-badge" style="background: #FEF3C7; color: #D97706;">قيد المراجعة</span>
                @elseif($property->is_suspended)
                <span class="stat-badge" style="background: #FEE2E2; color: #DC2626;">موقوف</span>
                @else
                <span class="stat-badge" style="background: #F3F4F6; color: #6B7280;">مرفوض</span>
                @endif
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding: 0.75rem; background: #F9FAFB; border-radius: 8px;">
                <div>
                    @if($property->is_room_rentable)
                    <div style="font-size: 1.25rem; font-weight: 700; color: #3B82F6;">
                        <i class="fas fa-users ml-1"></i>
                        قابلة للمشاركة
                    </div>
                    <div style="font-size: 0.875rem; color: #6B7280;">
                        غرف متاحة للإيجار
                    </div>
                    @else
                    <div style="font-size: 1.5rem; font-weight: 800; color: #1d313f;">{{ number_format($property->price, 2) }}</div>
                    <div style="font-size: 0.875rem; color: #6B7280;">
                        @if($property->price_type == 'monthly') شهرياً
                        @elseif($property->price_type == 'yearly') سنوياً
                        @else يومياً
                        @endif
                    </div>
                    @endif
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 0.875rem; color: #6B7280; margin-bottom: 0.25rem;">المشاهدات</div>
                    <div style="font-weight: 700; color: #1F2937;">{{ $property->views_count ?? 0 }}</div>
                </div>
            </div>
            
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('owner.properties.show', $property) }}" style="flex: 1; text-align: center; padding: 0.75rem; background: #F3F4F6; color: #1F2937; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-eye ml-1"></i> عرض
                </a>
                <a href="{{ route('owner.properties.edit', $property) }}" style="flex: 1; text-align: center; padding: 0.75rem; background: {{ $property->admin_status === 'rejected' ? '#FEF3C7' : '#DBEAFE' }}; color: #1d313f; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; {{ $property->admin_status === 'rejected' ? 'border: 2px solid #F59E0B;' : '' }}">
                    <i class="fas fa-edit ml-1"></i> {{ $property->admin_status === 'rejected' ? 'تعديل وإعادة الإرسال' : 'تعديل' }}
                </a>
            </div>
            
            @if($property->bookings->count() > 0)
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #E5E7EB;">
                <div style="font-size: 0.875rem; color: #6B7280;">
                    <i class="fas fa-calendar-check ml-1"></i>
                    {{ $property->bookings->count() }} طلب معاينة
                </div>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div style="background: white; border-radius: 16px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
    <i class="fas fa-building" style="font-size: 4rem; color: #9CA3AF; margin-bottom: 1.5rem; opacity: 0.5;"></i>
    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">لا توجد وحدات بعد</h3>
    <p style="color: #6B7280; margin-bottom: 2rem;">ابدأ بإضافة وحدة جديدة لعرضها على الموقع</p>
    <a href="{{ route('owner.properties.create') }}" style="background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; padding: 1rem 2rem; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);">
        <i class="fas fa-plus"></i>
        إضافة وحدة جديدة
    </a>
</div>
@endif
@endsection


