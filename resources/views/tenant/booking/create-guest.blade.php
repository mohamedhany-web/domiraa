@extends('layouts.app')

@section('title', 'حجز موعد للمعاينة')

@push('styles')
<style>
    .booking-page {
        min-height: calc(100vh - 80px);
        padding: 2rem 0;
        background: linear-gradient(135deg, #F9FAFB 0%, #F0F9FF 100%);
    }
    
    .booking-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    .booking-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .booking-header h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #1F2937;
        margin-bottom: 0.5rem;
    }
    
    .booking-header p {
        color: #6B7280;
        font-size: 1rem;
    }
    
    .property-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }
    
    .property-card h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1rem;
    }
    
    .property-info {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        color: #6B7280;
    }
    
    .property-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .property-info-item i {
        color: #1d313f;
        width: 20px;
    }
    
    .booking-form-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.625rem;
        font-size: 0.95rem;
        gap: 0.5rem;
    }
    
    .form-label i {
        color: #1d313f;
        width: 20px;
        font-size: 0.9rem;
    }
    
    .form-input,
    .form-select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
        font-family: 'Cairo', sans-serif;
    }
    
    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .info-box {
        background: linear-gradient(135deg, #DBEAFE 0%, #E0F2FE 100%);
        border: 2px solid #93C5FD;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .info-box p {
        color: #1d313f;
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.6;
    }
    
    .info-box strong {
        font-weight: 700;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }
    
    .btn-cancel {
        padding: 0.875rem 1.5rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        color: #6B7280;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-cancel:hover {
        background: #F9FAFB;
        border-color: #D1D5DB;
    }
    
    .btn-submit {
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(29, 49, 63, 0.3);
    }
    
    @media (max-width: 768px) {
        .booking-page {
            padding: 1.5rem 0;
        }
        
        .booking-container {
            padding: 0 1rem;
        }
        
        .booking-header h1 {
            font-size: 1.75rem;
        }
        
        .property-card,
        .booking-form-card {
            padding: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-cancel,
        .btn-submit {
            width: 100%;
            justify-content: center;
        }
        
        .wallet-info {
            padding: 1rem !important;
            margin-top: 0.75rem !important;
        }
        
        .wallet-info h3 {
            font-size: 1rem !important;
            margin-bottom: 0.75rem !important;
        }
        
        .wallet-info > div {
            padding: 0.75rem !important;
        }
        
        .wallet-info-item {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.5rem !important;
            padding: 0.875rem 0 !important;
        }
        
        .wallet-info-item > span:first-child {
            font-size: 0.875rem !important;
            width: 100% !important;
        }
        
        .wallet-info-item > span:last-child {
            font-size: 0.95rem !important;
            width: 100% !important;
            word-break: break-all;
            padding: 0.5rem !important;
            background: white !important;
            border-radius: 6px !important;
            border: 1px solid rgba(107, 137, 128, 0.2) !important;
            display: block !important;
        }
    }
    
    @media (max-width: 480px) {
        .wallet-info {
            padding: 0.875rem !important;
        }
        
        .wallet-info > div {
            padding: 0.625rem !important;
        }
        
        .wallet-info-item {
            padding: 0.75rem 0 !important;
        }
        
        .wallet-info-item > span:first-child {
            font-size: 0.8rem !important;
        }
        
        .wallet-info-item > span:last-child {
            font-size: 0.85rem !important;
            padding: 0.5rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="booking-page">
    <div class="booking-container">
        <div class="booking-header">
            <h1>حجز موعد للمعاينة</h1>
            <p>املأ البيانات التالية لحجز موعد المعاينة</p>
        </div>
        
        <div class="property-card">
            <h2>{{ $property->address }}</h2>
            <div class="property-info">
                <div class="property-info-item">
                    <i class="fas fa-tag"></i>
                    <span>{{ number_format($property->price) }} {{ $property->price_type === 'monthly' ? 'شهرياً' : ($property->price_type === 'yearly' ? 'سنوياً' : 'يومياً') }}</span>
                </div>
                @if($property->area)
                <div class="property-info-item">
                    <i class="fas fa-ruler-combined"></i>
                    <span>{{ $property->area }} م²</span>
                </div>
                @endif
                @if($property->rooms)
                <div class="property-info-item">
                    <i class="fas fa-bed"></i>
                    <span>{{ $property->rooms }} غرف</span>
                </div>
                @endif
            </div>
        </div>
        
        <div class="booking-form-card">
            @if($wallets->isEmpty())
            <div style="background: #FEF3C7; border: 2px solid #F59E0B; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
                <p style="color: #92400E; font-size: 1rem; margin: 0; font-weight: 600;">
                    <i class="fas fa-exclamation-triangle"></i>
                    لا توجد محافظ متاحة للدفع. يرجى التواصل مع المؤجر لإضافة محفظة.
                </p>
            </div>
            @endif
            
            <form method="POST" action="{{ route('inspection.store', $property) }}" enctype="multipart/form-data" id="guestBookingForm">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf_token_input">
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user"></i>
                        <span>الاسم الكامل *</span>
                    </label>
                    <input type="text" name="name" class="form-input" 
                           placeholder="أدخل اسمك الكامل" 
                           value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i>
                        <span>البريد الإلكتروني *</span>
                    </label>
                    <input type="email" name="email" class="form-input" 
                           placeholder="example@email.com" 
                           value="{{ old('email') }}" required>
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-phone"></i>
                        <span>رقم الهاتف *</span>
                    </label>
                    <input type="tel" name="phone" class="form-input" 
                           placeholder="01XXXXXXXXX" 
                           value="{{ old('phone') }}" required 
                           pattern="[0-9]{10,15}">
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-check"></i>
                        <span>نوع الحجز *</span>
                    </label>
                    <select name="booking_type" id="booking_type" class="form-select" required>
                        <option value="inspection" {{ old('booking_type') == 'inspection' ? 'selected' : '' }}>معاينة فقط ({{ number_format($inspectionFee) }} جنيه)</option>
                        <option value="reservation" {{ old('booking_type') == 'reservation' ? 'selected' : '' }}>حجز الوحدة ({{ $reservationPercentage }}% من السعر - {{ number_format(($property->price * $reservationPercentage) / 100, 2) }} جنيه)</option>
                    </select>
                    @error('booking_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt"></i>
                        <span>تاريخ المعاينة *</span>
                    </label>
                    <input type="date" name="inspection_date" class="form-input" 
                           required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           value="{{ old('inspection_date') }}">
                    @error('inspection_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-clock"></i>
                        <span>وقت المعاينة *</span>
                    </label>
                    <input type="time" name="inspection_time" class="form-input" 
                           required value="{{ old('inspection_time') }}">
                    @error('inspection_time')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-wallet"></i>
                        <span>اختر المحفظة *</span>
                    </label>
                    <select name="wallet_id" id="wallet_id" class="form-select" required>
                        <option value="">-- اختر المحفظة --</option>
                        @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}" data-wallet='@json($wallet)'>
                            {{ $wallet->name }} 
                            @if($wallet->bank_name)
                            - {{ $wallet->bank_name }}
                            @endif
                        </option>
                        @endforeach
                    </select>
                    @error('wallet_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="wallet-info" id="walletInfo" style="background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%); border: 2px solid #6b8980; border-radius: 12px; padding: 1.5rem; margin-top: 1rem; display: none;">
                    <h3 style="font-size: 1.125rem; font-weight: 700; color: #536b63; margin-bottom: 1rem;">
                        <i class="fas fa-info-circle"></i> بيانات المحفظة للتحويل
                    </h3>
                    <div style="background: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <p style="color: #92400E; font-size: 0.9rem; margin-bottom: 1rem; font-weight: 600;">
                            <i class="fas fa-exclamation-triangle"></i> 
                            يرجى التحويل إلى البيانات التالية ثم رفع إيصال التحويل
                        </p>
                        <div id="walletDetails"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-receipt"></i>
                        <span>رفع إيصال التحويل *</span>
                    </label>
                    <div class="file-upload-area" id="fileUploadArea" style="border: 2px dashed #D1D5DB; border-radius: 12px; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.3s ease; background: #F9FAFB;">
                        <input type="file" name="receipt" id="receipt" accept="image/*,application/pdf" required style="display: none;">
                        <p style="margin-bottom: 0.5rem;">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #9CA3AF; margin-bottom: 0.5rem;"></i>
                        </p>
                        <p style="color: #6B7280; font-weight: 600; margin-bottom: 0.25rem;">
                            اسحب وأفلت الإيصال هنا أو <span style="color: #1d313f; cursor: pointer;">اضغط للرفع</span>
                        </p>
                        <p style="color: #9CA3AF; font-size: 0.875rem;">JPG, PNG, PDF (حد أقصى 5MB)</p>
                    </div>
                    <div class="file-preview" id="filePreview" style="margin-top: 1rem; padding: 1rem; background: #F9FAFB; border-radius: 8px; display: none;">
                        <img id="previewImage" src="" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 8px; margin-bottom: 0.5rem; display: none;">
                        <p id="previewFileName" style="color: #1F2937; font-weight: 600;"></p>
                        <button type="button" id="removeFile" style="margin-top: 0.5rem; padding: 0.5rem 1rem; background: #EF4444; color: white; border: none; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-times"></i> إزالة
                        </button>
                    </div>
                    @error('receipt')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div id="amount_display" style="display: none; background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%); border: 2px solid #10B981; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                        <span style="font-weight: 600; color: #065F46; font-size: 1rem;">
                            <i class="fas fa-money-bill-wave" style="margin-left: 0.5rem;"></i>
                            المبلغ المطلوب دفعه:
                        </span>
                        <span id="amount_value" style="font-size: 1.75rem; font-weight: 800; color: #047857;">0 جنيه</span>
                    </div>
                </div>
                
                <div class="info-box">
                    <p>
                        <strong>ملاحظة:</strong> 
                        <span id="amount_note">رسوم المعاينة {{ number_format($inspectionFee) }} جنيه. يرجى اختيار المحفظة ورفع إيصال التحويل لإكمال الحجز.</span>
                    </p>
                </div>
                
                <div class="form-actions">
                    <a href="{{ route('property.show', $property) }}" class="btn-cancel">
                        <i class="fas fa-times"></i>
                        <span>إلغاء</span>
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check"></i>
                        <span>تأكيد الحجز والدفع</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingType = document.getElementById('booking_type');
    const note = document.getElementById('amount_note');
    const walletSelect = document.getElementById('wallet_id');
    const walletInfo = document.getElementById('walletInfo');
    const walletDetails = document.getElementById('walletDetails');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const receiptInput = document.getElementById('receipt');
    const filePreview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const previewFileName = document.getElementById('previewFileName');
    const removeFileBtn = document.getElementById('removeFile');
    
    // تحديث ملاحظة المبلغ
    const inspectionFee = {{ $inspectionFee }};
    const reservationPercentage = {{ $reservationPercentage }};
    const propertyPrice = {{ $property->price }};
    const amountDisplay = document.getElementById('amount_display');
    const amountValue = document.getElementById('amount_value');
    
    // عرض المبلغ عند تحميل الصفحة
    updateAmountDisplay('inspection');
    
    bookingType.addEventListener('change', function() {
        updateAmountDisplay(this.value);
    });
    
    function updateAmountDisplay(type) {
        if (type === 'reservation') {
            const reservationAmount = (propertyPrice * reservationPercentage) / 100;
            note.textContent = 'حجز الوحدة يتطلب دفع ' + reservationPercentage + '% من السعر كعربون. يرجى اختيار المحفظة ورفع إيصال التحويل لإكمال الحجز.';
            amountValue.textContent = new Intl.NumberFormat('ar-EG', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2 
            }).format(reservationAmount) + ' جنيه';
            amountDisplay.style.display = 'block';
        } else {
            note.textContent = 'رسوم المعاينة ' + new Intl.NumberFormat('ar-EG').format(inspectionFee) + ' جنيه. يرجى اختيار المحفظة ورفع إيصال التحويل لإكمال الحجز.';
            amountValue.textContent = new Intl.NumberFormat('ar-EG').format(inspectionFee) + ' جنيه';
            amountDisplay.style.display = 'block';
        }
    }
    
    // عرض بيانات المحفظة عند الاختيار
    walletSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const wallet = JSON.parse(selectedOption.getAttribute('data-wallet'));
            let html = '';
            
            if (wallet.bank_name) {
                html += `<div class="wallet-info-item" style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(107, 137, 128, 0.2);"><span style="font-weight: 600; color: #536b63; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-university"></i> اسم البنك:</span><span style="font-weight: 700; color: #1d313f; word-break: break-word;">${wallet.bank_name}</span></div>`;
            }
            if (wallet.account_name) {
                html += `<div class="wallet-info-item" style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(107, 137, 128, 0.2);"><span style="font-weight: 600; color: #536b63; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-user"></i> اسم صاحب الحساب:</span><span style="font-weight: 700; color: #1d313f; word-break: break-word;">${wallet.account_name}</span></div>`;
            }
            if (wallet.account_number) {
                html += `<div class="wallet-info-item" style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(107, 137, 128, 0.2);"><span style="font-weight: 600; color: #536b63; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-hashtag"></i> رقم الحساب:</span><span style="font-weight: 700; color: #1d313f; font-family: monospace; font-size: 1.1rem; word-break: break-all; direction: ltr; text-align: right; display: inline-block;">${wallet.account_number}</span></div>`;
            }
            if (wallet.iban) {
                html += `<div class="wallet-info-item" style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(107, 137, 128, 0.2);"><span style="font-weight: 600; color: #536b63; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-barcode"></i> رقم الآيبان:</span><span style="font-weight: 700; color: #1d313f; font-family: monospace; font-size: 1.1rem; word-break: break-all; direction: ltr; text-align: right; display: inline-block;">${wallet.iban}</span></div>`;
            }
            if (wallet.phone_number) {
                html += `<div class="wallet-info-item" style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(107, 137, 128, 0.2);"><span style="font-weight: 600; color: #536b63; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-phone"></i> رقم الهاتف:</span><span style="font-weight: 700; color: #1d313f; word-break: break-word; direction: ltr; text-align: right; display: inline-block;">${wallet.phone_number}</span></div>`;
            }
            
            if (!html) {
                html = '<p style="color: #6B7280; text-align: center; padding: 1rem;">لا توجد بيانات متاحة لهذه المحفظة</p>';
            }
            
            walletDetails.innerHTML = html;
            walletInfo.style.display = 'block';
        } else {
            walletInfo.style.display = 'none';
        }
    });
    
    // رفع الملف
    fileUploadArea.addEventListener('click', () => receiptInput.click());
    
    fileUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        fileUploadArea.style.borderColor = '#1d313f';
        fileUploadArea.style.background = '#DBEAFE';
    });
    
    fileUploadArea.addEventListener('dragleave', () => {
        fileUploadArea.style.borderColor = '#D1D5DB';
        fileUploadArea.style.background = '#F9FAFB';
    });
    
    fileUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        fileUploadArea.style.borderColor = '#D1D5DB';
        fileUploadArea.style.background = '#F9FAFB';
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            receiptInput.files = files;
            handleFileSelect(files[0]);
        }
    });
    
    receiptInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFileSelect(this.files[0]);
        }
    });
    
    function handleFileSelect(file) {
        if (file.size > 5 * 1024 * 1024) {
            alert('حجم الملف كبير جداً. الحد الأقصى 5MB');
            return;
        }
        
        previewFileName.textContent = file.name;
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.style.display = 'none';
        }
        
        filePreview.style.display = 'block';
    }
    
    removeFileBtn.addEventListener('click', function() {
        receiptInput.value = '';
        filePreview.style.display = 'none';
        previewImage.src = '';
        previewImage.style.display = 'none';
    });
});
</script>
@endsection



