@extends('layouts.admin')

@section('title', 'إدارة المحافظ')
@section('page-title', 'إدارة المحافظ')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.25rem;
    }
    
    .stat-icon.blue { background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); color: white; }
    .stat-icon.green { background: linear-gradient(135deg, #8aa69d 0%, #6b8980 100%); color: white; }
    .stat-icon.purple { background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); color: white; }
    .stat-icon.orange { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white; }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1F2937;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .filter-bar {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filter-bar select {
        padding: 0.625rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .wallets-section {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    .section-header {
        padding: 1.25rem;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: var(--primary);
    }
    
    .wallets-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .wallets-table th,
    .wallets-table td {
        padding: 1rem;
        text-align: right;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .wallets-table th {
        background: #F9FAFB;
        font-weight: 700;
        color: #374151;
        font-size: 0.85rem;
    }
    
    .wallets-table tr:hover {
        background: #F9FAFB;
    }
    
    .wallet-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .wallet-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .wallet-icon.bank {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        color: white;
    }
    
    .wallet-icon.cash {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
    }
    
    .wallet-details h4 {
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 0.25rem 0;
    }
    
    .wallet-details p {
        color: #6B7280;
        font-size: 0.85rem;
        margin: 0;
    }
    
    .user-info {
        display: flex;
        flex-direction: column;
    }
    
    .user-name {
        font-weight: 600;
        color: #1F2937;
    }
    
    .user-role {
        font-size: 0.8rem;
        color: #6B7280;
    }
    
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
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
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-view {
        background: #EFF6FF;
        color: #2563EB;
    }
    
    .btn-view:hover {
        background: #DBEAFE;
    }
    
    .btn-toggle {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .btn-toggle:hover {
        background: #FDE68A;
    }
    
    .btn-delete {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .btn-delete:hover {
        background: #FECACA;
    }
    
    .btn-add {
        background: linear-gradient(135deg, #1d313f 0%, #2a4a5e 100%);
        color: white;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    
    .btn-add:hover {
        background: linear-gradient(135deg, #2a4a5e 0%, #1d313f 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    .btn-edit {
        background: #E0E7FF;
        color: #4338CA;
    }
    
    .btn-edit:hover {
        background: #C7D2FE;
    }
    
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
    
    .pagination-wrapper {
        padding: 1rem;
        border-top: 1px solid #E5E7EB;
    }
    
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-bar {
            flex-direction: column;
        }
        
        .filter-bar select {
            width: 100%;
        }
        
        .wallets-table {
            font-size: 0.85rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-wallet"></i>
        </div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">إجمالي المحافظ</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-university"></i>
        </div>
        <div class="stat-value">{{ $stats['bank'] }}</div>
        <div class="stat-label">حسابات بنكية</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-mobile-alt"></i>
        </div>
        <div class="stat-value">{{ $stats['mobile'] ?? 0 }}</div>
        <div class="stat-label">محافظ نقدية</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value">{{ $stats['active'] }}</div>
        <div class="stat-label">محافظ نشطة</div>
    </div>
</div>

<!-- Filter -->
<form action="{{ route('admin.wallets') }}" method="GET" class="filter-bar" id="filterForm">
    <select name="type" onchange="this.form.submit()">
        <option value="">جميع الأنواع</option>
        <option value="bank" {{ request('type') == 'bank' ? 'selected' : '' }}>حساب بنكي</option>
        <option value="mobile_wallet" {{ request('type') == 'mobile_wallet' ? 'selected' : '' }}>محفظة نقدية</option>
    </select>
    
    <select name="status" onchange="this.form.submit()">
        <option value="">جميع الحالات</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
    </select>
</form>

@if(session('success'))
<div style="background: #D1FAE5; border: 1px solid #6b8980; color: #536b63; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<!-- Wallets Table -->
<div class="wallets-section">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h2 class="section-title">
            <i class="fas fa-wallet"></i>
            قائمة المحافظ
        </h2>
        <a href="{{ route('admin.wallets.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            إضافة محفظة
        </a>
    </div>
    
    <div style="overflow-x: auto;">
        @if($wallets->count() > 0)
        <table class="wallets-table">
            <thead>
                <tr>
                    <th>المحفظة</th>
                    <th>المالك</th>
                    <th>رقم الحساب / الهاتف</th>
                    <th>الحالة</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wallets as $wallet)
                <tr>
                    <td>
                        <div class="wallet-info">
                            <div class="wallet-icon {{ $wallet->type == 'bank' ? 'bank' : 'cash' }}">
                                <i class="fas {{ $wallet->type == 'bank' ? 'fa-university' : 'fa-mobile-alt' }}"></i>
                            </div>
                            <div class="wallet-details">
                                <h4>{{ $wallet->name ?? ($wallet->type == 'bank' ? 'حساب بنكي' : 'محفظة نقدية') }}</h4>
                                <p>{{ $wallet->type == 'bank' ? ($wallet->bank_name ?? '-') : ($wallet->name ?? '-') }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="user-info">
                            <span class="user-name">{{ $wallet->user->name ?? 'غير معروف' }}</span>
                            <span class="user-role">{{ $wallet->user->role == 'owner' ? 'مؤجر' : ($wallet->user->role == 'admin' ? 'أدمن' : 'مستأجر') }}</span>
                        </div>
                    </td>
                    <td dir="ltr" style="text-align: right;">
                        @if($wallet->type == 'bank')
                            {{ $wallet->account_number ?? $wallet->iban ?? '-' }}
                        @else
                            {{ $wallet->phone_number ?? '-' }}
                        @endif
                    </td>
                    <td>
                        <span class="status-badge {{ $wallet->is_active ? 'active' : 'inactive' }}">
                            {{ $wallet->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td>{{ $wallet->created_at->format('Y/m/d') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.wallets.show', $wallet) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.wallets.edit', $wallet) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.wallets.toggle', $wallet) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-toggle" title="{{ $wallet->is_active ? 'تعطيل' : 'تفعيل' }}">
                                    <i class="fas {{ $wallet->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.wallets.destroy', $wallet) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه المحفظة؟')">
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
            <i class="fas fa-wallet"></i>
            <h3>لا توجد محافظ</h3>
            <p>لم يتم إضافة أي محافظ بعد</p>
        </div>
        @endif
    </div>
    
    @if($wallets->hasPages())
    <div class="pagination-wrapper">
        {{ $wallets->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

