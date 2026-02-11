@extends('layouts.admin')

@section('title', 'تعديل المصروف')
@section('page-title', 'تعديل المصروف')

@push('styles')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .form-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .btn-back {
        background: #F3F4F6;
        color: #374151;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
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
        font-size: 0.95rem;
    }
    
    .form-group label span {
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
    
    .current-receipt {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 10px;
        margin-top: 0.5rem;
    }
    
    .current-receipt img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .current-receipt a {
        color: var(--primary);
        font-weight: 600;
    }
    
    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        margin-top: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
    }
    
    .alert-error {
        background: #FEE2E2;
        color: #DC2626;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2 class="form-title"><i class="fas fa-edit"></i> تعديل المصروف</h2>
            <a href="{{ route('admin.expenses.index') }}" class="btn-back">
                <i class="fas fa-arrow-right"></i>
                العودة
            </a>
        </div>
        
        @if($errors->any())
        <div class="alert-error">
            <ul style="margin: 0; padding-right: 1rem;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('admin.expenses.update', $expense) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <div class="form-group">
                    <label>المحفظة <span>*</span></label>
                    <select name="wallet_id" required>
                        <option value="">اختر المحفظة</option>
                        @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}" {{ old('wallet_id', $expense->wallet_id) == $wallet->id ? 'selected' : '' }}>
                            {{ $wallet->display_name }} ({{ number_format($wallet->balance, 2) }} ج.م)
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>الفئة <span>*</span></label>
                    <select name="category_id" required>
                        <option value="">اختر الفئة</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group full">
                    <label>عنوان المصروف <span>*</span></label>
                    <input type="text" name="title" value="{{ old('title', $expense->title) }}" required placeholder="وصف مختصر للمصروف">
                </div>
                
                <div class="form-group">
                    <label>المبلغ <span>*</span></label>
                    <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" step="0.01" min="0.01" required>
                </div>
                
                <div class="form-group">
                    <label>تاريخ المصروف <span>*</span></label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                </div>
                
                <div class="form-group">
                    <label>المورد</label>
                    <input type="text" name="vendor" value="{{ old('vendor', $expense->vendor) }}" placeholder="اسم المورد أو المتجر">
                </div>
                
                <div class="form-group">
                    <label>رقم الفاتورة</label>
                    <input type="text" name="invoice_number" value="{{ old('invoice_number', $expense->invoice_number) }}" placeholder="رقم الفاتورة إن وجد">
                </div>
                
                <div class="form-group full">
                    <label>الوصف التفصيلي</label>
                    <textarea name="description" rows="3" placeholder="تفاصيل إضافية عن المصروف">{{ old('description', $expense->description) }}</textarea>
                </div>
                
                <div class="form-group full">
                    <label>الإيصال (PDF أو صورة)</label>
                    <input type="file" name="receipt" accept=".pdf,.jpg,.jpeg,.png">
                    
                    @if($expense->receipt_path)
                    <div class="current-receipt">
                        @if(in_array(pathinfo($expense->receipt_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ \App\Helpers\StorageHelper::url($expense->receipt_path) }}" alt="الإيصال الحالي" onerror="this.src='{{ \App\Helpers\StorageHelper::placeholder() }}'; this.onerror=null;">
                        @else
                        <i class="fas fa-file-pdf" style="font-size: 2rem; color: #DC2626;"></i>
                        @endif
                        <div>
                            <p style="margin: 0; font-weight: 600;">الإيصال الحالي</p>
                            <a href="{{ \App\Helpers\StorageHelper::url($expense->receipt_path) }}" target="_blank">عرض الملف</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                حفظ التعديلات
            </button>
        </form>
    </div>
</div>
@endsection

