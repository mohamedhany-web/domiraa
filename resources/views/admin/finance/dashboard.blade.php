@extends('layouts.admin')

@section('title', 'لوحة التحكم المالية')
@section('page-title', 'لوحة التحكم المالية')

@push('styles')
<style>
    .finance-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        opacity: 0.1;
        transform: translate(30%, -30%);
    }
    
    .stat-card.balance::before { background: #3B82F6; }
    .stat-card.income::before { background: #10B981; }
    .stat-card.expenses::before { background: #EF4444; }
    .stat-card.profit::before { background: #8B5CF6; }
    
    .stat-icon {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .stat-card.balance .stat-icon { background: linear-gradient(135deg, #3B82F6, #2563EB); color: white; }
    .stat-card.income .stat-icon { background: linear-gradient(135deg, #10B981, #059669); color: white; }
    .stat-card.expenses .stat-icon { background: linear-gradient(135deg, #EF4444, #DC2626); color: white; }
    .stat-card.profit .stat-icon { background: linear-gradient(135deg, #8B5CF6, #7C3AED); color: white; }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1F2937;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: #6B7280;
        font-size: 0.9rem;
    }
    
    .stat-change {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.85rem;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        margin-top: 0.5rem;
    }
    
    .stat-change.up { background: #D1FAE5; color: #059669; }
    .stat-change.down { background: #FEE2E2; color: #DC2626; }
    
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }
    
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #F3F4F6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-title i {
        color: var(--primary);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* الرسم البياني */
    .chart-container {
        height: 300px;
        position: relative;
    }
    
    /* المحافظ */
    .wallets-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .wallet-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 12px;
        transition: all 0.2s ease;
    }
    
    .wallet-item:hover {
        background: #F3F4F6;
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
    
    .wallet-icon.bank { background: linear-gradient(135deg, #3B82F6, #2563EB); color: white; }
    .wallet-icon.stc { background: linear-gradient(135deg, #8B5CF6, #7C3AED); color: white; }
    
    .wallet-info {
        flex: 1;
    }
    
    .wallet-name {
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.25rem;
    }
    
    .wallet-details {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .wallet-balance {
        font-size: 1.1rem;
        font-weight: 800;
        color: #059669;
    }
    
    /* المصروفات حسب الفئة */
    .category-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .category-item:last-child {
        border-bottom: none;
    }
    
    .category-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .category-info {
        flex: 1;
    }
    
    .category-name {
        font-weight: 600;
        color: #1F2937;
    }
    
    .category-bar {
        height: 6px;
        background: #E5E7EB;
        border-radius: 3px;
        margin-top: 0.5rem;
        overflow: hidden;
    }
    
    .category-bar-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s ease;
    }
    
    .category-amount {
        font-weight: 700;
        color: #1F2937;
    }
    
    /* المعاملات الأخيرة */
    .transaction-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .transaction-item:last-child {
        border-bottom: none;
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
    .transaction-icon.transfer { background: #DBEAFE; color: #2563EB; }
    
    .transaction-info {
        flex: 1;
    }
    
    .transaction-desc {
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 0.25rem;
    }
    
    .transaction-meta {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .transaction-amount {
        font-weight: 700;
    }
    
    .transaction-amount.income { color: #059669; }
    .transaction-amount.expense { color: #DC2626; }
    
    /* مصروفات قيد الانتظار */
    .pending-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #FEF3C7;
        border-radius: 10px;
        margin-bottom: 0.75rem;
    }
    
    .pending-item:last-child {
        margin-bottom: 0;
    }
    
    .pending-info {
        flex: 1;
    }
    
    .pending-title {
        font-weight: 600;
        color: #92400E;
    }
    
    .pending-amount {
        font-weight: 700;
        color: #B45309;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .btn-approve {
        background: #D1FAE5;
        color: #059669;
    }
    
    .btn-approve:hover {
        background: #A7F3D0;
    }
    
    @media (max-width: 1200px) {
        .finance-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .finance-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- الإحصائيات الرئيسية -->
<div class="finance-stats">
    <div class="stat-card balance">
        <div class="stat-icon">
            <i class="fas fa-wallet"></i>
        </div>
        <div class="stat-value">{{ number_format($totalBalance, 2) }} <small style="font-size: 0.6em;">ج.م</small></div>
        <div class="stat-label">إجمالي الرصيد</div>
    </div>
    
    <div class="stat-card income">
        <div class="stat-icon">
            <i class="fas fa-arrow-down"></i>
        </div>
        <div class="stat-value">{{ number_format($monthlyIncome, 2) }} <small style="font-size: 0.6em;">ج.م</small></div>
        <div class="stat-label">إيرادات الشهر</div>
    </div>
    
    <div class="stat-card expenses">
        <div class="stat-icon">
            <i class="fas fa-arrow-up"></i>
        </div>
        <div class="stat-value">{{ number_format($monthlyExpenses, 2) }} <small style="font-size: 0.6em;">ج.م</small></div>
        <div class="stat-label">مصروفات الشهر</div>
    </div>
    
    <div class="stat-card profit">
        <div class="stat-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-value">{{ number_format($monthlyIncome - $monthlyExpenses, 2) }} <small style="font-size: 0.6em;">ج.م</small></div>
        <div class="stat-label">صافي الربح</div>
    </div>
</div>

<div class="content-grid">
    <!-- الجانب الأيسر -->
    <div>
        <!-- الرسم البياني -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-area"></i>
                    الأداء المالي (آخر 12 شهر)
                </h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- آخر المعاملات -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exchange-alt"></i>
                    آخر المعاملات
                </h3>
                <a href="{{ route('admin.finance.transactions') }}" class="btn-sm" style="background: #F3F4F6; color: #374151;">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                @forelse($recentTransactions as $transaction)
                <div class="transaction-item">
                    <div class="transaction-icon {{ $transaction->isIncome() ? 'income' : 'expense' }}">
                        <i class="fas {{ $transaction->isIncome() ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                    </div>
                    <div class="transaction-info">
                        <div class="transaction-desc">{{ $transaction->description }}</div>
                        <div class="transaction-meta">
                            {{ $transaction->wallet->display_name }} • {{ $transaction->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="transaction-amount {{ $transaction->isIncome() ? 'income' : 'expense' }}">
                        {{ $transaction->isIncome() ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} ج.م
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 2rem; color: #6B7280;">
                    <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <p>لا توجد معاملات</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- الجانب الأيمن -->
    <div>
        <!-- المحافظ -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-wallet"></i>
                    المحافظ
                </h3>
                <a href="{{ route('admin.wallets') }}" class="btn-sm" style="background: #F3F4F6; color: #374151;">
                    إدارة
                </a>
            </div>
            <div class="card-body">
                <div class="wallets-list">
                    @forelse($wallets as $wallet)
                    <div class="wallet-item">
                        <div class="wallet-icon {{ $wallet->type == 'bank' ? 'bank' : 'stc' }}">
                            <i class="fas {{ $wallet->type == 'bank' ? 'fa-university' : 'fa-mobile-alt' }}"></i>
                        </div>
                        <div class="wallet-info">
                            <div class="wallet-name">{{ $wallet->display_name }}</div>
                            <div class="wallet-details">
                                <span>{{ $wallet->type_name }}</span>
                            </div>
                        </div>
                        <div class="wallet-balance">{{ number_format($wallet->balance, 2) }}</div>
                    </div>
                    @empty
                    <p style="text-align: center; color: #6B7280;">لا توجد محافظ</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- المصروفات حسب الفئة -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie"></i>
                    المصروفات حسب الفئة
                </h3>
            </div>
            <div class="card-body">
                @php
                    $maxExpense = $expensesByCategory->max('expenses_sum_amount') ?: 1;
                @endphp
                @forelse($expensesByCategory->where('expenses_sum_amount', '>', 0) as $category)
                <div class="category-item">
                    <div class="category-icon" style="background: {{ $category->color ?? '#6B7280' }};">
                        <i class="fas {{ $category->icon ?? 'fa-tag' }}"></i>
                    </div>
                    <div class="category-info">
                        <div class="category-name">{{ $category->name }}</div>
                        <div class="category-bar">
                            <div class="category-bar-fill" style="width: {{ ($category->expenses_sum_amount / $maxExpense) * 100 }}%; background: {{ $category->color ?? '#6B7280' }};"></div>
                        </div>
                    </div>
                    <div class="category-amount">{{ number_format($category->expenses_sum_amount, 2) }}</div>
                </div>
                @empty
                <p style="text-align: center; color: #6B7280; padding: 1rem;">لا توجد مصروفات هذا الشهر</p>
                @endforelse
            </div>
        </div>
        
        <!-- مصروفات قيد الانتظار -->
        @if($pendingExpenses->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock"></i>
                    قيد الانتظار
                </h3>
                <span style="background: #FEF3C7; color: #B45309; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                    {{ $pendingExpenses->count() }}
                </span>
            </div>
            <div class="card-body">
                @foreach($pendingExpenses as $expense)
                <div class="pending-item">
                    <div class="pending-info">
                        <div class="pending-title">{{ $expense->title }}</div>
                        <div style="font-size: 0.85rem; color: #92400E;">{{ $expense->category->name ?? '-' }}</div>
                    </div>
                    <div class="pending-amount">{{ number_format($expense->amount, 2) }}</div>
                    <form action="{{ route('admin.expenses.approve', $expense) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-sm btn-approve">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('financeChart').getContext('2d');
    const chartData = @json($chartData);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.month),
            datasets: [
                {
                    label: 'الإيرادات',
                    data: chartData.map(d => d.income),
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'المصروفات',
                    data: chartData.map(d => d.expenses),
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    rtl: true,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' ج.م';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection

