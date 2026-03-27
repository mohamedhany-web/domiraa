@extends('layouts.admin')

@section('title', 'تعديل وحدة')
@section('page-title', 'تعديل وحدة')

@push('styles')
<style>
    .admin-edit-page {
        max-width: 100%;
        margin: 0;
        width: 100%;
    }
    
    .admin-edit-page * {
        box-sizing: border-box;
    }
    
    .admin-edit-page img {
        max-width: 100%;
    }
    
    .form-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
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
    
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.875rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    /* Prevent horizontal overflow from long text/urls */
    .form-input,
    .form-select,
    .form-textarea {
        min-width: 0;
    }
    
    /* Make actions wrap cleanly on narrow widths */
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        font-weight: 700;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    .btn-cancel {
        background: #F3F4F6;
        color: #374151;
        font-weight: 700;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: #E5E7EB;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="admin-edit-page">
<div class="form-section">
    @if (session('success'))
        <div style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 1rem;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.properties.update', $property) }}">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">النوع <span style="color: #DC2626;">*</span></label>
                <select name="property_type_id" class="form-select" required>
                    <option value="">اختر النوع</option>
                    @foreach(\App\Models\PropertyType::active() as $type)
                    <option value="{{ $type->id }}" {{ old('property_type_id', $property->property_type_id) == $type->id ? 'selected' : '' }}>
                        @if($type->icon)
                        <i class="{{ $type->icon }}"></i>
                        @endif
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
                @error('property_type_id')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">الحالة <span style="color: #DC2626;">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="furnished" {{ old('status', $property->status) == 'furnished' ? 'selected' : '' }}>مفروش</option>
                    <option value="unfurnished" {{ old('status', $property->status) == 'unfurnished' ? 'selected' : '' }}>على البلاط</option>
                </select>
                @error('status')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">العنوان <span style="color: #DC2626;">*</span></label>
            <input type="text" name="address" class="form-input" value="{{ old('address', $property->address) }}" required>
            @error('address')
                <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">خط العرض</label>
                <input type="text" name="location_lat" class="form-input" value="{{ old('location_lat', $property->location_lat) }}">
                @error('location_lat')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">خط الطول</label>
                <input type="text" name="location_lng" class="form-input" value="{{ old('location_lng', $property->location_lng) }}">
                @error('location_lng')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">السعر <span style="color: #DC2626;">*</span></label>
                <input type="number" name="price" class="form-input" value="{{ old('price', $property->price) }}" step="0.01" required>
                @error('price')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">نوع السعر <span style="color: #DC2626;">*</span></label>
                <select name="price_type" class="form-select" required>
                    <option value="daily" {{ old('price_type', $property->price_type) == 'daily' ? 'selected' : '' }}>يومي</option>
                    <option value="monthly" {{ old('price_type', $property->price_type) == 'monthly' ? 'selected' : '' }}>شهري</option>
                    <option value="yearly" {{ old('price_type', $property->price_type) == 'yearly' ? 'selected' : '' }}>سنوي</option>
                </select>
                @error('price_type')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">مدة العقد</label>
                <input type="number" name="contract_duration" class="form-input" value="{{ old('contract_duration', $property->contract_duration) }}" min="1">
                @error('contract_duration')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
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
            <label class="form-label">الزيادة السنوية (%)</label>
                <input type="number" name="annual_increase" class="form-input" value="{{ old('annual_increase', $property->annual_increase) }}" step="0.01" min="0" max="100">
                @error('annual_increase')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">رابط الفيديو</label>
            <input type="url" name="video_url" class="form-input" value="{{ old('video_url', $property->video_url) }}">
            @error('video_url')
                <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">اشتراطات خاصة</label>
            <textarea name="special_requirements" class="form-textarea">{{ old('special_requirements', $property->special_requirements) }}</textarea>
            @error('special_requirements')
                <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">حالة المراجعة <span style="color: #DC2626;">*</span></label>
                <select name="admin_status" class="form-select" required>
                    <option value="pending" {{ old('admin_status', $property->admin_status) == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                    <option value="approved" {{ old('admin_status', $property->admin_status) == 'approved' ? 'selected' : '' }}>معتمد</option>
                    <option value="rejected" {{ old('admin_status', $property->admin_status) == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                </select>
                @error('admin_status')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">ملاحظات الأدمن</label>
            <textarea name="admin_notes" class="form-textarea">{{ old('admin_notes', $property->admin_notes) }}</textarea>
            @error('admin_notes')
                <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save" style="margin-left: 0.5rem;"></i>
                حفظ التغييرات
            </button>
            <a href="{{ route('admin.properties') }}" class="btn-cancel">
                <i class="fas fa-times" style="margin-left: 0.5rem;"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

<div class="form-section">
    <div class="form-group">
        <label class="form-label">صور الوحدة (صور العقار)</label>

        @if($property->images()->count())
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; margin-top: 0.75rem;">
                @foreach($property->images()->get() as $img)
                    <div style="border:1px solid #E5E7EB; border-radius: 10px; overflow:hidden; background:#fff;">
                        <a href="{{ $img->url }}" target="_blank" rel="noopener">
                            <img src="{{ $img->thumbnail_url }}" alt="image" style="width:100%; height:120px; object-fit:cover; display:block;">
                        </a>
                        <div style="padding: 8px;">
                            <form method="POST" action="{{ route('admin.properties.images.destroy', [$property, $img]) }}" onsubmit="return confirm('حذف هذه الصورة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width:100%; background:#FEF2F2; color:#991B1B; border:1px solid #FECACA; padding:8px; border-radius:8px; cursor:pointer; font-weight:700;">
                                    حذف الصورة
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="color:#6B7280; margin-top:0.5rem;">لا توجد صور حالياً.</div>
        @endif

        <div style="margin-top: 1rem;">
            <form method="POST" action="{{ route('admin.properties.images.store', $property) }}" enctype="multipart/form-data">
                @csrf
                <input type="file" name="images[]" multiple accept="image/*" class="form-input">
                @error('images')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn-submit" style="margin-top: 0.75rem;">
                    <i class="fas fa-images" style="margin-left: 0.5rem;"></i>
                    إضافة صور جديدة
                </button>
            </form>
        </div>
    </div>

    @if($property->rooms()->count())
        <div class="form-group">
            <label class="form-label">صور الغرف/الوحدات داخل الوحدة</label>
            @foreach($property->rooms()->get() as $room)
                <div style="border:1px solid #E5E7EB; border-radius: 12px; padding: 12px; margin-top: 12px;">
                    <div style="display:flex; justify-content:space-between; gap: 10px; flex-wrap:wrap; align-items:center;">
                        <div style="font-weight:800; color:#111827;">
                            {{ $room->room_name }} @if($room->room_number) <span style="color:#6B7280;">({{ $room->room_number }})</span> @endif
                        </div>
                        <div style="color:#6B7280; font-size: 0.9rem;">{{ $room->price }} / {{ $room->price_type }}</div>
                    </div>

                    @php($roomImages = $room->images ?? [])
                    @if(count($roomImages))
                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; margin-top: 0.75rem;">
                            @foreach($roomImages as $path)
                                <div style="border:1px solid #E5E7EB; border-radius: 10px; overflow:hidden; background:#fff;">
                                    <a href="{{ \App\Helpers\StorageHelper::url($path) }}" target="_blank" rel="noopener">
                                        <img src="{{ \App\Helpers\StorageHelper::thumbnailUrl($path) }}" alt="room-image" style="width:100%; height:120px; object-fit:cover; display:block;">
                                    </a>
                                    <div style="padding: 8px;">
                                        <form method="POST" action="{{ route('admin.properties.rooms.images.destroy', [$property, $room]) }}" onsubmit="return confirm('حذف هذه الصورة؟');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="path" value="{{ $path }}">
                                            <button type="submit" style="width:100%; background:#FEF2F2; color:#991B1B; border:1px solid #FECACA; padding:8px; border-radius:8px; cursor:pointer; font-weight:700;">
                                                حذف الصورة
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="color:#6B7280; margin-top:0.5rem;">لا توجد صور لهذه الغرفة/الوحدة.</div>
                    @endif

                    <div style="margin-top: 0.75rem;">
                        <form method="POST" action="{{ route('admin.properties.rooms.images.store', [$property, $room]) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="images[]" multiple accept="image/*" class="form-input">
                            <button type="submit" class="btn-submit" style="margin-top: 0.75rem;">
                                <i class="fas fa-plus" style="margin-left: 0.5rem;"></i>
                                إضافة صور لهذه الغرفة/الوحدة
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
</div>
@endsection



