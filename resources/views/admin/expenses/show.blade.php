@extends('layouts.admin')

@section('title', 'تفاصيل المصروف')
@section('page-title', 'تفاصيل المصروف')

@push('styles')
<style>
    .expense-detail-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }
    
    .detail-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .expense-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .expense-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
    }
    
    .expense-info h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
    }
    
    .expense-info p {
        color: #6B7280;
        margin: 0;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-pending { background: #FEF3C7; color: #B45309; }
    .status-approved { background: #DBEAFE; color: #2563EB; }
    .status-rejected { background: #FEE2E2; color: #DC2626; }
    .status-paid { background: #D1FAE5; color: #059669; }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }
    
    .info-item {
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 12px;
    }
    
    .info-item.full {
        grid-column: 1 / -1;
    }
    
    .info-label {
        font-size: 0.85rem;
        color: #6B7280;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #1F2937;
    }
    
    .amount-display {
        font-size: 2rem;
        font-weight: 800;
        color: #DC2626;
        text-align: center;
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.05), rgba(220, 38, 38, 0.1));
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }
    
    .timeline {
        position: relative;
        padding-right: 1.5rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        right: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #E5E7EB;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.25rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        right: -1.3rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary);
        border: 2px solid white;
        box-shadow: 0 0 0 2px var(--primary);
    }
    
    .timeline-item.inactive::before {
        background: #D1D5DB;
        box-shadow: 0 0 0 2px #D1D5DB;
    }
    
    .timeline-time {
        font-size: 0.75rem;
        color: #6B7280;
        margin-bottom: 0.25rem;
    }
    
    .timeline-title {
        font-weight: 600;
        color: #1F2937;
    }
    
    .timeline-desc {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }
    
    .btn {
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }
    
    .btn-success {
        background: #D1FAE5;
        color: #059669;
    }
    
    .btn-warning {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .btn-danger {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .btn-secondary {
        background: #F3F4F6;
        color: #374151;
    }
    
    .receipt-preview {
        text-align: center;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 12px;
    }
    
    .receipt-preview img {
        max-width: 100%;
        border-radius: 8px;
    }
    
    .receipt-preview a {
        color: var(--primary);
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .expense-detail-container {
            grid-template-columns: 1fr;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="expense-detail-container">
    <div class="main-content">
        <div class="detail-card">
            <div class="expense-header">
                <div class="expense-icon" style="background: {{ $expense->category->color ?? '#6B7280' }};">
                    <i class="fas {{ $expense->category->icon ?? 'fa-receipt' }}"></i>
                </div>
                <div class="expense-info">
                    <h2>{{ $expense->title }}</h2>
                    <p>{{ $expense->category->name ?? '-' }} • {{ $expense->code }}</p>
                </div>
                <span class="status-badge status-{{ $expense->status }}">
                    {{ $expense->status_name }}
                </span>
            </div>
            
            <div class="amount-display">
                {{ number_format($expense->amount, 2) }} ج.م
            </div>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">المحفظة</div>
                    <div class="info-value">{{ $expense->wallet->display_name ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الفئة</div>
                    <div class="info-value">{{ $expense->category->name ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">تاريخ المصروف</div>
                    <div class="info-value">{{ $expense->expense_date->format('Y/m/d') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">المورد</div>
                    <div class="info-value">{{ $expense->vendor ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">رقم الفاتورة</div>
                    <div class="info-value">{{ $expense->invoice_number ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">تم الإنشاء بواسطة</div>
                    <div class="info-value">{{ $expense->creator->name ?? '-' }}</div>
                </div>
                @if($expense->description)
                <div class="info-item full">
                    <div class="info-label">الوصف</div>
                    <div class="info-value">{{ $expense->description }}</div>
                </div>
                @endif
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
                
                @if(!$expense->isPaid())
                <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    تعديل
                </a>
                @endif
                
                @if($expense->isPending())
                <form action="{{ route('admin.expenses.approve', $expense) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i>
                        اعتماد
                    </button>
                </form>
                @endif
                
                @if($expense->isApproved())
                <form action="{{ route('admin.expenses.pay', $expense) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-money-bill"></i>
                        دفع
                    </button>
                </form>
                @endif
            </div>
        </div>
        
        @if($expense->receipt_path)
        <div class="detail-card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-invoice"></i>
                    الإيصال
                </h3>
            </div>
            <div class="receipt-preview">
                @if(in_array(pathinfo($expense->receipt_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                <img src="{{ \App\Helpers\StorageHelper::url($expense->receipt_path) }}" alt="الإيصال" onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'; this.onerror=null;">
                @else
                <a href="{{ \App\Helpers\StorageHelper::url($expense->receipt_path) }}" target="_blank">
                    <i class="fas fa-file-pdf" style="font-size: 4rem; color: #DC2626;"></i>
                    <p>عرض ملف PDF</p>
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>
    
    <div class="sidebar-content">
        <div class="detail-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history"></i>
                    سجل المعاملات
                </h3>
            </div>
            
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-time">{{ $expense->created_at->format('Y/m/d H:i') }}</div>
                    <div class="timeline-title">تم إنشاء المصروف</div>
                    <div class="timeline-desc">بواسطة {{ $expense->creator->name ?? '-' }}</div>
                </div>
                
                @if($expense->approved_at)
                <div class="timeline-item">
                    <div class="timeline-time">{{ $expense->approved_at->format('Y/m/d H:i') }}</div>
                    <div class="timeline-title">تم اعتماد المصروف</div>
                    <div class="timeline-desc">بواسطة {{ $expense->approver->name ?? '-' }}</div>
                </div>
                @else
                <div class="timeline-item inactive">
                    <div class="timeline-title">في انتظار الاعتماد</div>
                </div>
                @endif
                
                @if($expense->paid_at)
                <div class="timeline-item">
                    <div class="timeline-time">{{ $expense->paid_at->format('Y/m/d H:i') }}</div>
                    <div class="timeline-title">تم الدفع</div>
                    <div class="timeline-desc">تم خصم {{ number_format($expense->amount, 2) }} ج.م من المحفظة</div>
                </div>
                @elseif($expense->isApproved())
                <div class="timeline-item inactive">
                    <div class="timeline-title">في انتظار الدفع</div>
                </div>
                @endif
                
                @if($expense->isRejected())
                <div class="timeline-item">
                    <div class="timeline-time">{{ $expense->rejected_at?->format('Y/m/d H:i') ?? '-' }}</div>
                    <div class="timeline-title" style="color: #DC2626;">تم رفض المصروف</div>
                    @if($expense->rejection_reason)
                    <div class="timeline-desc">السبب: {{ $expense->rejection_reason }}</div>
                    @endif
                </div>
                @endif
            </div>
        </div>
        
        @if($expense->transaction)
        <div class="detail-card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exchange-alt"></i>
                    المعاملة المالية
                </h3>
            </div>
            <div class="info-grid" style="grid-template-columns: 1fr;">
                <div class="info-item">
                    <div class="info-label">رقم المعاملة</div>
                    <div class="info-value">#{{ $expense->transaction->id }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الرصيد قبل</div>
                    <div class="info-value">{{ number_format($expense->transaction->balance_before ?? 0, 2) }} ج.م</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الرصيد بعد</div>
                    <div class="info-value">{{ number_format($expense->transaction->balance_after ?? 0, 2) }} ج.م</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

