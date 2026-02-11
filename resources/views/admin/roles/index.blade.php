@extends('layouts.admin')

@section('title', 'إدارة الأدوار')

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
    
    .roles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }
    
    .role-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
        transition: all 0.3s ease;
    }
    
    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .role-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .role-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
    
    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .role-badge.system {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .role-badge.custom {
        background: #D1FAE5;
        color: #059669;
    }
    
    .role-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.5rem;
    }
    
    .role-description {
        color: #6B7280;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        line-height: 1.6;
    }
    
    .role-stats {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1rem;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 10px;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary);
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: #6B7280;
    }
    
    .role-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        flex: 1;
        padding: 0.625rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
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
    
    .btn-delete {
        background: #FEE2E2;
        color: #DC2626;
        border: none;
        cursor: pointer;
    }
    
    .btn-delete:hover {
        background: #FECACA;
    }
    
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .roles-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user-tag"></i>
        إدارة الأدوار
    </h1>
    <a href="{{ route('admin.roles.create') }}" class="btn-add">
        <i class="fas fa-plus"></i>
        إضافة دور جديد
    </a>
</div>

<div class="roles-grid">
    @forelse($roles as $role)
    <div class="role-card">
        <div class="role-header">
            <div class="role-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <span class="role-badge {{ $role->is_system ? 'system' : 'custom' }}">
                {{ $role->is_system ? 'نظامي' : 'مخصص' }}
            </span>
        </div>
        
        <h3 class="role-name">{{ $role->display_name }}</h3>
        <p class="role-description">{{ $role->description ?? 'لا يوجد وصف' }}</p>
        
        <div class="role-stats">
            <div class="stat-item">
                <div class="stat-value">{{ $role->users_count }}</div>
                <div class="stat-label">مستخدم</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $role->permissions_count }}</div>
                <div class="stat-label">صلاحية</div>
            </div>
        </div>
        
        <div class="role-actions">
            <a href="{{ route('admin.roles.show', $role) }}" class="btn-action btn-view">
                <i class="fas fa-eye"></i>
                عرض
            </a>
            <a href="{{ route('admin.roles.edit', $role) }}" class="btn-action btn-edit">
                <i class="fas fa-edit"></i>
                تعديل
            </a>
            @if(!$role->is_system)
            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="flex: 1;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action btn-delete" style="width: 100%;">
                    <i class="fas fa-trash"></i>
                    حذف
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
        <i class="fas fa-user-tag" style="font-size: 3rem; color: #9CA3AF; margin-bottom: 1rem;"></i>
        <p style="color: #6B7280;">لا توجد أدوار حالياً</p>
    </div>
    @endforelse
</div>

<div style="margin-top: 2rem;">
    {{ $roles->links() }}
</div>
@endsection

