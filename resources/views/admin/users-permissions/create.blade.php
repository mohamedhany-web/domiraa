@extends('layouts.admin')

@section('title', 'إضافة مستخدم جديد')

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
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .permissions-section {
        margin-top: 2rem;
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
        flex: 1;
    }
    
    .permission-type {
        display: flex;
        gap: 0.5rem;
    }
    
    .permission-type label {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        cursor: pointer;
    }
    
    .permission-type input[type="radio"] {
        accent-color: var(--primary);
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
    }
    
    .btn-cancel:hover {
        background: #E5E7EB;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .permissions-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-submit, .btn-cancel {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user-plus"></i>
        إضافة مستخدم جديد
    </h1>
</div>

<form action="{{ route('admin.users-permissions.store') }}" method="POST">
    @csrf
    
    <div class="form-card">
        <div class="form-section">
            <h2 class="form-section-title">
                <i class="fas fa-user"></i>
                المعلومات الأساسية
            </h2>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">الاسم الكامل *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">البريد الإلكتروني *</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                    @error('email')
                        <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">رقم الهاتف *</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" required>
                    @error('phone')
                        <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">نوع الحساب *</label>
                    <select name="role" class="form-select" required>
                        <option value="">اختر نوع الحساب</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>مدير</option>
                        <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>مؤجر</option>
                        <option value="tenant" {{ old('role') === 'tenant' ? 'selected' : '' }}>مستأجر</option>
                    </select>
                    @error('role')
                        <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">الدور</label>
                    <select name="role_id" class="form-select">
                        <option value="">بدون دور محدد</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->display_name ?? $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">حالة الحساب *</label>
                    <select name="account_status" class="form-select" required>
                        <option value="active" {{ old('account_status') === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="pending" {{ old('account_status') === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                        <option value="suspended" {{ old('account_status') === 'suspended' ? 'selected' : '' }}>موقوف</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">كلمة المرور *</label>
                    <input type="password" name="password" class="form-input" required minlength="8">
                    <p style="font-size: 0.75rem; color: #6B7280; margin-top: 0.5rem;">يجب أن تحتوي على 8 أحرف على الأقل مع أحرف كبيرة وصغيرة وأرقام</p>
                    @error('password')
                        <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">تأكيد كلمة المرور *</label>
                    <input type="password" name="password_confirmation" class="form-input" required minlength="8">
                </div>
            </div>
        </div>
        
        <div class="form-section permissions-section">
            <h2 class="form-section-title">
                <i class="fas fa-key"></i>
                الصلاحيات المباشرة (اختياري)
            </h2>
            <p style="color: #6B7280; margin-bottom: 1.5rem; font-size: 0.9rem;">
                يمكنك منح أو منع صلاحيات محددة للمستخدم بغض النظر عن صلاحيات الدور المحدد له
            </p>
            
            <div class="permissions-grid">
                @forelse($permissions as $group => $groupPermissions)
                <div class="permission-group">
                    <h3 class="permission-group-title">{{ ucfirst($group) ?? 'عام' }}</h3>
                    @foreach($groupPermissions as $permission)
                    <div class="permission-item">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                               id="perm_{{ $permission->id }}"
                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                        <label for="perm_{{ $permission->id }}">{{ $permission->display_name ?? $permission->name }}</label>
                    </div>
                    @endforeach
                </div>
                @empty
                <p style="color: #6B7280;">لا توجد صلاحيات متاحة. قم بإنشاء صلاحيات أولاً.</p>
                @endforelse
            </div>
        </div>
        
        <div class="form-actions">
            <a href="{{ route('admin.users-permissions.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                إنشاء المستخدم
            </button>
        </div>
    </div>
</form>
@endsection

