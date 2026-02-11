@extends('layouts.admin')

@section('title', 'إدارة الصلاحيات')

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
        min-width: 200px;
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
    
    .permissions-table {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
    }
    
    .permissions-table th, .permissions-table td {
        padding: 1rem 1.25rem;
        text-align: right;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .permissions-table th {
        background: #F9FAFB;
        font-weight: 700;
        color: #374151;
    }
    
    .permissions-table tr:hover {
        background: #F9FAFB;
    }
    
    .permissions-table tr:last-child td {
        border-bottom: none;
    }
    
    .permission-name {
        font-family: monospace;
        background: #F3F4F6;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
    }
    
    .group-badge {
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
        
        .filter-form {
            flex-direction: column;
        }
        
        .filter-group {
            width: 100%;
        }
        
        .permissions-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-key"></i>
        إدارة الصلاحيات
    </h1>
    <a href="{{ route('admin.permissions.create') }}" class="btn-add">
        <i class="fas fa-plus"></i>
        إضافة صلاحية
    </a>
</div>

<div class="filter-card">
    <form method="GET" action="{{ route('admin.permissions.index') }}" class="filter-form">
        <div class="filter-group">
            <label class="filter-label">البحث</label>
            <input type="text" name="search" class="filter-input" placeholder="ابحث بالاسم..." value="{{ request('search') }}">
        </div>
        <div class="filter-group">
            <label class="filter-label">المجموعة</label>
            <select name="group" class="filter-select">
                <option value="">الكل</option>
                @foreach($groups as $group)
                <option value="{{ $group }}" {{ request('group') === $group ? 'selected' : '' }}>{{ $group }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-filter">
            <i class="fas fa-search"></i>
            بحث
        </button>
    </form>
</div>

<table class="permissions-table">
    <thead>
        <tr>
            <th>الاسم التقني</th>
            <th>الاسم المعروض</th>
            <th>المجموعة</th>
            <th>الوصف</th>
            <th>الإجراءات</th>
        </tr>
    </thead>
    <tbody>
        @forelse($permissions as $permission)
        <tr>
            <td><code class="permission-name">{{ $permission->name }}</code></td>
            <td>{{ $permission->display_name }}</td>
            <td>
                @if($permission->group)
                <span class="group-badge">{{ $permission->group }}</span>
                @else
                <span style="color: #9CA3AF;">-</span>
                @endif
            </td>
            <td>{{ Str::limit($permission->description, 50) ?? '-' }}</td>
            <td>
                <div class="actions">
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه الصلاحية؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align: center; padding: 2rem;">
                <i class="fas fa-key" style="font-size: 2rem; color: #9CA3AF; margin-bottom: 0.5rem;"></i>
                <p style="color: #6B7280;">لا توجد صلاحيات</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 1.5rem;">
    {{ $permissions->withQueryString()->links() }}
</div>
@endsection

