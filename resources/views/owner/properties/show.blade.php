@extends('layouts.owner')

@section('title', 'تفاصيل الوحدة - منصة دوميرا')
@section('page-title', 'تفاصيل الوحدة')

@push('styles')
<style>
    .property-header {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .property-code {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .property-code span {
        background: var(--primary);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
    }
    
    .header-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
    }
    
    .btn-back {
        background: #F3F4F6;
        color: #374151;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        background: #E5E7EB;
    }
    
    /* Image Gallery */
    .image-gallery {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }
    
    .main-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }
    
    .thumbnail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 0.5rem;
        padding: 0.75rem;
        background: #F9FAFB;
    }
    
    .thumbnail {
        aspect-ratio: 4/3;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.2s ease;
    }
    
    .thumbnail:hover, .thumbnail.active {
        border-color: var(--primary);
    }
    
    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Info Section */
    .info-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .section-title i {
        color: var(--primary);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.25rem;
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .info-label {
        font-size: 0.85rem;
        color: #6B7280;
        font-weight: 500;
    }
    
    .info-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .info-value.price {
        color: var(--primary);
        font-size: 1.5rem;
    }
    
    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-badge.pending {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .status-badge.approved {
        background: #D1FAE5;
        color: #059669;
    }
    
    .status-badge.rejected {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    /* Amenities */
    .amenities-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .amenity-tag {
        background: #F3F4F6;
        color: #374151;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .amenity-tag i {
        color: var(--primary);
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    .stat-card i {
        font-size: 1.5rem;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1F2937;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    /* Notes Box */
    .notes-box {
        background: #FEF3C7;
        border: 1px solid #F59E0B;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .notes-box h4 {
        font-weight: 700;
        color: #92400E;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .notes-box p {
        color: #78350F;
        font-size: 0.95rem;
    }
    
    @media (max-width: 768px) {
        .property-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .header-actions {
            justify-content: center;
        }
        
        .main-image {
            height: 250px;
        }
        
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<!-- Property Header -->
<div class="property-header">
    <div class="property-code">
        <i class="fas fa-building" style="font-size: 1.5rem; color: var(--primary);"></i>
        <div>
            <div style="font-size: 0.85rem; color: #6B7280;">كود الوحدة</div>
            <span>{{ $property->code }}</span>
        </div>
    </div>
    
    <div class="header-actions">
        <a href="{{ route('owner.properties.index') }}" class="btn-back">
            <i class="fas fa-arrow-right"></i>
            العودة للقائمة
        </a>
        <a href="{{ route('owner.properties.edit', $property) }}" class="btn-edit">
            <i class="fas fa-edit"></i>
            تعديل الوحدة
        </a>
    </div>
</div>

<!-- Quick Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <i class="fas fa-eye"></i>
        <div class="stat-value">{{ $property->views_count ?? 0 }}</div>
        <div class="stat-label">المشاهدات</div>
    </div>
    <div class="stat-card">
        <i class="fas fa-calendar-check"></i>
        <div class="stat-value">{{ $property->bookings->count() }}</div>
        <div class="stat-label">الحجوزات</div>
    </div>
    <div class="stat-card">
        <i class="fas fa-star"></i>
        <div class="stat-value">{{ number_format($property->average_rating, 1) }}</div>
        <div class="stat-label">التقييم</div>
    </div>
    <div class="stat-card">
        <i class="fas fa-comments"></i>
        <div class="stat-value">{{ $property->inquiries->count() ?? 0 }}</div>
        <div class="stat-label">الاستفسارات</div>
    </div>
</div>

<!-- Image Gallery -->
@if($property->images->count() > 0)
<div class="image-gallery">
    <img src="{{ $property->images->first()->url }}" alt="{{ $property->address }}" class="main-image" id="mainImage" loading="lazy" onerror="this.src='/images/placeholder.svg';">
    
    @if($property->images->count() > 1)
    <div class="thumbnail-grid">
        @foreach($property->images as $index => $image)
        <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeMainImage('{{ $image->url }}', this)">
            <img src="{{ $image->thumbnail_url }}" alt="صورة {{ $index + 1 }}" loading="lazy" onerror="this.src='/images/placeholder.svg';">
        </div>
        @endforeach
    </div>
    @endif
</div>
@else
<div class="image-gallery" style="padding: 3rem; text-align: center;">
    <i class="fas fa-images" style="font-size: 4rem; color: #D1D5DB; margin-bottom: 1rem;"></i>
    <p style="color: #6B7280;">لا توجد صور لهذه الوحدة</p>
</div>
@endif

<!-- Property Info -->
<div class="info-section">
    <h2 class="section-title">
        <i class="fas fa-info-circle"></i>
        معلومات الوحدة
    </h2>
    
    <div style="margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">{{ $property->address }}</h3>
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            @if($property->admin_status === 'pending')
                <span class="status-badge pending">
                    <i class="fas fa-clock"></i>
                    قيد المراجعة
                </span>
            @elseif($property->admin_status === 'approved')
                <span class="status-badge approved">
                    <i class="fas fa-check-circle"></i>
                    معتمدة
                </span>
            @else
                <span class="status-badge rejected">
                    <i class="fas fa-times-circle"></i>
                    مرفوضة
                </span>
            @endif
            
            <span style="background: #DBEAFE; color: #1E40AF; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600;">
                {{ $property->propertyType->name ?? 'غير محدد' }}
            </span>
            
            <span style="background: #F3F4F6; color: #374151; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600;">
                {{ $property->status === 'furnished' ? 'مفروش' : 'غير مفروش' }}
            </span>
        </div>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">السعر</span>
            <span class="info-value price">
                {{ number_format($property->price) }} ج.م
                <span style="font-size: 0.9rem; font-weight: 500;">/ {{ $property->price_type === 'monthly' ? 'شهر' : ($property->price_type === 'yearly' ? 'سنة' : 'يوم') }}</span>
            </span>
        </div>
        
        @if($property->area)
        <div class="info-item">
            <span class="info-label">المساحة</span>
            <span class="info-value">{{ $property->area }} م²</span>
        </div>
        @endif
        
        @if($property->rooms)
        <div class="info-item">
            <span class="info-label">عدد الغرف</span>
            <span class="info-value">{{ $property->rooms }}</span>
        </div>
        @endif
        
        @if($property->bathrooms)
        <div class="info-item">
            <span class="info-label">عدد الحمامات</span>
            <span class="info-value">{{ $property->bathrooms }}</span>
        </div>
        @endif
        
        @if($property->floor)
        <div class="info-item">
            <span class="info-label">الطابق</span>
            <span class="info-value">{{ $property->floor }}</span>
        </div>
        @endif
        
        @if($property->contract_duration)
        <div class="info-item">
            <span class="info-label">مدة العقد</span>
            <span class="info-value">
                {{ $property->contract_duration }} 
                @if($property->contract_duration_type)
                    @if($property->contract_duration_type === 'daily') يوم
                    @elseif($property->contract_duration_type === 'weekly') أسبوع
                    @elseif($property->contract_duration_type === 'monthly') شهر
                    @elseif($property->contract_duration_type === 'yearly') سنة
                    @endif
                @else
                    شهر
                @endif
            </span>
        </div>
        @endif
        
        @if($property->annual_increase)
        <div class="info-item">
            <span class="info-label">الزيادة السنوية</span>
            <span class="info-value">{{ $property->annual_increase }}%</span>
        </div>
        @endif
    </div>
    
    @if($property->admin_notes)
    <div class="notes-box">
        <h4><i class="fas fa-comment-alt"></i> ملاحظات الإدارة</h4>
        <p>{{ $property->admin_notes }}</p>
    </div>
    @endif
</div>

<!-- Amenities -->
@if($property->amenities && count($property->amenities) > 0)
<div class="info-section">
    <h2 class="section-title">
        <i class="fas fa-star"></i>
        المميزات والخدمات
    </h2>
    
    @php
        $amenitiesLabels = [
            'wifi' => ['واي فاي', 'fa-wifi'],
            'parking' => ['موقف سيارات', 'fa-parking'],
            'elevator' => ['مصعد', 'fa-chevron-up'],
            'security' => ['حراسة أمنية', 'fa-shield-alt'],
            'garden' => ['حديقة', 'fa-tree'],
            'pool' => ['مسبح', 'fa-swimming-pool'],
            'gym' => ['صالة رياضية', 'fa-dumbbell'],
            'ac' => ['تكييف', 'fa-snowflake'],
            'kitchen' => ['مطبخ مجهز', 'fa-utensils'],
            'laundry' => ['غسالة', 'fa-tshirt'],
            'balcony' => ['شرفة', 'fa-door-open'],
            'storage' => ['مخزن', 'fa-box'],
        ];
    @endphp
    
    <div class="amenities-list">
        @foreach($property->amenities as $amenity)
            @if(isset($amenitiesLabels[$amenity]))
            <span class="amenity-tag">
                <i class="fas {{ $amenitiesLabels[$amenity][1] }}"></i>
                {{ $amenitiesLabels[$amenity][0] }}
            </span>
            @endif
        @endforeach
    </div>
</div>
@endif

<!-- Rooms Section (if room rentable) -->
@if($property->is_room_rentable && $property->rooms && $property->rooms->count() > 0)
<div class="info-section">
    <h2 class="section-title">
        <i class="fas fa-door-open"></i>
        تفاصيل الغرف
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
        @foreach($property->rooms as $room)
        <div style="background: #F9FAFB; border: 2px solid #E5E7EB; border-radius: 12px; padding: 1.5rem; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#E5E7EB'; this.style.transform='translateY(0)'">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin: 0;">{{ $room->room_name }}</h3>
                @if($room->is_available)
                <span style="background: #D1FAE5; color: #059669; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">
                    <i class="fas fa-check-circle"></i> متاحة
                </span>
                @else
                <span style="background: #FEE2E2; color: #DC2626; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">
                    <i class="fas fa-times-circle"></i> غير متاحة
                </span>
                @endif
            </div>
            
            <div style="margin-bottom: 1rem;">
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 0.25rem;">
                    {{ number_format($room->price) }} ج.م
                    <span style="font-size: 0.9rem; font-weight: 500; color: #6B7280;">
                        / {{ $room->price_type === 'monthly' ? 'شهر' : ($room->price_type === 'yearly' ? 'سنة' : 'يوم') }}
                    </span>
                </div>
            </div>
            
            @if($room->description)
            <p style="color: #374151; line-height: 1.6; margin-bottom: 1rem; font-size: 0.95rem;">{{ $room->description }}</p>
            @endif
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; margin-bottom: 1rem;">
                @if($room->area)
                <div style="background: white; padding: 0.75rem; border-radius: 8px;">
                    <div style="font-size: 0.85rem; color: #6B7280; margin-bottom: 0.25rem;">المساحة</div>
                    <div style="font-weight: 700; color: #1F2937;">{{ $room->area }} م²</div>
                </div>
                @endif
                
                @if($room->beds)
                <div style="background: white; padding: 0.75rem; border-radius: 8px;">
                    <div style="font-size: 0.85rem; color: #6B7280; margin-bottom: 0.25rem;">عدد الأسرة</div>
                    <div style="font-weight: 700; color: #1F2937;">{{ $room->beds }} سرير</div>
                </div>
                @endif
            </div>
            
            @if($room->amenities && count($room->amenities) > 0)
            <div style="margin-bottom: 1rem;">
                <div style="font-size: 0.9rem; color: #6B7280; margin-bottom: 0.5rem; font-weight: 600;">المرافق:</div>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    @php
                        $roomAmenityLabels = [
                            'private_bathroom' => 'حمام خاص',
                            'tv' => 'تلفزيون',
                            'ac' => 'تكييف',
                            'wifi' => 'واي فاي',
                            'balcony' => 'شرفة',
                            'wardrobe' => 'خزانة'
                        ];
                    @endphp
                    @foreach($room->amenities as $amenity)
                        @if(isset($roomAmenityLabels[$amenity]))
                        <span style="background: white; color: #374151; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.85rem; font-weight: 500;">
                            {{ $roomAmenityLabels[$amenity] }}
                        </span>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
            
            @if($room->images && count($room->images) > 0)
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                @foreach(array_slice($room->images, 0, 3) as $image)
                <img src="{{ \App\Helpers\StorageHelper::url($image) }}" 
                     alt="{{ $room->room_name }}" 
                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #E5E7EB;"
                     onerror="this.src='/images/placeholder.svg';">
                @endforeach
                @if(count($room->images) > 3)
                <div style="width: 80px; height: 80px; background: #F3F4F6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6B7280; font-weight: 600; font-size: 0.9rem;">
                    +{{ count($room->images) - 3 }}
                </div>
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Special Requirements -->
@if($property->special_requirements)
<div class="info-section">
    <h2 class="section-title">
        <i class="fas fa-file-alt"></i>
        متطلبات خاصة
    </h2>
    <p style="color: #374151; line-height: 1.8;">{{ $property->special_requirements }}</p>
</div>
@endif

<!-- Video -->
@if($property->video_url)
<div class="info-section">
    <h2 class="section-title">
        <i class="fas fa-video"></i>
        فيديو الوحدة
    </h2>
    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px;">
        @php
            $videoId = '';
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $property->video_url, $match)) {
                $videoId = $match[1];
            }
        @endphp
        @if($videoId)
            <iframe 
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                src="https://www.youtube.com/embed/{{ $videoId }}" 
                allowfullscreen>
            </iframe>
        @else
            <a href="{{ $property->video_url }}" target="_blank" style="display: block; padding: 2rem; text-align: center; background: #F3F4F6; border-radius: 12px;">
                <i class="fas fa-external-link-alt" style="margin-left: 0.5rem;"></i>
                فتح رابط الفيديو
            </a>
        @endif
    </div>
</div>
@endif

<!-- Ownership Proof -->
@if($property->ownership_proof)
<div class="info-section">
    <h2 class="section-title">
        <i class="fas fa-file-contract"></i>
        إثبات الملكية
    </h2>
    @php
        $isPDF = pathinfo($property->ownership_proof, PATHINFO_EXTENSION) === 'pdf';
        $fileUrl = \App\Helpers\StorageHelper::url($property->ownership_proof);
    @endphp
    <div style="background: #F9FAFB; border: 2px solid #E5E7EB; border-radius: 12px; padding: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
            @if($isPDF)
            <div style="background: #EF4444; color: white; width: 80px; height: 80px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                <i class="fas fa-file-pdf"></i>
            </div>
            @else
            <img src="{{ $fileUrl }}" alt="إثبات الملكية" style="width: 80px; height: 80px; object-fit: cover; border-radius: 12px; border: 2px solid #E5E7EB;">
            @endif
            <div style="flex: 1;">
                <div style="font-weight: 700; color: #1F2937; margin-bottom: 0.5rem; font-size: 1.1rem;">ملف إثبات الملكية</div>
                <div style="font-size: 0.9rem; color: #6B7280;">{{ basename($property->ownership_proof) }}</div>
            </div>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="{{ $fileUrl }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.5rem; background: var(--primary); color: white; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-eye"></i>
                عرض الملف
            </a>
            <a href="{{ route('owner.properties.ownership-proof.download', $property) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.5rem; background: #10B981; color: white; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-download"></i>
                تحميل الملف
            </a>
        </div>
        @if($isPDF)
        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #E5E7EB;">
            <iframe src="{{ $fileUrl }}" style="width: 100%; height: 600px; border: none; border-radius: 8px;" frameborder="0"></iframe>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Map Location -->
@if($property->location_lat && $property->location_lng)
<div class="info-section">
    <h2 class="section-title">
        <i class="fas fa-map-marker-alt"></i>
        موقع الوحدة على الخريطة
    </h2>
    <div id="property-show-map" style="width: 100%; height: 400px; border-radius: 12px; border: 2px solid #E5E7EB;"></div>
    <div style="margin-top: 1rem; padding: 1rem; background: #F9FAFB; border-radius: 8px;">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <div>
                <span style="font-weight: 600; color: #374151;">خط العرض:</span>
                <span style="color: #6B7280;">{{ $property->location_lat }}</span>
            </div>
            <div>
                <span style="font-weight: 600; color: #374151;">خط الطول:</span>
                <span style="color: #6B7280;">{{ $property->location_lng }}</span>
            </div>
        </div>
    </div>
</div>
@endif

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
@if($property->location_lat && $property->location_lng)
// Initialize map for property show page
document.addEventListener('DOMContentLoaded', function() {
    const lat = {{ $property->location_lat }};
    const lng = {{ $property->location_lng }};
    
    const map = L.map('property-show-map').setView([lat, lng], 15);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Add marker
    L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
        })
    }).addTo(map).bindPopup('موقع الوحدة').openPopup();
});
@endif

function changeMainImage(url, thumbnail) {
    document.getElementById('mainImage').src = url;
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    thumbnail.classList.add('active');
}
</script>
@endpush
@endsection
