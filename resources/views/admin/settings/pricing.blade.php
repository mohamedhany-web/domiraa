@extends('layouts.admin')

@section('title', 'إعدادات الأسعار')
@section('page-title', 'إعدادات الأسعار والرسوم')

@push('styles')
<style>
    .settings-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .settings-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .settings-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .settings-header h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
    }
    
    .settings-header p {
        color: #6B7280;
        margin: 0;
    }
    
    .setting-group {
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        background: #F9FAFB;
        border-radius: 12px;
    }
    
    .setting-group-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .setting-group-title i {
        color: var(--primary);
    }
    
    .setting-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: white;
        border-radius: 10px;
        margin-bottom: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .setting-item:last-child {
        margin-bottom: 0;
    }
    
    .setting-info {
        flex: 1;
    }
    
    .setting-label {
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.25rem;
    }
    
    .setting-desc {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .setting-input {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .setting-input input {
        width: 120px;
        padding: 0.75rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        text-align: center;
        transition: all 0.2s ease;
    }
    
    .setting-input input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .setting-input .unit {
        font-weight: 600;
        color: #6B7280;
        min-width: 40px;
    }
    
    .btn-save {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
    }
    
    .alert {
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert-success {
        background: #D1FAE5;
        color: #065F46;
        border: 1px solid #6b8980;
    }
    
    .preview-box {
        background: linear-gradient(135deg, rgba(29, 49, 63, 0.05), rgba(107, 137, 128, 0.05));
        border: 2px dashed var(--secondary);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .preview-title {
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .preview-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .preview-item:last-child {
        border-bottom: none;
    }
    
    .preview-label {
        color: #6B7280;
    }
    
    .preview-value {
        font-weight: 700;
        color: var(--primary);
    }
    
    @media (max-width: 768px) {
        .setting-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .setting-input {
            width: 100%;
        }
        
        .setting-input input {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-container">
    <div class="settings-card">
        <div class="settings-header">
            <h2><i class="fas fa-coins"></i> إعدادات الأسعار والرسوم</h2>
            <p>تحكم في رسوم المعاينة والحجز وعمولة المنصة</p>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif
        
        <form action="{{ route('admin.settings.pricing.update') }}" method="POST">
            @csrf
            
            <div class="setting-group">
                <h3 class="setting-group-title">
                    <i class="fas fa-hand-holding-usd"></i>
                    رسوم المعاينة والحجز
                </h3>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <div class="setting-label">رسوم المعاينة</div>
                        <div class="setting-desc">المبلغ المطلوب من المستأجر لحجز معاينة الوحدة</div>
                    </div>
                    <div class="setting-input">
                        <input type="number" name="inspection_fee" id="inspection_fee" 
                               value="{{ \App\Models\Setting::get('inspection_fee', 50) }}" 
                               step="0.01" min="0" required>
                        <span class="unit">ج.م</span>
                    </div>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <div class="setting-label">نسبة الحجز - يومي</div>
                        <div class="setting-desc">نسبة من سعر الوحدة اليومي كرسوم حجز مبدئي</div>
                    </div>
                    <div class="setting-input">
                        <input type="number" name="reservation_percentage_daily" id="reservation_percentage_daily" 
                               value="{{ \App\Models\Setting::get('reservation_percentage_daily', 10) }}" 
                               step="0.1" min="0" max="100" required>
                        <span class="unit">%</span>
                    </div>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <div class="setting-label">نسبة الحجز - شهري</div>
                        <div class="setting-desc">نسبة من سعر الوحدة الشهري كرسوم حجز مبدئي</div>
                    </div>
                    <div class="setting-input">
                        <input type="number" name="reservation_percentage_monthly" id="reservation_percentage_monthly" 
                               value="{{ \App\Models\Setting::get('reservation_percentage_monthly', 10) }}" 
                               step="0.1" min="0" max="100" required>
                        <span class="unit">%</span>
                    </div>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <div class="setting-label">نسبة الحجز - سنوي</div>
                        <div class="setting-desc">نسبة من سعر الوحدة السنوي كرسوم حجز مبدئي</div>
                    </div>
                    <div class="setting-input">
                        <input type="number" name="reservation_percentage_yearly" id="reservation_percentage_yearly" 
                               value="{{ \App\Models\Setting::get('reservation_percentage_yearly', 10) }}" 
                               step="0.1" min="0" max="100" required>
                        <span class="unit">%</span>
                    </div>
                </div>
            </div>
            
            <div class="setting-group">
                <h3 class="setting-group-title">
                    <i class="fas fa-percentage"></i>
                    عمولة المنصة
                </h3>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <div class="setting-label">نسبة عمولة المنصة</div>
                        <div class="setting-desc">نسبة العمولة التي تحصل عليها المنصة من كل عملية</div>
                    </div>
                    <div class="setting-input">
                        <input type="number" name="platform_fee_percentage" id="platform_fee_percentage" 
                               value="{{ \App\Models\Setting::get('platform_fee_percentage', 5) }}" 
                               step="0.1" min="0" max="100" required>
                        <span class="unit">%</span>
                    </div>
                </div>
            </div>
            
            <div class="preview-box">
                <h4 class="preview-title">
                    <i class="fas fa-calculator"></i>
                    مثال على وحدة بسعر 1000 ج.م
                </h4>
                <div class="preview-item">
                    <span class="preview-label">رسوم المعاينة:</span>
                    <span class="preview-value" id="preview_inspection">50 ج.م</span>
                </div>
                <div class="preview-item">
                    <span class="preview-label">رسوم الحجز يومي:</span>
                    <span class="preview-value" id="preview_reservation_daily">100 ج.م</span>
                </div>
                <div class="preview-item">
                    <span class="preview-label">رسوم الحجز شهري:</span>
                    <span class="preview-value" id="preview_reservation_monthly">100 ج.م</span>
                </div>
                <div class="preview-item">
                    <span class="preview-label">رسوم الحجز سنوي:</span>
                    <span class="preview-value" id="preview_reservation_yearly">100 ج.م</span>
                </div>
                <div class="preview-item">
                    <span class="preview-label">عمولة المنصة (5%):</span>
                    <span class="preview-value" id="preview_platform">50 ج.م</span>
                </div>
            </div>
            
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i>
                حفظ الإعدادات
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inspectionInput = document.getElementById('inspection_fee');
    const reservationDailyInput = document.getElementById('reservation_percentage_daily');
    const reservationMonthlyInput = document.getElementById('reservation_percentage_monthly');
    const reservationYearlyInput = document.getElementById('reservation_percentage_yearly');
    const platformInput = document.getElementById('platform_fee_percentage');
    
    function updatePreview() {
        const basePrice = 1000;
        const inspection = parseFloat(inspectionInput.value) || 0;
        const reservationDaily = (parseFloat(reservationDailyInput.value) || 0) / 100 * basePrice;
        const reservationMonthly = (parseFloat(reservationMonthlyInput.value) || 0) / 100 * basePrice;
        const reservationYearly = (parseFloat(reservationYearlyInput.value) || 0) / 100 * basePrice;
        const platform = (parseFloat(platformInput.value) || 0) / 100 * basePrice;
        
        document.getElementById('preview_inspection').textContent = inspection.toFixed(2) + ' ج.م';
        document.getElementById('preview_reservation_daily').textContent = reservationDaily.toFixed(2) + ' ج.م';
        document.getElementById('preview_reservation_monthly').textContent = reservationMonthly.toFixed(2) + ' ج.م';
        document.getElementById('preview_reservation_yearly').textContent = reservationYearly.toFixed(2) + ' ج.م';
        document.getElementById('preview_platform').textContent = platform.toFixed(2) + ' ج.م';
    }
    
    inspectionInput.addEventListener('input', updatePreview);
    reservationDailyInput.addEventListener('input', updatePreview);
    reservationMonthlyInput.addEventListener('input', updatePreview);
    reservationYearlyInput.addEventListener('input', updatePreview);
    platformInput.addEventListener('input', updatePreview);
    
    updatePreview();
});
</script>
@endsection

