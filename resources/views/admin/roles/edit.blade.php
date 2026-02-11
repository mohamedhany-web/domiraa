@extends('layouts.admin')

@section('title', 'تعديل الدور: ' . $role->display_name)

@push('styles')
<style>
    .page-header {
        margin-bottom: 2rem;
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
    
    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .form-section-title {
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
    
    .form-section-title i {
        color: var(--primary);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input, .form-textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .form-input:disabled {
        background: #F3F4F6;
        cursor: not-allowed;
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
        text-transform: capitalize;
    }
    
    .permission-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
    }
    
    .permission-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary);
    }
    
    .permission-item label {
        cursor: pointer;
        color: #374151;
        font-size: 0.9rem;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #E5E7EB;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
    }
    
    .btn-cancel {
        background: #F3F4F6;
        color: #374151;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: #E5E7EB;
    }
    
    .system-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: #FEE2E2;
        color: #DC2626;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-right: 0.5rem;
    }
    
    .select-all-wrapper {
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: rgba(107, 137, 128, 0.1);
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-edit"></i>
        تعديل الدور: {{ $role->display_name }}
        @if($role->is_system)
        <span class="system-badge">نظامي</span>
        @endif
    </h1>
</div>

<form action="{{ route('admin.roles.update', $role) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="form-card">
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-info-circle"></i>
                معلومات الدور
            </h2>
            
            <div class="form-group">
                <label class="form-label">اسم الدور (بالإنجليزية) *</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $role->name) }}" 
                       {{ $role->is_system ? 'disabled' : '' }}
                       placeholder="مثال: content_manager" required pattern="[a-z_]+">
                @if($role->is_system)
                <p style="color: #6B7280; font-size: 0.875rem; margin-top: 0.5rem;">
                    لا يمكن تغيير اسم الأدوار النظامية
                </p>
                @endif
                @error('name')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">الاسم المعروض (بالعربية) *</label>
                <input type="text" name="display_name" class="form-input" value="{{ old('display_name', $role->display_name) }}" 
                       placeholder="مثال: مدير المحتوى" required>
                @error('display_name')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-textarea" rows="3" placeholder="وصف مختصر للدور ومسؤولياته">{{ old('description', $role->description) }}</textarea>
            </div>
        </div>
        
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-key"></i>
                الصلاحيات
            </h2>
            
            <div class="select-all-wrapper">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" id="selectAll" style="width: 18px; height: 18px; accent-color: var(--primary);">
                    <span style="font-weight: 600;">تحديد الكل / إلغاء التحديد</span>
                </label>
            </div>
            
            <div class="permissions-grid">
                @foreach($permissions as $group => $groupPermissions)
                <div class="permission-group">
                    <h3 class="permission-group-title">
                        {{ $group ?? 'عام' }}
                    </h3>
                    @foreach($groupPermissions as $permission)
                    <div class="permission-item">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                               id="permission_{{ $permission->id }}"
                               {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                        <label for="permission_{{ $permission->id }}">{{ $permission->display_name }}</label>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="form-actions">
            <a href="{{ route('admin.roles.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                حفظ التعديلات
            </button>
        </div>
    </div>
</form>

<script>
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
</script>
@endsection

