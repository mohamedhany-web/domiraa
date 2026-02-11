@extends('layouts.owner')

@section('title', 'الإعدادات - منصة دوميرا')
@section('page-title', 'الإعدادات')

@section('content')
<div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 1.5rem;">
        <i class="fas fa-cog ml-2"></i>
        الإعدادات العامة
    </h2>
    
    <form action="{{ route('owner.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">اللغة</label>
            <select name="language" required style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem;">
                <option value="ar" {{ old('language', $user->language ?? 'ar') == 'ar' ? 'selected' : '' }}>العربية</option>
                <option value="en" {{ old('language', $user->language ?? 'ar') == 'en' ? 'selected' : '' }}>English</option>
            </select>
        </div>
        
        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">طريقة التواصل المفضلة</label>
            <select name="preferred_contact" required style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem;">
                <option value="email" {{ old('preferred_contact', $user->preferred_contact ?? 'both') == 'email' ? 'selected' : '' }}>البريد الإلكتروني</option>
                <option value="phone" {{ old('preferred_contact', $user->preferred_contact ?? 'both') == 'phone' ? 'selected' : '' }}>الهاتف</option>
                <option value="both" {{ old('preferred_contact', $user->preferred_contact ?? 'both') == 'both' ? 'selected' : '' }}>كلاهما</option>
            </select>
        </div>
        
        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 1rem;">الإشعارات</label>
            
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; padding: 1rem; background: #F9FAFB; border-radius: 8px;">
                <input type="checkbox" name="notification_email" id="notification_email" {{ old('notification_email', $user->notification_email ?? true) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                <label for="notification_email" style="font-weight: 600; color: #374151; cursor: pointer;">الإشعارات عبر البريد الإلكتروني</label>
            </div>
            
            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #F9FAFB; border-radius: 8px;">
                <input type="checkbox" name="notification_sms" id="notification_sms" {{ old('notification_sms', $user->notification_sms ?? false) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                <label for="notification_sms" style="font-weight: 600; color: #374151; cursor: pointer;">الإشعارات عبر الرسائل النصية</label>
            </div>
        </div>
        
        <button type="submit" style="background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; padding: 0.875rem 2rem; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);">
            <i class="fas fa-save ml-2"></i>
            حفظ الإعدادات
        </button>
    </form>
</div>
@endsection



