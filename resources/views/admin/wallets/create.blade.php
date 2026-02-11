@extends('layouts.admin')

@section('title', 'إضافة محفظة')
@section('page-title', 'إضافة محفظة جديدة')

@push('styles')
<style>
    .form-container {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        max-width: 800px;
        margin: 0 auto;
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
        font-size: 1.1rem;
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
    
    .form-group.full-width {
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
        border-radius: 8px;
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
        border-radius: 8px;
    }
    
    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: var(--primary);
    }
    
    .checkbox-label {
        font-weight: 600;
        color: #374151;
    }
    
    .type-selector {
        display: flex;
        gap: 1rem;
    }
    
    .type-option {
        flex: 1;
        padding: 1.25rem;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease;
    }
    
    .type-option:hover {
        border-color: var(--primary);
    }
    
    .type-option.active {
        border-color: var(--primary);
        background: rgba(29, 49, 63, 0.05);
    }
    
    .type-option input {
        display: none;
    }
    
    .type-option i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .type-option.bank i { color: #3B82F6; }
    .type-option.cash i { color: #10B981; }
    
    .type-option span {
        font-weight: 700;
        color: #1F2937;
    }
    
    .bank-fields,
    .cash-fields {
        display: none;
    }
    
    .bank-fields.show,
    .cash-fields.show {
        display: block;
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
        border-radius: 8px;
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
        background: linear-gradient(135deg, #1d313f 0%, #2a4a5e 100%);
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
        margin-top: 0.25rem;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .type-selector {
            flex-direction: column;
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
        <h2><i class="fas fa-wallet"></i> إضافة محفظة جديدة</h2>
        <p>أضف محفظة مالية جديدة للمستخدم</p>
    </div>
    
    <form action="{{ route('admin.wallets.store') }}" method="POST">
        @csrf
        
        <!-- نوع المحفظة -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-credit-card"></i>
                نوع المحفظة
            </h3>
            
            <div class="type-selector">
                <label class="type-option bank active">
                    <input type="radio" name="type" value="bank" checked>
                    <i class="fas fa-university"></i>
                    <span>حساب بنكي</span>
                </label>
                <label class="type-option cash">
                    <input type="radio" name="type" value="mobile_wallet">
                    <i class="fas fa-mobile-alt"></i>
                    <span>محفظة نقدية</span>
                </label>
            </div>
        </div>
        
        <!-- معلومات أساسية -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-info-circle"></i>
                المعلومات الأساسية
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>المالك <span class="required">*</span></label>
                    <select name="user_id" required>
                        <option value="">اختر المالك</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->role == 'owner' ? 'مؤجر' : 'أدمن' }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>اسم المحفظة <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="مثل: فودافون كاش، انستا باي" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- بيانات الحساب البنكي -->
        <div class="form-section bank-fields show">
            <h3 class="section-title">
                <i class="fas fa-university"></i>
                بيانات الحساب البنكي
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>اسم البنك <span class="required">*</span></label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="مثال: بنك الراجحي">
                    @error('bank_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>رقم الحساب <span class="required">*</span></label>
                    <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="أدخل رقم الحساب" dir="ltr">
                    @error('account_number')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>اسم صاحب الحساب <span class="required">*</span></label>
                    <input type="text" name="account_name" value="{{ old('account_name') }}" placeholder="الاسم كما يظهر في البنك">
                    @error('account_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>رقم الآيبان (IBAN)</label>
                    <input type="text" name="iban" value="{{ old('iban') }}" placeholder="SA..." dir="ltr">
                    @error('iban')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- بيانات المحفظة النقدية -->
        <div class="form-section cash-fields">
            <h3 class="section-title">
                <i class="fas fa-mobile-alt"></i>
                بيانات المحفظة النقدية
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>رقم الهاتف <span class="required">*</span></label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="05xxxxxxxx" dir="ltr">
                    @error('phone_number')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- ملاحظات -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-sticky-note"></i>
                ملاحظات
            </h3>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>ملاحظات (اختياري)</label>
                    <textarea name="notes" placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                </div>
                
                <div class="form-group full-width">
                    <div class="checkbox-group">
                        <input type="checkbox" name="is_active" id="is_active" checked>
                        <label for="is_active" class="checkbox-label">تفعيل المحفظة فوراً</label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- أزرار الإجراءات -->
        <div class="form-actions">
            <a href="{{ route('admin.wallets') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                حفظ المحفظة
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeOptions = document.querySelectorAll('.type-option');
    const bankFields = document.querySelector('.bank-fields');
    const cashFields = document.querySelector('.cash-fields');
    
    typeOptions.forEach(option => {
        option.addEventListener('click', function() {
            typeOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            const selectedType = this.querySelector('input').value;
            
            if (selectedType === 'bank') {
                bankFields.classList.add('show');
                cashFields.classList.remove('show');
            } else if (selectedType === 'mobile_wallet') {
                bankFields.classList.remove('show');
                cashFields.classList.add('show');
            }
        });
    });
});
</script>
@endsection

