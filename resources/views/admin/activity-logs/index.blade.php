@extends('layouts.admin')

@section('title', 'سجل الأنشطة')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-title i {
        color: var(--primary);
    }
    
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
    }
    
    .filter-form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    .filter-group {
        flex: 1;
        min-width: 180px;
    }
    
    .filter-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .filter-input, .filter-select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .btn-filter {
        background: var(--primary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }
    
    .activity-timeline {
        position: relative;
    }
    
    .activity-timeline::before {
        content: '';
        position: absolute;
        right: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #E5E7EB;
    }
    
    .activity-item {
        position: relative;
        padding: 1.5rem 1.5rem 1.5rem 0;
        margin-right: 40px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
        margin-bottom: 1rem;
    }
    
    .activity-item::before {
        content: '';
        position: absolute;
        right: -30px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--primary);
        border: 4px solid white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    
    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }
    
    .activity-info h4 {
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 0.25rem 0;
    }
    
    .activity-info p {
        font-size: 0.875rem;
        color: #6B7280;
        margin: 0;
    }
    
    .activity-time {
        font-size: 0.75rem;
        color: #9CA3AF;
        text-align: left;
    }
    
    .activity-details {
        background: #F9FAFB;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 0.75rem;
    }
    
    .activity-user {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .activity-user-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.75rem;
    }
    
    .activity-user-name {
        font-weight: 600;
        color: #374151;
    }
    
    .activity-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        font-size: 0.8rem;
        color: #6B7280;
    }
    
    .activity-meta span {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-success { background: #D1FAE5; color: #059669; }
    .badge-warning { background: #FEF3C7; color: #D97706; }
    .badge-danger { background: #FEE2E2; color: #DC2626; }
    .badge-info { background: #DBEAFE; color: #1E40AF; }
    .badge-default { background: #F3F4F6; color: #374151; }
    
    @media (max-width: 768px) {
        .activity-timeline::before {
            display: none;
        }
        
        .activity-item {
            margin-right: 0;
        }
        
        .activity-item::before {
            display: none;
        }
        
        .filter-form {
            flex-direction: column;
        }
        
        .filter-group {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-history"></i>
        سجل الأنشطة
    </h1>
</div>

<div class="filter-card">
    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="filter-form">
        <div class="filter-group">
            <label class="filter-label">البحث</label>
            <input type="text" name="search" class="filter-input" placeholder="البحث في الوصف..." value="{{ request('search') }}">
        </div>
        <div class="filter-group">
            <label class="filter-label">المستخدم</label>
            <select name="user_id" class="filter-select">
                <option value="">كل المستخدمين</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">نوع السجل</label>
            <select name="log_name" class="filter-select">
                <option value="">الكل</option>
                <option value="authentication" {{ request('log_name') === 'authentication' ? 'selected' : '' }}>التوثيق</option>
                <option value="user" {{ request('log_name') === 'user' ? 'selected' : '' }}>المستخدمين</option>
                <option value="property" {{ request('log_name') === 'property' ? 'selected' : '' }}>العقارات</option>
                <option value="booking" {{ request('log_name') === 'booking' ? 'selected' : '' }}>الحجوزات</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">من تاريخ</label>
            <input type="date" name="from_date" class="filter-input" value="{{ request('from_date') }}">
        </div>
        <div class="filter-group">
            <label class="filter-label">إلى تاريخ</label>
            <input type="date" name="to_date" class="filter-input" value="{{ request('to_date') }}">
        </div>
        <button type="submit" class="btn-filter">
            <i class="fas fa-search"></i>
            بحث
        </button>
    </form>
</div>

<div class="activity-timeline">
    @forelse($activityLogs as $log)
    <div class="activity-item">
        <div class="activity-header">
            <div class="activity-info">
                <h4>{{ $log->description }}</h4>
                <p>
                    <span class="badge badge-{{ $log->log_name === 'authentication' ? 'info' : ($log->log_name === 'user' ? 'warning' : 'default') }}">
                        {{ $log->log_name ?? 'عام' }}
                    </span>
                </p>
            </div>
            <div class="activity-time">
                {{ $log->created_at->format('Y/m/d') }}<br>
                {{ $log->created_at->format('H:i:s') }}
            </div>
        </div>
        
        <div class="activity-details">
            @if($log->causer)
            <div class="activity-user">
                <div class="activity-user-avatar">{{ mb_substr($log->causer->name ?? 'N', 0, 1) }}</div>
                <span class="activity-user-name">{{ $log->causer->name ?? 'غير معروف' }}</span>
            </div>
            @endif
            
            <div class="activity-meta">
                @if($log->ip_address)
                <span><i class="fas fa-globe"></i> {{ $log->ip_address }}</span>
                @endif
                @if($log->subject_type)
                <span><i class="fas fa-link"></i> {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</span>
                @endif
                <span><i class="fas fa-clock"></i> {{ $log->created_at->diffForHumans() }}</span>
            </div>
            
            @if($log->properties && count($log->properties) > 0)
            <details style="margin-top: 0.75rem;">
                <summary style="cursor: pointer; font-weight: 600; color: var(--primary);">تفاصيل التغييرات</summary>
                <pre style="background: #1F2937; color: #F9FAFB; padding: 1rem; border-radius: 8px; margin-top: 0.5rem; overflow-x: auto; font-size: 0.75rem;">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </details>
            @endif
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
        <i class="fas fa-history" style="font-size: 3rem; color: #9CA3AF; margin-bottom: 1rem;"></i>
        <h3 style="color: #374151; margin: 0;">لا توجد أنشطة مسجلة</h3>
    </div>
    @endforelse
</div>

<div style="margin-top: 1.5rem;">
    {{ $activityLogs->withQueryString()->links() }}
</div>
@endsection

