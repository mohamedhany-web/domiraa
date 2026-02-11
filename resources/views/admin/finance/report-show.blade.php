@extends('layouts.admin')

@section('title', $report->title)
@section('page-title', $report->title)

@push('styles')
<style>
    .report-header {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .report-header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .report-info h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
    }
    
    .report-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        color: #6B7280;
        font-size: 0.9rem;
    }
    
    .report-meta span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .report-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .btn {
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-back {
        background: #F3F4F6;
        color: #374151;
    }
    
    .btn-print {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }
    
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    
    .summary-card {
        background: #F9FAFB;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
    }
    
    .summary-card.income {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
        border: 2px solid rgba(16, 185, 129, 0.3);
    }
    
    .summary-card.expenses {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
        border: 2px solid rgba(239, 68, 68, 0.3);
    }
    
    .summary-card.profit {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }
    
    .summary-card.profit .summary-label {
        color: rgba(255,255,255,0.8);
    }
    
    .summary-value {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
    }
    
    .summary-card.income .summary-value { color: #059669; }
    .summary-card.expenses .summary-value { color: #DC2626; }
    
    .summary-label {
        font-size: 0.9rem;
        color: #6B7280;
    }
    
    .report-section {
        background: white;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .section-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-header i {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .section-header h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
    }
    
    .section-body {
        padding: 1.5rem;
    }
    
    .data-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    /* Wallets Summary */
    .wallet-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 10px;
        margin-bottom: 0.75rem;
    }
    
    .wallet-item:last-child {
        margin-bottom: 0;
    }
    
    .wallet-info {
        flex: 1;
    }
    
    .wallet-name {
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.25rem;
    }
    
    .wallet-stats {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
    }
    
    .wallet-stats .income { color: #059669; }
    .wallet-stats .expense { color: #DC2626; }
    
    .wallet-balance {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    /* Categories */
    .category-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .category-item:last-child {
        border-bottom: none;
    }
    
    .category-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-left: 0.75rem;
        font-size: 0.9rem;
    }
    
    .category-name {
        flex: 1;
        font-weight: 600;
        color: #1F2937;
    }
    
    .category-count {
        color: #6B7280;
        font-size: 0.85rem;
        margin-left: 1rem;
    }
    
    .category-total {
        font-weight: 700;
        color: #1F2937;
    }
    
    /* Daily Chart Placeholder */
    .daily-chart {
        height: 250px;
        display: flex;
        align-items: flex-end;
        gap: 4px;
        padding: 1rem 0;
    }
    
    .daily-bar {
        flex: 1;
        background: linear-gradient(180deg, var(--primary), var(--secondary));
        border-radius: 4px 4px 0 0;
        min-height: 10px;
        position: relative;
    }
    
    .daily-bar:hover::after {
        content: attr(data-value);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #1F2937;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        white-space: nowrap;
    }
    
    /* Notes */
    .notes-section {
        background: #FEF3C7;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .notes-section h4 {
        color: #92400E;
        margin: 0 0 0.5rem 0;
        font-size: 0.9rem;
    }
    
    .notes-section p {
        color: #78350F;
        margin: 0;
    }
    
    @media print {
        .sidebar, .header, .report-actions {
            display: none !important;
        }
        
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .report-section {
            break-inside: avoid;
        }
    }
    
    @media (max-width: 1024px) {
        .data-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .summary-cards {
            grid-template-columns: 1fr;
        }
        
        .report-header-top {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="report-header">
    <div class="report-header-top">
        <div class="report-info">
            <h1><i class="fas fa-file-alt"></i> {{ $report->title }}</h1>
            <div class="report-meta">
                <span><i class="fas fa-calendar"></i> {{ $report->start_date->format('Y/m/d') }} - {{ $report->end_date->format('Y/m/d') }}</span>
                <span><i class="fas fa-tag"></i> {{ $report->type_name }}</span>
                <span><i class="fas fa-user"></i> {{ $report->creator->name ?? 'غير معروف' }}</span>
                <span><i class="fas fa-clock"></i> {{ $report->created_at->format('Y/m/d H:i') }}</span>
            </div>
        </div>
        <div class="report-actions">
            <a href="{{ route('admin.finance.reports') }}" class="btn btn-back">
                <i class="fas fa-arrow-right"></i>
                العودة
            </a>
            <button onclick="window.print()" class="btn btn-print">
                <i class="fas fa-print"></i>
                طباعة
            </button>
        </div>
    </div>
    
    <div class="summary-cards">
        <div class="summary-card income">
            <div class="summary-value">{{ number_format($report->total_income, 2) }}</div>
            <div class="summary-label">إجمالي الإيرادات (ج.م)</div>
        </div>
        <div class="summary-card expenses">
            <div class="summary-value">{{ number_format($report->total_expenses, 2) }}</div>
            <div class="summary-label">إجمالي المصروفات (ج.م)</div>
        </div>
        <div class="summary-card profit">
            <div class="summary-value">{{ number_format($report->net_profit, 2) }}</div>
            <div class="summary-label">صافي الربح (ج.م)</div>
        </div>
    </div>
</div>

<div class="data-grid">
    <!-- ملخص المحافظ -->
    @if(isset($report->data['wallets_summary']) && count($report->data['wallets_summary']) > 0)
    <div class="report-section">
        <div class="section-header">
            <i style="background: linear-gradient(135deg, #3B82F6, #2563EB);"><span class="fas fa-wallet"></span></i>
            <h3>ملخص المحافظ</h3>
        </div>
        <div class="section-body">
            @foreach($report->data['wallets_summary'] as $wallet)
            <div class="wallet-item">
                <div class="wallet-info">
                    <div class="wallet-name">{{ $wallet['name'] }}</div>
                    <div class="wallet-stats">
                        <span class="income"><i class="fas fa-arrow-down"></i> {{ number_format($wallet['income'] ?? 0, 2) }}</span>
                        <span class="expense"><i class="fas fa-arrow-up"></i> {{ number_format($wallet['expenses'] ?? 0, 2) }}</span>
                    </div>
                </div>
                <div class="wallet-balance">{{ number_format($wallet['balance'] ?? 0, 2) }} ج.م</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- المصروفات حسب الفئة -->
    @if(isset($report->data['expenses_by_category']) && count($report->data['expenses_by_category']) > 0)
    <div class="report-section">
        <div class="section-header">
            <i style="background: linear-gradient(135deg, #EF4444, #DC2626);"><span class="fas fa-chart-pie"></span></i>
            <h3>المصروفات حسب الفئة</h3>
        </div>
        <div class="section-body">
            @php
                $categories = \App\Models\ExpenseCategory::all()->keyBy('id');
            @endphp
            @foreach($report->data['expenses_by_category'] as $categoryId => $data)
            @php
                $category = $categories[$categoryId] ?? null;
            @endphp
            <div class="category-item">
                <div class="category-icon" style="background: {{ $category->color ?? '#6B7280' }};">
                    <i class="fas {{ $category->icon ?? 'fa-tag' }}"></i>
                </div>
                <span class="category-name">{{ $category->name ?? 'فئة #' . $categoryId }}</span>
                <span class="category-count">{{ $data['count'] ?? 0 }} مصروف</span>
                <span class="category-total">{{ number_format($data['total'] ?? 0, 2) }} ج.م</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- الإيرادات حسب النوع -->
@if(isset($report->data['income_by_type']) && count($report->data['income_by_type']) > 0)
<div class="report-section">
    <div class="section-header">
        <i style="background: linear-gradient(135deg, #10B981, #059669);"><span class="fas fa-coins"></span></i>
        <h3>الإيرادات حسب المصدر</h3>
    </div>
    <div class="section-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            @foreach($report->data['income_by_type'] as $type => $amount)
            <div style="background: #D1FAE5; padding: 1rem; border-radius: 10px; text-align: center;">
                <div style="font-weight: 700; color: #059669; font-size: 1.25rem;">{{ number_format($amount, 2) }} ج.م</div>
                <div style="color: #065F46; font-size: 0.85rem;">{{ $type ?: 'أخرى' }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- الملخص اليومي -->
@if(isset($report->data['daily_summary']) && count($report->data['daily_summary']) > 0)
<div class="report-section">
    <div class="section-header">
        <i style="background: linear-gradient(135deg, #8B5CF6, #7C3AED);"><span class="fas fa-chart-bar"></span></i>
        <h3>الملخص اليومي</h3>
    </div>
    <div class="section-body">
        @php
            $dailyData = collect($report->data['daily_summary']);
            $maxValue = max($dailyData->max('income'), $dailyData->max('expenses'), 1);
        @endphp
        <div class="daily-chart">
            @foreach($dailyData as $date => $data)
            @php
                $incomeHeight = ($data['income'] / $maxValue) * 200;
                $expenseHeight = ($data['expenses'] / $maxValue) * 200;
            @endphp
            <div style="display: flex; flex-direction: column; align-items: center; flex: 1; gap: 2px;">
                <div class="daily-bar" style="height: {{ $incomeHeight }}px; background: linear-gradient(180deg, #10B981, #059669);" data-value="إيراد: {{ number_format($data['income'], 0) }}"></div>
                <div class="daily-bar" style="height: {{ $expenseHeight }}px; background: linear-gradient(180deg, #EF4444, #DC2626);" data-value="مصروف: {{ number_format($data['expenses'], 0) }}"></div>
                <small style="font-size: 0.65rem; color: #6B7280; writing-mode: vertical-rl; transform: rotate(180deg);">{{ \Carbon\Carbon::parse($date)->format('d') }}</small>
            </div>
            @endforeach
        </div>
        <div style="display: flex; justify-content: center; gap: 2rem; margin-top: 1rem;">
            <span style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="width: 12px; height: 12px; background: #10B981; border-radius: 2px;"></span>
                إيرادات
            </span>
            <span style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="width: 12px; height: 12px; background: #EF4444; border-radius: 2px;"></span>
                مصروفات
            </span>
        </div>
    </div>
</div>
@endif

<!-- ملاحظات -->
@if($report->notes)
<div class="report-section">
    <div class="section-body">
        <div class="notes-section">
            <h4><i class="fas fa-sticky-note"></i> ملاحظات</h4>
            <p>{{ $report->notes }}</p>
        </div>
    </div>
</div>
@endif

<!-- معلومات إضافية -->
<div class="report-section">
    <div class="section-header">
        <i style="background: linear-gradient(135deg, #6B7280, #4B5563);"><span class="fas fa-info-circle"></span></i>
        <h3>معلومات التقرير</h3>
    </div>
    <div class="section-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <div style="color: #6B7280; font-size: 0.85rem;">عدد المعاملات</div>
                <div style="font-weight: 700; color: #1F2937;">{{ $report->data['transactions_count'] ?? 0 }}</div>
            </div>
            <div>
                <div style="color: #6B7280; font-size: 0.85rem;">عدد المصروفات</div>
                <div style="font-weight: 700; color: #1F2937;">{{ $report->data['expenses_count'] ?? 0 }}</div>
            </div>
            <div>
                <div style="color: #6B7280; font-size: 0.85rem;">عدد المدفوعات</div>
                <div style="font-weight: 700; color: #1F2937;">{{ $report->data['payments_count'] ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

