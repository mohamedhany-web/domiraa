@extends('layouts.admin')

@section('title', 'تفاصيل الدور: ' . $role->display_name)

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
    
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
        margin-bottom: 1.5rem;
    }
    
    .info-card-title {
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
    
    .info-card-title i {
        color: var(--primary);
    }
    
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
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .permissions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .permission-tag {
        padding: 0.5rem 1rem;
        background: rgba(107, 137, 128, 0.1);
        color: var(--primary);
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .users-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .users-table th, .users-table td {
        padding: 1rem;
        text-align: right;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .users-table th {
        background: #F9FAFB;
        font-weight: 700;
        color: #374151;
    }
    
    .users-table tr:hover {
        background: #F9FAFB;
    }
    
    .user-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }
    
    .user-link:hover {
        text-decoration: underline;
    }
    
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-active {
        background: #D1FAE5;
        color: #059669;
    }
    
    .badge-suspended {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .badge-system {
        background: #FEE2E2;
        color: #DC2626;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user-tag"></i>
        {{ $role->display_name }}
        @if($role->is_system)
        <span class="badge badge-system">نظامي</span>
        @endif
    </h1>
    <div class="header-actions">
        <a href="{{ route('admin.roles.index') }}" class="btn-back">
            <i class="fas fa-arrow-right"></i>
            رجوع
        </a>
        <a href="{{ route('admin.roles.edit', $role) }}" class="btn-edit">
            <i class="fas fa-edit"></i>
            تعديل
        </a>
    </div>
</div>

<div class="info-card">
    <h2 class="info-card-title">
        <i class="fas fa-info-circle"></i>
        معلومات الدور
    </h2>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">الاسم التقني</div>
            <div class="info-value">{{ $role->name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">عدد المستخدمين</div>
            <div class="info-value">{{ $role->users->count() }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">عدد الصلاحيات</div>
            <div class="info-value">{{ $role->permissions->count() }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">تاريخ الإنشاء</div>
            <div class="info-value">{{ $role->created_at->format('Y/m/d') }}</div>
        </div>
    </div>
    @if($role->description)
    <div style="margin-top: 1.5rem; padding: 1rem; background: #F9FAFB; border-radius: 10px;">
        <div class="info-label">الوصف</div>
        <p style="color: #374151; margin: 0;">{{ $role->description }}</p>
    </div>
    @endif
</div>

<div class="info-card">
    <h2 class="info-card-title">
        <i class="fas fa-key"></i>
        الصلاحيات ({{ $role->permissions->count() }})
    </h2>
    @if($role->permissions->count() > 0)
    <div class="permissions-list">
        @foreach($role->permissions as $permission)
        <span class="permission-tag">{{ $permission->display_name }}</span>
        @endforeach
    </div>
    @else
    <p style="color: #6B7280;">لا توجد صلاحيات مخصصة لهذا الدور</p>
    @endif
</div>

<div class="info-card">
    <h2 class="info-card-title">
        <i class="fas fa-users"></i>
        المستخدمون ({{ $role->users->count() }})
    </h2>
    @if($role->users->count() > 0)
    <table class="users-table">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الهاتف</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($role->users as $user)
            <tr>
                <td>
                    <a href="{{ route('admin.users-permissions.show', $user) }}" class="user-link">{{ $user->name }}</a>
                </td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>
                    <span class="badge {{ $user->account_status === 'active' ? 'badge-active' : 'badge-suspended' }}">
                        {{ $user->account_status === 'active' ? 'نشط' : 'موقوف' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="color: #6B7280;">لا يوجد مستخدمون بهذا الدور</p>
    @endif
</div>
@endsection

