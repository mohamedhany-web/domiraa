@extends('layouts.admin')

@section('title', 'مراجعة الوحدة')
@section('page-title', 'مراجعة الوحدة')

@push('styles')
<style>
    .property-review {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }
    
    .property-images {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .property-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .info-card {
        background: #F9FAFB;
        border-radius: 8px;
        padding: 1.25rem;
    }
    
    .info-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #6B7280;
        font-size: 0.875rem;
    }
    
    .info-value {
        font-weight: 700;
        color: #1F2937;
        font-size: 0.875rem;
    }
    
    .action-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    .action-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1.5rem;
    }
    
    .action-form {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .action-form:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-textarea {
        width: 100%;
        padding: 0.875rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.875rem;
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }
    
    .form-textarea:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .btn-approve {
        background: linear-gradient(135deg, #6b8980 0%, #536b63 100%);
        color: white;
        font-weight: 700;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 137, 128, 0.3);
    }
    
    .btn-reject {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        color: white;
        font-weight: 700;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .badge-approved {
        background: #D1FAE5;
        color: #536b63;
    }
    
    .badge-rejected {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    @media (max-width: 768px) {
        .property-images {
            grid-template-columns: 1fr;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Property Details -->
<div class="property-review">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-900">{{ $property->address }}</h2>
        <span class="badge {{ $property->admin_status === 'pending' ? 'badge-pending' : ($property->admin_status === 'approved' ? 'badge-approved' : 'badge-rejected') }}">
            {{ $property->admin_status === 'pending' ? 'قيد المراجعة' : ($property->admin_status === 'approved' ? 'معتمد' : 'مرفوض') }}
        </span>
    </div>
    
    @if($property->images->count() > 0)
    <div class="property-images">
        @foreach($property->images as $image)
        <img src="{{ $image->url }}" alt="{{ $property->address }}" class="property-image" onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'; this.onerror=null;">
        @endforeach
    </div>
    @endif
    
    <div class="info-grid">
        <div class="info-card">
            <h3 class="info-title">
                <i class="fas fa-building"></i>
                معلومات الوحدة
            </h3>
            <div class="info-item">
                <span class="info-label">كود الوحدة:</span>
                <span class="info-value">{{ $property->code }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">النوع:</span>
                <span class="info-value">{{ $property->propertyType->name ?? 'غير محدد' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">الحالة:</span>
                <span class="info-value">{{ $property->status === 'furnished' ? 'مفروش' : 'على البلاط' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">السعر:</span>
                <span class="info-value">{{ number_format($property->price) }} 
                    {{ $property->price_type === 'monthly' ? 'شهري' : ($property->price_type === 'yearly' ? 'سنوي' : 'يومي') }}
                </span>
            </div>
            @if($property->contract_duration)
            <div class="info-item">
                <span class="info-label">مدة العقد:</span>
                <span class="info-value">
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
            </div>
            @endif
            @if($property->annual_increase)
            <div class="info-item">
                <span class="info-label">الزيادة السنوية:</span>
                <span class="info-value">{{ $property->annual_increase }}%</span>
            </div>
            @endif
        </div>
        
        <div class="info-card">
            <h3 class="info-title">
                <i class="fas fa-user"></i>
                معلومات المالك
            </h3>
            <div class="info-item">
                <span class="info-label">الاسم:</span>
                <span class="info-value">{{ $property->user->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">البريد:</span>
                <span class="info-value">{{ $property->user->email }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">الهاتف:</span>
                <span class="info-value">{{ $property->user->phone ?? '-' }}</span>
            </div>
        </div>
        
        @if($property->special_requirements || $property->video_url)
        <div class="info-card">
            <h3 class="info-title">
                <i class="fas fa-info-circle"></i>
                معلومات إضافية
            </h3>
            @if($property->video_url)
            <div class="info-item">
                <span class="info-label">رابط الفيديو:</span>
                <a href="{{ $property->video_url }}" target="_blank" class="info-value text-blue-600 hover:underline">
                    <i class="fas fa-external-link-alt ml-1"></i>
                    عرض
                </a>
            </div>
            @endif
            @if($property->special_requirements)
            <div class="info-item">
                <span class="info-label">اشتراطات خاصة:</span>
                <span class="info-value text-sm">{{ Str::limit($property->special_requirements, 50) }}</span>
            </div>
            @endif
        </div>
        @endif
    </div>
    
    <!-- Rooms Section (if room rentable) -->
    @if($property->is_room_rentable && $property->rooms && $property->rooms->count() > 0)
    <div class="info-card" style="margin-top: 1.5rem;">
        <h3 class="info-title">
            <i class="fas fa-door-open"></i>
            تفاصيل الغرف
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
            @foreach($property->rooms as $room)
            <div style="background: #F9FAFB; border: 2px solid #E5E7EB; border-radius: 12px; padding: 1.5rem; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#E5E7EB'; this.style.transform='translateY(0)'">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <h4 style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin: 0;">{{ $room->room_name }}</h4>
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
                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #E5E7EB; cursor: pointer;"
                         onclick="window.open('{{ \App\Helpers\StorageHelper::url($image) }}', '_blank')"
                         onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'; this.onerror=null;">
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
    
    @if($property->ownership_proof)
    <div class="info-card">
        <h3 class="info-title">
            <i class="fas fa-file-alt"></i>
            إثبات الملكية
        </h3>
        <a href="{{ \App\Helpers\StorageHelper::url($property->ownership_proof) }}" target="_blank" 
           class="text-blue-600 hover:text-blue-800 font-semibold">
            <i class="fas fa-download ml-2"></i>
            عرض/تحميل الملف
        </a>
    </div>
    @endif
    
    @if($property->admin_notes)
    <div class="info-card mt-4">
        <h3 class="info-title">
            <i class="fas fa-sticky-note"></i>
            ملاحظات سابقة
        </h3>
        <p class="text-gray-700">{{ $property->admin_notes }}</p>
    </div>
    @endif
</div>

<!-- Actions -->
<div class="action-section">
    <h2 class="action-title">اتخاذ قرار</h2>
    
    <form method="POST" action="{{ route('admin.properties.approve', $property) }}" class="action-form">
        @csrf
        <div class="form-group">
            <label class="form-label">ملاحظات (اختياري)</label>
            <textarea name="admin_notes" class="form-textarea" 
                      placeholder="أضف ملاحظات حول الموافقة...">{{ old('admin_notes') }}</textarea>
        </div>
        <button type="submit" class="btn-approve">
            <i class="fas fa-check ml-2"></i>
            الموافقة على الوحدة
        </button>
    </form>
    
    <form method="POST" action="{{ route('admin.properties.reject', $property) }}" class="action-form" id="rejectPropertyForm">
        @csrf
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf_token_input">
        <div class="form-group">
            <label class="form-label">سبب الرفض <span class="text-red-600">*</span></label>
            <textarea name="rejection_reason" id="rejection_reason" class="form-textarea" required minlength="10"
                      placeholder="اكتب سبب رفض الوحدة (على الأقل 10 أحرف)...">{{ old('rejection_reason', $property->rejection_reason) }}</textarea>
            @error('rejection_reason')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            @if(session('error'))
                <p class="text-red-600 text-sm mt-1">{{ session('error') }}</p>
            @endif
        </div>
        <button type="submit" class="btn-reject" id="rejectBtn">
            <i class="fas fa-times ml-2"></i>
            رفض الوحدة
        </button>
    </form>
    
    <script>
    document.getElementById('rejectPropertyForm')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('rejectBtn');
        const reason = document.getElementById('rejection_reason')?.value;
        
        if (!reason || reason.trim().length < 10) {
            e.preventDefault();
            alert('يجب كتابة سبب الرفض (على الأقل 10 أحرف)');
            return false;
        }
        
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري الرفض...';
        }
    });
    </script>
</div>
@endsection


