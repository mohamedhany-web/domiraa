@extends('layouts.admin')

@section('title', 'سجل أنشطة: ' . $user->name)

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
    
    .btn-back {
        background: #F3F4F6;
        color: #374151;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-back:hover {
        background: #E5E7EB;
    }
    
    .user-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
    }
    
    .user-info h3 {
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 0.25rem 0;
    }
    
    .user-info p {
        color: #6B7280;
        margin: 0;
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
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-history"></i>
        سجل الأنشطة
    </h1>
    <a href="{{ route('admin.users-permissions.show', $user) }}" class="btn-back">
        <i class="fas fa-arrow-right"></i>
        رجوع للملف الشخصي
    </a>
</div>

<div class="user-card">
    <div class="user-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
    <div class="user-info">
        <h3>{{ $user->name }}</h3>
        <p>{{ $user->email }}</p>
    </div>
</div>

<div class="activity-timeline">
    @forelse($activityLogs as $log)
    <div class="activity-item">
        <div class="activity-header">
            <div class="activity-info">
                <h4>{{ $log->description }}</h4>
                <p>
                    <span class="badge badge-{{ $log->log_name === 'authentication' ? 'info' : 'default' }}">
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
        <h3 style="color: #374151; margin: 0;">لا توجد أنشطة مسجلة لهذا المستخدم</h3>
    </div>
    @endforelse
</div>

<div style="margin-top: 1.5rem;">
    {{ $activityLogs->links() }}
</div>
@endsection

