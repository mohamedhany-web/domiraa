@extends('layouts.admin')

@section('title', 'تعديل حجز')
@section('page-title', 'تعديل حجز')

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
    .form-select {
        width: 100%;
        padding: 0.875rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        font-family: 'Cairo', sans-serif;
    }
    
    .form-input:focus,
    .form-select:focus {
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
    
    .btn-cancel:hover {
        background: #E5E7EB;
    }
    
    .info-box {
        background: #F0FDF4;
        border: 1px solid #6b8980;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
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
    @if($booking->payments->count() > 0)
    <div class="info-box">
        <p style="margin: 0; color: #536b63; font-weight: 600;">
            <i class="fas fa-info-circle"></i> 
            يوجد {{ $booking->payments->count() }} دفعة مرتبطة بهذا الحجز. يمكنك مراجعتها من صفحة المدفوعات.
        </p>
    </div>
    @endif
    
    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">الوحدة <span style="color: #DC2626;">*</span></label>
                <select name="property_id" class="form-select" required>
                    <option value="">اختر الوحدة</option>
                    @foreach($properties as $property)
                        <option value="{{ $property->id }}" {{ old('property_id', $booking->property_id) == $property->id ? 'selected' : '' }}>
                            {{ $property->address }} ({{ $property->code }}) - {{ $property->user->name }}
                        </option>
                    @endforeach
                </select>
                @error('property_id')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">المستأجر <span style="color: #DC2626;">*</span></label>
                <select name="user_id" class="form-select" required>
                    <option value="">اختر المستأجر</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $booking->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">تاريخ المعاينة <span style="color: #DC2626;">*</span></label>
                <input type="date" name="inspection_date" class="form-input" value="{{ old('inspection_date', $booking->inspection_date ? $booking->inspection_date->format('Y-m-d') : '') }}" required>
                @error('inspection_date')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">وقت المعاينة <span style="color: #DC2626;">*</span></label>
                @php
                    $timeValue = old('inspection_time', $booking->inspection_time);
                    if ($timeValue && is_object($timeValue)) {
                        $timeValue = $timeValue->format('H:i');
                    } elseif ($timeValue && is_string($timeValue) && strlen($timeValue) > 5) {
                        $timeValue = substr($timeValue, 0, 5);
                    }
                @endphp
                <input type="time" name="inspection_time" class="form-input" value="{{ $timeValue }}" required>
                @error('inspection_time')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">نوع الحجز <span style="color: #DC2626;">*</span></label>
                <select name="booking_type" class="form-select" required>
                    <option value="">اختر النوع</option>
                    <option value="inspection" {{ old('booking_type', $booking->booking_type) == 'inspection' ? 'selected' : '' }}>معاينة</option>
                    <option value="reservation" {{ old('booking_type', $booking->booking_type) == 'reservation' ? 'selected' : '' }}>حجز</option>
                </select>
                @error('booking_type')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">المبلغ <span style="color: #DC2626;">*</span></label>
                <input type="number" name="amount" class="form-input" value="{{ old('amount', $booking->amount) }}" step="0.01" min="0" required>
                @error('amount')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">حالة الدفع <span style="color: #DC2626;">*</span></label>
                <select name="payment_status" class="form-select" required>
                    <option value="pending" {{ old('payment_status', $booking->payment_status) == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="paid" {{ old('payment_status', $booking->payment_status) == 'paid' ? 'selected' : '' }}>مدفوع</option>
                    <option value="refunded" {{ old('payment_status', $booking->payment_status) == 'refunded' ? 'selected' : '' }}>مسترد</option>
                </select>
                @error('payment_status')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">حالة الحجز <span style="color: #DC2626;">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>مؤكدة</option>
                    <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>مكتملة</option>
                    <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                </select>
                @error('status')
                    <p style="color: #DC2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save" style="margin-left: 0.5rem;"></i>
                حفظ التغييرات
            </button>
            <a href="{{ route('admin.bookings') }}" class="btn-cancel">
                <i class="fas fa-times" style="margin-left: 0.5rem;"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection

