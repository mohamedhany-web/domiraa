@extends('layouts.admin')

@section('title', 'فئات المصروفات')
@section('page-title', 'فئات المصروفات')

@push('styles')
<style>
    .categories-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .btn-back {
        background: #F3F4F6;
        color: #374151;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-add {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.25rem;
    }
    
    .category-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        position: relative;
        transition: all 0.2s ease;
    }
    
    .category-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    
    .category-card.inactive {
        opacity: 0.6;
    }
    
    .category-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .category-icon {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .category-info h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 0.25rem 0;
    }
    
    .category-info p {
        color: #6B7280;
        font-size: 0.85rem;
        margin: 0;
    }
    
    .category-stats {
        display: flex;
        gap: 1.5rem;
        padding: 1rem 0;
        border-top: 1px solid #F3F4F6;
        border-bottom: 1px solid #F3F4F6;
        margin-bottom: 1rem;
    }
    
    .stat-item {
        text-align: center;
        flex: 1;
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1F2937;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #6B7280;
    }
    
    .category-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        flex: 1;
        padding: 0.625rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .btn-edit {
        background: #EFF6FF;
        color: #2563EB;
    }
    
    .btn-edit:hover {
        background: #DBEAFE;
    }
    
    .btn-toggle {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .btn-toggle:hover {
        background: #FDE68A;
    }
    
    .btn-delete {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .btn-delete:hover {
        background: #FECACA;
    }
    
    .status-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .status-badge.active {
        background: #D1FAE5;
        color: #059669;
    }
    
    .status-badge.inactive {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6B7280;
        cursor: pointer;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.95rem;
    }
    
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .color-picker {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .color-option {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.2s ease;
    }
    
    .color-option:hover,
    .color-option.selected {
        border-color: #1F2937;
        transform: scale(1.1);
    }
    
    .icon-picker {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .icon-option {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid #E5E7EB;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        background: #F9FAFB;
    }
    
    .icon-option:hover,
    .icon-option.selected {
        border-color: var(--primary);
        background: rgba(29, 49, 63, 0.1);
    }
    
    .btn-submit {
        width: 100%;
        padding: 0.875rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1rem;
    }
    
    @media (max-width: 768px) {
        .categories-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="categories-header">
    <a href="{{ route('admin.expenses.index') }}" class="btn-back">
        <i class="fas fa-arrow-right"></i>
        العودة للمصروفات
    </a>
    <button onclick="openAddModal()" class="btn-add">
        <i class="fas fa-plus"></i>
        إضافة فئة
    </button>
</div>

@if(session('success'))
<div style="background: #D1FAE5; border: 1px solid #6b8980; color: #536b63; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background: #FEE2E2; border: 1px solid #DC2626; color: #DC2626; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<div class="categories-grid">
    @forelse($categories as $category)
    <div class="category-card {{ !$category->is_active ? 'inactive' : '' }}">
        <span class="status-badge {{ $category->is_active ? 'active' : 'inactive' }}">
            {{ $category->is_active ? 'نشط' : 'معطل' }}
        </span>
        
        <div class="category-header">
            <div class="category-icon" style="background: {{ $category->color ?? '#6B7280' }};">
                <i class="fas {{ $category->icon ?? 'fa-tag' }}"></i>
            </div>
            <div class="category-info">
                <h3>{{ $category->name }}</h3>
                <p>{{ $category->name_en ?? '-' }}</p>
            </div>
        </div>
        
        <div class="category-stats">
            <div class="stat-item">
                <div class="stat-value">{{ $category->expenses_count }}</div>
                <div class="stat-label">مصروف</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($category->expenses_sum_amount ?? 0, 0) }}</div>
                <div class="stat-label">ج.م</div>
            </div>
        </div>
        
        <div class="category-actions">
            <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->name_en }}', '{{ $category->icon }}', '{{ $category->color }}', '{{ $category->description }}', {{ $category->is_active ? 'true' : 'false' }})" class="btn-action btn-edit">
                <i class="fas fa-edit"></i>
                تعديل
            </button>
            @if($category->expenses_count == 0)
            <form action="{{ route('admin.expenses.categories.destroy', $category) }}" method="POST" style="flex: 1;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #6B7280;">
        <i class="fas fa-tags" style="font-size: 4rem; color: #D1D5DB; margin-bottom: 1rem;"></i>
        <h3>لا توجد فئات</h3>
        <p>قم بإضافة فئة جديدة للمصروفات</p>
    </div>
    @endforelse
</div>

<!-- Modal إضافة فئة -->
<div class="modal-overlay" id="addModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">إضافة فئة جديدة</h3>
            <button class="modal-close" onclick="closeModal('addModal')">&times;</button>
        </div>
        <form action="{{ route('admin.expenses.categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>اسم الفئة (عربي)</label>
                <input type="text" name="name" required placeholder="مثال: صيانة">
            </div>
            <div class="form-group">
                <label>اسم الفئة (إنجليزي)</label>
                <input type="text" name="name_en" placeholder="مثال: Maintenance">
            </div>
            <div class="form-group">
                <label>الأيقونة</label>
                <div class="icon-picker" id="iconPicker">
                    @foreach(['fa-tools', 'fa-users', 'fa-building', 'fa-car', 'fa-bolt', 'fa-bullhorn', 'fa-paperclip', 'fa-plane', 'fa-utensils', 'fa-shopping-cart', 'fa-laptop', 'fa-phone', 'fa-gift', 'fa-heart', 'fa-star', 'fa-tag'] as $icon)
                    <div class="icon-option" data-icon="{{ $icon }}" onclick="selectIcon(this, 'iconInput')">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="icon" id="iconInput" value="fa-tag">
            </div>
            <div class="form-group">
                <label>اللون</label>
                <div class="color-picker" id="colorPicker">
                    @foreach(['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4', '#6B7280', '#1d313f', '#6b8980'] as $color)
                    <div class="color-option" style="background: {{ $color }};" data-color="{{ $color }}" onclick="selectColor(this, 'colorInput')"></div>
                    @endforeach
                </div>
                <input type="hidden" name="color" id="colorInput" value="#6B7280">
            </div>
            <div class="form-group">
                <label>الوصف (اختياري)</label>
                <textarea name="description" rows="2" placeholder="وصف مختصر للفئة"></textarea>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> حفظ الفئة
            </button>
        </form>
    </div>
</div>

<!-- Modal تعديل فئة -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">تعديل الفئة</h3>
            <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>اسم الفئة (عربي)</label>
                <input type="text" name="name" id="editName" required>
            </div>
            <div class="form-group">
                <label>اسم الفئة (إنجليزي)</label>
                <input type="text" name="name_en" id="editNameEn">
            </div>
            <div class="form-group">
                <label>الأيقونة</label>
                <div class="icon-picker" id="editIconPicker">
                    @foreach(['fa-tools', 'fa-users', 'fa-building', 'fa-car', 'fa-bolt', 'fa-bullhorn', 'fa-paperclip', 'fa-plane', 'fa-utensils', 'fa-shopping-cart', 'fa-laptop', 'fa-phone', 'fa-gift', 'fa-heart', 'fa-star', 'fa-tag'] as $icon)
                    <div class="icon-option" data-icon="{{ $icon }}" onclick="selectIcon(this, 'editIconInput')">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="icon" id="editIconInput">
            </div>
            <div class="form-group">
                <label>اللون</label>
                <div class="color-picker" id="editColorPicker">
                    @foreach(['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4', '#6B7280', '#1d313f', '#6b8980'] as $color)
                    <div class="color-option" style="background: {{ $color }};" data-color="{{ $color }}" onclick="selectColor(this, 'editColorInput')"></div>
                    @endforeach
                </div>
                <input type="hidden" name="color" id="editColorInput">
            </div>
            <div class="form-group">
                <label>الوصف (اختياري)</label>
                <textarea name="description" id="editDescription" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="editIsActive" value="1" style="width: 18px; height: 18px;">
                    الفئة نشطة
                </label>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> حفظ التعديلات
            </button>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function selectIcon(element, inputId) {
    const container = element.parentElement;
    container.querySelectorAll('.icon-option').forEach(opt => opt.classList.remove('selected'));
    element.classList.add('selected');
    document.getElementById(inputId).value = element.dataset.icon;
}

function selectColor(element, inputId) {
    const container = element.parentElement;
    container.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('selected'));
    element.classList.add('selected');
    document.getElementById(inputId).value = element.dataset.color;
}

function openEditModal(id, name, nameEn, icon, color, description, isActive) {
    document.getElementById('editForm').action = '/admin/expenses/categories/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editNameEn').value = nameEn || '';
    document.getElementById('editIconInput').value = icon || 'fa-tag';
    document.getElementById('editColorInput').value = color || '#6B7280';
    document.getElementById('editDescription').value = description || '';
    document.getElementById('editIsActive').checked = isActive;
    
    // تحديد الأيقونة
    document.querySelectorAll('#editIconPicker .icon-option').forEach(opt => {
        opt.classList.toggle('selected', opt.dataset.icon === icon);
    });
    
    // تحديد اللون
    document.querySelectorAll('#editColorPicker .color-option').forEach(opt => {
        opt.classList.toggle('selected', opt.dataset.color === color);
    });
    
    document.getElementById('editModal').classList.add('active');
}

// إغلاق Modal عند النقر خارجها
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});
</script>
@endsection

