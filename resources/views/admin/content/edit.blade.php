@extends('layouts.admin')

@section('title', 'تعديل صفحة')
@section('page-title', 'تعديل صفحة')

@push('styles')
<style>
    .form-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.875rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 200px;
        font-family: inherit;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        font-weight: 700;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    .btn-cancel {
        background: #F3F4F6;
        color: #374151;
        font-weight: 700;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="form-section">
    <form method="POST" action="{{ route('admin.content.update', $contentPage) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">العنوان <span style="color: #DC2626;">*</span></label>
            <input type="text" name="title" class="form-input" value="{{ old('title', $contentPage->title) }}" required>
            @error('title')
                <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">الرابط (Slug) <span style="color: #DC2626;">*</span></label>
            <input type="text" name="slug" class="form-input" value="{{ old('slug', $contentPage->slug) }}" required>
            @error('slug')
                <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">النوع <span style="color: #DC2626;">*</span></label>
                <select name="type" class="form-select" required>
                    <option value="page" {{ old('type', $contentPage->type) == 'page' ? 'selected' : '' }}>صفحة</option>
                    <option value="faq" {{ old('type', $contentPage->type) == 'faq' ? 'selected' : '' }}>أسئلة شائعة</option>
                    <option value="terms" {{ old('type', $contentPage->type) == 'terms' ? 'selected' : '' }}>شروط الاستخدام</option>
                    <option value="privacy" {{ old('type', $contentPage->type) == 'privacy' ? 'selected' : '' }}>سياسة الخصوصية</option>
                    <option value="banner" {{ old('type', $contentPage->type) == 'banner' ? 'selected' : '' }}>بانر</option>
                </select>
                @error('type')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">الترتيب</label>
                <input type="number" name="order" class="form-input" value="{{ old('order', $contentPage->order) }}" min="0">
                @error('order')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">المحتوى <span style="color: #DC2626;">*</span></label>
            <textarea name="content" class="form-textarea" required>{{ old('content', $contentPage->content) }}</textarea>
            @error('content')
                <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $contentPage->is_active) ? 'checked' : '' }}>
                <span class="form-label" style="margin: 0;">نشط</span>
            </label>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save" style="margin-left: 0.5rem;"></i>
                حفظ التغييرات
            </button>
            <a href="{{ route('admin.content') }}" class="btn-cancel">
                <i class="fas fa-times" style="margin-left: 0.5rem;"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection



