@extends('layouts.admin')

@section('title', 'التقارير المالية')
@section('page-title', 'التقارير المالية')

@push('styles')
<style>
    .reports-header {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .report-generator {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .generator-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .generator-title i {
        color: var(--primary);
    }
    
    .generator-form {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .form-group label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6B7280;
    }
    
    .form-group input,
    .form-group select {
        padding: 0.625rem 0.875rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .btn-generate {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .btn-generate:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    .reports-list {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .list-header {
        padding: 1.25rem;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .list-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    table th,
    table td {
        padding: 1rem;
        text-align: right;
        border-bottom: 1px solid #E5E7EB;
    }
    
    table th {
        background: #F9FAFB;
        font-weight: 700;
        color: #374151;
        font-size: 0.85rem;
    }
    
    table tr:hover {
        background: #F9FAFB;
    }
    
    .report-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .report-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .report-icon.monthly { background: linear-gradient(135deg, #3B82F6, #2563EB); }
    .report-icon.quarterly { background: linear-gradient(135deg, #10B981, #059669); }
    .report-icon.yearly { background: linear-gradient(135deg, #8B5CF6, #7C3AED); }
    .report-icon.custom { background: linear-gradient(135deg, #F59E0B, #D97706); }
    
    .report-title {
        font-weight: 700;
        color: #1F2937;
    }
    
    .report-date {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .type-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .type-badge.monthly { background: #DBEAFE; color: #2563EB; }
    .type-badge.quarterly { background: #D1FAE5; color: #059669; }
    .type-badge.yearly { background: #E0E7FF; color: #4338CA; }
    .type-badge.custom { background: #FEF3C7; color: #D97706; }
    
    .summary-cell {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .summary-cell .income { color: #059669; font-weight: 600; }
    .summary-cell .expense { color: #DC2626; font-weight: 600; }
    .summary-cell .profit { color: #1F2937; font-weight: 700; }
    
    .action-btns {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    
    .btn-view { background: #EFF6FF; color: #2563EB; }
    .btn-delete { background: #FEE2E2; color: #DC2626; }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6B7280;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #D1D5DB;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 1024px) {
        .reports-header {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .generator-form {
            flex-direction: column;
        }
        
        .form-group {
            width: 100%;
        }
        
        .btn-generate {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- مولدات التقارير -->
<div class="reports-header">
    <!-- تقرير شهري -->
    <div class="report-generator">
        <h3 class="generator-title">
            <i class="fas fa-calendar-alt"></i>
            إنشاء تقرير شهري
        </h3>
        <form action="{{ route('admin.finance.reports.monthly') }}" method="POST" class="generator-form">
            @csrf
            <div class="form-group">
                <label>السنة</label>
                <select name="year">
                    @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label>الشهر</label>
                <select name="month">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                    </option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn-generate">
                <i class="fas fa-file-alt"></i>
                إنشاء التقرير
            </button>
        </form>
    </div>
    
    <!-- تقرير مخصص -->
    <div class="report-generator">
        <h3 class="generator-title">
            <i class="fas fa-sliders-h"></i>
            إنشاء تقرير مخصص
        </h3>
        <form action="{{ route('admin.finance.reports.custom') }}" method="POST" class="generator-form">
            @csrf
            <div class="form-group">
                <label>من تاريخ</label>
                <input type="date" name="start_date" required>
            </div>
            <div class="form-group">
                <label>إلى تاريخ</label>
                <input type="date" name="end_date" required>
            </div>
            <div class="form-group">
                <label>عنوان التقرير (اختياري)</label>
                <input type="text" name="title" placeholder="عنوان مخصص">
            </div>
            <button type="submit" class="btn-generate">
                <i class="fas fa-file-alt"></i>
                إنشاء التقرير
            </button>
        </form>
    </div>
</div>

@if(session('success'))
<div style="background: #D1FAE5; border: 1px solid #6b8980; color: #536b63; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- قائمة التقارير -->
<div class="reports-list">
    <div class="list-header">
        <h3 class="list-title"><i class="fas fa-file-alt"></i> التقارير المحفوظة</h3>
    </div>
    
    <div style="overflow-x: auto;">
        @if($reports->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>التقرير</th>
                    <th>النوع</th>
                    <th>الفترة</th>
                    <th>الملخص المالي</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr>
                    <td>
                        <div class="report-info">
                            <div class="report-icon {{ $report->type }}">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <div class="report-title">{{ $report->title }}</div>
                                <div class="report-date">{{ $report->created_at->format('Y/m/d H:i') }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="type-badge {{ $report->type }}">{{ $report->type_name }}</span>
                    </td>
                    <td>
                        {{ $report->start_date->format('Y/m/d') }} - {{ $report->end_date->format('Y/m/d') }}
                    </td>
                    <td>
                        <div class="summary-cell">
                            <span class="income">+ {{ number_format($report->total_income, 2) }}</span>
                            <span class="expense">- {{ number_format($report->total_expenses, 2) }}</span>
                            <span class="profit">= {{ number_format($report->net_profit, 2) }} ج.م</span>
                        </div>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('admin.finance.report.show', $report) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.finance.reports.destroy', $report) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-file-alt"></i>
            <h3>لا توجد تقارير</h3>
            <p>قم بإنشاء تقريرك الأول من النماذج أعلاه</p>
        </div>
        @endif
    </div>
    
    @if($reports->hasPages())
    <div style="padding: 1rem; border-top: 1px solid #E5E7EB;">
        {{ $reports->links() }}
    </div>
    @endif
</div>
@endsection

