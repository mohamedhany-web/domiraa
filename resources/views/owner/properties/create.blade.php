@extends('layouts.owner')

@section('title', 'إضافة وحدة جديدة - منصة دوميرا')
@section('page-title', 'إضافة وحدة جديدة')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
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
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
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
        
        .file-preview {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('owner.properties.store') }}" enctype="multipart/form-data" id="propertyForm">
    @csrf
    
    <!-- المعلومات الأساسية -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="fas fa-info-circle"></i>
            المعلومات الأساسية
        </h2>
        
        <div class="form-group">
            <label class="form-label required">إثبات الملكية</label>
            <div class="file-upload-area" onclick="document.getElementById('ownership_proof').click()">
                <div class="file-upload-icon">
                    <i class="fas fa-file-upload"></i>
                </div>
                <div class="file-upload-text">اضغط لرفع ملف إثبات الملكية</div>
                <div class="file-upload-hint">PDF, JPG, PNG (حد أقصى 5MB)</div>
            </div>
            <input type="file" name="ownership_proof" id="ownership_proof" accept=".pdf,.jpg,.jpeg,.png" required class="file-input" onchange="handleFileSelect(this, 'ownership_proof')">
            <div id="ownership_proof_preview" class="file-preview" style="display: none;"></div>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label required">نوع الوحدة</label>
                <select name="property_type_id" required class="form-select">
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
                <label class="form-label required">الحالة</label>
                <select name="status" required class="form-select">
                    <option value="">اختر الحالة</option>
                    <option value="furnished">مفروش</option>
                    <option value="unfurnished">على البلاط</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label required">العنوان التفصيلي</label>
            <input type="text" name="address" required class="form-input" value="{{ old('address') }}" placeholder="مثال: القاهرة الجديدة، الحي الأول، شارع النصر">
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">المساحة (م²)</label>
                <input type="number" name="area" min="1" step="1" class="form-input" value="{{ old('area') }}" placeholder="مثال: 120">
            </div>
            
            <div class="form-group" id="roomsFieldGroup">
                <label class="form-label">عدد الغرف</label>
                <input type="number" name="rooms" id="roomsField" min="0" step="1" class="form-input" value="{{ old('rooms') }}" placeholder="مثال: 3">
            </div>
            
            <div class="form-group">
                <label class="form-label">عدد الحمامات</label>
                <input type="number" name="bathrooms" min="0" step="1" class="form-input" value="{{ old('bathrooms') }}" placeholder="مثال: 2">
            </div>
            
            <div class="form-group">
                <label class="form-label">الدور</label>
                <input type="text" name="floor" class="form-input" placeholder="مثال: الثالث">
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
                <button type="button" onclick="searchAddress()" style="padding: 0.875rem 1.5rem; background: var(--primary); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; white-space: nowrap;">
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
                <input type="text" name="location_lat" id="location_lat" class="form-input" placeholder="سيتم التحديد تلقائياً من الخريطة" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">خط الطول (Longitude)</label>
                <input type="text" name="location_lng" id="location_lng" class="form-input" placeholder="سيتم التحديد تلقائياً من الخريطة" readonly>
            </div>
        </div>
    </div>
    
    <!-- الأسعار والعقد -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="fas fa-money-bill-wave"></i>
            الأسعار والعقد
        </h2>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_room_rentable" id="is_room_rentable" value="1" {{ old('is_room_rentable') ? 'checked' : '' }} onchange="toggleRoomRental(this)">
                <span class="form-label" style="margin: 0;">الوحدة قابلة للمشاركة (أكثر من مستأجر واحد)</span>
            </label>
            <div class="form-help">
                <i class="fas fa-info-circle"></i>
                <span>إذا كانت الوحدة قابلة للمشاركة (مثل شقق الطلبة أو الفنادق)، يمكنك إضافة تفاصيل كل غرفة</span>
            </div>
        </div>
        
        <div class="form-grid" id="normalPriceSection">
            <div class="form-group">
                <label class="form-label required">السعر</label>
                <input type="number" name="price" id="property_price" step="0.01" min="0" required class="form-input" value="{{ old('price') }}" placeholder="مثال: 5000">
            </div>
            
            <div class="form-group">
                <label class="form-label required">نوع السعر</label>
                <select name="price_type" id="property_price_type" required class="form-select">
                    <option value="">اختر نوع السعر</option>
                    <option value="daily" {{ old('price_type') == 'daily' ? 'selected' : '' }}>يومي</option>
                    <option value="monthly" {{ old('price_type') == 'monthly' ? 'selected' : '' }}>شهري</option>
                    <option value="yearly" {{ old('price_type') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                </select>
            </div>
        </div>
        
        <div id="roomRentalSection" style="display: none;">
            <div class="form-group">
                <label class="form-label">عدد الغرف القابلة للإيجار</label>
                <input type="number" name="total_rooms" id="total_rooms" min="1" class="form-input" value="{{ old('total_rooms') }}" placeholder="مثال: 4" onchange="updateRoomsList()">
            </div>
            
            <div id="roomsContainer" style="margin-top: 1.5rem;"></div>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">مدة العقد</label>
                <input type="number" name="contract_duration" min="1" class="form-input" placeholder="مثال: 1">
            </div>
            
            <div class="form-group">
                <label class="form-label">نوع مدة العقد</label>
                <select name="contract_duration_type" class="form-select">
                    <option value="">اختر النوع</option>
                    <option value="daily">يومي</option>
                    <option value="weekly">أسبوعي</option>
                    <option value="monthly">شهري</option>
                    <option value="yearly">سنوي</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">الزيادة السنوية (%)</label>
            <input type="number" name="annual_increase" step="0.01" min="0" max="100" class="form-input" placeholder="مثال: 5">
        </div>
    </div>
    
    <!-- الوسائط -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="fas fa-images"></i>
            الوسائط
        </h2>
        
        <div class="form-group">
            <label class="form-label required">صور الوحدة</label>
            <div class="file-upload-area" onclick="document.getElementById('images').click()">
                <div class="file-upload-icon">
                    <i class="fas fa-images"></i>
                </div>
                <div class="file-upload-text">اضغط لرفع صور الوحدة</div>
                <div class="file-upload-hint">يمكن رفع عدة صور - JPG, PNG (حد أقصى 2MB لكل صورة)</div>
            </div>
            <input type="file" name="images[]" id="images" multiple accept="image/*" required class="file-input" onchange="handleImagesSelect(this)">
            <div id="images_preview" class="file-preview"></div>
        </div>
        
        <div class="form-group">
            <label class="form-label">رابط الفيديو</label>
            <input type="url" name="video_url" class="form-input" placeholder="https://youtube.com/watch?v=...">
            <div class="form-help">
                <i class="fas fa-info-circle"></i>
                <span>رابط فيديو من YouTube أو أي منصة أخرى</span>
            </div>
        </div>
    </div>
    
    <!-- معلومات إضافية -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="fas fa-clipboard-list"></i>
            معلومات إضافية
        </h2>
        
        <div class="form-group">
            <label class="form-label">اشتراطات خاصة</label>
            <textarea name="special_requirements" rows="4" class="form-textarea" placeholder="أي اشتراطات أو شروط خاصة للوحدة..."></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label">المرافق المتاحة</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #F9FAFB; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                    <input type="checkbox" name="amenities[]" value="gas" style="width: 18px; height: 18px;">
                    <span style="font-weight: 600; color: #374151;">غاز</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #F9FAFB; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                    <input type="checkbox" name="amenities[]" value="electricity" style="width: 18px; height: 18px;">
                    <span style="font-weight: 600; color: #374151;">كهرباء</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #F9FAFB; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                    <input type="checkbox" name="amenities[]" value="water" style="width: 18px; height: 18px;">
                    <span style="font-weight: 600; color: #374151;">مياه</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #F9FAFB; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                    <input type="checkbox" name="amenities[]" value="internet" style="width: 18px; height: 18px;">
                    <span style="font-weight: 600; color: #374151;">إنترنت</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #F9FAFB; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                    <input type="checkbox" name="amenities[]" value="elevator" style="width: 18px; height: 18px;">
                    <span style="font-weight: 600; color: #374151;">مصعد</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem; background: #F9FAFB; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                    <input type="checkbox" name="amenities[]" value="parking" style="width: 18px; height: 18px;">
                    <span style="font-weight: 600; color: #374151;">موقف سيارات</span>
                </label>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="form-actions">
        <a href="{{ route('owner.properties.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>
            حفظ وإرسال للمراجعة
        </button>
    </div>
</form>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;
let marker;
let defaultLat = 30.0444; // Cairo default
let defaultLng = 31.2357;

// Initialize map
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map centered on Egypt (Cairo)
    map = L.map('property-map').setView([defaultLat, defaultLng], 13);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Add click event to map
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        updateLocation(lat, lng);
    });
    
    // Try to get user location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            map.setView([lat, lng], 13);
        });
    }
});

// Update location marker and form fields
function updateLocation(lat, lng) {
    // Update form fields
    document.getElementById('location_lat').value = lat.toFixed(6);
    document.getElementById('location_lng').value = lng.toFixed(6);
    
    // Remove existing marker if any
    if (marker) {
        map.removeLayer(marker);
    }
    
    // Add new marker
    marker = L.marker([lat, lng], {
        draggable: true,
        icon: L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
        })
    }).addTo(map);
    
    // Add popup with coordinates
    marker.bindPopup(`الموقع المحدد:<br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`).openPopup();
    
    // Update location when marker is dragged
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateLocation(position.lat, position.lng);
    });
}

// Search address using Nominatim (OpenStreetMap geocoding)
function searchAddress() {
    const address = document.getElementById('address-search').value;
    if (!address) {
        alert('يرجى إدخال عنوان للبحث');
        return;
    }
    
    // Show loading
    const searchBtn = event.target;
    const originalText = searchBtn.innerHTML;
    searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري البحث...';
    searchBtn.disabled = true;
    
    // Geocode address
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&accept-language=ar&limit=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                
                // Update map view
                map.setView([lat, lng], 15);
                
                // Update location
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

// Allow Enter key to trigger search
document.addEventListener('DOMContentLoaded', function() {
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
</script>
<script>
    function handleFileSelect(input, fieldName) {
        const file = input.files[0];
        if (file) {
            const preview = document.getElementById(fieldName + '_preview');
            if (preview) {
                preview.style.display = 'block';
                const reader = new FileReader();
                reader.onload = function(e) {
                    const isPDF = file.type === 'application/pdf';
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    
                    if (isPDF) {
                        preview.innerHTML = `
                            <div class="file-preview-item" style="background: #F3F4F6; padding: 1.5rem; border-radius: 12px; border: 2px solid #E5E7EB; display: flex; align-items: center; gap: 1rem;">
                                <div style="background: #EF4444; color: white; width: 60px; height: 60px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: #1F2937; margin-bottom: 0.25rem;">${file.name}</div>
                                    <div style="font-size: 0.875rem; color: #6B7280;">حجم الملف: ${fileSize} MB</div>
                                </div>
                                <button type="button" class="file-preview-remove" onclick="removeFile('${fieldName}')" style="background: #EF4444; color: white; border: none; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                    } else {
                        preview.innerHTML = `
                            <div class="file-preview-item">
                                <img src="${e.target.result}" alt="Preview">
                                <button type="button" class="file-preview-remove" onclick="removeFile('${fieldName}')">
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
    
    function handleImagesSelect(input) {
        const preview = document.getElementById('images_preview');
        preview.innerHTML = '';
        preview.style.display = 'grid';
        
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'file-preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}">
                    <button type="button" class="file-preview-remove" onclick="removeImage(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
    
    function removeFile(fieldName) {
        document.getElementById(fieldName).value = '';
        const preview = document.getElementById(fieldName + '_preview');
        if (preview) {
            preview.style.display = 'none';
            preview.innerHTML = '';
        }
    }
    
    function removeImage(index) {
        const input = document.getElementById('images');
        const dt = new DataTransfer();
        Array.from(input.files).forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        input.files = dt.files;
        handleImagesSelect(input);
    }
    
    // Drag and drop
    document.addEventListener('DOMContentLoaded', function() {
        const uploadAreas = document.querySelectorAll('.file-upload-area');
        uploadAreas.forEach(area => {
            area.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
            
            area.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });
            
            area.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });
        });
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
            // Update rooms list if total_rooms has a value
            const totalRoomsInput = document.getElementById('total_rooms');
            if (totalRoomsInput && totalRoomsInput.value) {
                updateRoomsList();
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
            document.getElementById('roomsContainer').innerHTML = '';
            // Show and enable rooms field when room rentable is disabled
            if (roomsFieldGroup) roomsFieldGroup.style.display = 'block';
            if (roomsField) {
                roomsField.disabled = false;
                roomsField.setAttribute('name', 'rooms'); // Restore name attribute
            }
        }
    }
    
    function updateRoomsList() {
        const totalRooms = parseInt(document.getElementById('total_rooms').value) || 0;
        const container = document.getElementById('roomsContainer');
        
        if (totalRooms <= 0) {
            container.innerHTML = '';
            return;
        }
        
        let html = '<div style="background: #F9FAFB; padding: 1.5rem; border-radius: 12px; margin-bottom: 1rem;">';
        html += '<h3 style="margin-bottom: 1rem; color: #1F2937; font-size: 1.1rem; font-weight: 700;">';
        html += '<i class="fas fa-door-open" style="margin-left: 0.5rem;"></i>تفاصيل الغرف';
        html += '</h3>';
        
        // Get old input values from PHP
        const oldRooms = @json(old('rooms', []));
        
        for (let i = 1; i <= totalRooms; i++) {
            const oldRoom = oldRooms[i] || {};
            const oldPrice = oldRoom.price || '';
            const oldPriceType = oldRoom.price_type || '';
            const oldDescription = oldRoom.description || '';
            const oldArea = oldRoom.area || '';
            const oldBeds = oldRoom.beds || '1';
            const oldAmenities = oldRoom.amenities || [];
            
            html += `<div class="room-item" style="background: white; padding: 1.5rem; border-radius: 10px; margin-bottom: 1rem; border: 2px solid #E5E7EB;">`;
            html += `<h4 style="margin-bottom: 1rem; color: var(--primary); font-weight: 700;">غرفة ${i}</h4>`;
            
            html += `<div class="form-group">`;
            html += `<label class="form-label">وصف الغرفة</label>`;
            html += `<textarea name="rooms[${i}][description]" class="form-input" rows="3" placeholder="وصف تفصيلي للغرفة...">${oldDescription}</textarea>`;
            html += `</div>`;
            
            html += `<div class="form-grid">`;
            html += `<div class="form-group">`;
            html += `<label class="form-label required">سعر الغرفة</label>`;
            html += `<input type="number" name="rooms[${i}][price]" step="0.01" min="0" required class="form-input" placeholder="مثال: 1000" value="${oldPrice}">`;
            html += `</div>`;
            
            html += `<div class="form-group">`;
            html += `<label class="form-label required">نوع السعر</label>`;
            html += `<select name="rooms[${i}][price_type]" required class="form-select">`;
            html += `<option value="">اختر نوع السعر</option>`;
            html += `<option value="daily" ${oldPriceType === 'daily' ? 'selected' : ''}>يومي</option>`;
            html += `<option value="monthly" ${oldPriceType === 'monthly' ? 'selected' : ''}>شهري</option>`;
            html += `<option value="yearly" ${oldPriceType === 'yearly' ? 'selected' : ''}>سنوي</option>`;
            html += `</select>`;
            html += `</div>`;
            html += `</div>`;
            
            html += `<div class="form-grid">`;
            html += `<div class="form-group">`;
            html += `<label class="form-label">المساحة (م²)</label>`;
            html += `<input type="number" name="rooms[${i}][area]" min="1" class="form-input" placeholder="مثال: 15" value="${oldArea}">`;
            html += `</div>`;
            
            html += `<div class="form-group">`;
            html += `<label class="form-label">عدد الأسرة</label>`;
            html += `<input type="number" name="rooms[${i}][beds]" min="1" value="${oldBeds}" class="form-input" placeholder="مثال: 1">`;
            html += `</div>`;
            html += `</div>`;
            
            html += `<div class="form-group">`;
            html += `<label class="form-label">المرافق</label>`;
            html += `<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem; margin-top: 0.5rem;">`;
            const amenities = ['private_bathroom', 'tv', 'ac', 'wifi', 'balcony', 'wardrobe'];
            const amenityLabels = {
                'private_bathroom': 'حمام خاص',
                'tv': 'تلفزيون',
                'ac': 'تكييف',
                'wifi': 'واي فاي',
                'balcony': 'شرفة',
                'wardrobe': 'خزانة'
            };
            amenities.forEach(amenity => {
                const isChecked = oldAmenities.includes(amenity) ? 'checked' : '';
                html += `<label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="rooms[${i}][amenities][]" value="${amenity}" ${isChecked}> ${amenityLabels[amenity]}</label>`;
            });
            html += `</div>`;
            html += `</div>`;
            
            html += `<div class="form-group">`;
            html += `<label class="form-label required">صور الغرفة <span style="color: #EF4444;">*</span></label>`;
            html += `<input type="file" name="rooms[${i}][images][]" multiple accept="image/*" required class="form-input room-image-input" data-room-index="${i}">`;
            html += `<div class="form-help"><i class="fas fa-info-circle"></i> <span>يجب رفع صورة واحدة على الأقل للغرفة</span></div>`;
            html += `<div class="room-images-preview" data-room-index="${i}" style="display: flex; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;"></div>`;
            html += `</div>`;
            
            html += `</div>`;
        }
        
        html += '</div>';
        container.innerHTML = html;
        
        // Add event listeners for image previews after HTML is inserted
        container.querySelectorAll('.room-image-input').forEach(input => {
            input.addEventListener('change', function(e) {
                const roomIndex = this.getAttribute('data-room-index');
                const preview = container.querySelector(`.room-images-preview[data-room-index="${roomIndex}"]`);
                if (preview) {
                    preview.innerHTML = '';
                    Array.from(e.target.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.width = '80px';
                            img.style.height = '80px';
                            img.style.objectFit = 'cover';
                            img.style.borderRadius = '8px';
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });
        });
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('is_room_rentable');
        if (checkbox) {
            // Always call toggleRoomRental to set initial state
            toggleRoomRental(checkbox);
        }
        
        // Restore old values if validation failed
        @if(old('is_room_rentable'))
            if (checkbox) {
                checkbox.checked = true;
                toggleRoomRental(checkbox);
            }
            const totalRoomsInput = document.getElementById('total_rooms');
            if (totalRoomsInput && {{ old('total_rooms', 0) }}) {
                totalRoomsInput.value = {{ old('total_rooms', 0) }};
                // Call updateRoomsList after setting the value
                setTimeout(function() {
                    updateRoomsList();
                }, 100);
            }
        @endif
    });
</script>
@endpush
@endsection


