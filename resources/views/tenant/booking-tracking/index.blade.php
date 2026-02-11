@extends('layouts.app')

@section('title', 'تتبع حالة الطلب - منصة دوميرا')

@push('styles')
<style>
    .tracking-form {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        max-width: 600px;
        margin: 0 auto;
    }
    
    .form-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1F2937;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    
    .form-subtitle {
        color: #6B7280;
        text-align: center;
        margin-bottom: 2rem;
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
    
    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: 'Cairo', sans-serif;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .btn-primary {
        width: 100%;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(29, 49, 63, 0.3);
    }
    
    .info-box {
        background: #DBEAFE;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-right: 4px solid #1d313f;
    }
    
    .info-box i {
        color: #1d313f;
        margin-left: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="tracking-form">
            <h1 class="form-title">
                <i class="fas fa-search"></i>
                تتبع حالة الطلب
            </h1>
            <p class="form-subtitle">أدخل رقم الهاتف للتحقق من جميع طلباتك</p>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <strong>ملاحظة:</strong> سيتم عرض جميع الطلبات المرتبطة برقم الهاتف الذي تدخله.
            </div>
            
            <form method="POST" action="{{ route('booking.track') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">رقم الهاتف</label>
                    <input type="tel" name="phone" class="form-input" 
                           placeholder="مثال: 01012345678" 
                           value="{{ old('phone') }}" 
                           required>
                    @error('phone')
                        <span style="color: #EF4444; font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search"></i>
                    تتبع الطلبات
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

