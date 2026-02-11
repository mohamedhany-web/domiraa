@extends('layouts.admin')

@section('title', 'خدمة العملاء')
@section('page-title', 'خدمة العملاء')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }
    
    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.25rem;
    }
    
    .stat-icon.blue { background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); color: white; }
    .stat-icon.green { background: linear-gradient(135deg, #8aa69d 0%, #6b8980 100%); color: white; }
    .stat-icon.yellow { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white; }
    .stat-icon.purple { background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); color: white; }
    .stat-icon.gray { background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%); color: white; }
    .stat-icon.red { background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); color: white; }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1F2937;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    /* Filter Bar */
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
    
    .filter-bar input,
    .filter-bar select {
        padding: 0.625rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    
    .filter-bar input:focus,
    .filter-bar select:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .filter-bar input {
        flex: 1;
        min-width: 200px;
    }
    
    /* Tickets Table */
    .tickets-section {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    .section-header {
        padding: 1.25rem;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .tickets-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .tickets-table th,
    .tickets-table td {
        padding: 1rem;
        text-align: right;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .tickets-table th {
        background: #F9FAFB;
        font-weight: 700;
        color: #374151;
        font-size: 0.85rem;
    }
    
    .tickets-table tr:hover {
        background: #F9FAFB;
    }
    
    .ticket-number {
        font-weight: 700;
        color: var(--primary);
    }
    
    .ticket-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .ticket-name {
        font-weight: 600;
        color: #1F2937;
    }
    
    .ticket-contact {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-badge.open { background: #DBEAFE; color: #1E40AF; }
    .status-badge.pending { background: #FEF3C7; color: #D97706; }
    .status-badge.answered { background: #D1FAE5; color: #059669; }
    .status-badge.closed { background: #F3F4F6; color: #6B7280; }
    
    .priority-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .priority-badge.low { background: #F3F4F6; color: #6B7280; }
    .priority-badge.medium { background: #FEF3C7; color: #D97706; }
    .priority-badge.high { background: #FEE2E2; color: #DC2626; }
    
    .unread-badge {
        background: #DC2626;
        color: white;
        padding: 0.125rem 0.5rem;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    .time-ago {
        font-size: 0.85rem;
        color: #6B7280;
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
    
    /* Pagination */
    .pagination-wrapper {
        padding: 1rem;
        border-top: 1px solid #E5E7EB;
    }
    
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .filter-bar {
            flex-direction: column;
        }
        
        .filter-bar input,
        .filter-bar select {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">إجمالي التذاكر</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-folder-open"></i>
        </div>
        <div class="stat-value">{{ $stats['open'] }}</div>
        <div class="stat-label">مفتوحة</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon yellow">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value">{{ $stats['pending'] }}</div>
        <div class="stat-label">قيد الانتظار</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value">{{ $stats['answered'] }}</div>
        <div class="stat-label">تم الرد</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon gray">
            <i class="fas fa-archive"></i>
        </div>
        <div class="stat-value">{{ $stats['closed'] }}</div>
        <div class="stat-label">مغلقة</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fas fa-envelope"></i>
        </div>
        <div class="stat-value">{{ $stats['unread'] }}</div>
        <div class="stat-label">غير مقروءة</div>
    </div>
</div>

<!-- Filter -->
<form action="{{ route('admin.support.index') }}" method="GET" class="filter-bar">
    <input type="text" name="search" placeholder="بحث برقم التذكرة أو الاسم أو الهاتف..." value="{{ request('search') }}">
    
    <select name="status" onchange="this.form.submit()">
        <option value="">جميع الحالات</option>
        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>مفتوحة</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
        <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>تم الرد</option>
        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
    </select>
    
    <select name="priority" onchange="this.form.submit()">
        <option value="">جميع الأولويات</option>
        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>عالية</option>
        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>متوسطة</option>
        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>منخفضة</option>
    </select>
    
    <button type="submit" style="background: var(--primary); color: white; padding: 0.625rem 1.25rem; border: none; border-radius: 8px; cursor: pointer;">
        <i class="fas fa-search"></i>
    </button>
</form>

<!-- Tickets Table -->
<div class="tickets-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-headset"></i>
            تذاكر الدعم الفني
        </h2>
    </div>
    
    <div class="table-responsive">
        @if($tickets->count() > 0)
        <table class="tickets-table">
            <thead>
                <tr>
                    <th>رقم التذكرة</th>
                    <th>العميل</th>
                    <th>الموضوع</th>
                    <th>الحالة</th>
                    <th>الأولوية</th>
                    <th>آخر رد</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                <tr>
                    <td>
                        <span class="ticket-number">{{ $ticket->ticket_number }}</span>
                        @if($ticket->unread_messages_count > 0)
                            <span class="unread-badge">{{ $ticket->unread_messages_count }} جديد</span>
                        @endif
                    </td>
                    <td>
                        <div class="ticket-info">
                            <span class="ticket-name">{{ $ticket->name }}</span>
                            <span class="ticket-contact">
                                {{ $ticket->phone }}
                                @if($ticket->email) • {{ $ticket->email }} @endif
                            </span>
                        </div>
                    </td>
                    <td>{{ $ticket->subject ?? 'استفسار عام' }}</td>
                    <td>
                        <span class="status-badge {{ $ticket->status }}">
                            {{ $ticket->status_label }}
                        </span>
                    </td>
                    <td>
                        <span class="priority-badge {{ $ticket->priority }}">
                            {{ $ticket->priority_label }}
                        </span>
                    </td>
                    <td>
                        <span class="time-ago">{{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : $ticket->created_at->diffForHumans() }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.support.show', $ticket) }}" class="action-btn">
                            <i class="fas fa-eye"></i>
                            عرض
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>لا توجد تذاكر</h3>
            <p>لم يتم العثور على تذاكر تطابق معايير البحث</p>
        </div>
        @endif
    </div>
    
    @if($tickets->hasPages())
    <div class="pagination-wrapper">
        {{ $tickets->withQueryString()->links() }}
    </div>
    @endif
</div>

<script>
// Auto refresh for new tickets
setInterval(() => {
    fetch('{{ route("admin.support.unread-count") }}')
        .then(r => r.json())
        .then(data => {
            if (data.count > 0) {
                // Could show a notification or update badge
            }
        });
}, 30000);
</script>
@endsection

