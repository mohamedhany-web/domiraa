@extends('layouts.owner')

@section('title', 'تعديل الوحدة - منصة دوميرا')
@section('page-title', 'تعديل الوحدة')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Form Sections */
    .form-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .section-title i {
        color: #1d313f;
        font-size: 1.25rem;
    }
    
    /* Form Elements */
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-label.required::after {
        content: ' *';
        color: #EF4444;
    }
    
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: 'Cairo', sans-serif;
        background: #FAFAFA;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
        background: white;
    }
    
    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }
    
    .form-help {
        font-size: 0.875rem;
        color: #6B7280;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-help i {
        color: #9CA3AF;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    
    /* File Upload */
    .file-upload-area {
        border: 2px dashed #D1D5DB;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        background: #F9FAFB;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .file-upload-area:hover {
        border-color: #1d313f;
        background: #F0F9FF;
    }
    
    .file-upload-area.dragover {
        border-color: #1d313f;
        background: #DBEAFE;
    }
    
    .file-upload-icon {
        font-size: 3rem;
        color: #9CA3AF;
        margin-bottom: 1rem;
    }
    
    .file-upload-text {
        color: #374151;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .file-upload-hint {
        color: #6B7280;
        font-size: 0.875rem;
    }
    
    .file-input {
        display: none;
    }
    
    .file-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .file-preview-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
    }
    
    .file-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .file-preview-remove {
        position: absolute;
        top: 0.5rem;
        left: 0.5rem;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.875rem;
    }
    
    /* Current Images */
    .current-images {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .current-image {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .current-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .current-image .image-order {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: #1d313f;
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
    }
    
    /* Preview Grid */
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .preview-item {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .preview-item .remove-btn {
        position: absolute;
        top: 0.5rem;
        left: 0.5rem;
        width: 28px;
        height: 28px;
        background: #DC2626;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }
    
    .preview-item .remove-btn:hover {
        transform: scale(1.1);
        background: #B91C1C;
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
    
    /* Info Box */
    .info-box {
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .info-box i {
        color: #3B82F6;
        margin-top: 0.2rem;
    }
    
    .info-box p {
        color: #1E40AF;
        font-size: 0.9rem;
        margin: 0;
    }
    
    /* Amenities */
    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.75rem;
    }
    
    .amenity-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }
    
    .amenity-item:hover {
        background: #F3F4F6;
    }
    
    .amenity-item.selected {
        background: rgba(29, 49, 63, 0.1);
        border-color: #1d313f;
    }
    
    .amenity-item input[type="checkbox"] {
        accent-color: #1d313f;
        width: 18px;
        height: 18px;
    }
    
    .amenity-item span {
        color: #374151;
        font-weight: 500;
    }
    
    /* Room Items */
    .room-item {
        background: #F9FAFB;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        border: 2px solid #E5E7EB;
    }
    
    .room-item h4 {
        margin-bottom: 1rem;
        color: #1d313f;
        font-weight: 700;
    }
    
    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #E5E7EB;
    }
    
    .btn {
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
        text-decoration: none;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(29, 49, 63, 0.4);
    }
    
    .btn-secondary {
        background: #F3F4F6;
        color: #374151;
    }
    
    .btn-secondary:hover {
        background: #E5E7EB;
    }
    
    /* Error Messages */
    .error-messages {
        background: #FEE2E2;
        border: 1px solid #EF4444;
        color: #DC2626;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    
    .error-messages strong {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .error-messages ul {
        margin: 0;
        padding-right: 1.5rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .form-section {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .file-upload-area {
            padding: 1.5rem;
        }
        
        .file-upload-icon {
            font-size: 2.5rem;
        }
        
        .form-actions {
            flex-direction: column-reverse;
            gap: 0.75rem;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
        
        .amenities-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 480px) {
        .form-section {
            padding: 1rem;
        }
        
        .section-title {
            font-size: 1.125rem;
        }
        
        .file-upload-area {
            padding: 1rem;
        }
        
        .file-preview,
        .current-images,
        .preview-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
@if ($errors->any())
<div class="error-messages">
    <strong>
        <i class="fas fa-exclamation-circle"></i>
        يرجى تصحيح الأخطاء التالية:
    </strong>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('owner.properties.update', $property) }}" enctype="multipart/form-data" id="editPropertyForm">
    @csrf
    @method('PUT')
    
    <!-- حالة الوحدة -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="fas fa-info-circle"></i>
            حالة الوحدة
        </h2>
        
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <span>حالة الموافقة:</span>
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
            
            <span style="color: #6B7280; font-size: 0.9rem;">
                كود الوحدة: <strong>{{ $property->code }}</strong>
            </span>
        </div>
        
        @if($property->admin_status === 'rejected' && $property->rejection_reason)
        <div class="info-box" style="background-color: #FEF2F2; border-color: #FCA5A5;">
            <i class="fas fa-times-circle" style="color: #DC2626;"></i>
            <div>
                <p style="color: #DC2626; font-weight: 600; margin-bottom: 0.5rem;">سبب الرفض:</p>
                <p style="color: #991B1B;">{{ $property->rejection_reason }}</p>
                <p style="color: #991B1B; font-size: 0.875rem; margin-top: 0.5rem;">
                    <i class="fas fa-info-circle"></i>
                    يمكنك تعديل الوحدة بعد إصلاح المشاكل المذكورة. سيتم إعادة إرسالها للمراجعة بعد التحديث.
                </p>
            </div>
        </div>
        @elseif($property->admin_notes)
        <div class="info-box">
            <i class="fas fa-comment-alt"></i>
            <p><strong>ملاحظات الإدارة:</strong> {{ $property->admin_notes }}</p>
        </div>
        @endif
    </div>
    
    <!-- المعلومات الأساسية -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="fas fa-building"></i>
            المعلومات الأساسية
        </h2>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label required">نوع الوحدة</label>
                <select name="property_type_id" class="form-select" required>
                    <option value="">اختر النوع</option>
                    @foreach(\App\Models\PropertyType::active() as $type)
                    <option value="{{ $type->id }}" {{ old('property_type_id', $property->property_type_id) == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label required">حالة التأثيث</label>
                <select name="status" class="form-select" required>
                    <option value="furnished" {{ old('status', $property->status) === 'furnished' ? 'selected' : '' }}>مفروش</option>
                    <option value="unfurnished" {{ old('status', $property->status) === 'unfurnished' ? 'selected' : '' }}>غير مفروش</option>
                </select>
            </div>
            
            <div class="form-group full-width">
                <label class="form-label required">العنوان</label>
                <input type="text" name="address" class="form-input" value="{{ old('address', $property->address) }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">المساحة (م²)</label>
                <input type="number" name="area" class="form-input" value="{{ old('area', $property->area) }}" min="1" step="1">
            </div>
            
            <div class="form-group" id="roomsFieldGroup">
                <label class="form-label">عدد الغرف</label>
                <input type="number" name="rooms" id="roomsField" class="form-input" value="{{ old('rooms', $property->rooms) }}" min="0" step="1">
            </div>
            
            <div class="form-group">
                <label class="form-label">عدد الحمامات</label>
                <input type="number" name="bathrooms" class="form-input" value="{{ old('bathrooms', $property->bathrooms) }}" min="0" step="1">
            </div>
            
            <div class="form-group">
                <label class="form-label">الطابق</label>
                <input type="text" name="floor" class="form-input" value="{{ old('floor', $property->floor) }}">
            </div>
        </div>
    </div>
    
    <!-- الموقع -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="fas fa-map-marker-alt"></i>
            الموقع على الخريطة
        </h2>
        
        <div class="form-group">
            <label class="form-label">ابحث عن العنوان</label>
            <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                <input type="text" id="address-search" class="form-input" placeholder="ابحث عن عنوان أو مكان...">
                <button type="button" onclick="searchAddress()" style="padding: 0.875rem 1.5rem; background: #1d313f; color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                    <i class="fas fa-search"></i> بحث
                </button>
            </div>
        </div>
        
        <div class="form-group">
            <div id="property-map" style="width: 100%; height: 400px; border-radius: 12px; border: 2px solid #E5E7EB; margin-bottom: 1rem;"></div>
            <div class="form-help">
                <i class="fas fa-info-circle"></i>
                <span>انقر على الخريطة لتحديد موقع الوحدة بدقة</span>
            </div>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">خط العرض (Latitude)</label>
                <input type="text" name="location_lat" id="location_lat" class="form-input" value="{{ old('location_lat', $property->location_lat) }}" placeholder="سيتم التحديد تلقائياً من الخريطة" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">خط الطول (Longitude)</label>
                <input type="text" name="location_lng" id="location_lng" class="form-input" value="{{ old('location_lng', $property->location_lng) }}" placeholder="سيتم التحديد تلقائياً من الخريطة" readonly>
            </div>
        </div>
    </div>
    
    <!-- الأسعار والعقد -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="fas fa-money-bill-wave"></i>
            السعر والتعاقد
        </h2>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_room_rentable" id="is_room_rentable" value="1" {{ old('is_room_rentable', $property->is_room_rentable) ? 'checked' : '' }} onchange="toggleRoomRental(this)">
                <span class="form-label" style="margin: 0;">الوحدة قابلة للمشاركة (أكثر من مستأجر واحد)</span>
            </label>
        </div>
        
        <div class="form-grid" id="normalPriceSection" style="{{ $property->is_room_rentable ? 'display: none;' : '' }}">
            <div class="form-group">
                <label class="form-label">السعر</label>
                <input type="number" name="price" id="property_price" class="form-input" value="{{ old('price', $property->price) }}" step="0.01" min="0" {{ !$property->is_room_rentable ? 'required' : '' }}>
            </div>
            
            <div class="form-group">
                <label class="form-label">نوع السعر</label>
                <select name="price_type" id="property_price_type" class="form-select" {{ !$property->is_room_rentable ? 'required' : '' }}>
                    <option value="daily" {{ old('price_type', $property->price_type) === 'daily' ? 'selected' : '' }}>يومي</option>
                    <option value="monthly" {{ old('price_type', $property->price_type) === 'monthly' ? 'selected' : '' }}>شهري</option>
                    <option value="yearly" {{ old('price_type', $property->price_type) === 'yearly' ? 'selected' : '' }}>سنوي</option>
                </select>
            </div>
        </div>
        
        <div id="roomRentalSection" style="display: {{ $property->is_room_rentable ? 'block' : 'none' }};">
            <div class="form-group">
                <label class="form-label">عدد الغرف القابلة للإيجار</label>
                @php
                    $roomsCount = 0;
                    // Use rooms() method to get relation, not property
                    try {
                        if ($property->relationLoaded('rooms')) {
                            $roomsCollection = $property->getRelation('rooms');
                        } else {
                            $roomsCollection = $property->rooms()->get();
                        }
                        if ($roomsCollection && is_object($roomsCollection) && method_exists($roomsCollection, 'count')) {
                            $roomsCount = $roomsCollection->count();
                        }
                    } catch (\Exception $e) {
                        $roomsCount = 0;
                    }
                    if ($roomsCount == 0 && $property->total_rooms) {
                        $roomsCount = $property->total_rooms;
                    }
                @endphp
                <input type="number" name="total_rooms" id="total_rooms" min="1" class="form-input" value="{{ old('total_rooms', $property->total_rooms ?? $roomsCount) }}" onchange="updateRoomsList()" {{ $property->is_room_rentable ? 'required' : '' }}>
            </div>
            
            <div id="roomsContainer" style="margin-top: 1.5rem;">
                @php
                    // Use rooms() method to get relation, not property
                    // Always use the relation method to avoid conflict with 'rooms' field
                    $roomsRelation = collect([]);
                    try {
                        // Always use rooms() method, not property access
                        $roomsRelation = $property->rooms()->get();
                        // Ensure it's a collection
                        if (!is_object($roomsRelation) || !method_exists($roomsRelation, 'count')) {
                            $roomsRelation = collect([]);
                        }
                    } catch (\Exception $e) {
                        $roomsRelation = collect([]);
                    }
                @endphp
                @if($property->is_room_rentable && $roomsRelation && is_object($roomsRelation) && method_exists($roomsRelation, 'count') && $roomsRelation->count() > 0)
                    @foreach($roomsRelation as $index => $room)
                    <div class="room-item" data-room-id="{{ $room->id }}">
                        <h4>غرفة {{ $index + 1 }}</h4>
                        
                        <input type="hidden" name="rooms[{{ $room->id }}][id]" value="{{ $room->id }}">
                        
                        <div class="form-group">
                            <label class="form-label">وصف الغرفة</label>
                            <textarea name="rooms[{{ $room->id }}][description]" class="form-textarea" rows="3">{{ $room->description }}</textarea>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label required">سعر الغرفة</label>
                                <input type="number" name="rooms[{{ $room->id }}][price]" step="0.01" min="0" required class="form-input" value="{{ $room->price }}">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label required">نوع السعر</label>
                                <select name="rooms[{{ $room->id }}][price_type]" required class="form-select">
                                    <option value="daily" {{ $room->price_type === 'daily' ? 'selected' : '' }}>يومي</option>
                                    <option value="monthly" {{ $room->price_type === 'monthly' ? 'selected' : '' }}>شهري</option>
                                    <option value="yearly" {{ $room->price_type === 'yearly' ? 'selected' : '' }}>سنوي</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">المساحة (م²)</label>
                                <input type="number" name="rooms[{{ $room->id }}][area]" min="1" class="form-input" value="{{ $room->area }}">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">عدد الأسرة</label>
                                <input type="number" name="rooms[{{ $room->id }}][beds]" min="1" class="form-input" value="{{ $room->beds }}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">المرافق</label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem; margin-top: 0.5rem;">
                                @php
                                    $roomAmenities = is_array($room->amenities) ? $room->amenities : [];
                                @endphp
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[{{ $room->id }}][amenities][]" value="private_bathroom" {{ in_array('private_bathroom', $roomAmenities) ? 'checked' : '' }}> حمام خاص</label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[{{ $room->id }}][amenities][]" value="tv" {{ in_array('tv', $roomAmenities) ? 'checked' : '' }}> تلفزيون</label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[{{ $room->id }}][amenities][]" value="ac" {{ in_array('ac', $roomAmenities) ? 'checked' : '' }}> تكييف</label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[{{ $room->id }}][amenities][]" value="wifi" {{ in_array('wifi', $roomAmenities) ? 'checked' : '' }}> واي فاي</label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[{{ $room->id }}][amenities][]" value="balcony" {{ in_array('balcony', $roomAmenities) ? 'checked' : '' }}> شرفة</label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[{{ $room->id }}][amenities][]" value="wardrobe" {{ in_array('wardrobe', $roomAmenities) ? 'checked' : '' }}> خزانة</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">صور الغرفة</label>
                            <input type="file" name="rooms[{{ $room->id }}][images][]" multiple accept="image/*" class="form-input">
                            <div class="form-help"><i class="fas fa-info-circle"></i> <span>يمكن إضافة صور جديدة للغرفة (الصور الحالية محفوظة)</span></div>
                            @if($room->images && count($room->images) > 0)
                            <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                                @foreach($room->images as $image)
                                <img src="{{ \App\Helpers\StorageHelper::url($image) }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;" onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'">
                                @endforeach
                            </div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="rooms[{{ $room->id }}][is_available]" value="1" {{ $room->is_available ? 'checked' : '' }}>
                                <span>الغرفة متاحة</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">مدة العقد</label>
                <input type="number" name="contract_duration" class="form-input" value="{{ old('contract_duration', $property->contract_duration) }}" min="1">
            </div>
            
            <div class="form-group">
                <label class="form-label">نوع مدة العقد</label>
                <select name="contract_duration_type" class="form-select">
                    <option value="">اختر النوع</option>
                    <option value="daily" {{ old('contract_duration_type', $property->contract_duration_type) === 'daily' ? 'selected' : '' }}>يومي</option>
                    <option value="weekly" {{ old('contract_duration_type', $property->contract_duration_type) === 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                    <option value="monthly" {{ old('contract_duration_type', $property->contract_duration_type) === 'monthly' ? 'selected' : '' }}>شهري</option>
                    <option value="yearly" {{ old('contract_duration_type', $property->contract_duration_type) === 'yearly' ? 'selected' : '' }}>سنوي</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">نسبة الزيادة السنوية (%)</label>
            <input type="number" name="annual_increase" class="form-input" value="{{ old('annual_increase', $property->annual_increase) }}" step="0.1" min="0" max="100">
        </div>
    </div>
    
    <!-- صور الوحدة -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="fas fa-images"></i>
            صور الوحدة
        </h2>
        
        @if($property->images && $property->images->count() > 0)
        <div style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1rem; font-weight: 600; color: #374151; margin-bottom: 1rem;">الصور الحالية ({{ $property->images->count() }})</h3>
            <div class="current-images">
                @foreach($property->images as $index => $image)
                <div class="current-image">
                    <img src="{{ $image->thumbnail_url ?? $image->image_url }}" alt="صورة الوحدة" loading="lazy" onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'">
                    <span class="image-order">{{ $index + 1 }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <h3 style="font-size: 1rem; font-weight: 600; color: #374151; margin-bottom: 1rem;">إضافة صور جديدة</h3>
        <div class="file-upload-area" id="uploadArea">
            <input type="file" name="images[]" id="imageInput" multiple accept="image/jpeg,image/jpg,image/png,image/webp" style="display: none;">
            <div class="file-upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div class="file-upload-text">اسحب الصور هنا أو انقر للاختيار</div>
            <div class="file-upload-hint">الحد الأقصى 5MB لكل صورة - صيغ مدعومة: JPG, PNG, WEBP</div>
        </div>
        
        <div class="preview-grid" id="previewGrid"></div>
    </div>
    
    <!-- المميزات والخدمات -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="fas fa-star"></i>
            المميزات والخدمات
        </h2>
        
        @php
            $amenitiesList = [
                'wifi' => 'واي فاي',
                'parking' => 'موقف سيارات',
                'elevator' => 'مصعد',
                'security' => 'حراسة أمنية',
                'garden' => 'حديقة',
                'pool' => 'مسبح',
                'gym' => 'صالة رياضية',
                'ac' => 'تكييف',
                'kitchen' => 'مطبخ مجهز',
                'laundry' => 'غسالة',
                'balcony' => 'شرفة',
                'storage' => 'مخزن',
            ];
            $currentAmenities = old('amenities', is_array($property->amenities) ? $property->amenities : []);
        @endphp
        
        <div class="amenities-grid">
            @foreach($amenitiesList as $key => $label)
            <label class="amenity-item {{ in_array($key, $currentAmenities) ? 'selected' : '' }}">
                <input type="checkbox" name="amenities[]" value="{{ $key }}" 
                       {{ in_array($key, $currentAmenities) ? 'checked' : '' }}>
                <span>{{ $label }}</span>
            </label>
            @endforeach
        </div>
    </div>
    
    <!-- معلومات إضافية -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="fas fa-file-alt"></i>
            معلومات إضافية
        </h2>
        
        <div class="form-group">
            <label class="form-label">رابط فيديو (يوتيوب)</label>
            <input type="url" name="video_url" class="form-input" value="{{ old('video_url', $property->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
        </div>
        
        <div class="form-group">
            <label class="form-label">متطلبات خاصة</label>
            <textarea name="special_requirements" class="form-textarea" placeholder="أي متطلبات خاصة للمستأجر...">{{ old('special_requirements', $property->special_requirements) }}</textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label">إثبات الملكية (اختياري)</label>
            
            @if($property->ownership_proof)
            <div style="background: #F9FAFB; border: 2px solid #E5E7EB; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    @php
                        $isPDF = pathinfo($property->ownership_proof, PATHINFO_EXTENSION) === 'pdf';
                        $fileUrl = \App\Helpers\StorageHelper::url($property->ownership_proof);
                    @endphp
                    @if($isPDF)
                    <div style="background: #EF4444; color: white; width: 60px; height: 60px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    @else
                    <img src="{{ $fileUrl }}" alt="إثبات الملكية" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                    @endif
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #1F2937; margin-bottom: 0.25rem;">ملف إثبات الملكية الحالي</div>
                        <div style="font-size: 0.875rem; color: #6B7280;">{{ basename($property->ownership_proof) }}</div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <a href="{{ $fileUrl }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: #1d313f; color: white; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-eye"></i>
                        عرض الملف
                    </a>
                    @if(route('owner.properties.ownership-proof.download', $property))
                    <a href="{{ route('owner.properties.ownership-proof.download', $property) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: #10B981; color: white; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-download"></i>
                        تحميل الملف
                    </a>
                    @endif
                </div>
            </div>
            @endif
            
            <div class="file-upload-area" onclick="document.getElementById('ownership_proof').click()" style="cursor: pointer;">
                <div class="file-upload-icon">
                    <i class="fas fa-file-upload"></i>
                </div>
                <div class="file-upload-text">اضغط لرفع ملف إثبات الملكية جديد</div>
                <div class="file-upload-hint">PDF, JPG, PNG (حد أقصى 5MB)</div>
            </div>
            <input type="file" name="ownership_proof" id="ownership_proof" accept=".pdf,.jpg,.jpeg,.png" class="file-input" style="display: none;" onchange="handleOwnershipProofSelect(this)">
            <div id="ownership_proof_preview" class="file-preview" style="display: none; margin-top: 1rem;"></div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="form-section">
        <div class="form-actions">
            <a href="{{ route('owner.properties.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-save"></i>
                حفظ التغييرات
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Image Upload
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const imageInput = document.getElementById('imageInput');
    const previewGrid = document.getElementById('previewGrid');
    let selectedFiles = [];
    
    uploadArea.addEventListener('click', () => imageInput.click());
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });
    
    imageInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });
    
    function handleFiles(files) {
        for (let file of files) {
            if (file.type.startsWith('image/')) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('حجم الصورة يجب أن يكون أقل من 5MB');
                    continue;
                }
                selectedFiles.push(file);
                previewFile(file, selectedFiles.length - 1);
            }
        }
        updateFileInput();
    }
    
    function previewFile(file, index) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.dataset.index = index;
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="remove-btn" onclick="removeImage(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            previewGrid.appendChild(div);
        };
        reader.readAsDataURL(file);
    }
    
    window.removeImage = function(index) {
        selectedFiles.splice(index, 1);
        updatePreview();
        updateFileInput();
    };
    
    function updatePreview() {
        previewGrid.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            previewFile(file, index);
        });
    }
    
    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        imageInput.files = dt.files;
    }
    
    // Amenity toggle
    document.querySelectorAll('.amenity-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            }
            this.classList.toggle('selected', this.querySelector('input').checked);
        });
    });
    
    // Initialize room rental toggle on page load
    const roomRentableCheckbox = document.getElementById('is_room_rentable');
    if (roomRentableCheckbox) {
        toggleRoomRental(roomRentableCheckbox);
    }
});

// Room rental functions
function toggleRoomRental(checkbox) {
    const roomSection = document.getElementById('roomRentalSection');
    const normalPriceSection = document.getElementById('normalPriceSection');
    const priceInput = document.getElementById('property_price');
    const priceTypeInput = document.getElementById('property_price_type');
    const roomsFieldGroup = document.getElementById('roomsFieldGroup');
    const roomsField = document.getElementById('roomsField');
    
    if (checkbox.checked) {
        roomSection.style.display = 'block';
        normalPriceSection.style.display = 'none';
        if (priceInput) {
            priceInput.removeAttribute('required');
            priceInput.disabled = true;
            priceInput.value = '';
            priceInput.removeAttribute('name'); // Remove name to prevent submission
        }
        if (priceTypeInput) {
            priceTypeInput.removeAttribute('required');
            priceTypeInput.disabled = true;
            priceTypeInput.value = '';
            priceTypeInput.removeAttribute('name'); // Remove name to prevent submission
        }
        // Hide and disable rooms field when room rentable is enabled
        if (roomsFieldGroup) roomsFieldGroup.style.display = 'none';
        if (roomsField) {
            roomsField.disabled = true;
            roomsField.value = '';
            roomsField.removeAttribute('name'); // Remove name to prevent submission
        }
    } else {
        roomSection.style.display = 'none';
        normalPriceSection.style.display = 'grid';
        if (priceInput) {
            priceInput.setAttribute('required', 'required');
            priceInput.disabled = false;
            priceInput.setAttribute('name', 'price'); // Restore name attribute
        }
        if (priceTypeInput) {
            priceTypeInput.setAttribute('required', 'required');
            priceTypeInput.disabled = false;
            priceTypeInput.setAttribute('name', 'price_type'); // Restore name attribute
        }
        // Show and enable rooms field when room rentable is disabled
        if (roomsFieldGroup) roomsFieldGroup.style.display = 'block';
        if (roomsField) {
            roomsField.disabled = false;
            roomsField.setAttribute('name', 'rooms'); // Restore name attribute
        }
        // إزالة required من total_rooms عندما يكون مخفي
        const totalRoomsInput = document.getElementById('total_rooms');
        if (totalRoomsInput) {
            totalRoomsInput.removeAttribute('required');
            totalRoomsInput.removeAttribute('name'); // Remove name to prevent submission
            totalRoomsInput.value = ''; // Clear value
        }
    }
    
    // إضافة required لـ total_rooms عندما يكون مفعّل
    if (checkbox.checked) {
        const totalRoomsInput = document.getElementById('total_rooms');
        if (totalRoomsInput) {
            totalRoomsInput.setAttribute('required', 'required');
            totalRoomsInput.setAttribute('name', 'total_rooms'); // Restore name attribute
        }
    }
}

function updateRoomsList() {
    const totalRooms = parseInt(document.getElementById('total_rooms').value) || 0;
    const container = document.getElementById('roomsContainer');
    // Count only rooms without data-room-id (new rooms), not existing ones from database
    const existingNewRooms = container.querySelectorAll('.room-item:not([data-room-id])').length;
    const existingRoomsWithId = container.querySelectorAll('.room-item[data-room-id]').length;
    const totalExisting = existingNewRooms + existingRoomsWithId;
    
    // Only remove new rooms (without data-room-id), not existing ones
    if (totalRooms < totalExisting) {
        const newRooms = container.querySelectorAll('.room-item:not([data-room-id])');
        const roomsToRemove = totalExisting - totalRooms;
        for (let i = newRooms.length - 1; i >= 0 && roomsToRemove > 0; i--) {
            newRooms[i].remove();
            roomsToRemove--;
        }
        return;
    }
    
    // Add new rooms only if needed
    const roomsToAdd = totalRooms - totalExisting;
    for (let i = 1; i <= roomsToAdd; i++) {
        const roomIndex = totalExisting + i;
        let html = `<div class="room-item">`;
        html += `<h4>غرفة ${roomIndex}</h4>`;
        
        html += `<div class="form-group">`;
        html += `<label class="form-label">وصف الغرفة</label>`;
        html += `<textarea name="rooms[${roomIndex}][description]" class="form-textarea" rows="3"></textarea>`;
        html += `</div>`;
        
        html += `<div class="form-grid">`;
        html += `<div class="form-group">`;
        html += `<label class="form-label required">سعر الغرفة</label>`;
        html += `<input type="number" name="rooms[${roomIndex}][price]" step="0.01" min="0" required class="form-input">`;
        html += `</div>`;
        
        html += `<div class="form-group">`;
        html += `<label class="form-label required">نوع السعر</label>`;
        html += `<select name="rooms[${roomIndex}][price_type]" required class="form-select">`;
        html += `<option value="">اختر نوع السعر</option>`;
        html += `<option value="daily">يومي</option>`;
        html += `<option value="monthly">شهري</option>`;
        html += `<option value="yearly">سنوي</option>`;
        html += `</select>`;
        html += `</div>`;
        html += `</div>`;
        
        html += `<div class="form-grid">`;
        html += `<div class="form-group">`;
        html += `<label class="form-label">المساحة (م²)</label>`;
        html += `<input type="number" name="rooms[${roomIndex}][area]" min="1" class="form-input">`;
        html += `</div>`;
        
        html += `<div class="form-group">`;
        html += `<label class="form-label">عدد الأسرة</label>`;
        html += `<input type="number" name="rooms[${roomIndex}][beds]" min="1" value="1" class="form-input">`;
        html += `</div>`;
        html += `</div>`;
        
        html += `<div class="form-group">`;
        html += `<label class="form-label">المرافق</label>`;
        html += `<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem; margin-top: 0.5rem;">`;
        html += `<label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[${roomIndex}][amenities][]" value="private_bathroom"> حمام خاص</label>`;
        html += `<label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[${roomIndex}][amenities][]" value="tv"> تلفزيون</label>`;
        html += `<label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[${roomIndex}][amenities][]" value="ac"> تكييف</label>`;
        html += `<label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[${roomIndex}][amenities][]" value="wifi"> واي فاي</label>`;
        html += `<label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[${roomIndex}][amenities][]" value="balcony"> شرفة</label>`;
        html += `<label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[${roomIndex}][amenities][]" value="wardrobe"> خزانة</label>`;
        html += `</div>`;
        html += `</div>`;
        
        html += `<div class="form-group">`;
        html += `<label class="form-label required">صور الغرفة</label>`;
        html += `<input type="file" name="rooms[${roomIndex}][images][]" multiple accept="image/*" required class="form-input">`;
        html += `</div>`;
        
        html += `</div>`;
        container.insertAdjacentHTML('beforeend', html);
    }
}

// Handle ownership proof file selection
function handleOwnershipProofSelect(input) {
    const file = input.files[0];
    if (file) {
        const preview = document.getElementById('ownership_proof_preview');
        if (preview) {
            preview.style.display = 'block';
            const reader = new FileReader();
            reader.onload = function(e) {
                const isPDF = file.type === 'application/pdf';
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                
                if (isPDF) {
                    preview.innerHTML = `
                        <div style="background: #F3F4F6; padding: 1.5rem; border-radius: 12px; border: 2px solid #E5E7EB; display: flex; align-items: center; gap: 1rem;">
                            <div style="background: #EF4444; color: white; width: 60px; height: 60px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #1F2937; margin-bottom: 0.25rem;">${file.name}</div>
                                <div style="font-size: 0.875rem; color: #6B7280;">حجم الملف: ${fileSize} MB</div>
                            </div>
                            <button type="button" onclick="removeOwnershipProof()" style="background: #EF4444; color: white; border: none; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                } else {
                    preview.innerHTML = `
                        <div style="background: #F3F4F6; padding: 1rem; border-radius: 12px; border: 2px solid #E5E7EB; display: inline-block;">
                            <img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                            <button type="button" onclick="removeOwnershipProof()" style="background: #EF4444; color: white; border: none; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; margin-top: 0.5rem;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                }
            };
            reader.readAsDataURL(file);
        }
    }
}

function removeOwnershipProof() {
    document.getElementById('ownership_proof').value = '';
    const preview = document.getElementById('ownership_proof_preview');
    if (preview) {
        preview.style.display = 'none';
        preview.innerHTML = '';
    }
}

// Map initialization
let map;
let marker;
let defaultLat = {{ $property->location_lat ?? '30.0444' }};
let defaultLng = {{ $property->location_lng ?? '31.2357' }};

document.addEventListener('DOMContentLoaded', function() {
    if (defaultLat && defaultLng) {
        map = L.map('property-map').setView([parseFloat(defaultLat), parseFloat(defaultLng)], 15);
        updateLocation(parseFloat(defaultLat), parseFloat(defaultLng));
    } else {
        map = L.map('property-map').setView([30.0444, 31.2357], 13);
    }
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        updateLocation(lat, lng);
    });
    
    if (!defaultLat || !defaultLng) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 13);
            });
        }
    }
    
    const addressInput = document.getElementById('address-search');
    if (addressInput) {
        addressInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchAddress();
            }
        });
    }
});

function updateLocation(lat, lng) {
    document.getElementById('location_lat').value = lat.toFixed(6);
    document.getElementById('location_lng').value = lng.toFixed(6);
    
    if (marker) {
        map.removeLayer(marker);
    }
    
    marker = L.marker([lat, lng], {
        draggable: true,
        icon: L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
        })
    }).addTo(map);
    
    marker.bindPopup(`الموقع المحدد:<br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`).openPopup();
    
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateLocation(position.lat, position.lng);
    });
}

function searchAddress() {
    const address = document.getElementById('address-search').value;
    if (!address) {
        alert('يرجى إدخال عنوان للبحث');
        return;
    }
    
    const searchBtn = event.target;
    const originalText = searchBtn.innerHTML;
    searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري البحث...';
    searchBtn.disabled = true;
    
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&accept-language=ar&limit=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                map.setView([lat, lng], 15);
                updateLocation(lat, lng);
            } else {
                alert('لم يتم العثور على العنوان المحدد. يرجى المحاولة مرة أخرى أو تحديد الموقع يدوياً على الخريطة.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء البحث. يرجى المحاولة مرة أخرى أو تحديد الموقع يدوياً على الخريطة.');
        })
        .finally(() => {
            searchBtn.innerHTML = originalText;
            searchBtn.disabled = false;
        });
}

// حل نهائي - إزالة required من total_rooms عندما يكون مخفي
window.addEventListener('load', function() {
    const btn = document.getElementById('submitBtn');
    const form = document.getElementById('editPropertyForm');
    const roomRentableCheckbox = document.getElementById('is_room_rentable');
    const totalRoomsInput = document.getElementById('total_rooms');
    
    if (btn && form) {
        // إزالة أي disabled
        btn.disabled = false;
        
        // التأكد من أن الزر يمكن النقر عليه
        btn.style.pointerEvents = 'auto';
        btn.style.cursor = 'pointer';
        btn.style.opacity = '1';
        
        console.log('✅ Button ready:', btn.type, btn.disabled);
        console.log('✅ Form ready:', form.action);
    }
    
    // إزالة required من total_rooms إذا كان is_room_rentable غير مفعّل
    if (roomRentableCheckbox && totalRoomsInput) {
        if (!roomRentableCheckbox.checked) {
            totalRoomsInput.removeAttribute('required');
            totalRoomsInput.removeAttribute('name');
            totalRoomsInput.value = '';
            console.log('✅ Removed required from total_rooms (room rental disabled)');
        }
    }
});
</script>
@endpush
@endsection
