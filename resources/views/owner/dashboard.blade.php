@extends('layouts.owner')

@section('title', 'لوحة التحكم - منصة دوميرا')
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
    
    .stat-icon.blue {
        background: linear-gradient(135deg, #60A5FA 0%, #2a4456 100%);
        color: white;
    }
    
    .stat-icon.green {
        background: linear-gradient(135deg, #8aa69d 0%, #6b8980 100%);
        color: white;
    }
    
    .stat-icon.orange {
        background: linear-gradient(135deg, #FB923C 0%, #F97316 100%);
        color: white;
    }
    
    .stat-icon.red {
        background: linear-gradient(135deg, #F87171 0%, #EF4444 100%);
        color: white;
    }
    
    .stat-icon.purple {
        background: linear-gradient(135deg, #A78BFA 0%, #8B5CF6 100%);
        color: white;
    }
    
    .stat-icon.pink {
        background: linear-gradient(135deg, #F472B6 0%, #EC4899 100%);
        color: white;
    }
    
    .stat-icon.teal {
        background: linear-gradient(135deg, #5EEAD4 0%, #14B8A6 100%);
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
    
    /* Alerts Section */
    .alerts-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 1.25rem;
    }
    
    .alerts-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .alerts-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alerts-title i {
        color: #F59E0B;
    }
    
    .alert-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        margin-bottom: 0.75rem;
        background: #FEF3C7;
        border-radius: 8px;
        border-right: 3px solid #F59E0B;
        transition: all 0.3s ease;
    }
    
    .alert-item:hover {
        background: #FDE68A;
        transform: translateX(-5px);
    }
    
    .alert-icon {
        width: 40px;
        height: 40px;
        background: #F59E0B;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    
    .alert-content {
        flex: 1;
    }
    
    .alert-text {
        font-weight: 700;
        color: #92400E;
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }
    
    .alert-link {
        color: #B45309;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.3s ease;
    }
    
    .alert-link:hover {
        color: #92400E;
    }
    
    /* Bottom Sections Grid */
    .bottom-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
        margin-top: 1.25rem;
    }
    
    .recent-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
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
    
    .section-title i {
        color: #1d313f;
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
    
    .badge-suspended {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .badge-rejected {
        background: #F3F4F6;
        color: #6B7280;
    }
    
    .badge-confirmed {
        background: #D1FAE5;
        color: #536b63;
    }
    
    .badge-completed {
        background: #DBEAFE;
        color: #2563EB;
    }
    
    .badge-cancelled {
        background: #FEE2E2;
        color: #DC2626;
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
    
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        color: #9CA3AF;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state p {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
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
            flex-wrap: wrap;
        }
        
        .stat-content {
            margin-left: 0;
            flex: 1;
            min-width: 0;
        }
        
        .stat-value {
            font-size: 1.5rem;
            word-break: break-word;
        }
        
        .stat-label {
            font-size: 0.8rem;
        }
        
        .stat-trend {
            font-size: 0.7rem;
            flex-wrap: wrap;
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
        
        .recent-section {
            padding: 1rem;
            margin-top: 1rem;
            overflow-x: hidden;
        }
        
        .section-header {
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            flex-wrap: wrap;
        }
        
        .section-title {
            font-size: 1rem;
        }
        
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            max-width: 100%;
        }
        
        .table {
            font-size: 0.75rem;
            min-width: 600px;
            width: 100%;
        }
        
        .table th,
        .table td {
            padding: 0.625rem 0.5rem;
            white-space: nowrap;
        }
        
        .badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            white-space: nowrap;
        }
        
        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.7rem;
            white-space: nowrap;
        }
        
        .alerts-section {
            padding: 1rem;
            overflow-x: hidden;
        }
        
        .alerts-title {
            font-size: 1rem;
        }
        
        .alert-item {
            padding: 0.875rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .alert-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        .alert-content {
            min-width: 0;
            flex: 1;
        }
        
        .alert-text {
            font-size: 0.85rem;
            word-break: break-word;
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
            word-break: break-word;
        }
        
        .stat-label {
            font-size: 0.75rem;
            margin-bottom: 0.375rem;
        }
        
        .stat-trend {
            font-size: 0.65rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }
        
        .stat-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
        
        .recent-section {
            padding: 0.875rem;
            overflow-x: hidden;
        }
        
        .section-title {
            font-size: 0.9rem;
        }
        
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            font-size: 0.7rem;
            min-width: 500px;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.375rem;
        }
        
        .alerts-section {
            padding: 0.875rem;
        }
        
        .alert-item {
            padding: 0.75rem;
        }
        
        .alert-text {
            font-size: 0.8rem;
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
                    <i class="fas fa-building"></i>
                    <span>جميع الوحدات</span>
                </div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-building"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">وحدات نشطة</div>
                <div class="stat-value">{{ $activeProperties }}</div>
                <div class="stat-trend">
                    <i class="fas fa-check-circle"></i>
                    <span>جاهزة للعرض</span>
                </div>
            </div>
            <div class="stat-icon green">
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
                    <i class="fas fa-clock"></i>
                    <span>تحتاج مراجعة</span>
                </div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">موقوفة</div>
                <div class="stat-value">{{ $suspendedProperties }}</div>
                <div class="stat-trend">
                    <i class="fas fa-ban"></i>
                    <span>معلقة مؤقتاً</span>
                </div>
            </div>
            <div class="stat-icon red">
                <i class="fas fa-ban"></i>
            </div>
        </div>
    </div>
</div>

<!-- Second Row Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">إجمالي الحجوزات</div>
                <div class="stat-value">{{ $totalBookings }}</div>
                <div class="stat-trend">
                    <i class="fas fa-calendar-check"></i>
                    <span>جميع الحجوزات</span>
                </div>
            </div>
            <div class="stat-icon purple">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">طلبات معاينة</div>
                <div class="stat-value">{{ $pendingInspections }}</div>
                <div class="stat-trend">
                    <i class="fas fa-eye"></i>
                    <span>في انتظار الرد</span>
                </div>
            </div>
            <div class="stat-icon pink">
                <i class="fas fa-eye"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">الحجوزات المؤكدة</div>
                <div class="stat-value">{{ $confirmedBookings }}</div>
                <div class="stat-trend">
                    <i class="fas fa-check-circle"></i>
                    <span>تم تأكيدها</span>
                </div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="opacity: 0.7;">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">متوسط التقييم</div>
                <div class="stat-value">-</div>
                <div class="stat-trend">
                    <i class="fas fa-star"></i>
                    <span>قريباً</span>
                </div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
</div>

<!-- Alerts Section -->
@if($alerts['pending_inspections'] > 0 || $alerts['unread_messages'] > 0 || $alerts['pending_properties'] > 0 || $alerts['upcoming_inspections'] > 0)
<div class="alerts-section">
    <div class="alerts-header">
        <h2 class="alerts-title">
            <i class="fas fa-bell"></i>
            التنبيهات العاجلة
        </h2>
    </div>
    
    @if($alerts['pending_inspections'] > 0)
    <div class="alert-item">
        <div class="alert-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="alert-content">
            <div class="alert-text">لديك {{ $alerts['pending_inspections'] }} طلب معاينة في انتظار الرد</div>
            <a href="{{ route('owner.inspections') }}" class="alert-link">
                عرض الطلبات <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
    @endif
    
    @if($alerts['unread_messages'] > 0)
    <div class="alert-item">
        <div class="alert-icon">
            <i class="fas fa-envelope"></i>
        </div>
        <div class="alert-content">
            <div class="alert-text">لديك {{ $alerts['unread_messages'] }} رسالة غير مقروءة</div>
            <a href="{{ route('owner.messages') }}" class="alert-link">
                عرض الرسائل <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
    @endif
    
    @if($alerts['pending_properties'] > 0)
    <div class="alert-item">
        <div class="alert-icon">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="alert-content">
            <div class="alert-text">لديك {{ $alerts['pending_properties'] }} وحدة قيد المراجعة</div>
            <a href="{{ route('owner.properties.index') }}" class="alert-link">
                عرض الوحدات <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
    @endif
    
    @if($alerts['upcoming_inspections'] > 0)
    <div class="alert-item">
        <div class="alert-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="alert-content">
            <div class="alert-text">لديك {{ $alerts['upcoming_inspections'] }} معاينة قريبة (خلال 3 أيام)</div>
            <a href="{{ route('owner.inspections') }}" class="alert-link">
                عرض المواعيد <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Bottom Sections Grid -->
<div class="bottom-grid">
    <!-- Recent Properties -->
    <div class="recent-section">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-building"></i>
                الوحدات الأخيرة
            </h3>
        </div>
        
        @if($recentProperties->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>العنوان</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentProperties as $property)
                    <tr>
                        <td style="color: #6B7280; font-weight: 600;">{{ $property->code }}</td>
                        <td style="color: #1F2937; font-weight: 600;">{{ Str::limit($property->address, 30) }}</td>
                        <td>
                            @if($property->admin_status == 'approved' && !$property->is_suspended)
                            <span class="badge badge-approved">نشط</span>
                            @elseif($property->admin_status == 'pending')
                            <span class="badge badge-pending">قيد المراجعة</span>
                            @elseif($property->is_suspended)
                            <span class="badge badge-suspended">موقوف</span>
                            @else
                            <span class="badge badge-rejected">مرفوض</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('owner.properties.show', $property) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                                عرض
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-building"></i>
            <p>لا توجد وحدات بعد</p>
            <a href="{{ route('owner.properties.create') }}" class="btn-action btn-view">
                <i class="fas fa-plus"></i>
                إضافة وحدة جديدة
            </a>
        </div>
        @endif
    </div>
    
    <!-- Recent Bookings -->
    <div class="recent-section">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-calendar-check"></i>
                الحجوزات الأخيرة
            </h3>
        </div>
        
        @if($recentBookings->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>المستأجر</th>
                        <th>الوحدة</th>
                        <th>التاريخ</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                    <tr>
                        <td style="color: #1F2937; font-weight: 600;">{{ $booking->user->name }}</td>
                        <td style="color: #6B7280;">{{ Str::limit($booking->property->address, 25) }}</td>
                        <td style="color: #6B7280;">{{ $booking->inspection_date ? $booking->inspection_date->format('Y-m-d') : 'غير محدد' }}</td>
                        <td>
                            @if($booking->status == 'pending')
                            <span class="badge badge-pending">قيد الانتظار</span>
                            @elseif($booking->status == 'confirmed')
                            <span class="badge badge-confirmed">مؤكد</span>
                            @elseif($booking->status == 'completed')
                            <span class="badge badge-completed">مكتمل</span>
                            @else
                            <span class="badge badge-cancelled">ملغي</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-calendar-check"></i>
            <p>لا توجد حجوزات بعد</p>
        </div>
        @endif
    </div>
</div>
@endsection


