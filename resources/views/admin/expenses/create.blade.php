@extends('layouts.admin')

@section('title', 'إضافة مصروف')
@section('page-title', 'إضافة مصروف جديد')

@push('styles')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .form-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .form-header h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
    }
    
    .form-header p {
        color: #6B7280;
        margin: 0;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: var(--primary);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-group.full {
        grid-column: 1 / -1;
    }
    
    .form-group label {
        font-weight: 600;
        color: #374151;
        font-size: 0.9rem;
    }
    
    .form-group label .required {
        color: #DC2626;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.75rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 10px;
    }
    
    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: var(--primary);
    }
    
    .category-select {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }
    
    .category-option {
        padding: 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .category-option:hover {
        border-color: var(--primary);
    }
    
    .category-option.selected {
        border-color: var(--primary);
        background: rgba(29, 49, 63, 0.05);
    }
    
    .category-option input {
        display: none;
    }
    
    .category-option i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .category-option span {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1.5rem;
        border-top: 2px solid #F3F4F6;
    }
    
    .btn {
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    .btn-secondary {
        background: #F3F4F6;
        color: #374151;
        text-decoration: none;
    }
    
    .btn-secondary:hover {
        background: #E5E7EB;
    }
    
    .error-message {
        color: #DC2626;
        font-size: 0.85rem;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .category-select {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="form-header">
        <h2><i class="fas fa-file-invoice-dollar"></i> إضافة مصروف جديد</h2>
        <p>سجل مصروفاتك وتتبع نفقاتك بسهولة</p>
    </div>
    
    <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- فئة المصروف -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-tag"></i>
                فئة المصروف
            </h3>
            <div class="category-select">
                @foreach($categories as $category)
                <label class="category-option {{ old('category_id') == $category->id ? 'selected' : '' }}">
                    <input type="radio" name="category_id" value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'checked' : '' }} required>
                    <i class="fas {{ $category->icon ?? 'fa-tag' }}" style="color: {{ $category->color ?? '#6B7280' }};"></i>
                    <span>{{ $category->name }}</span>
                </label>
                @endforeach
            </div>
            @error('category_id')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <!-- التفاصيل الأساسية -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-info-circle"></i>
                التفاصيل الأساسية
            </h3>
            <div class="form-grid">
                <div class="form-group full">
                    <label>عنوان المصروف <span class="required">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="مثال: صيانة المكتب" required>
                    @error('title')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>المبلغ <span class="required">*</span></label>
                    <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0.01" placeholder="0.00" required>
                    @error('amount')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>تاريخ المصروف <span class="required">*</span></label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                    @error('expense_date')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>المحفظة <span class="required">*</span></label>
                    <select name="wallet_id" required>
                        <option value="">اختر المحفظة</option>
                        @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}" {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                            {{ $wallet->display_name }} ({{ number_format($wallet->balance, 2) }} ج.م)
                        </option>
                        @endforeach
                    </select>
                    @error('wallet_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>المورد/الجهة</label>
                    <input type="text" name="vendor" value="{{ old('vendor') }}" placeholder="اسم المورد أو الجهة">
                </div>
                
                <div class="form-group">
                    <label>رقم الفاتورة</label>
                    <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" placeholder="رقم الفاتورة (اختياري)">
                </div>
                
                <div class="form-group full">
                    <label>الوصف</label>
                    <textarea name="description" placeholder="تفاصيل إضافية عن المصروف...">{{ old('description') }}</textarea>
                </div>
                
                <div class="form-group full">
                    <label>إرفاق إيصال/فاتورة</label>
                    <input type="file" name="receipt" accept=".pdf,.jpg,.jpeg,.png">
                    <small style="color: #6B7280;">PDF, JPG, PNG - الحد الأقصى 5MB</small>
                </div>
                
                <div class="form-group full">
                    <div class="checkbox-group">
                        <input type="checkbox" name="auto_approve" id="auto_approve" value="1">
                        <label for="auto_approve" style="margin: 0; cursor: pointer;">اعتماد المصروف تلقائياً</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                حفظ المصروف
            </button>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('.category-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.category-option').forEach(opt => opt.classList.remove('selected'));
        this.classList.add('selected');
    });
});
</script>
@endsection

