@extends('layouts.admin')

@section('title', 'لوحة تحكم الأدمن')
@section('page-title', 'لوحة التحكم')

@push('styles')
<style>
    /* Stats Grid - 4 cards per row */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
        min-height: 140px;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .stat-icon.orange {
        background: linear-gradient(135deg, #FB923C 0%, #F97316 100%);
        color: white;
    }
    
    .stat-icon.purple {
        background: linear-gradient(135deg, #A78BFA 0%, #8B5CF6 100%);
        color: white;
    }
    
    .stat-icon.green {
        background: linear-gradient(135deg, #8aa69d 0%, #6b8980 100%);
        color: white;
    }
    
    .stat-icon.blue {
        background: linear-gradient(135deg, #60A5FA 0%, #2a4456 100%);
        color: white;
    }
    
    .stat-icon.light-purple {
        background: linear-gradient(135deg, #C4B5FD 0%, #A78BFA 100%);
        color: white;
    }
    
    .stat-icon.yellow {
        background: linear-gradient(135deg, #FCD34D 0%, #FBBF24 100%);
        color: white;
    }
    
    .stat-icon.light-blue {
        background: linear-gradient(135deg, #93C5FD 0%, #60A5FA 100%);
        color: white;
    }
    
    .stat-content {
        flex: 1;
        margin-left: 1rem;
    }
    
    .stat-label {
        color: #6B7280;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #1F2937;
        margin-bottom: 0.5rem;
        line-height: 1;
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b8980;
    }
    
    /* Bottom Sections Grid */
    .bottom-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
        margin-top: 1.25rem;
    }
    
    .chart-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        min-height: 350px;
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .chart-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .chart-title i {
        color: #1d313f;
    }
    
    /* User Distribution */
    .user-dist-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        margin-bottom: 0.75rem;
        background: #F9FAFB;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .user-dist-item:hover {
        background: #F3F4F6;
        transform: translateX(-5px);
    }
    
    .user-dist-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .user-dist-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    
    .user-dist-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.9rem;
    }
    
    .user-dist-value {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .user-dist-count {
        font-weight: 700;
        color: #1F2937;
        font-size: 1rem;
    }
    
    .user-dist-percent {
        font-weight: 600;
        color: #6B7280;
        font-size: 0.875rem;
    }
    
    /* Activity Tabs */
    .activity-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .activity-tab {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid #E5E7EB;
        background: white;
        color: #6B7280;
    }
    
    .activity-tab.active {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        border-color: transparent;
    }
    
    .activity-tab:hover:not(.active) {
        background: #F3F4F6;
    }
    
    .activity-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 200px;
        color: #9CA3AF;
    }
    
    .activity-empty i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .activity-empty p {
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    /* Recent Properties Section */
    .recent-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-top: 1.25rem;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
    }
    
    .table thead {
        background: #F9FAFB;
    }
    
    .table th {
        text-align: right;
        padding: 0.875rem 1rem;
        color: #374151;
        font-weight: 700;
        font-size: 0.8rem;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .table td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #F3F4F6;
        color: #4B5563;
        font-weight: 500;
    }
    
    .table tbody tr:hover {
        background: #F9FAFB;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .badge-approved {
        background: #D1FAE5;
        color: #536b63;
    }
    
    .badge-rejected {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .badge-residential {
        background: #DBEAFE;
        color: #1d313f;
    }
    
    .badge-commercial {
        background: #EDE9FE;
        color: #7C3AED;
    }
    
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-view {
        background: #DBEAFE;
        color: #1d313f;
    }
    
    .btn-view:hover {
        background: #BFDBFE;
    }
    
    /* Responsive */
    @media (max-width: 1400px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .bottom-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .stat-card {
            min-height: 130px;
            padding: 1.25rem;
        }
        
        .stat-value {
            font-size: 1.75rem;
        }
        
        .stat-icon {
            width: 45px;
            height: 45px;
            font-size: 1.25rem;
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .stat-card {
            min-height: 110px;
            padding: 1rem;
        }
        
        .stat-header {
            gap: 0.75rem;
        }
        
        .stat-content {
            margin-left: 0;
            flex: 1;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
        }
        
        .stat-trend {
            font-size: 0.7rem;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .bottom-grid {
            grid-template-columns: 1fr;
            gap: 0.875rem;
            margin-top: 1rem;
        }
        
        .chart-section {
            min-height: 280px;
            padding: 1rem;
        }
        
        .chart-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .chart-title {
            font-size: 1rem;
        }
        
        .activity-tabs {
            width: 100%;
            justify-content: space-between;
        }
        
        .activity-tab {
            flex: 1;
            text-align: center;
        }
        
        .user-dist-item {
            padding: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .user-dist-label {
            font-size: 0.85rem;
        }
        
        .user-dist-count {
            font-size: 0.9rem;
        }
        
        .user-dist-percent {
            font-size: 0.8rem;
        }
        
        .recent-section {
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .section-header {
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
        }
        
        .section-title {
            font-size: 1rem;
        }
        
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            font-size: 0.75rem;
            min-width: 600px;
        }
        
        .table th,
        .table td {
            padding: 0.625rem 0.5rem;
            white-space: nowrap;
        }
        
        .badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
        }
        
        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.7rem;
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            gap: 0.75rem;
        }
        
        .stat-card {
            min-height: 100px;
            padding: 0.875rem;
        }
        
        .stat-value {
            font-size: 1.375rem;
        }
        
        .stat-label {
            font-size: 0.75rem;
            margin-bottom: 0.375rem;
        }
        
        .stat-trend {
            font-size: 0.65rem;
            margin-top: 0.5rem;
        }
        
        .stat-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
        
        .chart-section {
            min-height: 250px;
            padding: 0.875rem;
        }
        
        .chart-title {
            font-size: 0.9rem;
        }
        
        .user-dist-item {
            padding: 0.75rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .user-dist-info {
            flex: 1;
            min-width: 120px;
        }
        
        .user-dist-value {
            flex-shrink: 0;
        }
        
        .recent-section {
            padding: 0.875rem;
        }
        
        .section-title {
            font-size: 0.9rem;
        }
        
        .table {
            font-size: 0.7rem;
            min-width: 500px;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.375rem;
        }
        
        .property-name {
            font-size: 0.8rem;
        }
        
        .property-price {
            font-size: 0.7rem;
        }
    }
</style>
@endpush

@section('content')
<!-- First Row Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">إجمالي الوحدات</div>
                <div class="stat-value">{{ $totalProperties }}</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>جميع الوحدات</span>
                </div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-building"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">وحدات معتمدة</div>
                <div class="stat-value">{{ $approvedProperties }}</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>جاهزة للعرض</span>
                </div>
            </div>
            <div class="stat-icon purple">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">قيد المراجعة</div>
                <div class="stat-value">{{ $pendingProperties }}</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>تحتاج مراجعة</span>
                </div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">إجمالي المستخدمين</div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>مستخدم نشط</span>
                </div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
</div>

<!-- Second Row Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">المؤجرون</div>
                <div class="stat-value">{{ \App\Models\User::where('role', 'owner')->count() }}</div>
            </div>
            <div class="stat-icon light-purple">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">المستأجرون</div>
                <div class="stat-value">{{ \App\Models\User::where('role', 'tenant')->count() }}</div>
            </div>
            <div class="stat-icon yellow">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">الحجوزات</div>
                <div class="stat-value">{{ \App\Models\Booking::count() }}</div>
            </div>
            <div class="stat-icon light-blue">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">الاستفسارات</div>
                <div class="stat-value">{{ \App\Models\Inquiry::count() }}</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-comments"></i>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Sections -->
<div class="bottom-grid">
    <!-- User Distribution -->
    <div class="chart-section">
        <div class="chart-header">
            <h3 class="chart-title">
                <i class="fas fa-chart-pie"></i>
                توزيع المستخدمين
            </h3>
        </div>
        <div class="user-distribution">
            @php
                $owners = \App\Models\User::where('role', 'owner')->count();
                $tenants = \App\Models\User::where('role', 'tenant')->count();
                $admins = \App\Models\User::where('role', 'admin')->count();
                $total = $totalUsers;
                $ownersPercent = $total > 0 ? round(($owners / $total) * 100) : 0;
                $tenantsPercent = $total > 0 ? round(($tenants / $total) * 100) : 0;
                $adminsPercent = $total > 0 ? round(($admins / $total) * 100) : 0;
            @endphp
            
            <div class="user-dist-item">
                <div class="user-dist-info">
                    <div class="user-dist-dot bg-green-500"></div>
                    <span class="user-dist-label">المستأجرون</span>
                </div>
                <div class="user-dist-value">
                    <span class="user-dist-count">{{ $tenants }}</span>
                    <span class="user-dist-percent">{{ $tenantsPercent }}%</span>
                </div>
            </div>
            
            <div class="user-dist-item">
                <div class="user-dist-info">
                    <div class="user-dist-dot bg-blue-500"></div>
                    <span class="user-dist-label">المؤجرون</span>
                </div>
                <div class="user-dist-value">
                    <span class="user-dist-count">{{ $owners }}</span>
                    <span class="user-dist-percent">{{ $ownersPercent }}%</span>
                </div>
            </div>
            
            <div class="user-dist-item">
                <div class="user-dist-info">
                    <div class="user-dist-dot bg-purple-500"></div>
                    <span class="user-dist-label">المديرون</span>
                </div>
                <div class="user-dist-value">
                    <span class="user-dist-count">{{ $admins }}</span>
                    <span class="user-dist-percent">{{ $adminsPercent }}%</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Activity -->
    <div class="chart-section">
        <div class="chart-header">
            <h3 class="chart-title">
                <i class="fas fa-chart-line"></i>
                نشاط المستخدمين
            </h3>
            <div class="activity-tabs">
                <button class="activity-tab" data-tab="monthly">شهري</button>
                <button class="activity-tab active" data-tab="weekly">أسبوعي</button>
            </div>
        </div>
        <div class="activity-content">
            <div class="activity-empty">
                <i class="fas fa-chart-line"></i>
                <p>لا توجد بيانات للنشاط الأسبوعي</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Properties -->
<div class="recent-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-building"></i>
            الوحدات الأخيرة
        </h2>
    </div>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>الوحدة</th>
                    <th>المالك</th>
                    <th>النوع</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentProperties as $property)
                <tr>
                    <td>
                        <div class="font-semibold text-gray-900 text-sm">{{ Str::limit($property->address, 30) }}</div>
                        <div class="text-xs text-gray-500">
                            {{ number_format($property->price) }} 
                            {{ $property->price_type === 'monthly' ? ' /شهر' : ($property->price_type === 'yearly' ? ' /سنة' : ' /يوم') }}
                        </div>
                    </td>
                    <td>
                        <div class="font-semibold text-gray-900 text-sm">{{ $property->user->name }}</div>
                    </td>
                    <td>
                        <span class="badge {{ ($property->propertyType->slug ?? '') === 'residential' ? 'badge-residential' : 'badge-commercial' }}">
                            {{ $property->propertyType->name ?? 'غير محدد' }}
                        </span>
                    </td>
                    <td>
                        @if($property->admin_status === 'pending')
                            <span class="badge badge-pending">قيد المراجعة</span>
                        @elseif($property->admin_status === 'approved')
                            <span class="badge badge-approved">معتمد</span>
                        @else
                            <span class="badge badge-rejected">مرفوض</span>
                        @endif
                    </td>
                    <td>
                        <div class="text-sm font-semibold">{{ $property->created_at->format('Y-m-d') }}</div>
                    </td>
                    <td>
                        <a href="{{ route('admin.properties.review', $property) }}" class="btn-action btn-view">
                            <i class="fas fa-eye"></i>
                            مراجعة
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        لا توجد وحدات حالياً
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.activity-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
@endsection


