@extends('layouts.admin')

@section('title', 'إدارة أنواع الوحدة')
@section('page-title', 'إدارة أنواع الوحدة')

@push('styles')
<style>
    .property-types-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .card-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table thead {
        background: #F9FAFB;
    }
    
    .table th {
        padding: 1rem;
        text-align: right;
        font-weight: 700;
        color: #374151;
        border-bottom: 2px solid #E5E7EB;
    }
    
    .table td {
        padding: 1rem;
        border-bottom: 1px solid #E5E7EB;
        color: #6B7280;
    }
    
    .table tr:hover {
        background: #F9FAFB;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .badge-active {
        background: #D1FAE5;
        color: #065F46;
    }
    
    .badge-inactive {
        background: #FEE2E2;
        color: #991B1B;
    }
    
    .btn-group {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-sm {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }
    
    .btn-edit {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .btn-edit:hover {
        background: #FDE68A;
    }
    
    .btn-delete {
        background: #FEE2E2;
        color: #991B1B;
    }
    
    .btn-delete:hover {
        background: #FECACA;
    }
    
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    
    .modal.active {
        display: flex;
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .alert {
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    
    .alert-success {
        background: #D1FAE5;
        color: #065F46;
        border: 1px solid #6b8980;
    }
    
    .alert-error {
        background: #FEE2E2;
        color: #991B1B;
        border: 1px solid #EF4444;
    }
</style>
@endpush

@section('content')
<div class="property-types-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-tags"></i>
                أنواع الوحدة
            </h2>
            <button class="btn-primary" onclick="openModal()">
                <i class="fas fa-plus"></i>
                إضافة نوع جديد
            </button>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif
        
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>المعرف</th>
                    <th>الأيقونة</th>
                    <th>الترتيب</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($propertyTypes as $type)
                <tr>
                    <td style="font-weight: 700; color: #1F2937;">{{ $type->name }}</td>
                    <td><code style="background: #F3F4F6; padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $type->slug }}</code></td>
                    <td>
                        @if($type->icon)
                        <i class="{{ $type->icon }}" style="font-size: 1.5rem; color: var(--primary);"></i>
                        @else
                        <span style="color: #9CA3AF;">-</span>
                        @endif
                    </td>
                    <td>{{ $type->sort_order }}</td>
                    <td>
                        <span class="badge {{ $type->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $type->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button class="btn-sm btn-edit" onclick="editType({{ $type->id }}, '{{ $type->name }}', '{{ $type->slug }}', '{{ $type->icon ?? '' }}', {{ $type->sort_order }}, {{ $type->is_active ? 'true' : 'false' }})">
                                <i class="fas fa-edit"></i>
                                تعديل
                            </button>
                            <form action="{{ route('admin.property-types.destroy', $type) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا النوع؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-delete">
                                    <i class="fas fa-trash"></i>
                                    حذف
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: #9CA3AF;">
                        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                        لا توجد أنواع وحدات
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="typeModal">
    <div class="modal-content">
        <div class="card-header">
            <h2 class="card-title" id="modalTitle">
                <i class="fas fa-plus"></i>
                إضافة نوع جديد
            </h2>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; color: #6B7280; cursor: pointer;">&times;</button>
        </div>
        
        <form id="typeForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="form-group">
                <label class="form-label">الاسم <span style="color: #EF4444;">*</span></label>
                <input type="text" name="name" id="name" class="form-input" required placeholder="مثال: سكني">
            </div>
            
            <div class="form-group">
                <label class="form-label">المعرف (Slug) <span style="color: #EF4444;">*</span></label>
                <input type="text" name="slug" id="slug" class="form-input" required placeholder="مثال: residential">
                <small style="color: #6B7280; font-size: 0.875rem;">يستخدم في الكود، يجب أن يكون فريداً</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">الأيقونة</label>
                <input type="text" name="icon" id="icon" class="form-input" placeholder="مثال: fas fa-home">
                <small style="color: #6B7280; font-size: 0.875rem;">اسم class للأيقونة من Font Awesome</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">ترتيب العرض</label>
                <input type="number" name="sort_order" id="sort_order" class="form-input" value="0" min="0">
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <span style="margin-right: 0.5rem;">نشط</span>
                </label>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i>
                    حفظ
                </button>
                <button type="button" onclick="closeModal()" style="flex: 1; background: #F3F4F6; color: #374151; padding: 0.75rem 1.5rem; border-radius: 10px; border: none; font-weight: 700; cursor: pointer;">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let editingId = null;

function openModal() {
    editingId = null;
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> إضافة نوع جديد';
    document.getElementById('typeForm').reset();
    document.getElementById('typeForm').action = '{{ route("admin.property-types.store") }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('typeModal').classList.add('active');
}

function closeModal() {
    document.getElementById('typeModal').classList.remove('active');
    editingId = null;
}

function editType(id, name, slug, icon, sortOrder, isActive) {
    editingId = id;
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> تعديل نوع الوحدة';
    document.getElementById('name').value = name;
    document.getElementById('slug').value = slug;
    document.getElementById('icon').value = icon || '';
    document.getElementById('sort_order').value = sortOrder;
    document.getElementById('is_active').checked = isActive;
    document.getElementById('typeForm').action = '{{ route("admin.property-types.update", ":id") }}'.replace(':id', id);
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('typeModal').classList.add('active');
}

// Auto-generate slug from name
document.getElementById('name')?.addEventListener('input', function() {
    if (!editingId) {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        document.getElementById('slug').value = slug;
    }
});

// Close modal on outside click
document.getElementById('typeModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection

