@extends('layouts.admin')

@section('title', 'إدارة المحتوى')
@section('page-title', 'إدارة المحتوى')

@push('styles')
<style>
    .content-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        font-weight: 700;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }
    
    .content-card {
        background: #F9FAFB;
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid #E5E7EB;
        transition: all 0.3s ease;
    }
    
    .content-card:hover {
        background: #F3F4F6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .content-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .content-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.5rem;
    }
    
    .content-meta {
        font-size: 0.75rem;
        color: #6B7280;
    }
    
    .content-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-edit {
        background: #DBEAFE;
        color: #1d313f;
    }
    
    .btn-edit:hover {
        background: #BFDBFE;
    }
    
    .btn-delete {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .btn-delete:hover {
        background: #FECACA;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
    }
    
    .badge-active {
        background: #D1FAE5;
        color: #536b63;
    }
    
    .badge-inactive {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    @media (max-width: 768px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-file-alt"></i>
            صفحات المحتوى
        </h2>
        <a href="{{ route('admin.content.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            إضافة صفحة جديدة
        </a>
    </div>
    
    <div class="content-grid">
        @forelse($pages as $page)
        <div class="content-card">
            <div class="content-card-header">
                <div style="flex: 1;">
                    <h3 class="content-title">{{ $page->title }}</h3>
                    <div class="content-meta">
                        <span>النوع: {{ $page->type === 'page' ? 'صفحة' : ($page->type === 'faq' ? 'أسئلة شائعة' : ($page->type === 'terms' ? 'شروط الاستخدام' : ($page->type === 'privacy' ? 'سياسة الخصوصية' : 'بانر'))) }}</span>
                        <br>
                        <span>الرابط: /{{ $page->slug }}</span>
                    </div>
                </div>
                <div>
                    <span class="badge {{ $page->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $page->is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>
            </div>
            
            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                <a href="{{ route('admin.content.edit', $page) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i>
                    تعديل
                </a>
                <form method="POST" action="{{ route('admin.content.destroy', $page) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه الصفحة؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete">
                        <i class="fas fa-trash"></i>
                        حذف
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 3rem; color: #9CA3AF; grid-column: 1 / -1;">
            <i class="fas fa-file-alt" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p style="font-size: 1rem; font-weight: 600;">لا توجد صفحات</p>
        </div>
        @endforelse
    </div>
</div>
@endsection



