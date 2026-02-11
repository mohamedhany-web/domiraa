@extends('layouts.admin')

@section('title', 'تفاصيل المحفظة')
@section('page-title', 'تفاصيل المحفظة')

@push('styles')
<style>
    .wallet-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .wallet-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .wallet-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .wallet-icon.bank {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        color: white;
    }
    
    .wallet-icon.stc {
        background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        color: white;
    }
    
    .wallet-name h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1F2937;
        margin: 0 0 0.25rem 0;
    }
    
    .wallet-name p {
        color: #6B7280;
        margin: 0;
    }
    
    .header-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-back {
        background: #F3F4F6;
        color: #374151;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
    }
    
    .btn-back:hover {
        background: #E5E7EB;
    }
    
    .btn-toggle {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-toggle.activate {
        background: #D1FAE5;
        color: #059669;
    }
    
    .btn-toggle.deactivate {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
    }
    
    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .card-title i {
        color: var(--primary);
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        color: #6B7280;
    }
    
    .info-value {
        font-weight: 600;
        color: #1F2937;
    }
    
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .status-badge.active {
        background: #D1FAE5;
        color: #059669;
    }
    
    .status-badge.inactive {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    /* Payments Table */
    .payments-section {
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .section-header {
        padding: 1.25rem;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .payments-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .payments-table th,
    .payments-table td {
        padding: 1rem;
        text-align: right;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .payments-table th {
        background: #F9FAFB;
        font-weight: 600;
        color: #374151;
        font-size: 0.85rem;
    }
    
    .empty-payments {
        text-align: center;
        padding: 2rem;
        color: #6B7280;
    }
    
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .wallet-header {
            flex-direction: column;
            text-align: center;
        }
        
        .wallet-title {
            flex-direction: column;
        }
        
        .header-actions {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="wallet-header">
    <div class="wallet-title">
        <div class="wallet-icon {{ $wallet->type == 'bank' ? 'bank' : 'stc' }}">
            <i class="fas {{ $wallet->type == 'bank' ? 'fa-university' : 'fa-mobile-alt' }}"></i>
        </div>
        <div class="wallet-name">
            <h2>{{ $wallet->name ?? ($wallet->type == 'bank' ? 'حساب بنكي' : 'STC Pay') }}</h2>
            <p>{{ $wallet->bank_name ?? 'STC Pay' }}</p>
        </div>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.wallets') }}" class="btn-back">
            <i class="fas fa-arrow-right"></i>
            العودة
        </a>
        <form action="{{ route('admin.wallets.toggle', $wallet) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-toggle {{ $wallet->is_active ? 'deactivate' : 'activate' }}">
                <i class="fas {{ $wallet->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                {{ $wallet->is_active ? 'تعطيل' : 'تفعيل' }}
            </button>
        </form>
    </div>
</div>

@if(session('success'))
<div style="background: #D1FAE5; border: 1px solid #6b8980; color: #536b63; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="info-grid">
    <!-- Wallet Details -->
    <div class="info-card">
        <h3 class="card-title">
            <i class="fas fa-wallet"></i>
            تفاصيل المحفظة
        </h3>
        
        <div class="info-row">
            <span class="info-label">النوع</span>
            <span class="info-value">{{ $wallet->type == 'bank' ? 'حساب بنكي' : 'STC Pay' }}</span>
        </div>
        
        @if($wallet->type == 'bank')
        <div class="info-row">
            <span class="info-label">اسم البنك</span>
            <span class="info-value">{{ $wallet->bank_name ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">رقم الحساب</span>
            <span class="info-value" dir="ltr">{{ $wallet->account_number ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">IBAN</span>
            <span class="info-value" dir="ltr">{{ $wallet->iban ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">اسم صاحب الحساب</span>
            <span class="info-value">{{ $wallet->account_name ?? '-' }}</span>
        </div>
        @else
        <div class="info-row">
            <span class="info-label">رقم الهاتف</span>
            <span class="info-value" dir="ltr">{{ $wallet->phone_number ?? '-' }}</span>
        </div>
        @endif
        
        <div class="info-row">
            <span class="info-label">الحالة</span>
            <span class="status-badge {{ $wallet->is_active ? 'active' : 'inactive' }}">
                {{ $wallet->is_active ? 'نشط' : 'غير نشط' }}
            </span>
        </div>
        
        @if($wallet->notes)
        <div class="info-row">
            <span class="info-label">ملاحظات</span>
            <span class="info-value">{{ $wallet->notes }}</span>
        </div>
        @endif
    </div>
    
    <!-- Owner Details -->
    <div class="info-card">
        <h3 class="card-title">
            <i class="fas fa-user"></i>
            معلومات المالك
        </h3>
        
        <div class="info-row">
            <span class="info-label">الاسم</span>
            <span class="info-value">{{ $wallet->user->name ?? 'غير معروف' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">البريد الإلكتروني</span>
            <span class="info-value">{{ $wallet->user->email ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">الهاتف</span>
            <span class="info-value" dir="ltr">{{ $wallet->user->phone ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">نوع الحساب</span>
            <span class="info-value">
                @if($wallet->user->role == 'owner')
                    مؤجر
                @elseif($wallet->user->role == 'admin')
                    أدمن
                @else
                    مستأجر
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">تاريخ الإنشاء</span>
            <span class="info-value">{{ $wallet->created_at->format('Y/m/d H:i') }}</span>
        </div>
    </div>
</div>

<!-- Recent Payments -->
<div class="payments-section">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-money-bill-wave"></i>
            آخر المدفوعات
        </h3>
    </div>
    
    @if($wallet->payments && $wallet->payments->count() > 0)
    <table class="payments-table">
        <thead>
            <tr>
                <th>رقم الدفعة</th>
                <th>المبلغ</th>
                <th>الحالة</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wallet->payments as $payment)
            <tr>
                <td>#{{ $payment->id }}</td>
                <td>{{ number_format($payment->amount, 2) }} ج.م</td>
                <td>
                    <span class="status-badge {{ $payment->status == 'completed' ? 'active' : 'inactive' }}">
                        {{ $payment->status == 'completed' ? 'مكتمل' : ($payment->status == 'pending' ? 'قيد الانتظار' : 'ملغي') }}
                    </span>
                </td>
                <td>{{ $payment->created_at->format('Y/m/d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-payments">
        <i class="fas fa-receipt" style="font-size: 2rem; color: #D1D5DB; margin-bottom: 0.5rem;"></i>
        <p>لا توجد مدفوعات مرتبطة بهذه المحفظة</p>
    </div>
    @endif
</div>
@endsection

