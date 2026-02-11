@extends('layouts.admin')

@section('title', 'تفاصيل البلاغ')
@section('page-title', 'تفاصيل البلاغ')

@push('styles')
<style>
    .complaint-detail {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }
    
    .detail-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .detail-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .detail-label {
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .detail-value {
        color: #4B5563;
        font-size: 0.9rem;
    }
    
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
        min-height: 100px;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
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
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .badge-new {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .badge-under_review {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .badge-resolved {
        background: #D1FAE5;
        color: #536b63;
    }
</style>
@endpush

@section('content')
<div class="complaint-detail">
    <div class="detail-section">
        <div class="detail-label">عنوان البلاغ</div>
        <div class="detail-value" style="font-size: 1.125rem; font-weight: 700;">{{ $complaint->title }}</div>
    </div>
    
    <div class="detail-section">
        <div class="detail-label">الوصف</div>
        <div class="detail-value" style="background: #F9FAFB; padding: 1rem; border-radius: 8px; line-height: 1.6;">{{ $complaint->description }}</div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div class="detail-section">
            <div class="detail-label">من</div>
            <div class="detail-value">{{ $complaint->user->name }}</div>
        </div>
        
        @if($complaint->property)
        <div class="detail-section">
            <div class="detail-label">الوحدة</div>
            <div class="detail-value">{{ $complaint->property->code }}</div>
        </div>
        @endif
        
        @if($complaint->reportedUser)
        <div class="detail-section">
            <div class="detail-label">ضد</div>
            <div class="detail-value">{{ $complaint->reportedUser->name }}</div>
        </div>
        @endif
        
        <div class="detail-section">
            <div class="detail-label">النوع</div>
            <div class="detail-value">
                {{ $complaint->complaint_type === 'property' ? 'وحدة' : ($complaint->complaint_type === 'owner' ? 'مؤجر' : ($complaint->complaint_type === 'tenant' ? 'مستأجر' : 'أخرى')) }}
            </div>
        </div>
        
        <div class="detail-section">
            <div class="detail-label">الحالة</div>
            <div class="detail-value">
                <span class="badge badge-{{ $complaint->status }}">
                    {{ $complaint->status === 'new' ? 'جديد' : ($complaint->status === 'under_review' ? 'قيد المراجعة' : ($complaint->status === 'resolved' ? 'تم الحل' : 'مرفوض')) }}
                </span>
            </div>
        </div>
        
        <div class="detail-section">
            <div class="detail-label">التاريخ</div>
            <div class="detail-value">{{ $complaint->created_at->format('Y-m-d H:i') }}</div>
        </div>
    </div>
    
    @if($complaint->admin_response)
    <div class="detail-section">
        <div class="detail-label">رد الأدمن</div>
        <div class="detail-value" style="background: #E0E7FF; padding: 1rem; border-radius: 8px; border-right: 3px solid #1d313f;">{{ $complaint->admin_response }}</div>
    </div>
    @endif
</div>

<div class="form-section">
    <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">الحالة <span style="color: #DC2626;">*</span></label>
            <select name="status" class="form-select" required>
                <option value="new" {{ old('status', $complaint->status) == 'new' ? 'selected' : '' }}>جديد</option>
                <option value="under_review" {{ old('status', $complaint->status) == 'under_review' ? 'selected' : '' }}>قيد المراجعة</option>
                <option value="resolved" {{ old('status', $complaint->status) == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                <option value="rejected" {{ old('status', $complaint->status) == 'rejected' ? 'selected' : '' }}>مرفوض</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">الإجراء المتخذ <span style="color: #DC2626;">*</span></label>
            <select name="action_taken" class="form-select" required>
                <option value="none" {{ old('action_taken', $complaint->action_taken) == 'none' ? 'selected' : '' }}>لا يوجد</option>
                <option value="warning" {{ old('action_taken', $complaint->action_taken) == 'warning' ? 'selected' : '' }}>تحذير</option>
                <option value="suspend_property" {{ old('action_taken', $complaint->action_taken) == 'suspend_property' ? 'selected' : '' }}>إيقاف الوحدة</option>
                <option value="suspend_account" {{ old('action_taken', $complaint->action_taken) == 'suspend_account' ? 'selected' : '' }}>إيقاف الحساب</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">رد الأدمن</label>
            <textarea name="admin_response" class="form-textarea">{{ old('admin_response', $complaint->admin_response) }}</textarea>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save" style="margin-left: 0.5rem;"></i>
                حفظ التغييرات
            </button>
            <a href="{{ route('admin.complaints') }}" class="btn-cancel">
                <i class="fas fa-times" style="margin-left: 0.5rem;"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection



