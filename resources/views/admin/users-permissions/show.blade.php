@extends('layouts.admin')

@section('title', 'تفاصيل المستخدم: ' . $user->name)

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
    
    .header-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
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
    
    .user-profile-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
        margin-bottom: 1.5rem;
    }
    
    .user-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .user-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 2rem;
    }
    
    .user-main-info h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
    }
    
    .user-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-admin { background: #FEE2E2; color: #DC2626; }
    .badge-owner { background: #DBEAFE; color: #1E40AF; }
    .badge-tenant { background: #D1FAE5; color: #059669; }
    .badge-active { background: #D1FAE5; color: #059669; }
    .badge-suspended { background: #FEE2E2; color: #DC2626; }
    .badge-pending { background: #FEF3C7; color: #D97706; }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .info-item {
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 10px;
    }
    
    .info-label {
        font-size: 0.875rem;
        color: #6B7280;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        font-size: 1rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .section-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #E5E7EB;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: var(--primary);
    }
    
    .permissions-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .perm-stat {
        background: #F9FAFB;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
    }
    
    .perm-stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary);
    }
    
    .perm-stat-label {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .permission-group {
        background: #F9FAFB;
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid #E5E7EB;
    }
    
    .permission-group-title {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .permission-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        border-radius: 6px;
        margin-bottom: 0.25rem;
    }
    
    .permission-item.active {
        background: #D1FAE5;
    }
    
    .permission-item.denied {
        background: #FEE2E2;
        text-decoration: line-through;
    }
    
    .permission-item i {
        font-size: 0.75rem;
    }
    
    .permission-item i.fa-check {
        color: #10B981;
    }
    
    .permission-item i.fa-ban {
        color: #EF4444;
    }
    
    .permission-name {
        font-size: 0.875rem;
        color: #374151;
    }
    
    .permission-source {
        font-size: 0.65rem;
        padding: 0.1rem 0.4rem;
        border-radius: 10px;
        margin-right: auto;
    }
    
    .permission-source.role {
        background: rgba(107, 137, 128, 0.2);
        color: var(--secondary-dark);
    }
    
    .permission-source.direct {
        background: #DBEAFE;
        color: #1E40AF;
    }
    
    .no-permissions {
        text-align: center;
        padding: 2rem;
        color: #6B7280;
    }
    
    .no-permissions i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #D1D5DB;
    }
    
    .activity-list {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .activity-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #F3F4F6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6B7280;
        flex-shrink: 0;
    }
    
    .activity-content h4 {
        font-weight: 600;
        color: #1F2937;
        margin: 0 0 0.25rem 0;
    }
    
    .activity-content p {
        font-size: 0.875rem;
        color: #6B7280;
        margin: 0;
    }
    
    .activity-time {
        font-size: 0.75rem;
        color: #9CA3AF;
    }
    
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .user-header {
            flex-direction: column;
            text-align: center;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .permissions-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user"></i>
        تفاصيل المستخدم
    </h1>
    <div class="header-actions">
        <a href="{{ route('admin.users-permissions.index') }}" class="btn-back">
            <i class="fas fa-arrow-right"></i>
            رجوع
        </a>
        <a href="{{ route('admin.users-permissions.edit', $user) }}" class="btn-edit">
            <i class="fas fa-edit"></i>
            تعديل الصلاحيات
        </a>
    </div>
</div>

<div class="user-profile-card">
    <div class="user-header">
        <div class="user-avatar-large">{{ mb_substr($user->name, 0, 1) }}</div>
        <div class="user-main-info">
            <h2>{{ $user->name }}</h2>
            <div class="user-badges">
                <span class="badge badge-{{ $user->role }}">
                    @switch($user->role)
                        @case('admin') مدير @break
                        @case('owner') مؤجر @break
                        @case('tenant') مستأجر @break
                    @endswitch
                </span>
                <span class="badge badge-{{ $user->account_status }}">
                    @switch($user->account_status)
                        @case('active') نشط @break
                        @case('pending') قيد المراجعة @break
                        @case('suspended') موقوف @break
                    @endswitch
                </span>
                @if($user->roleModel)
                <span class="badge" style="background: rgba(107, 137, 128, 0.1); color: var(--primary);">
                    <i class="fas fa-user-shield" style="margin-left: 0.25rem;"></i>
                    {{ $user->roleModel->display_name ?? $user->roleModel->name }}
                </span>
                @endif
            </div>
        </div>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">البريد الإلكتروني</div>
            <div class="info-value">{{ $user->email }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">رقم الهاتف</div>
            <div class="info-value">{{ $user->phone ?? '-' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">تاريخ التسجيل</div>
            <div class="info-value">{{ $user->created_at->format('Y/m/d H:i') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">آخر تحديث</div>
            <div class="info-value">{{ $user->updated_at->format('Y/m/d H:i') }}</div>
        </div>
    </div>
</div>

<div class="section-card">
    <h2 class="section-title">
        <i class="fas fa-key"></i>
        الصلاحيات
    </h2>
    
    <!-- Permissions Summary -->
    <div class="permissions-summary">
        <div class="perm-stat">
            <div class="perm-stat-number">{{ $allPermissions->count() }}</div>
            <div class="perm-stat-label">إجمالي الصلاحيات</div>
        </div>
        <div class="perm-stat">
            <div class="perm-stat-number">{{ $rolePermissions->count() }}</div>
            <div class="perm-stat-label">من الدور</div>
        </div>
        <div class="perm-stat">
            <div class="perm-stat-number">{{ $user->directPermissions->where('pivot.granted', true)->count() }}</div>
            <div class="perm-stat-label">ممنوح مباشرة</div>
        </div>
        <div class="perm-stat">
            <div class="perm-stat-number">{{ $user->directPermissions->where('pivot.granted', false)->count() }}</div>
            <div class="perm-stat-label">ممنوع مباشرة</div>
        </div>
    </div>
    
    @if($allPermissions->count() > 0)
    @php
        // Group permissions by their prefix
        $groupedPermissions = $allPermissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'general';
        });
        
        // Get direct permission IDs for marking
        $directGrantedIds = $user->directPermissions->where('pivot.granted', true)->pluck('id')->toArray();
        $directDeniedIds = $user->directPermissions->where('pivot.granted', false)->pluck('id')->toArray();
        $rolePermissionIds = $rolePermissions->pluck('id')->toArray();
    @endphp
    
    <div class="permissions-grid">
        @foreach($groupedPermissions as $group => $perms)
        <div class="permission-group">
            <h3 class="permission-group-title">
                <i class="fas fa-folder"></i>
                {{ ucfirst($group) }}
                <span style="font-weight: normal; font-size: 0.8rem; color: #6B7280;">({{ $perms->count() }})</span>
            </h3>
            @foreach($perms as $permission)
            @php
                $isDirectGranted = in_array($permission->id, $directGrantedIds);
                $isFromRole = in_array($permission->id, $rolePermissionIds) && !$isDirectGranted;
            @endphp
            <div class="permission-item active">
                <i class="fas fa-check"></i>
                <span class="permission-name">{{ $permission->display_name ?? $permission->name }}</span>
                @if($isDirectGranted)
                <span class="permission-source direct">مباشر</span>
                @elseif($isFromRole)
                <span class="permission-source role">من الدور</span>
                @endif
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    @else
    <div class="no-permissions">
        <i class="fas fa-key"></i>
        <p>لا توجد صلاحيات لهذا المستخدم</p>
    </div>
    @endif
    
    <!-- Denied Permissions -->
    @if($user->directPermissions->where('pivot.granted', false)->count() > 0)
    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #E5E7EB;">
        <h3 style="font-size: 1rem; font-weight: 700; color: #DC2626; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-ban"></i>
            الصلاحيات الممنوعة ({{ $user->directPermissions->where('pivot.granted', false)->count() }})
        </h3>
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
            @foreach($user->directPermissions->where('pivot.granted', false) as $permission)
            <span style="background: #FEE2E2; color: #DC2626; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.85rem; text-decoration: line-through;">
                {{ $permission->display_name ?? $permission->name }}
            </span>
            @endforeach
        </div>
    </div>
    @endif
</div>

@if($recentActivityLogs && $recentActivityLogs->count() > 0)
<div class="section-card">
    <h2 class="section-title">
        <i class="fas fa-history"></i>
        آخر الأنشطة ({{ $recentActivityLogs->count() }})
    </h2>
    
    <div class="activity-list">
        @foreach($recentActivityLogs as $log)
        <div class="activity-item">
            <div class="activity-icon">
                @switch($log->log_name)
                    @case('create') <i class="fas fa-plus" style="color: #10B981;"></i> @break
                    @case('update') <i class="fas fa-edit" style="color: #3B82F6;"></i> @break
                    @case('delete') <i class="fas fa-trash" style="color: #EF4444;"></i> @break
                    @case('authentication') <i class="fas fa-sign-in-alt" style="color: #8B5CF6;"></i> @break
                    @case('user') <i class="fas fa-user" style="color: #F59E0B;"></i> @break
                    @default <i class="fas fa-circle"></i>
                @endswitch
            </div>
            <div class="activity-content">
                <h4>{{ $log->description }}</h4>
                <p>
                    @if($log->ip_address)
                    <i class="fas fa-globe" style="margin-left: 0.25rem;"></i> {{ $log->ip_address }}
                    @endif
                </p>
                <span class="activity-time">{{ $log->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @endforeach
    </div>
    
    <div style="margin-top: 1rem; text-align: center;">
        <a href="{{ route('admin.users-permissions.activity-logs', $user) }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">
            عرض كل الأنشطة <i class="fas fa-arrow-left"></i>
        </a>
    </div>
</div>
@endif
@endsection
