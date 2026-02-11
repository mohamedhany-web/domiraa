@extends('layouts.admin')

@section('title', 'الإعدادات العامة')
@section('page-title', 'الإعدادات العامة')

@push('styles')
<style>
    .settings-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .settings-nav {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .settings-nav a {
        padding: 0.875rem 1.5rem;
        background: white;
        border-radius: 10px;
        text-decoration: none;
        color: #374151;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.2s ease;
    }
    
    .settings-nav a:hover,
    .settings-nav a.active {
        background: var(--primary);
        color: white;
    }
    
    .settings-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }
    
    .settings-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .settings-card-header i {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
    
    .settings-card-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
    }
    
    .setting-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 10px;
        margin-bottom: 0.75rem;
    }
    
    .setting-row:last-child {
        margin-bottom: 0;
    }
    
    .setting-info {
        flex: 1;
    }
    
    .setting-label {
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.25rem;
    }
    
    .setting-desc {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .setting-input input,
    .setting-input select {
        padding: 0.625rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.95rem;
        min-width: 200px;
    }
    
    .setting-input input:focus,
    .setting-input select:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .btn-save {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
        transition: all 0.2s ease;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
    }
    
    .alert {
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert-success {
        background: #D1FAE5;
        color: #065F46;
    }
    
    @media (max-width: 768px) {
        .setting-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .setting-input input,
        .setting-input select {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-container">
    <div class="settings-nav">
        <a href="{{ route('admin.settings.index') }}" class="active">
            <i class="fas fa-cog"></i>
            الإعدادات العامة
        </a>
        <a href="{{ route('admin.settings.pricing') }}">
            <i class="fas fa-coins"></i>
            الأسعار والرسوم
        </a>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        
        @foreach($groups as $groupKey => $groupName)
        @if(isset($settings[$groupKey]) && $settings[$groupKey]->count() > 0)
        <div class="settings-card">
            <div class="settings-card-header">
                <i style="background: linear-gradient(135deg, var(--primary), var(--secondary));">
                    @if($groupKey == 'general')
                    <span class="fas fa-cog"></span>
                    @elseif($groupKey == 'contact')
                    <span class="fas fa-address-card"></span>
                    @elseif($groupKey == 'pricing')
                    <span class="fas fa-coins"></span>
                    @else
                    <span class="fas fa-sliders-h"></span>
                    @endif
                </i>
                <h3>{{ $groupName }}</h3>
            </div>
            
            @foreach($settings[$groupKey] as $setting)
            <div class="setting-row">
                <div class="setting-info">
                    <div class="setting-label">{{ $setting->display_name ?? $setting->key }}</div>
                    @if($setting->description)
                    <div class="setting-desc">{{ $setting->description }}</div>
                    @endif
                </div>
                <div class="setting-input">
                    @if($setting->type == 'boolean')
                    <select name="settings[{{ $setting->key }}]">
                        <option value="1" {{ $setting->value ? 'selected' : '' }}>نعم</option>
                        <option value="0" {{ !$setting->value ? 'selected' : '' }}>لا</option>
                    </select>
                    @elseif($setting->type == 'number')
                    <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" step="any">
                    @elseif(strpos($setting->key, 'social_') === 0 || strpos($setting->key, 'footer_') === 0)
                    <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" placeholder="@if(strpos($setting->key, 'social_') === 0) مثال: https://facebook.com/yourpage @elseif($setting->key == 'footer_phone') مثال: 01000000000 @endif" style="min-width: 300px;">
                    @else
                    <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @endforeach
        
        <button type="submit" class="btn-save">
            <i class="fas fa-save"></i>
            حفظ جميع الإعدادات
        </button>
    </form>
</div>
@endsection
