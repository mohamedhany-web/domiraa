@extends('layouts.app')

@section('title', 'تفاصيل الوحدة - ' . $property->address)

@push('styles')
<style>
    .property-hero {
        position: relative;
        height: 500px;
        overflow: hidden;
        border-radius: 20px;
        margin-bottom: 2rem;
    }
    
    .property-hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .property-hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
        padding: 2rem;
        color: white;
    }
    
    .property-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    
    .property-badge.residential {
        background: linear-gradient(135deg, #1d313f 0%, #2a4456 100%);
        color: white;
    }
    
    .property-badge.commercial {
        background: linear-gradient(135deg, #6b8980 0%, #8aa69d 100%);
        color: white;
    }
    
    .property-title {
        font-size: 2rem;
        font-weight: 800;
        color: white;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    
    .property-price-large {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #FCD34D 0%, #F59E0B 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 1rem 0;
    }
    
    .property-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .info-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .info-card-icon.blue {
        background: linear-gradient(135deg, rgba(29, 49, 63, 0.1) 0%, rgba(29, 49, 63, 0.15) 100%);
        color: #1d313f;
    }
    
    .info-card-icon.green {
        background: linear-gradient(135deg, rgba(107, 137, 128, 0.15) 0%, rgba(107, 137, 128, 0.2) 100%);
        color: #6b8980;
    }
    
    .info-card-icon.orange {
        background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
        color: #EF4444;
    }
    
    .info-card-icon.purple {
        background: linear-gradient(135deg, #EDE9FE 0%, #DDD6FE 100%);
        color: #7C3AED;
    }
    
    .info-card-label {
        font-size: 0.875rem;
        color: #6B7280;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .info-card-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1F2937;
    }
    
    .section-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1F2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #F3F4F6;
    }
    
    .section-title i {
        color: #1d313f;
        font-size: 1.5rem;
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 4/3;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .gallery-item:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .amenity-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 12px;
        border: 2px solid #E5E7EB;
        transition: all 0.3s ease;
    }
    
    .amenity-item:hover {
        background: rgba(107, 137, 128, 0.1);
        border-color: #1d313f;
        transform: translateX(-5px);
    }
    
    .amenity-item i {
        color: #1d313f;
        font-size: 1.25rem;
    }
    
    .amenity-item span {
        font-weight: 600;
        color: #374151;
    }
    
    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .action-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .action-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .action-card-title i {
        color: #1d313f;
    }
    
    .btn-primary-large {
        width: 100%;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        margin-top: 1rem;
        position: relative;
        z-index: 1;
    }
    
    .btn-primary-large:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(29, 49, 63, 0.3);
    }
    
    .btn-primary-large:active {
        transform: translateY(0);
    }
    
    a.btn-primary-large {
        pointer-events: auto;
        -webkit-tap-highlight-color: transparent;
    }
    
    .btn-secondary-large {
        width: 100%;
        padding: 1rem 2rem;
        background: #F3F4F6;
        color: #374151;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        margin-top: 1rem;
    }
    
    .btn-secondary-large:hover {
        background: #E5E7EB;
        transform: translateY(-2px);
    }
    
    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        font-family: 'Cairo', sans-serif;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .form-textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 1rem;
        margin-bottom: 1rem;
        min-height: 120px;
        resize: vertical;
        font-family: 'Cairo', sans-serif;
    }
    
    .form-textarea:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .property-code {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #FCD34D 0%, #F59E0B 100%);
        color: #78350F;
        border-radius: 25px;
        font-weight: 800;
        font-size: 1.1rem;
        margin-top: 1rem;
    }
    
    @media (max-width: 768px) {
        .property-hero {
            height: 350px;
        }
        
        .property-title {
            font-size: 1.5rem;
        }
        
        .property-price-large {
            font-size: 2rem;
        }
        
        .property-info-grid {
            grid-template-columns: 1fr;
        }
        
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .action-buttons {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Hero Section with Main Image -->
        <div class="property-hero">
            @if($property->images->first())
                <img src="{{ $property->images->first()->url }}" 
                     alt="{{ $property->address }}" 
                     class="property-hero-image"
                     loading="lazy"
                     onerror="this.src='/images/placeholder.svg';">
            @else
                <img src="/images/placeholder.svg" 
                     alt="{{ $property->address }}" 
                     class="property-hero-image">
            @endif
            <div class="property-hero-overlay">
                <span class="property-badge {{ $property->propertyType->slug ?? '' }}">
                    {{ $property->propertyType->name ?? 'غير محدد' }}
                </span>
                <h1 class="property-title">{{ $property->address }}</h1>
                @if(!$property->is_room_rentable)
                <div class="property-price-large">
                    {{ number_format($property->price) }}
                    <span style="font-size: 1.5rem;">
                        {{ $property->price_type === 'monthly' ? ' /شهر' : ($property->price_type === 'yearly' ? ' /سنة' : ' /يوم') }}
                    </span>
                </div>
                @else
                <div class="property-price-large">
                    قابلة للمشاركة
                    <span style="font-size: 1.5rem; display: block; margin-top: 0.5rem;">
                        @php
                            $availableRoomsCount = 0;
                            if ($property->is_room_rentable) {
                                try {
                                    $rooms = $property->relationLoaded('rooms') ? $property->getRelation('rooms') : $property->rooms()->get();
                                    
                                    if ($rooms && (is_countable($rooms) || is_iterable($rooms))) {
                                        foreach ($rooms as $room) {
                                            // Check if room is available - handle different data types
                                            $isAvailable = false;
                                            
                                            // Direct boolean check
                                            if ($room->is_available === true) {
                                                $isAvailable = true;
                                            }
                                            // Integer check (1 = true, 0 = false)
                                            elseif ($room->is_available === 1) {
                                                $isAvailable = true;
                                            }
                                            // String check
                                            elseif ($room->is_available === '1' || (is_string($room->is_available) && strtolower($room->is_available) === 'true')) {
                                                $isAvailable = true;
                                            }
                                            // Cast check - if it's cast as boolean, check the raw value
                                            elseif (isset($room->attributes['is_available'])) {
                                                $rawValue = $room->attributes['is_available'];
                                                if ($rawValue === 1 || $rawValue === '1' || $rawValue === true || (is_string($rawValue) && strtolower($rawValue) === 'true')) {
                                                    $isAvailable = true;
                                                }
                                            }
                                            
                                            if ($isAvailable) {
                                                $availableRoomsCount++;
                                            }
                                        }
                                    }
                                } catch (\Exception $e) {
                                    // If there's an error, try to get rooms directly
                                    if (isset($property->rooms) && is_countable($property->rooms)) {
                                        foreach ($property->rooms as $room) {
                                            if ($room->is_available === true || $room->is_available === 1 || $room->is_available === '1') {
                                                $availableRoomsCount++;
                                            }
                                        }
                                    }
                                }
                            }
                        @endphp
                        {{ $availableRoomsCount }} غرفة متاحة
                    </span>
                </div>
                @endif
                <div class="property-code">كود الوحدة: {{ $property->code }}</div>
            </div>
        </div>
        
        <!-- Property Info Cards -->
        <div class="property-info-grid">
            @if($property->area)
            <div class="info-card">
                <div class="info-card-icon blue">
                    <i class="fas fa-ruler-combined"></i>
                </div>
                <div class="info-card-label">المساحة</div>
                <div class="info-card-value">{{ $property->area }} م²</div>
            </div>
            @endif
            
            @if($property->rooms)
            <div class="info-card">
                <div class="info-card-icon green">
                    <i class="fas fa-bed"></i>
                </div>
                <div class="info-card-label">عدد الغرف</div>
                <div class="info-card-value">{{ $property->rooms }} غرفة</div>
            </div>
            @endif
            
            @if($property->bathrooms)
            <div class="info-card">
                <div class="info-card-icon orange">
                    <i class="fas fa-bath"></i>
                </div>
                <div class="info-card-label">عدد الحمامات</div>
                <div class="info-card-value">{{ $property->bathrooms }} حمام</div>
            </div>
            @endif
            
            @if($property->floor)
            <div class="info-card">
                <div class="info-card-icon purple">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="info-card-label">الدور</div>
                <div class="info-card-value">{{ $property->floor }}</div>
            </div>
            @endif
        </div>
        
        <!-- Gallery Section -->
        @if($property->images->count() > 1)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="section-title">
                <i class="fas fa-images"></i>
                معرض الصور
            </h2>
            <div class="gallery-grid">
                @foreach($property->images->skip(1) as $image)
                    <div class="gallery-item">
                        <img src="{{ $image->thumbnail_url }}" 
                             data-full="{{ $image->url }}"
                             alt="صورة الوحدة"
                             loading="lazy"
                             onclick="openLightbox('{{ $image->url }}')"
                             onerror="this.src='/images/placeholder.svg';">
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Details Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="section-title">
                <i class="fas fa-info-circle"></i>
                تفاصيل الوحدة
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1F2937; margin-bottom: 1rem;">المعلومات الأساسية</h3>
                    <ul style="list-style: none; padding: 0; space-y: 0.75rem;">
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #F3F4F6; display: flex; justify-content: space-between;">
                            <span style="color: #6B7280; font-weight: 600;">النوع:</span>
                            <span style="color: #1F2937; font-weight: 700;">{{ $property->propertyType->name ?? 'غير محدد' }}</span>
                        </li>
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #F3F4F6; display: flex; justify-content: space-between;">
                            <span style="color: #6B7280; font-weight: 600;">الحالة:</span>
                            <span style="color: #1F2937; font-weight: 700;">{{ $property->status === 'furnished' ? 'مفروش' : 'على البلاط' }}</span>
                        </li>
                        @if($property->contract_duration)
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #F3F4F6; display: flex; justify-content: space-between;">
                            <span style="color: #6B7280; font-weight: 600;">مدة العقد:</span>
                            <span style="color: #1F2937; font-weight: 700;">
                                {{ $property->contract_duration }} 
                                @if($property->contract_duration_type)
                                    @if($property->contract_duration_type === 'daily') يوم
                                    @elseif($property->contract_duration_type === 'weekly') أسبوع
                                    @elseif($property->contract_duration_type === 'monthly') شهر
                                    @elseif($property->contract_duration_type === 'yearly') سنة
                                    @endif
                                @else
                                    سنة
                                @endif
                            </span>
                        </li>
                        @endif
                        @if($property->annual_increase)
                        <li style="padding: 0.75rem 0; display: flex; justify-content: space-between;">
                            <span style="color: #6B7280; font-weight: 600;">الزيادة السنوية:</span>
                            <span style="color: #1F2937; font-weight: 700;">{{ $property->annual_increase }}%</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Rooms Section (if room rentable and approved) -->
        @php
            $availableRooms = collect([]);
            // Always check if property is room rentable and approved
            if ($property->is_room_rentable && $property->admin_status === 'approved') {
                // Try to get rooms using the relation method
                try {
                    $rooms = $property->relationLoaded('rooms') ? $property->getRelation('rooms') : $property->rooms()->get();
                    
                    if ($rooms && (is_countable($rooms) || is_iterable($rooms))) {
                        foreach ($rooms as $room) {
                            // Check if room is available - handle different data types
                            $isAvailable = false;
                            
                            // Direct boolean check
                            if ($room->is_available === true) {
                                $isAvailable = true;
                            }
                            // Integer check (1 = true, 0 = false)
                            elseif ($room->is_available === 1) {
                                $isAvailable = true;
                            }
                            // String check
                            elseif ($room->is_available === '1' || (is_string($room->is_available) && strtolower($room->is_available) === 'true')) {
                                $isAvailable = true;
                            }
                            // Cast check - if it's cast as boolean, check the raw value
                            elseif (isset($room->attributes['is_available'])) {
                                $rawValue = $room->attributes['is_available'];
                                if ($rawValue === 1 || $rawValue === '1' || $rawValue === true || (is_string($rawValue) && strtolower($rawValue) === 'true')) {
                                    $isAvailable = true;
                                }
                            }
                            
                            if ($isAvailable) {
                                $availableRooms->push($room);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // If there's an error, try to get rooms directly
                    if (isset($property->rooms) && is_countable($property->rooms)) {
                        foreach ($property->rooms as $room) {
                            if ($room->is_available === true || $room->is_available === 1 || $room->is_available === '1') {
                                $availableRooms->push($room);
                            }
                        }
                    }
                }
            }
        @endphp
        
        @if($property->is_room_rentable && $property->admin_status === 'approved' && $availableRooms->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="section-title">
                <i class="fas fa-door-open"></i>
                تفاصيل الغرف المتاحة
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
                @foreach($availableRooms as $room)
                <div style="background: linear-gradient(135deg, #F9FAFB 0%, #FFFFFF 100%); border: 2px solid #E5E7EB; border-radius: 16px; padding: 1.5rem; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.05);" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.1)'" onmouseout="this.style.borderColor='#E5E7EB'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--primary); margin: 0;">{{ $room->room_name }}</h3>
                        <span style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.9rem; font-weight: 700; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);">
                            <i class="fas fa-check-circle"></i> متاحة
                        </span>
                    </div>
                    
                    <div style="margin-bottom: 1.25rem;">
                        <div style="font-size: 2rem; font-weight: 900; color: var(--primary); margin-bottom: 0.25rem;">
                            {{ number_format($room->price) }} ج.م
                        </div>
                        <div style="font-size: 1rem; color: #6B7280; font-weight: 600;">
                            / {{ $room->price_type === 'monthly' ? 'شهر' : ($room->price_type === 'yearly' ? 'سنة' : 'يوم') }}
                        </div>
                    </div>
                    
                    @if($room->description)
                    <p style="color: #374151; line-height: 1.8; margin-bottom: 1.25rem; font-size: 1rem;">{{ $room->description }}</p>
                    @endif
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.25rem;">
                        @if($room->area)
                        <div style="background: white; padding: 1rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div style="font-size: 0.85rem; color: #6B7280; margin-bottom: 0.5rem; font-weight: 600;">
                                <i class="fas fa-ruler-combined" style="margin-left: 0.5rem;"></i>المساحة
                            </div>
                            <div style="font-weight: 800; color: #1F2937; font-size: 1.25rem;">{{ $room->area }} م²</div>
                        </div>
                        @endif
                        
                        @if($room->beds)
                        <div style="background: white; padding: 1rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div style="font-size: 0.85rem; color: #6B7280; margin-bottom: 0.5rem; font-weight: 600;">
                                <i class="fas fa-bed" style="margin-left: 0.5rem;"></i>عدد الأسرة
                            </div>
                            <div style="font-weight: 800; color: #1F2937; font-size: 1.25rem;">{{ $room->beds }} سرير</div>
                        </div>
                        @endif
                    </div>
                    
                    @if($room->amenities && count($room->amenities) > 0)
                    <div style="margin-bottom: 1.25rem;">
                        <div style="font-size: 1rem; color: #374151; margin-bottom: 0.75rem; font-weight: 700;">
                            <i class="fas fa-star" style="margin-left: 0.5rem; color: var(--primary);"></i>المرافق المتاحة:
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                            @php
                                $roomAmenityLabels = [
                                    'private_bathroom' => ['حمام خاص', 'fa-bath'],
                                    'tv' => ['تلفزيون', 'fa-tv'],
                                    'ac' => ['تكييف', 'fa-snowflake'],
                                    'wifi' => ['واي فاي', 'fa-wifi'],
                                    'balcony' => ['شرفة', 'fa-door-open'],
                                    'wardrobe' => ['خزانة', 'fa-archive']
                                ];
                            @endphp
                            @foreach($room->amenities as $amenity)
                                @if(isset($roomAmenityLabels[$amenity]))
                                <span style="background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%); color: #374151; padding: 0.625rem 1rem; border-radius: 10px; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                    <i class="fas {{ $roomAmenityLabels[$amenity][1] }}" style="color: var(--primary);"></i>
                                    {{ $roomAmenityLabels[$amenity][0] }}
                                </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($room->images && count($room->images) > 0)
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        @foreach(array_slice($room->images, 0, 4) as $image)
                        <img src="{{ \App\Helpers\StorageHelper::url($image) }}" 
                             alt="{{ $room->room_name }}" 
                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; border: 2px solid #E5E7EB; cursor: pointer; transition: all 0.3s ease;"
                             onmouseover="this.style.transform='scale(1.1)'; this.style.borderColor='var(--primary)'"
                             onmouseout="this.style.transform='scale(1)'; this.style.borderColor='#E5E7EB'"
                             onclick="window.open('{{ \App\Helpers\StorageHelper::url($image) }}', '_blank')"
                             onerror="this.src='/images/placeholder.svg';">
                        @endforeach
                        @if(count($room->images) > 4)
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #6B7280; font-weight: 700; font-size: 1.1rem; border: 2px solid #E5E7EB;">
                            +{{ count($room->images) - 4 }}
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    <!-- زر حجز الغرفة -->
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid #E5E7EB;">
                        @if($room->is_available)
                        <a href="{{ route('room.booking.create', ['property' => $property->id, 'room' => $room->id]) }}" 
                           style="display: block; width: 100%; padding: 1rem; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; border-radius: 12px; text-decoration: none; font-weight: 700; text-align: center; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(59, 130, 246, 0.4)'"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)'">
                            <i class="fas fa-calendar-check" style="margin-left: 0.75rem;"></i>
                            حجز هذه الغرفة
                        </a>
                        @else
                        <div style="display: block; width: 100%; padding: 1rem; background: linear-gradient(135deg, #EF4444, #DC2626); color: white; border-radius: 12px; text-align: center; font-weight: 700; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);">
                            <i class="fas fa-lock" style="margin-left: 0.75rem;"></i>
                            تم حجز هذه الغرفة
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Amenities Section -->
        @if($property->amenities && count($property->amenities) > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="section-title">
                <i class="fas fa-star"></i>
                المرافق المتاحة
            </h2>
            <div class="amenities-grid">
                @php
                    $amenityIcons = [
                        'gas' => 'fa-fire',
                        'electricity' => 'fa-bolt',
                        'water' => 'fa-tint',
                        'internet' => 'fa-wifi',
                        'elevator' => 'fa-arrow-up',
                        'parking' => 'fa-car',
                    ];
                    $amenityNames = [
                        'gas' => 'غاز',
                        'electricity' => 'كهرباء',
                        'water' => 'مياه',
                        'internet' => 'إنترنت',
                        'elevator' => 'مصعد',
                        'parking' => 'موقف سيارات',
                    ];
                @endphp
                @foreach($property->amenities as $amenity)
                    <div class="amenity-item">
                        <i class="fas {{ $amenityIcons[$amenity] ?? 'fa-check' }}"></i>
                        <span>{{ $amenityNames[$amenity] ?? $amenity }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Special Requirements -->
        @if($property->special_requirements)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="section-title">
                <i class="fas fa-clipboard-list"></i>
                اشتراطات خاصة
            </h2>
            <p style="color: #374151; font-size: 1.1rem; line-height: 1.8;">{{ $property->special_requirements }}</p>
        </div>
        @endif
        
        <!-- Video Section -->
        @if($property->video_url)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="section-title">
                <i class="fas fa-video"></i>
                فيديو الوحدة
            </h2>
            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px;">
                <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                        src="{{ $property->video_url }}" 
                        frameborder="0" 
                        allowfullscreen></iframe>
            </div>
        </div>
        @endif
        
        <!-- Rooms Section (if property is room rentable) -->
        @if($property->is_room_rentable && $property->rooms && $property->rooms->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="section-title">
                <i class="fas fa-door-open"></i>
                الغرف المتاحة للإيجار
            </h2>
            <p style="color: #6B7280; margin-bottom: 2rem;">يمكنك حجز غرفة واحدة أو أكثر من هذه الوحدة</p>
            
            <div class="rooms-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                @foreach($property->rooms as $room)
                <div class="room-card" style="background: #F9FAFB; border-radius: 12px; padding: 1.5rem; border: 2px solid {{ $room->is_available ? '#E5E7EB' : '#FEE2E2' }}; position: relative;">
                    @if(!$room->is_available)
                    <div style="position: absolute; top: 1rem; left: 1rem; background: #EF4444; color: white; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem;">
                        محجوزة
                    </div>
                    @endif
                    
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1F2937; margin: 0;">
                                {{ $room->room_name }}
                                @if($room->room_number)
                                <span style="color: #6B7280; font-size: 0.875rem;">({{ $room->room_number }})</span>
                                @endif
                            </h3>
                            @if($room->description)
                            <p style="color: #6B7280; font-size: 0.9rem; margin-top: 0.5rem;">{{ Str::limit($room->description, 100) }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($room->images && count($room->images) > 0)
                    <div style="margin-bottom: 1rem; border-radius: 8px; overflow: hidden;">
                        <img src="{{ \App\Helpers\StorageHelper::url($room->images[0]) }}" alt="{{ $room->room_name }}" style="width: 100%; height: 200px; object-fit: cover;" onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'">
                    </div>
                    @endif
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                        @if($room->area)
                        <div style="text-align: center; padding: 0.75rem; background: white; border-radius: 8px;">
                            <div style="color: #6B7280; font-size: 0.875rem; margin-bottom: 0.25rem;">المساحة</div>
                            <div style="color: #1F2937; font-weight: 700;">{{ $room->area }} م²</div>
                        </div>
                        @endif
                        
                        <div style="text-align: center; padding: 0.75rem; background: white; border-radius: 8px;">
                            <div style="color: #6B7280; font-size: 0.875rem; margin-bottom: 0.25rem;">الأسرة</div>
                            <div style="color: #1F2937; font-weight: 700;">{{ $room->beds }}</div>
                        </div>
                    </div>
                    
                    @if($room->amenities && count($room->amenities) > 0)
                    <div style="margin-bottom: 1rem;">
                        <div style="color: #6B7280; font-size: 0.875rem; margin-bottom: 0.5rem; font-weight: 600;">المرافق:</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            @php
                                $amenityLabels = [
                                    'private_bathroom' => 'حمام خاص',
                                    'tv' => 'تلفزيون',
                                    'ac' => 'تكييف',
                                    'wifi' => 'واي فاي',
                                    'balcony' => 'شرفة',
                                    'wardrobe' => 'خزانة',
                                ];
                            @endphp
                            @foreach($room->amenities as $amenity)
                            <span style="background: white; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.875rem; color: #1F2937;">
                                {{ $amenityLabels[$amenity] ?? $amenity }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 2px solid #E5E7EB;">
                        <div>
                            <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">
                                {{ number_format($room->price) }}
                            </div>
                            <div style="color: #6B7280; font-size: 0.875rem;">
                                {{ $room->price_type === 'monthly' ? 'شهري' : ($room->price_type === 'yearly' ? 'سنوي' : 'يومي') }}
                            </div>
                        </div>
                        
                        @if($room->is_available)
                        <a href="{{ route('room.booking.create', ['property' => $property->id, 'room' => $room->id]) }}" 
                           class="btn-primary" 
                           style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; border-radius: 8px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-calendar-check"></i>
                            حجز الغرفة
                        </a>
                        @else
                        <button disabled style="padding: 0.75rem 1.5rem; background: #E5E7EB; color: #9CA3AF; border-radius: 8px; border: none; font-weight: 700; cursor: not-allowed;">
                            غير متاحة
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            @if(!$property->is_room_rentable)
            <div class="action-card">
                <h3 class="action-card-title">
                    <i class="fas fa-envelope"></i>
                    إرسال استفسار
                </h3>
                <form method="POST" action="{{ route('inquiry.store') }}">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    <input type="hidden" name="property_code" value="{{ $property->code }}">
                    <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}" 
                           class="form-input" 
                           placeholder="الاسم" required>
                    <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}" 
                           class="form-input" 
                           placeholder="رقم الهاتف" required>
                    <textarea name="message" rows="4" 
                              class="form-textarea" 
                              placeholder="رسالتك..." required></textarea>
                    <button type="submit" class="btn-primary-large">
                        <i class="fas fa-paper-plane"></i>
                        إرسال الاستفسار
                    </button>
                </form>
            </div>
            
            <div class="action-card">
                <h3 class="action-card-title">
                    <i class="fas fa-calendar-check"></i>
                    حجز موعد للمعاينة
                </h3>
                <a href="/property/{{ $property->id }}/inspection" 
                   class="btn-primary-large" 
                   style="text-decoration: none; display: block; width: 100%; cursor: pointer;">
                    <i class="fas fa-calendar-alt"></i>
                    حجز موعد الآن
                </a>
                @guest
                <p style="color: #6B7280; margin-top: 1rem; font-size: 0.875rem; text-align: center;">
                    يمكنك حجز موعد المعاينة بدون تسجيل دخول
                </p>
                @endguest
            </div>
            @endif
        </div>
    </div>
</div>
@endsection


