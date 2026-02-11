@extends('layouts.owner')

@section('title', 'حسابي - منصة دوميرا')
@section('page-title', 'حسابي')

@push('styles')
<style>
    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }
    
    .completion-bar {
        height: 8px;
        background: #E5E7EB;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    
    .completion-fill {
        height: 100%;
        background: linear-gradient(90deg, #1d313f 0%, #6b8980 100%);
        transition: width 0.3s ease;
    }
</style>
@endpush

@section('content')
<!-- Profile Completion -->
<div class="profile-card">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 1rem;">
        <i class="fas fa-user-circle ml-2"></i>
        اكتمال الحساب
    </h2>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
        <span style="color: #6B7280; font-weight: 600;">{{ $completion }}%</span>
        <span style="color: #6B7280; font-size: 0.875rem;">{{ $completion < 100 ? 'أكمل ملفك الشخصي' : 'مكتمل' }}</span>
    </div>
    <div class="completion-bar">
        <div class="completion-fill" style="width: {{ $completion }}%;"></div>
    </div>
</div>

<!-- Basic Information -->
<div class="profile-card">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 1.5rem;">
        <i class="fas fa-info-circle ml-2"></i>
        البيانات الأساسية
    </h2>
    
    <form action="{{ route('owner.account.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">الاسم</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;"
                       onfocus="this.style.borderColor='#1d313f'" onblur="this.style.borderColor='#E5E7EB'">
                @error('name')
                <span style="color: #EF4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;"
                       onfocus="this.style.borderColor='#1d313f'" onblur="this.style.borderColor='#E5E7EB'">
                @error('email')
                <span style="color: #EF4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">رقم الهاتف</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;"
                       onfocus="this.style.borderColor='#1d313f'" onblur="this.style.borderColor='#E5E7EB'">
                @error('phone')
                <span style="color: #EF4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <button type="submit" style="background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; padding: 0.875rem 2rem; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);">
            <i class="fas fa-save ml-2"></i>
            حفظ التغييرات
        </button>
    </form>
</div>

<!-- Account Status -->
<div class="profile-card">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 1.5rem;">
        <i class="fas fa-shield-alt ml-2"></i>
        حالة الحساب
    </h2>
    
    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #F9FAFB; border-radius: 12px; margin-bottom: 1rem;">
        <div style="width: 50px; height: 50px; background: {{ $user->is_verified ? 'linear-gradient(135deg, #6b8980 0%, #536b63 100%)' : 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
            <i class="fas {{ $user->is_verified ? 'fa-check' : 'fa-clock' }}"></i>
        </div>
        <div>
            <div style="font-weight: 700; color: #1F2937; margin-bottom: 0.25rem;">
                {{ $user->is_verified ? 'حساب موثق' : 'حساب غير موثق' }}
            </div>
            <div style="color: #6B7280; font-size: 0.875rem;">
                {{ $user->is_verified ? 'تم التحقق من حسابك' : 'يرجى رفع المستندات المطلوبة للتحقق' }}
            </div>
        </div>
    </div>
</div>

<!-- Documents Upload -->
<div class="profile-card">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 1.5rem;">
        <i class="fas fa-file-upload ml-2"></i>
        رفع المستندات
    </h2>
    
    <form action="{{ route('owner.account.upload-document') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">بطاقة شخصية</label>
                <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem;">
                <input type="hidden" name="document_type" value="id_card">
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">إثبات ملكية</label>
                <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem;">
                <input type="hidden" name="document_type" value="ownership_proof">
            </div>
        </div>
        
        <button type="submit" style="background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; padding: 0.875rem 2rem; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; margin-top: 1.5rem; box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);">
            <i class="fas fa-upload ml-2"></i>
            رفع المستندات
        </button>
    </form>
</div>

<!-- Change Password -->
<div class="profile-card">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 1.5rem;">
        <i class="fas fa-lock ml-2"></i>
        تغيير كلمة المرور
    </h2>
    
    <form action="{{ route('owner.account.change-password') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">كلمة المرور الحالية</label>
                <input type="password" name="current_password" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem;">
                @error('current_password')
                <span style="color: #EF4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">كلمة المرور الجديدة</label>
                <input type="password" name="password" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem;">
                @error('password')
                <span style="color: #EF4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem;">
            </div>
        </div>
        
        <button type="submit" style="background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; padding: 0.875rem 2rem; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);">
            <i class="fas fa-key ml-2"></i>
            تغيير كلمة المرور
        </button>
    </form>
</div>
@endsection



