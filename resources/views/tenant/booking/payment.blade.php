@extends('layouts.app')

@section('title', 'الدفع')

@push('styles')
<style>
    .payment-page {
        min-height: calc(100vh - 80px);
        padding: 2rem 0;
        background: linear-gradient(135deg, #F9FAFB 0%, #F0F9FF 100%);
    }
    
    .payment-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    .payment-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .payment-header h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #1F2937;
        margin-bottom: 0.5rem;
    }
    
    .payment-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }
    
    .payment-card h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .payment-card h2 i {
        color: #1d313f;
    }
    
    .booking-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .detail-item {
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 12px;
        border-right: 4px solid #1d313f;
    }
    
    .detail-item-label {
        font-size: 0.875rem;
        color: #6B7280;
        margin-bottom: 0.5rem;
    }
    
    .detail-item-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .amount-highlight {
        background: linear-gradient(135deg, #DBEAFE 0%, #E0F2FE 100%);
        border: 2px solid #2a4456;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .amount-highlight .label {
        font-size: 0.875rem;
        color: #1d313f;
        margin-bottom: 0.5rem;
    }
    
    .amount-highlight .value {
        font-size: 2rem;
        font-weight: 900;
        color: #1d313f;
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
    
    .form-select,
    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
        font-family: 'Cairo', sans-serif;
    }
    
    .form-select:focus,
    .form-input:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .wallet-info {
        background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
        border: 2px solid #6b8980;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1rem;
        display: none;
    }
    
    .wallet-info.active {
        display: block;
    }
    
    .wallet-info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(107, 137, 128, 0.2);
    }
    
    .wallet-info-item:last-child {
        border-bottom: none;
    }
    
    .wallet-info-label {
        font-weight: 600;
        color: #536b63;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .wallet-info-label i {
        width: 20px;
        text-align: center;
    }
    
    .wallet-info-value {
        color: #1F2937;
        font-weight: 500;
    }
    
    .file-upload-area {
        border: 2px dashed #D1D5DB;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #F9FAFB;
    }
    
    .file-upload-area:hover {
        border-color: #1d313f;
        background: #F0F9FF;
    }
    
    .file-upload-area.dragover {
        border-color: #1d313f;
        background: #DBEAFE;
    }
    
    .file-preview {
        margin-top: 1rem;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 8px;
        display: none;
    }
    
    .file-preview.active {
        display: block;
    }
    
    .file-preview img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    
    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        font-weight: 700;
        padding: 1rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(29, 49, 63, 0.3);
    }
    
    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .info-box {
        background: #FEF3C7;
        border: 2px solid #F59E0B;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .info-box p {
        color: #92400E;
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.6;
    }
    
    @media (max-width: 768px) {
        .payment-page {
            padding: 1.5rem 0;
        }
        
        .payment-container {
            padding: 0 1rem;
        }
        
        .payment-header h1 {
            font-size: 1.75rem;
        }
        
        .payment-card {
            padding: 1.5rem;
        }
        
        .booking-details {
            grid-template-columns: 1fr;
        }
        
        .wallet-info {
            padding: 1rem;
            margin-top: 0.75rem;
        }
        
        .wallet-info h3 {
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }
        
        .wallet-info > div {
            padding: 0.75rem;
        }
        
        .wallet-info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 0.875rem 0;
        }
        
        .wallet-info-label {
            font-size: 0.875rem;
            width: 100%;
        }
        
        .wallet-info-value {
            font-size: 0.95rem;
            width: 100%;
            word-break: break-all;
            padding: 0.5rem;
            background: white;
            border-radius: 6px;
            border: 1px solid rgba(107, 137, 128, 0.2);
        }
        
        .wallet-info-value[style*="monospace"] {
            font-size: 0.9rem !important;
            padding: 0.625rem;
        }
    }
    
    @media (max-width: 480px) {
        .wallet-info {
            padding: 0.875rem;
        }
        
        .wallet-info > div {
            padding: 0.625rem;
        }
        
        .wallet-info-item {
            padding: 0.75rem 0;
        }
        
        .wallet-info-label {
            font-size: 0.8rem;
        }
        
        .wallet-info-value {
            font-size: 0.85rem;
            padding: 0.5rem;
        }
        
        .wallet-info-value[style*="monospace"] {
            font-size: 0.8rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="payment-page">
    <div class="payment-container">
        <div class="payment-header">
            <h1>إتمام عملية الدفع</h1>
            <p style="color: #6B7280;">اختر المحفظة وقم برفع إيصال التحويل</p>
        </div>
        
        <div class="payment-card">
            <h2>
                <i class="fas fa-calendar-check"></i>
                تفاصيل الحجز
            </h2>
            <div class="booking-details">
                <div class="detail-item">
                    <div class="detail-item-label">الوحدة</div>
                    <div class="detail-item-value">{{ $booking->property->address }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">تاريخ المعاينة</div>
                    <div class="detail-item-value">{{ $booking->inspection_date->format('Y-m-d') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">وقت المعاينة</div>
                    <div class="detail-item-value">{{ $booking->inspection_time }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-item-label">نوع الحجز</div>
                    <div class="detail-item-value">{{ $booking->booking_type === 'inspection' ? 'معاينة' : 'حجز' }}</div>
                </div>
            </div>
            <div class="amount-highlight">
                <div class="label">المبلغ المطلوب</div>
                <div class="value">{{ number_format($booking->amount, 2) }} ج.م</div>
            </div>
        </div>
        
        <div class="payment-card">
            <h2>
                <i class="fas fa-wallet"></i>
                بيانات الدفع
            </h2>
            
            <form method="POST" action="{{ route('tenant.booking.confirm-payment', $booking) }}" enctype="multipart/form-data" id="paymentForm">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf_token_input">
                
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
                
                <div class="wallet-info" id="walletInfo">
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
                    <div class="file-upload-area" id="fileUploadArea">
                        <input type="file" name="receipt" id="receipt" accept="image/*,application/pdf" required class="hidden">
                        <p style="margin-bottom: 0.5rem;">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #9CA3AF; margin-bottom: 0.5rem;"></i>
                        </p>
                        <p style="color: #6B7280; font-weight: 600; margin-bottom: 0.25rem;">
                            اسحب وأفلت الإيصال هنا أو <span style="color: #1d313f; cursor: pointer;">اضغط للرفع</span>
                        </p>
                        <p style="color: #9CA3AF; font-size: 0.875rem;">JPG, PNG, PDF (حد أقصى 5MB)</p>
                    </div>
                    <div class="file-preview" id="filePreview">
                        <img id="previewImage" src="" alt="Preview" style="display: none;">
                        <p id="previewFileName" style="color: #1F2937; font-weight: 600;"></p>
                        <button type="button" id="removeFile" style="margin-top: 0.5rem; padding: 0.5rem 1rem; background: #EF4444; color: white; border: none; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-times"></i> إزالة
                        </button>
                    </div>
                    @error('receipt')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="info-box">
                    <p>
                        <strong>ملاحظة مهمة:</strong> 
                        بعد رفع الإيصال، سيتم مراجعة الدفعة من قبل إدارة الموقع. سيتم إشعارك عند تأكيد الدفعة.
                    </p>
                </div>
                
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-check"></i>
                    <span>إرسال طلب الدفع</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const walletSelect = document.getElementById('wallet_id');
    const walletInfo = document.getElementById('walletInfo');
    const walletDetails = document.getElementById('walletDetails');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const receiptInput = document.getElementById('receipt');
    const filePreview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const previewFileName = document.getElementById('previewFileName');
    const removeFileBtn = document.getElementById('removeFile');
    
    // عرض بيانات المحفظة عند الاختيار
    walletSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const wallet = JSON.parse(selectedOption.getAttribute('data-wallet'));
            let html = '';
            
                if (wallet.bank_name) {
                    html += `<div class="wallet-info-item"><span class="wallet-info-label"><i class="fas fa-university"></i> اسم البنك:</span><span class="wallet-info-value" style="font-weight: 700; color: #1d313f; word-break: break-word;">${wallet.bank_name}</span></div>`;
                }
                if (wallet.account_name) {
                    html += `<div class="wallet-info-item"><span class="wallet-info-label"><i class="fas fa-user"></i> اسم صاحب الحساب:</span><span class="wallet-info-value" style="font-weight: 700; color: #1d313f; word-break: break-word;">${wallet.account_name}</span></div>`;
                }
                if (wallet.account_number) {
                    html += `<div class="wallet-info-item"><span class="wallet-info-label"><i class="fas fa-hashtag"></i> رقم الحساب:</span><span class="wallet-info-value" style="font-weight: 700; color: #1d313f; font-family: monospace; font-size: 1.1rem; word-break: break-all; direction: ltr; text-align: right; display: inline-block;">${wallet.account_number}</span></div>`;
                }
                if (wallet.iban) {
                    html += `<div class="wallet-info-item"><span class="wallet-info-label"><i class="fas fa-barcode"></i> رقم الآيبان:</span><span class="wallet-info-value" style="font-weight: 700; color: #1d313f; font-family: monospace; font-size: 1.1rem; word-break: break-all; direction: ltr; text-align: right; display: inline-block;">${wallet.iban}</span></div>`;
                }
                if (wallet.phone_number) {
                    html += `<div class="wallet-info-item"><span class="wallet-info-label"><i class="fas fa-phone"></i> رقم الهاتف:</span><span class="wallet-info-value" style="font-weight: 700; color: #1d313f; word-break: break-word; direction: ltr; text-align: right; display: inline-block;">${wallet.phone_number}</span></div>`;
                }
            
            if (!html) {
                html = '<p style="color: #6B7280; text-align: center; padding: 1rem;">لا توجد بيانات متاحة لهذه المحفظة</p>';
            }
            
            walletDetails.innerHTML = html;
            walletInfo.classList.add('active');
        } else {
            walletInfo.classList.remove('active');
        }
    });
    
    // رفع الملف
    fileUploadArea.addEventListener('click', () => receiptInput.click());
    
    fileUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        fileUploadArea.classList.add('dragover');
    });
    
    fileUploadArea.addEventListener('dragleave', () => {
        fileUploadArea.classList.remove('dragover');
    });
    
    fileUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
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
        
        filePreview.classList.add('active');
    }
    
    removeFileBtn.addEventListener('click', function() {
        receiptInput.value = '';
        filePreview.classList.remove('active');
        previewImage.src = '';
        previewImage.style.display = 'none';
    });
});
</script>
@endsection


