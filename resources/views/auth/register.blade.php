@extends('layouts.app')

@section('title', 'إنشاء حساب - منصة دوميرا')

@push('styles')
<style>
    .auth-page-wrapper {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        padding: 2rem 0;
        background: linear-gradient(135deg, #F9FAFB 0%, rgba(107, 137, 128, 0.08) 100%);
    }
    
    .auth-container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        padding: 0 1.5rem;
    }
    
    .auth-left {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        border-radius: 24px;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .auth-left::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    }
    
    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    
    .floating-shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        animation: float 20s infinite ease-in-out;
    }
    
    .shape-1 {
        width: 200px;
        height: 200px;
        top: 10%;
        right: 10%;
        animation-delay: 0s;
    }
    
    .shape-2 {
        width: 150px;
        height: 150px;
        bottom: 20%;
        left: 15%;
        animation-delay: 5s;
    }
    
    .shape-3 {
        width: 100px;
        height: 100px;
        top: 60%;
        right: 30%;
        animation-delay: 10s;
    }
    
    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -30px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    
    .auth-left-content {
        position: relative;
        z-index: 10;
        color: white;
    }
    
    .auth-logo {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .auth-logo i {
        font-size: 2rem;
        color: white;
    }
    
    .auth-left-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .auth-left-subtitle {
        font-size: 1rem;
        opacity: 0.95;
        line-height: 1.7;
        margin-bottom: 2rem;
    }
    
    .auth-features {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .auth-features li {
        padding: 0.875rem 0;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        font-size: 0.95rem;
        opacity: 0.95;
    }
    
    .auth-features li i {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.9rem;
    }
    
    .auth-right {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .auth-form-container {
        width: 100%;
        max-width: 420px;
    }
    
    .auth-form-header {
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .auth-form-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1F2937;
        margin-bottom: 0.5rem;
    }
    
    .auth-form-subtitle {
        color: #6B7280;
        font-size: 0.95rem;
    }
    
    .auth-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        max-height: 85vh;
        overflow-y: auto;
    }
    
    .auth-card::-webkit-scrollbar {
        width: 6px;
    }
    
    .auth-card::-webkit-scrollbar-track {
        background: #F3F4F6;
        border-radius: 10px;
    }
    
    .auth-card::-webkit-scrollbar-thumb {
        background: #D1D5DB;
        border-radius: 10px;
    }
    
    .auth-card::-webkit-scrollbar-thumb:hover {
        background: #9CA3AF;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-label {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.625rem;
        font-size: 0.875rem;
        gap: 0.5rem;
    }
    
    .form-label i {
        color: #1d313f;
        width: 18px;
        font-size: 0.9rem;
    }
    
    .form-input,
    .form-select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
        font-family: 'Cairo', sans-serif;
    }
    
    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        font-weight: 700;
        padding: 0.875rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(29, 49, 63, 0.3);
    }
    
    .auth-footer {
        text-align: center;
        padding-top: 1.25rem;
        border-top: 1px solid #E5E7EB;
    }
    
    .auth-footer p {
        color: #6B7280;
        margin-bottom: 0.625rem;
        font-size: 0.875rem;
    }
    
    .auth-footer a {
        color: #1d313f;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }
    
    .auth-footer a:hover {
        color: #6b8980;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .auth-container {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .auth-left {
            padding: 2rem;
            min-height: 250px;
        }
        
        .auth-left-title {
            font-size: 1.75rem;
        }
        
        .auth-features {
            display: none;
        }
        
        .auth-card {
            max-height: none;
        }
    }
    
    @media (max-width: 768px) {
        .auth-page-wrapper {
            padding: 1.5rem 0;
        }
        
        .auth-container {
            padding: 0 1rem;
            gap: 1.5rem;
        }
        
        .auth-left {
            padding: 1.5rem;
            min-height: 200px;
        }
        
        .auth-left-title {
            font-size: 1.5rem;
        }
        
        .auth-left-subtitle {
            font-size: 0.9rem;
        }
        
        .auth-logo {
            width: 60px;
            height: 60px;
            margin-bottom: 1.5rem;
        }
        
        .auth-logo i {
            font-size: 1.75rem;
        }
        
        .auth-card {
            padding: 1.5rem;
        }
        
        .auth-form-title {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .auth-page-wrapper {
            padding: 1rem 0;
        }
        
        .auth-left {
            padding: 1.25rem;
            min-height: 180px;
        }
        
        .auth-left-title {
            font-size: 1.25rem;
        }
        
        .auth-card {
            padding: 1.25rem;
        }
        
        .auth-form-title {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-page-wrapper">
    <div class="auth-container">
        <!-- Left Side - Content -->
        <div class="auth-left">
            <div class="floating-shapes">
                <div class="floating-shape shape-1"></div>
                <div class="floating-shape shape-2"></div>
                <div class="floating-shape shape-3"></div>
            </div>
            
            <div class="auth-left-content">
                <div class="auth-logo">
                    <i class="fas fa-home"></i>
                </div>
                <h1 class="auth-left-title">انضم إلينا الآن!</h1>
                <p class="auth-left-subtitle">
                    ابدأ رحلتك مع منصة دوميرا وارفع عقارك أو ابحث عن الوحدة المثالية لك
                </p>
                
                <ul class="auth-features">
                    <li>
                        <i class="fas fa-rocket"></i>
                        <span>انضم في دقائق</span>
                    </li>
                    <li>
                        <i class="fas fa-shield-alt"></i>
                        <span>حساب آمن ومحمي</span>
                    </li>
                    <li>
                        <i class="fas fa-handshake"></i>
                        <span>خدمة عملاء ممتازة</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="auth-right">
            <div class="auth-form-container">
                <div class="auth-form-header">
                    <h2 class="auth-form-title">إنشاء حساب جديد</h2>
                    <p class="auth-form-subtitle">املأ البيانات التالية لإنشاء حسابك</p>
                </div>
                
                <div class="auth-card">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                <span>الاسم الكامل</span>
                            </label>
                            <input type="text" name="name" class="form-input" 
                                   placeholder="أدخل اسمك الكامل" 
                                   value="{{ old('name') }}" 
                                   required
                                   autocomplete="name"
                                   maxlength="255">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                <span>البريد الإلكتروني</span>
                            </label>
                            <input type="email" name="email" class="form-input" 
                                   placeholder="example@email.com" 
                                   value="{{ old('email') }}" 
                                   required
                                   autocomplete="email"
                                   maxlength="255">
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                <span>رقم الهاتف</span>
                            </label>
                            <input type="tel" name="phone" class="form-input" 
                                   placeholder="01XXXXXXXXX" 
                                   value="{{ old('phone') }}" 
                                   required 
                                   autocomplete="tel"
                                   maxlength="20">
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-tag"></i>
                                <span>نوع الحساب</span>
                            </label>
                            <select name="role" class="form-select" required autocomplete="off">
                                <option value="">اختر نوع الحساب</option>
                                <option value="tenant" {{ old('role') == 'tenant' ? 'selected' : '' }}>مستأجر</option>
                                <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>مؤجر</option>
                            </select>
                            @error('role')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                <span>كلمة المرور</span>
                            </label>
                            <input type="password" name="password" class="form-input" 
                                   placeholder="أدخل كلمة المرور (6 أحرف على الأقل)" 
                                   required 
                                   autocomplete="new-password"
                                   minlength="6"
                                   maxlength="255">
                            <small class="text-gray-600 text-xs mt-1 block">
                                يجب أن تحتوي كلمة المرور على 6 أحرف على الأقل
                            </small>
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                <span>تأكيد كلمة المرور</span>
                            </label>
                            <input type="password" name="password_confirmation" class="form-input" 
                                   placeholder="أعد إدخال كلمة المرور" 
                                   required 
                                   autocomplete="new-password"
                                   minlength="6"
                                   maxlength="255">
                        </div>
                        
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-user-plus"></i>
                            <span>إنشاء الحساب</span>
                        </button>
                    </form>
                    
                    <div class="auth-footer">
                        <p>لديك حساب بالفعل؟</p>
                        <a href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>تسجيل الدخول</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


