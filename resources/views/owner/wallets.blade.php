@extends('layouts.owner')

@section('title', 'المحافظ - منصة دوميرا')
@section('page-title', 'إدارة المحافظ')

@push('styles')
<style>
    .wallets-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .btn-add {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(29, 49, 63, 0.3);
    }
    
    .wallets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .wallet-card {
        background: linear-gradient(135deg, #F9FAFB 0%, #FFFFFF 100%);
        border: 2px solid #E5E7EB;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .wallet-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        border-color: #1d313f;
    }
    
    .wallet-card.inactive {
        opacity: 0.6;
        border-color: #D1D5DB;
    }
    
    .wallet-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .wallet-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1F2937;
        margin-bottom: 0.25rem;
    }
    
    .wallet-type {
        font-size: 0.875rem;
        color: #6B7280;
        padding: 0.25rem 0.75rem;
        background: #F3F4F6;
        border-radius: 8px;
        display: inline-block;
    }
    
    .wallet-status {
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .wallet-status.active {
        background: #D1FAE5;
        color: #536b63;
    }
    
    .wallet-status.inactive {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .wallet-details {
        margin-top: 1rem;
    }
    
    .wallet-detail-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #F3F4F6;
        font-size: 0.875rem;
    }
    
    .wallet-detail-item:last-child {
        border-bottom: none;
    }
    
    .wallet-detail-label {
        color: #6B7280;
        font-weight: 600;
    }
    
    .wallet-detail-value {
        color: #1F2937;
        font-weight: 500;
        text-align: left;
        word-break: break-all;
    }
    
    .wallet-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #F3F4F6;
    }
    
    .btn-action {
        flex: 1;
        padding: 0.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
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
    
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        overflow: auto;
    }
    
    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6B7280;
        cursor: pointer;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.875rem;
        font-family: 'Cairo', sans-serif;
    }
    
    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }
    
    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-checkbox input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #1d313f;
    }
    
    @media (max-width: 768px) {
        .wallets-grid {
            grid-template-columns: 1fr;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="wallets-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-wallet"></i>
            المحافظ
        </h2>
        <button type="button" class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i>
            إضافة محفظة جديدة
        </button>
    </div>
    
    @if($wallets->count() > 0)
    <div class="wallets-grid">
        @foreach($wallets as $wallet)
        <div class="wallet-card {{ !$wallet->is_active ? 'inactive' : '' }}">
            <div class="wallet-header">
                <div>
                    <div class="wallet-name">{{ $wallet->name }}</div>
                    <span class="wallet-type">
                        @if($wallet->type === 'bank')
                            <i class="fas fa-university"></i> بنك
                        @elseif($wallet->type === 'mobile_wallet')
                            <i class="fas fa-mobile-alt"></i> محفظة إلكترونية
                        @else
                            <i class="fas fa-wallet"></i> أخرى
                        @endif
                    </span>
                </div>
                <span class="wallet-status {{ $wallet->is_active ? 'active' : 'inactive' }}">
                    {{ $wallet->is_active ? 'نشطة' : 'غير نشطة' }}
                </span>
            </div>
            
            <div class="wallet-details">
                @if($wallet->bank_name)
                <div class="wallet-detail-item">
                    <span class="wallet-detail-label">اسم البنك:</span>
                    <span class="wallet-detail-value">{{ $wallet->bank_name }}</span>
                </div>
                @endif
                @if($wallet->account_name)
                <div class="wallet-detail-item">
                    <span class="wallet-detail-label">اسم صاحب الحساب:</span>
                    <span class="wallet-detail-value">{{ $wallet->account_name }}</span>
                </div>
                @endif
                @if($wallet->account_number)
                <div class="wallet-detail-item">
                    <span class="wallet-detail-label">رقم الحساب:</span>
                    <span class="wallet-detail-value">{{ $wallet->account_number }}</span>
                </div>
                @endif
                @if($wallet->iban)
                <div class="wallet-detail-item">
                    <span class="wallet-detail-label">رقم الآيبان:</span>
                    <span class="wallet-detail-value">{{ $wallet->iban }}</span>
                </div>
                @endif
                @if($wallet->phone_number)
                <div class="wallet-detail-item">
                    <span class="wallet-detail-label">رقم الهاتف:</span>
                    <span class="wallet-detail-value">{{ $wallet->phone_number }}</span>
                </div>
                @endif
                @if($wallet->notes)
                <div class="wallet-detail-item">
                    <span class="wallet-detail-label">ملاحظات:</span>
                    <span class="wallet-detail-value">{{ $wallet->notes }}</span>
                </div>
                @endif
            </div>
            
            <div class="wallet-actions">
                <button type="button" class="btn-action btn-edit" onclick="openEditModal({{ $wallet->id }}, '{{ $wallet->name }}', '{{ $wallet->bank_name ?? '' }}', '{{ $wallet->account_name ?? '' }}', '{{ $wallet->account_number ?? '' }}', '{{ $wallet->iban ?? '' }}', '{{ $wallet->phone_number ?? '' }}', '{{ $wallet->type }}', '{{ $wallet->notes ?? '' }}', {{ $wallet->is_active ? 'true' : 'false' }})">
                    <i class="fas fa-edit"></i> تعديل
                </button>
                <form method="POST" action="{{ route('owner.wallets.delete', $wallet) }}" style="flex: 1;" onsubmit="return confirm('هل أنت متأكد من حذف هذه المحفظة؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" style="width: 100%;">
                        <i class="fas fa-trash"></i> حذف
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align: center; padding: 4rem 2rem;">
        <i class="fas fa-wallet" style="font-size: 4rem; color: #9CA3AF; margin-bottom: 1.5rem; opacity: 0.5;"></i>
        <h3 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">لا توجد محافظ</h3>
        <p style="color: #6B7280; margin-bottom: 1.5rem;">ابدأ بإضافة محفظة جديدة لاستقبال المدفوعات</p>
        <button type="button" class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i>
            إضافة محفظة جديدة
        </button>
    </div>
    @endif
</div>

<!-- Add/Edit Modal -->
<div id="walletModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">إضافة محفظة جديدة</h3>
            <button type="button" class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <form id="walletForm" method="POST">
            @csrf
            <div id="formMethod" style="display: none;"></div>
            
            <div class="form-group">
                <label class="form-label">اسم المحفظة *</label>
                <input type="text" name="name" class="form-input" required placeholder="مثال: بنك مصر، فودافون كاش">
            </div>
            
            <div class="form-group">
                <label class="form-label">نوع المحفظة *</label>
                <select name="type" id="walletType" class="form-select" required onchange="toggleFields()">
                    <option value="">-- اختر النوع --</option>
                    <option value="bank">بنك</option>
                    <option value="mobile_wallet">محفظة إلكترونية</option>
                    <option value="other">أخرى</option>
                </select>
            </div>
            
            <div id="bankFields">
                <div class="form-group">
                    <label class="form-label">اسم البنك</label>
                    <input type="text" name="bank_name" class="form-input" placeholder="مثال: بنك مصر">
                </div>
                
                <div class="form-group">
                    <label class="form-label">اسم صاحب الحساب</label>
                    <input type="text" name="account_name" class="form-input" placeholder="اسم صاحب الحساب">
                </div>
                
                <div class="form-group">
                    <label class="form-label">رقم الحساب</label>
                    <input type="text" name="account_number" class="form-input" placeholder="رقم الحساب">
                </div>
                
                <div class="form-group">
                    <label class="form-label">رقم الآيبان</label>
                    <input type="text" name="iban" class="form-input" placeholder="رقم الآيبان">
                </div>
            </div>
            
            <div id="mobileFields" style="display: none;">
                <div class="form-group">
                    <label class="form-label">رقم الهاتف</label>
                    <input type="tel" name="phone_number" class="form-input" placeholder="01XXXXXXXXX">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-textarea" placeholder="ملاحظات إضافية (اختياري)"></textarea>
            </div>
            
            <div class="form-group" id="activeField" style="display: none;">
                <div class="form-checkbox">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label for="is_active">المحفظة نشطة</label>
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" onclick="closeModal()" style="padding: 0.75rem 1.5rem; background: #F3F4F6; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    إلغاء
                </button>
                <button type="submit" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 700;">
                    <i class="fas fa-check"></i> حفظ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleFields() {
    const type = document.getElementById('walletType').value;
    const bankFields = document.getElementById('bankFields');
    const mobileFields = document.getElementById('mobileFields');
    
    if (type === 'bank') {
        bankFields.style.display = 'block';
        mobileFields.style.display = 'none';
    } else if (type === 'mobile_wallet') {
        bankFields.style.display = 'none';
        mobileFields.style.display = 'block';
    } else {
        bankFields.style.display = 'block';
        mobileFields.style.display = 'block';
    }
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'إضافة محفظة جديدة';
    document.getElementById('walletForm').action = '{{ route("owner.wallets.store") }}';
    document.getElementById('formMethod').innerHTML = '';
    document.getElementById('walletForm').reset();
    document.getElementById('activeField').style.display = 'none';
    document.getElementById('walletModal').classList.add('active');
}

function openEditModal(id, name, bankName, accountName, accountNumber, iban, phoneNumber, type, notes, isActive) {
    document.getElementById('modalTitle').textContent = 'تعديل المحفظة';
    document.getElementById('walletForm').action = `/owner/wallets/${id}`;
    document.getElementById('formMethod').innerHTML = '@method("PUT")';
    
    document.querySelector('[name="name"]').value = name;
    document.querySelector('[name="bank_name"]').value = bankName;
    document.querySelector('[name="account_name"]').value = accountName;
    document.querySelector('[name="account_number"]').value = accountNumber;
    document.querySelector('[name="iban"]').value = iban;
    document.querySelector('[name="phone_number"]').value = phoneNumber;
    document.querySelector('[name="type"]').value = type;
    document.querySelector('[name="notes"]').value = notes;
    document.getElementById('is_active').checked = isActive;
    
    document.getElementById('activeField').style.display = 'block';
    toggleFields();
    document.getElementById('walletModal').classList.add('active');
}

function closeModal() {
    document.getElementById('walletModal').classList.remove('active');
    document.getElementById('walletForm').reset();
}

window.onclick = function(event) {
    const modal = document.getElementById('walletModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>
@endsection



