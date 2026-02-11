@extends('layouts.admin')

@section('title', 'المعاملات المالية')
@section('page-title', 'المعاملات المالية')

@push('styles')
<style>
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
    
    .transactions-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .card-header {
        padding: 1.25rem;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .btn-add-transaction {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
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
    
    .transaction-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .transaction-icon.income { background: #D1FAE5; color: #059669; }
    .transaction-icon.expense { background: #FEE2E2; color: #DC2626; }
    .transaction-icon.transfer_in { background: #DBEAFE; color: #2563EB; }
    .transaction-icon.transfer_out { background: #FEF3C7; color: #D97706; }
    .transaction-icon.refund { background: #E0E7FF; color: #4338CA; }
    .transaction-icon.adjustment { background: #F3F4F6; color: #374151; }
    
    .transaction-desc {
        font-weight: 600;
        color: #1F2937;
    }
    
    .transaction-ref {
        font-size: 0.8rem;
        color: #6B7280;
    }
    
    .amount-cell {
        font-weight: 700;
    }
    
    .amount-cell.income { color: #059669; }
    .amount-cell.expense { color: #DC2626; }
    
    .type-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .type-badge.income { background: #D1FAE5; color: #059669; }
    .type-badge.expense { background: #FEE2E2; color: #DC2626; }
    .type-badge.transfer_in { background: #DBEAFE; color: #2563EB; }
    .type-badge.transfer_out { background: #FEF3C7; color: #D97706; }
    .type-badge.refund { background: #E0E7FF; color: #4338CA; }
    .type-badge.adjustment { background: #F3F4F6; color: #374151; }
    
    .balance-change {
        display: flex;
        flex-direction: column;
        font-size: 0.85rem;
    }
    
    .balance-change .before {
        color: #6B7280;
        text-decoration: line-through;
    }
    
    .balance-change .after {
        font-weight: 600;
        color: #1F2937;
    }
    
    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6B7280;
        cursor: pointer;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.95rem;
    }
    
    .btn-submit {
        width: 100%;
        padding: 0.875rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1rem;
    }
    
    @media (max-width: 768px) {
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
        
        .card-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        
        .btn-add-transaction {
            width: 100%;
            justify-content: center;
        }
        
        table th, table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }
        
        .transaction-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .transaction-icon {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
        
        .modal-content {
            margin: 1rem;
            padding: 1.5rem;
        }
    }
    
    @media (max-width: 480px) {
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
        
        .transaction-info {
            flex-direction: row;
            align-items: center;
        }
    }
</style>
@endpush

@section('content')
<!-- الفلاتر -->
<form action="{{ route('admin.finance.transactions') }}" method="GET" class="filter-section">
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
        <label>النوع</label>
        <select name="type" onchange="this.form.submit()">
            <option value="">الكل</option>
            <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>إيراد</option>
            <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>مصروف</option>
            <option value="transfer_in" {{ request('type') == 'transfer_in' ? 'selected' : '' }}>تحويل وارد</option>
            <option value="transfer_out" {{ request('type') == 'transfer_out' ? 'selected' : '' }}>تحويل صادر</option>
            <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>استرداد</option>
            <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>تعديل</option>
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
</form>

@if(session('success'))
<div style="background: #D1FAE5; border: 1px solid #6b8980; color: #536b63; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- جدول المعاملات -->
<div class="transactions-card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-exchange-alt"></i> سجل المعاملات</h3>
        <button onclick="document.getElementById('addModal').classList.add('active')" class="btn-add-transaction">
            <i class="fas fa-plus"></i>
            إضافة معاملة
        </button>
    </div>
    
    <div style="overflow-x: auto;">
        @if($transactions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>المعاملة</th>
                    <th>المحفظة</th>
                    <th>النوع</th>
                    <th>المبلغ</th>
                    <th>الرصيد</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td data-label="المعاملة">
                        <div class="transaction-info">
                            <div class="transaction-icon {{ $transaction->type }}">
                                <i class="fas {{ $transaction->isIncome() ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                            </div>
                            <div>
                                <div class="transaction-desc">{{ $transaction->description }}</div>
                                @if($transaction->reference_type)
                                <div class="transaction-ref">{{ $transaction->reference_type }} #{{ $transaction->reference_id }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td data-label="المحفظة">{{ $transaction->wallet->display_name ?? '-' }}</td>
                    <td data-label="النوع">
                        <span class="type-badge {{ $transaction->type }}">
                            {{ $transaction->type_name }}
                        </span>
                    </td>
                    <td data-label="المبلغ" class="amount-cell {{ $transaction->isIncome() ? 'income' : 'expense' }}">
                        {{ $transaction->isIncome() ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} ج.م
                    </td>
                    <td data-label="الرصيد">
                        <div class="balance-change">
                            <span class="before">{{ number_format($transaction->balance_before ?? 0, 2) }}</span>
                            <span class="after">{{ number_format($transaction->balance_after ?? 0, 2) }}</span>
                        </div>
                    </td>
                    <td data-label="التاريخ">{{ $transaction->created_at->format('Y/m/d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align: center; padding: 3rem; color: #6B7280;">
            <i class="fas fa-inbox" style="font-size: 3rem; color: #D1D5DB; margin-bottom: 1rem;"></i>
            <h3>لا توجد معاملات</h3>
        </div>
        @endif
    </div>
    
    @if($transactions->hasPages())
    <div style="padding: 1rem; border-top: 1px solid #E5E7EB;">
        {{ $transactions->withQueryString()->links() }}
    </div>
    @endif
</div>

<!-- Modal إضافة معاملة -->
<div class="modal-overlay" id="addModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">إضافة معاملة يدوية</h3>
            <button class="modal-close" onclick="document.getElementById('addModal').classList.remove('active')">&times;</button>
        </div>
        <form action="{{ route('admin.finance.transactions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>المحفظة</label>
                <select name="wallet_id" required>
                    <option value="">اختر المحفظة</option>
                    @foreach($wallets as $wallet)
                    <option value="{{ $wallet->id }}">{{ $wallet->display_name }} ({{ number_format($wallet->balance, 2) }} ج.م)</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>نوع المعاملة</label>
                <select name="type" required>
                    <option value="income">إيراد (إضافة للرصيد)</option>
                    <option value="expense">مصروف (خصم من الرصيد)</option>
                    <option value="adjustment">تعديل رصيد</option>
                </select>
            </div>
            <div class="form-group">
                <label>المبلغ</label>
                <input type="number" name="amount" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label>الوصف</label>
                <input type="text" name="description" required placeholder="سبب المعاملة">
            </div>
            <div class="form-group">
                <label>ملاحظات (اختياري)</label>
                <textarea name="notes" rows="2"></textarea>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> حفظ المعاملة
            </button>
        </form>
    </div>
</div>
@endsection

