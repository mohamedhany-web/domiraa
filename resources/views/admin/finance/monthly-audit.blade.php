@extends('layouts.admin')

@section('title', 'الجرد الشهري')
@section('page-title', 'الجرد الشهري')

@push('styles')
<style>
    .audit-header {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .audit-title h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1F2937;
        margin: 0 0 0.25rem 0;
    }
    
    .audit-title p {
        color: #6B7280;
        margin: 0;
    }
    
    .month-selector {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    
    .month-selector select {
        padding: 0.75rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
    }
    
    .btn-print {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
        cursor: pointer;
    }
    
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .summary-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .summary-card.highlight {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }
    
    .summary-card.highlight .summary-label {
        color: rgba(255,255,255,0.8);
    }
    
    .summary-value {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
    }
    
    .summary-label {
        font-size: 0.9rem;
        color: #6B7280;
    }
    
    .audit-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .audit-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .audit-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #F3F4F6;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .audit-card-header i {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .audit-card-header h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
    }
    
    .audit-card-body {
        padding: 1.5rem;
    }
    
    /* جدول المحافظ */
    .wallet-audit-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 12px;
        margin-bottom: 0.75rem;
    }
    
    .wallet-audit-item:last-child {
        margin-bottom: 0;
    }
    
    .wallet-audit-info {
        flex: 1;
    }
    
    .wallet-audit-name {
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.25rem;
    }
    
    .wallet-audit-stats {
        display: flex;
        gap: 1.5rem;
        font-size: 0.85rem;
    }
    
    .wallet-audit-stats span {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .wallet-audit-stats .income { color: #059669; }
    .wallet-audit-stats .expense { color: #DC2626; }
    
    .wallet-audit-balance {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary);
    }
    
    /* المصروفات حسب الفئة */
    .category-audit-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .category-audit-item:last-child {
        border-bottom: none;
    }
    
    .category-audit-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-left: 1rem;
    }
    
    .category-audit-info {
        flex: 1;
    }
    
    .category-audit-name {
        font-weight: 600;
        color: #1F2937;
    }
    
    .category-audit-count {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .category-audit-total {
        font-weight: 700;
        color: #1F2937;
    }
    
    /* المدفوعات */
    .payment-method-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 10px;
        margin-bottom: 0.75rem;
    }
    
    .payment-method-item:last-child {
        margin-bottom: 0;
    }
    
    .payment-method-name {
        font-weight: 600;
        color: #1F2937;
    }
    
    .payment-method-stats {
        text-align: left;
    }
    
    .payment-method-count {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .payment-method-amount {
        font-weight: 700;
        color: #059669;
    }
    
    /* الطباعة */
    @media print {
        .sidebar, .header, .month-selector, .btn-print {
            display: none !important;
        }
        
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .audit-card, .summary-card {
            break-inside: avoid;
        }
    }
    
    @media (max-width: 1200px) {
        .summary-cards {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 1024px) {
        .audit-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .summary-cards {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .audit-header {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="audit-header">
    <div class="audit-title">
        <h2><i class="fas fa-clipboard-check"></i> الجرد الشهري</h2>
        <p>{{ $startDate->translatedFormat('F Y') }}</p>
    </div>
    <div class="month-selector">
        <form action="{{ route('admin.finance.monthly-audit') }}" method="GET" style="display: flex; gap: 0.5rem;">
            <select name="month" onchange="this.form.submit()">
                @foreach($months as $m)
                <option value="{{ $m['month'] }}" data-year="{{ $m['year'] }}" {{ ($year == $m['year'] && $month == $m['month']) ? 'selected' : '' }}>
                    {{ $m['label'] }}
                </option>
                @endforeach
            </select>
            <input type="hidden" name="year" id="selectedYear" value="{{ $year }}">
        </form>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i>
            طباعة
        </button>
    </div>
</div>

<!-- ملخص الشهر -->
<div class="summary-cards">
    <div class="summary-card">
        <div class="summary-value" style="color: #059669;">{{ number_format($totals['total_income'], 2) }}</div>
        <div class="summary-label">إجمالي الإيرادات</div>
    </div>
    <div class="summary-card">
        <div class="summary-value" style="color: #DC2626;">{{ number_format($totals['total_expenses'], 2) }}</div>
        <div class="summary-label">إجمالي المصروفات</div>
    </div>
    <div class="summary-card highlight">
        <div class="summary-value">{{ number_format($totals['net_profit'], 2) }}</div>
        <div class="summary-label">صافي الربح</div>
    </div>
    <div class="summary-card">
        <div class="summary-value">{{ number_format($totals['total_wallets_balance'], 2) }}</div>
        <div class="summary-label">رصيد المحافظ</div>
    </div>
    <div class="summary-card">
        <div class="summary-value">{{ $totals['payments_count'] }}</div>
        <div class="summary-label">عدد المدفوعات</div>
    </div>
</div>

<div class="audit-grid">
    <!-- المحافظ -->
    <div class="audit-card">
        <div class="audit-card-header">
            <i style="background: linear-gradient(135deg, #3B82F6, #2563EB);"><span class="fas fa-wallet"></span></i>
            <h3>ملخص المحافظ</h3>
        </div>
        <div class="audit-card-body">
            @forelse($walletsSummary as $wallet)
            <div class="wallet-audit-item">
                <div class="wallet-audit-info">
                    <div class="wallet-audit-name">{{ $wallet['name'] }}</div>
                    <div class="wallet-audit-stats">
                        <span class="income"><i class="fas fa-arrow-down"></i> {{ number_format($wallet['income'], 2) }}</span>
                        <span class="expense"><i class="fas fa-arrow-up"></i> {{ number_format($wallet['expenses'], 2) }}</span>
                        <span><i class="fas fa-exchange-alt"></i> {{ $wallet['transactions_count'] }} معاملة</span>
                    </div>
                </div>
                <div class="wallet-audit-balance">{{ number_format($wallet['current_balance'], 2) }} ج.م</div>
            </div>
            @empty
            <p style="text-align: center; color: #6B7280;">لا توجد محافظ</p>
            @endforelse
        </div>
    </div>
    
    <!-- المصروفات حسب الفئة -->
    <div class="audit-card">
        <div class="audit-card-header">
            <i style="background: linear-gradient(135deg, #EF4444, #DC2626);"><span class="fas fa-chart-pie"></span></i>
            <h3>المصروفات حسب الفئة</h3>
        </div>
        <div class="audit-card-body">
            @forelse($expensesSummary as $category)
            <div class="category-audit-item">
                <div class="category-audit-icon" style="background: {{ $category['color'] ?? '#6B7280' }};">
                    <i class="fas {{ $category['icon'] ?? 'fa-tag' }}"></i>
                </div>
                <div class="category-audit-info">
                    <div class="category-audit-name">{{ $category['name'] }}</div>
                    <div class="category-audit-count">{{ $category['count'] }} مصروف</div>
                </div>
                <div class="category-audit-total">{{ number_format($category['total'], 2) }} ج.م</div>
            </div>
            @empty
            <p style="text-align: center; color: #6B7280; padding: 1rem;">لا توجد مصروفات هذا الشهر</p>
            @endforelse
        </div>
    </div>
    
    <!-- المدفوعات حسب طريقة الدفع -->
    <div class="audit-card">
        <div class="audit-card-header">
            <i style="background: linear-gradient(135deg, #10B981, #059669);"><span class="fas fa-credit-card"></span></i>
            <h3>المدفوعات حسب الطريقة</h3>
        </div>
        <div class="audit-card-body">
            @forelse($paymentsSummary as $payment)
            <div class="payment-method-item">
                <div>
                    <div class="payment-method-name">
                        {{ $payment->payment_method == 'bank_transfer' ? 'تحويل بنكي' : ($payment->payment_method == 'stc_pay' ? 'STC Pay' : $payment->payment_method) }}
                    </div>
                    <div class="payment-method-count">{{ $payment->count }} عملية</div>
                </div>
                <div class="payment-method-stats">
                    <div class="payment-method-amount">{{ number_format($payment->total_amount, 2) }} ج.م</div>
                </div>
            </div>
            @empty
            <p style="text-align: center; color: #6B7280; padding: 1rem;">لا توجد مدفوعات هذا الشهر</p>
            @endforelse
        </div>
    </div>
    
    <!-- رسوم المنصة -->
    <div class="audit-card">
        <div class="audit-card-header">
            <i style="background: linear-gradient(135deg, #8B5CF6, #7C3AED);"><span class="fas fa-percentage"></span></i>
            <h3>عمولة المنصة</h3>
        </div>
        <div class="audit-card-body">
            <div style="text-align: center; padding: 2rem;">
                <div style="font-size: 2.5rem; font-weight: 800; color: #8B5CF6; margin-bottom: 0.5rem;">
                    {{ number_format($totals['platform_fees'] ?? 0, 2) }}
                </div>
                <div style="color: #6B7280;">جنيه مصري</div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #E5E7EB;">
                    <small style="color: #6B7280;">من {{ $totals['payments_count'] }} عملية دفع</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('select[name="month"]').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('selectedYear').value = selectedOption.dataset.year;
});
</script>
@endsection

