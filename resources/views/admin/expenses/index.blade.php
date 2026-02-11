@extends('layouts.admin')

@section('title', 'إدارة المصروفات')
@section('page-title', 'إدارة المصروفات')

@push('styles')
<style>
    .stats-row {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-mini {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .stat-mini-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1F2937;
    }
    
    .stat-mini-label {
        font-size: 0.8rem;
        color: #6B7280;
    }
    
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .filter-group label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6B7280;
    }
    
    .filter-group input,
    .filter-group select {
        padding: 0.5rem 0.75rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .expenses-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .table-header {
        padding: 1.25rem;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-add {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
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
    
    .expense-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .expense-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .expense-title {
        font-weight: 600;
        color: #1F2937;
    }
    
    .expense-code {
        font-size: 0.8rem;
        color: #6B7280;
    }
    
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-pending { background: #FEF3C7; color: #B45309; }
    .status-approved { background: #DBEAFE; color: #2563EB; }
    .status-rejected { background: #FEE2E2; color: #DC2626; }
    .status-paid { background: #D1FAE5; color: #059669; }
    
    .action-btns {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-view { background: #EFF6FF; color: #2563EB; }
    .btn-approve { background: #D1FAE5; color: #059669; }
    .btn-pay { background: #DBEAFE; color: #2563EB; }
    .btn-reject { background: #FEE2E2; color: #DC2626; }
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
    
    @media (max-width: 1200px) {
        .stats-row {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .filter-section {
            flex-direction: column;
        }
        
        .filter-group {
            width: 100%;
        }
        
        .filter-group input,
        .filter-group select {
            width: 100%;
        }
        
        .table-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        
        .table-header > div {
            width: 100%;
            flex-direction: column;
        }
        
        .btn-add, .btn-action.btn-view {
            width: 100%;
            justify-content: center;
        }
        
        table th, table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }
        
        .expense-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .expense-icon {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
        
        .action-btns {
            flex-direction: row;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 480px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
        
        .stat-mini {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
        }
        
        .stat-mini-value {
            font-size: 1.25rem;
        }
        
        /* تحويل الجدول لبطاقات على الموبايل */
        table, thead, tbody, th, td, tr {
            display: block;
        }
        
        thead {
            display: none;
        }
        
        tr {
            background: white;
            margin-bottom: 0.75rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 1rem;
        }
        
        td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #E5E7EB;
        }
        
        td:last-child {
            border-bottom: none;
        }
        
        td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #6B7280;
            font-size: 0.8rem;
        }
        
        .expense-info {
            flex-direction: row;
            align-items: center;
        }
        
        .action-btns {
            justify-content: flex-end;
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<!-- الإحصائيات -->
<div class="stats-row">
    <div class="stat-mini">
        <div class="stat-mini-value">{{ $stats['total'] }}</div>
        <div class="stat-mini-label">إجمالي المصروفات</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-value" style="color: #B45309;">{{ $stats['pending'] }}</div>
        <div class="stat-mini-label">قيد الانتظار</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-value" style="color: #2563EB;">{{ $stats['approved'] }}</div>
        <div class="stat-mini-label">معتمد</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-value" style="color: #059669;">{{ $stats['paid'] }}</div>
        <div class="stat-mini-label">مدفوع</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-value">{{ number_format($stats['total_amount'], 0) }}</div>
        <div class="stat-mini-label">إجمالي المبالغ (ج.م)</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-value">{{ number_format($stats['this_month'], 0) }}</div>
        <div class="stat-mini-label">هذا الشهر (ج.م)</div>
    </div>
</div>

<!-- الفلاتر -->
<form action="{{ route('admin.expenses.index') }}" method="GET" class="filter-section">
    <div class="filter-group">
        <label>الحالة</label>
        <select name="status" onchange="this.form.submit()">
            <option value="">الكل</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمد</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
        </select>
    </div>
    <div class="filter-group">
        <label>الفئة</label>
        <select name="category" onchange="this.form.submit()">
            <option value="">الكل</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label>المحفظة</label>
        <select name="wallet" onchange="this.form.submit()">
            <option value="">الكل</option>
            @foreach($wallets as $wallet)
            <option value="{{ $wallet->id }}" {{ request('wallet') == $wallet->id ? 'selected' : '' }}>{{ $wallet->display_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label>من تاريخ</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()">
    </div>
    <div class="filter-group">
        <label>إلى تاريخ</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()">
    </div>
    <div class="filter-group">
        <label>بحث</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث..." style="width: 150px;">
    </div>
    <button type="submit" class="btn-action btn-view" style="align-self: flex-end;">
        <i class="fas fa-search"></i>
    </button>
</form>

@if(session('success'))
<div style="background: #D1FAE5; border: 1px solid #6b8980; color: #536b63; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background: #FEE2E2; border: 1px solid #DC2626; color: #DC2626; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<!-- جدول المصروفات -->
<div class="expenses-table">
    <div class="table-header">
        <h3 class="table-title">
            <i class="fas fa-file-invoice-dollar"></i>
            قائمة المصروفات
        </h3>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.expenses.categories') }}" class="btn-action btn-view">
                <i class="fas fa-tags"></i> الفئات
            </a>
            <a href="{{ route('admin.expenses.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> إضافة مصروف
            </a>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        @if($expenses->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>المصروف</th>
                    <th>المحفظة</th>
                    <th>المبلغ</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr>
                    <td data-label="المصروف">
                        <div class="expense-info">
                            <div class="expense-icon" style="background: {{ $expense->category->color ?? '#6B7280' }};">
                                <i class="fas {{ $expense->category->icon ?? 'fa-receipt' }}"></i>
                            </div>
                            <div>
                                <div class="expense-title">{{ $expense->title }}</div>
                                <div class="expense-code">{{ $expense->code }} • {{ $expense->category->name ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td data-label="المحفظة">{{ $expense->wallet->display_name ?? '-' }}</td>
                    <td data-label="المبلغ" style="font-weight: 700;">{{ number_format($expense->amount, 2) }} ج.م</td>
                    <td data-label="التاريخ">{{ $expense->expense_date->format('Y/m/d') }}</td>
                    <td data-label="الحالة">
                        <span class="status-badge status-{{ $expense->status }}">
                            {{ $expense->status_name }}
                        </span>
                    </td>
                    <td data-label="الإجراءات">
                        <div class="action-btns">
                            <a href="{{ route('admin.expenses.show', $expense) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @if($expense->isPending())
                            <form action="{{ route('admin.expenses.approve', $expense) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-approve" title="اعتماد">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            
                            @if($expense->isApproved())
                            <form action="{{ route('admin.expenses.pay', $expense) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-pay" title="دفع">
                                    <i class="fas fa-money-bill"></i>
                                </button>
                            </form>
                            @endif
                            
                            @if(!$expense->isPaid())
                            <form action="{{ route('admin.expenses.destroy', $expense) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-file-invoice-dollar"></i>
            <h3>لا توجد مصروفات</h3>
            <p>لم يتم تسجيل أي مصروفات بعد</p>
        </div>
        @endif
    </div>
    
    @if($expenses->hasPages())
    <div style="padding: 1rem; border-top: 1px solid #E5E7EB;">
        {{ $expenses->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

