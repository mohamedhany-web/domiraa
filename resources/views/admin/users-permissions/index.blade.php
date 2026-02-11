@extends('layouts.admin')

@section('title', 'إدارة المستخدمين والصلاحيات')

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
    
    .btn-add {
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
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
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
    
    .users-table {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
    }
    
    .users-table th, .users-table td {
        padding: 1rem 1.25rem;
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
    
    .users-table tr:last-child td {
        border-bottom: none;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
    }
    
    .user-details h4 {
        font-weight: 700;
        color: #1F2937;
        margin: 0;
    }
    
    .user-details p {
        font-size: 0.875rem;
        color: #6B7280;
        margin: 0;
    }
    
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-admin {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .badge-owner {
        background: #DBEAFE;
        color: #1E40AF;
    }
    
    .badge-tenant {
        background: #D1FAE5;
        color: #059669;
    }
    
    .badge-active {
        background: #D1FAE5;
        color: #059669;
    }
    
    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .badge-suspended {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .role-badge {
        padding: 0.25rem 0.75rem;
        background: rgba(107, 137, 128, 0.1);
        color: var(--primary);
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-view {
        background: #F3F4F6;
        color: #374151;
    }
    
    .btn-view:hover {
        background: #E5E7EB;
    }
    
    .btn-edit {
        background: rgba(107, 137, 128, 0.1);
        color: var(--secondary);
    }
    
    .btn-edit:hover {
        background: rgba(107, 137, 128, 0.2);
    }
    
    .btn-suspend {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .btn-suspend:hover {
        background: #FDE68A;
    }
    
    .btn-activate {
        background: #D1FAE5;
        color: #059669;
    }
    
    .btn-activate:hover {
        background: #A7F3D0;
    }
    
    .btn-delete {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .btn-delete:hover {
        background: #FECACA;
    }
    
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .filter-form {
            flex-direction: column;
        }
        
        .filter-group {
            width: 100%;
        }
        
        .users-table {
            display: block;
            overflow-x: auto;
        }
        
        .actions {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-users-cog"></i>
        إدارة المستخدمين والصلاحيات
    </h1>
    <a href="{{ route('admin.users-permissions.create') }}" class="btn-add">
        <i class="fas fa-user-plus"></i>
        إضافة مستخدم
    </a>
</div>

<div class="filter-card">
    <form method="GET" action="{{ route('admin.users-permissions.index') }}" class="filter-form">
        <div class="filter-group">
            <label class="filter-label">البحث</label>
            <input type="text" name="search" class="filter-input" placeholder="الاسم، البريد، الهاتف..." value="{{ request('search') }}">
        </div>
        <div class="filter-group">
            <label class="filter-label">نوع الحساب</label>
            <select name="role" class="filter-select">
                <option value="">الكل</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>مدير</option>
                <option value="owner" {{ request('role') === 'owner' ? 'selected' : '' }}>مؤجر</option>
                <option value="tenant" {{ request('role') === 'tenant' ? 'selected' : '' }}>مستأجر</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">الحالة</label>
            <select name="status" class="filter-select">
                <option value="">الكل</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>موقوف</option>
            </select>
        </div>
        <button type="submit" class="btn-filter">
            <i class="fas fa-search"></i>
            بحث
        </button>
    </form>
</div>

<table class="users-table">
    <thead>
        <tr>
            <th>المستخدم</th>
            <th>نوع الحساب</th>
            <th>الدور</th>
            <th>الحالة</th>
            <th>تاريخ التسجيل</th>
            <th>الإجراءات</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
        <tr>
            <td>
                <div class="user-info">
                    <div class="user-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
                    <div class="user-details">
                        <h4>{{ $user->name }}</h4>
                        <p>{{ $user->email }}</p>
                    </div>
                </div>
            </td>
            <td>
                <span class="badge badge-{{ $user->role }}">
                    @switch($user->role)
                        @case('admin') مدير @break
                        @case('owner') مؤجر @break
                        @case('tenant') مستأجر @break
                    @endswitch
                </span>
            </td>
            <td>
                @if($user->roleModel)
                <span class="role-badge">{{ $user->roleModel->display_name ?? $user->roleModel->name }}</span>
                @else
                <span style="color: #9CA3AF;">-</span>
                @endif
                @php
                    $permCount = count($user->getAllPermissions());
                @endphp
                @if($permCount > 0)
                <span style="display: block; font-size: 0.7rem; color: #6B7280; margin-top: 0.25rem;">
                    {{ $permCount }} صلاحية
                </span>
                @endif
            </td>
            <td>
                <span class="badge badge-{{ $user->account_status }}">
                    @switch($user->account_status)
                        @case('active') نشط @break
                        @case('pending') قيد المراجعة @break
                        @case('suspended') موقوف @break
                    @endswitch
                </span>
            </td>
            <td>{{ $user->created_at->format('Y/m/d') }}</td>
            <td>
                <div class="actions">
                    <a href="{{ route('admin.users-permissions.show', $user) }}" class="btn-action btn-view" title="عرض">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.users-permissions.edit', $user) }}" class="btn-action btn-edit" title="تعديل">
                        <i class="fas fa-edit"></i>
                    </a>
                    @if(!$user->isSuperAdmin())
                        @if($user->account_status !== 'suspended')
                        <button type="button" class="btn-action btn-suspend" onclick="showSuspendModal({{ $user->id }})" title="إيقاف">
                            <i class="fas fa-ban"></i>
                        </button>
                        @else
                        <form action="{{ route('admin.users-permissions.activate', $user) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-action btn-activate" title="تفعيل">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('admin.users-permissions.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 2rem;">
                <i class="fas fa-users" style="font-size: 2rem; color: #9CA3AF; margin-bottom: 0.5rem;"></i>
                <p style="color: #6B7280;">لا يوجد مستخدمون</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 1.5rem;">
    {{ $users->withQueryString()->links() }}
</div>

<!-- Suspend Modal -->
<div id="suspendModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 2rem; max-width: 400px; width: 90%;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1F2937; margin-bottom: 1rem;">إيقاف حساب المستخدم</h3>
        <form id="suspendForm" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">سبب الإيقاف *</label>
                <textarea name="reason" required style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; min-height: 100px;"></textarea>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">مدة الإيقاف (بالأيام)</label>
                <input type="number" name="duration" min="1" max="365" style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px;" placeholder="اتركه فارغاً للإيقاف الدائم">
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="closeSuspendModal()" style="padding: 0.75rem 1.5rem; border-radius: 8px; background: #F3F4F6; color: #374151; border: none; cursor: pointer; font-weight: 600;">إلغاء</button>
                <button type="submit" style="padding: 0.75rem 1.5rem; border-radius: 8px; background: #DC2626; color: white; border: none; cursor: pointer; font-weight: 600;">تأكيد الإيقاف</button>
            </div>
        </form>
    </div>
</div>

<script>
function showSuspendModal(userId) {
    document.getElementById('suspendForm').action = `/admin/users-permissions/${userId}/suspend`;
    document.getElementById('suspendModal').style.display = 'flex';
}

function closeSuspendModal() {
    document.getElementById('suspendModal').style.display = 'none';
}

document.getElementById('suspendModal').addEventListener('click', function(e) {
    if (e.target === this) closeSuspendModal();
});
</script>
@endsection

