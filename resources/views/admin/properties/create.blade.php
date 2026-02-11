@extends('layouts.admin')

@section('title', 'إضافة وحدة جديدة')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-title i {
        color: var(--primary);
    }
    
    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
        margin-bottom: 1.5rem;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .form-section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #E5E7EB;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-section-title i {
        color: var(--primary);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-label .required {
        color: #DC2626;
    }
    
    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }
    
    /* Image Upload */
    .image-upload-area {
        border: 2px dashed #D1D5DB;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #F9FAFB;
    }
    
    .image-upload-area:hover {
        border-color: var(--primary);
        background: rgba(29, 49, 63, 0.05);
    }
    
    .image-upload-area.dragover {
        border-color: var(--primary);
        background: rgba(29, 49, 63, 0.1);
    }
    
    .image-upload-icon {
        font-size: 3rem;
        color: #9CA3AF;
        margin-bottom: 1rem;
    }
    
    .image-upload-text {
        color: #6B7280;
        margin-bottom: 0.5rem;
    }
    
    .image-upload-hint {
        font-size: 0.85rem;
        color: #9CA3AF;
    }
    
    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .image-preview-item {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .image-preview-remove {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
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
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    
    .image-preview-remove:hover {
        transform: scale(1.1);
        background: #B91C1C;
    }
    
    /* Amenities */
    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.75rem;
    }
    
    .amenity-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem;
        background: #F9FAFB;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }
    
    .amenity-item:hover {
        background: #F3F4F6;
    }
    
    .amenity-item.selected {
        background: rgba(29, 49, 63, 0.1);
        border-color: var(--primary);
    }
    
    .amenity-item input {
        accent-color: var(--primary);
    }
    
    .amenity-item label {
        cursor: pointer;
        font-size: 0.9rem;
        color: #374151;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #E5E7EB;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
    }
    
    .btn-cancel {
        background: #F3F4F6;
        color: #374151;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-cancel:hover {
        background: #E5E7EB;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-submit, .btn-cancel {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-plus-circle"></i>
        إضافة وحدة جديدة
    </h1>
</div>

@if ($errors->any())
<div style="background: #FEE2E2; border: 1px solid #EF4444; color: #DC2626; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
    <ul style="margin: 0; padding-right: 1.5rem;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data" id="propertyForm">
    @csrf
    
    <div class="form-card">
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-user"></i>
                معلومات المالك
            </h2>
            
            <div class="form-group">
                <label class="form-label">المالك <span class="required">*</span></label>
                <select name="user_id" class="form-select" required>
                    <option value="">اختر المالك</option>
                    @foreach($owners as $owner)
                    <option value="{{ $owner->id }}" {{ old('user_id') == $owner->id ? 'selected' : '' }}>
                        {{ $owner->name }} - {{ $owner->email }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-building"></i>
                معلومات الوحدة
            </h2>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">نوع الوحدة <span class="required">*</span></label>
                    <select name="property_type_id" class="form-select" required>
                        <option value="">اختر النوع</option>
                        @foreach(\App\Models\PropertyType::active() as $type)
                        <option value="{{ $type->id }}" {{ old('property_type_id') == $type->id ? 'selected' : '' }}>
                            @if($type->icon)
                            <i class="{{ $type->icon }}"></i>
                            @endif
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">حالة التأثيث <span class="required">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="">اختر الحالة</option>
                        <option value="furnished" {{ old('status') == 'furnished' ? 'selected' : '' }}>مفروش</option>
                        <option value="unfurnished" {{ old('status') == 'unfurnished' ? 'selected' : '' }}>غير مفروش</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label">العنوان <span class="required">*</span></label>
                    <input type="text" name="address" class="form-input" value="{{ old('address') }}" required placeholder="العنوان الكامل للوحدة">
                </div>
                
                <div class="form-group">
                    <label class="form-label">المساحة (م²)</label>
                    <input type="number" name="area" class="form-input" value="{{ old('area') }}" min="1" placeholder="مثال: 150">
                </div>
                
                <div class="form-group">
                    <label class="form-label">عدد الغرف</label>
                    <input type="number" name="rooms" class="form-input" value="{{ old('rooms') }}" min="0" placeholder="مثال: 3">
                </div>
                
                <div class="form-group">
                    <label class="form-label">عدد الحمامات</label>
                    <input type="number" name="bathrooms" class="form-input" value="{{ old('bathrooms') }}" min="0" placeholder="مثال: 2">
                </div>
                
                <div class="form-group">
                    <label class="form-label">الطابق</label>
                    <input type="text" name="floor" class="form-input" value="{{ old('floor') }}" placeholder="مثال: الثالث">
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-money-bill-wave"></i>
                السعر والتعاقد
            </h2>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">السعر <span class="required">*</span></label>
                    <input type="number" name="price" class="form-input" value="{{ old('price') }}" min="0" step="0.01" required placeholder="السعر بالجنيه">
                </div>
                
                <div class="form-group">
                    <label class="form-label">نوع السعر <span class="required">*</span></label>
                    <select name="price_type" class="form-select" required>
                        <option value="">اختر النوع</option>
                        <option value="daily" {{ old('price_type') == 'daily' ? 'selected' : '' }}>يومي</option>
                        <option value="monthly" {{ old('price_type') == 'monthly' ? 'selected' : '' }}>شهري</option>
                        <option value="yearly" {{ old('price_type') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                    </select>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">مدة العقد</label>
                        <input type="number" name="contract_duration" class="form-input" value="{{ old('contract_duration') }}" min="1" placeholder="مثال: 1">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">نوع مدة العقد</label>
                        <select name="contract_duration_type" class="form-select">
                            <option value="">اختر النوع</option>
                            <option value="daily" {{ old('contract_duration_type') === 'daily' ? 'selected' : '' }}>يومي</option>
                            <option value="weekly" {{ old('contract_duration_type') === 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                            <option value="monthly" {{ old('contract_duration_type') === 'monthly' ? 'selected' : '' }}>شهري</option>
                            <option value="yearly" {{ old('contract_duration_type') === 'yearly' ? 'selected' : '' }}>سنوي</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">نسبة الزيادة السنوية (%)</label>
                    <input type="number" name="annual_increase" class="form-input" value="{{ old('annual_increase') }}" min="0" max="100" step="0.1" placeholder="مثال: 5">
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-images"></i>
                صور الوحدة <span class="required">*</span>
            </h2>
            
            <div class="image-upload-area" id="imageUploadArea">
                <input type="file" name="images[]" id="imageInput" multiple accept="image/jpeg,image/jpg,image/png,image/webp" style="display: none;">
                <div class="image-upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <div class="image-upload-text">اسحب الصور هنا أو انقر للاختيار</div>
                <div class="image-upload-hint">الحد الأقصى 5MB لكل صورة - صيغ مدعومة: JPG, PNG, WEBP</div>
            </div>
            
            <div class="image-preview-grid" id="imagePreviewGrid"></div>
        </div>
        
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-star"></i>
                المميزات والخدمات
            </h2>
            
            <div class="amenities-grid">
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
                @endphp
                @foreach($amenitiesList as $key => $label)
                <label class="amenity-item">
                    <input type="checkbox" name="amenities[]" value="{{ $key }}" 
                           {{ in_array($key, old('amenities', [])) ? 'checked' : '' }}>
                    <span>{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>
        
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-info-circle"></i>
                معلومات إضافية
            </h2>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">رابط فيديو (يوتيوب)</label>
                    <input type="url" name="video_url" class="form-input" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label">متطلبات خاصة</label>
                    <textarea name="special_requirements" class="form-textarea" placeholder="أي متطلبات خاصة للمستأجر...">{{ old('special_requirements') }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-cog"></i>
                حالة الموافقة
            </h2>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">حالة الوحدة <span class="required">*</span></label>
                    <select name="admin_status" class="form-select" required>
                        <option value="approved" {{ old('admin_status', 'approved') == 'approved' ? 'selected' : '' }}>موافق عليها</option>
                        <option value="pending" {{ old('admin_status') == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                        <option value="rejected" {{ old('admin_status') == 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label">ملاحظات الأدمن</label>
                    <textarea name="admin_notes" class="form-textarea" placeholder="ملاحظات داخلية...">{{ old('admin_notes') }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <a href="{{ route('admin.properties') }}" class="btn-cancel">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                حفظ الوحدة
            </button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageUploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('imageInput');
    const imagePreviewGrid = document.getElementById('imagePreviewGrid');
    let selectedFiles = [];
    
    // Click to upload
    imageUploadArea.addEventListener('click', () => imageInput.click());
    
    // Drag and drop
    imageUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageUploadArea.classList.add('dragover');
    });
    
    imageUploadArea.addEventListener('dragleave', () => {
        imageUploadArea.classList.remove('dragover');
    });
    
    imageUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        imageUploadArea.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });
    
    // File input change
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
            div.className = 'image-preview-item';
            div.dataset.index = index;
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="image-preview-remove" onclick="removeImage(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            imagePreviewGrid.appendChild(div);
        };
        reader.readAsDataURL(file);
    }
    
    window.removeImage = function(index) {
        selectedFiles.splice(index, 1);
        updatePreview();
        updateFileInput();
    };
    
    function updatePreview() {
        imagePreviewGrid.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            previewFile(file, index);
        });
    }
    
    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        imageInput.files = dt.files;
    }
    
    // Amenity item click handler
    document.querySelectorAll('.amenity-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            }
            this.classList.toggle('selected', this.querySelector('input').checked);
        });
        
        // Initial state
        if (item.querySelector('input').checked) {
            item.classList.add('selected');
        }
    });
});
</script>
@endsection

