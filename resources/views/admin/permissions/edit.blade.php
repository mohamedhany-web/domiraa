@extends('layouts.admin')

@section('title', 'تعديل الصلاحية: ' . $permission->display_name)

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
        max-width: 600px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
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
    
    .form-hint {
        font-size: 0.875rem;
        color: #6B7280;
        margin-top: 0.5rem;
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
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-edit"></i>
        تعديل الصلاحية: {{ $permission->display_name }}
    </h1>
</div>

<form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="form-card">
        <div class="form-group">
            <label class="form-label">الاسم التقني *</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $permission->name) }}" 
                   placeholder="مثال: users.create" required pattern="[a-z_.]+">
            <p class="form-hint">استخدم الأحرف الإنجليزية الصغيرة والنقاط فقط</p>
            @error('name')
                <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">الاسم المعروض (بالعربية) *</label>
            <input type="text" name="display_name" class="form-input" value="{{ old('display_name', $permission->display_name) }}" 
                   placeholder="مثال: إنشاء مستخدم" required>
            @error('display_name')
                <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">المجموعة</label>
            <input type="text" name="group" class="form-input" value="{{ old('group', $permission->group) }}" 
                   placeholder="مثال: users" list="groups">
            <datalist id="groups">
                @foreach($groups as $group)
                <option value="{{ $group }}">
                @endforeach
            </datalist>
        </div>
        
        <div class="form-group">
            <label class="form-label">الوصف</label>
            <textarea name="description" class="form-textarea" rows="3" placeholder="وصف مختصر للصلاحية">{{ old('description', $permission->description) }}</textarea>
        </div>
        
        <div class="form-actions">
            <a href="{{ route('admin.permissions.index') }}" class="btn-cancel">
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
@endsection

