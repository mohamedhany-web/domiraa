@extends('errors.layout')

@section('title', 'غير مصرح - منصة دوميرا')

@section('content')
    <div class="error-code">403</div>
    <div class="error-icon-wrap">
        <i class="fas fa-lock"></i>
    </div>
    <h1 class="error-title">غير مصرح لك بالوصول</h1>
    <p class="error-message">
        عذراً، ليس لديك صلاحية لعرض هذه الصفحة. إذا كنت تعتقد أن هذا خطأ، يرجى التواصل مع الدعم.
    </p>
    <div class="error-actions">
        <a href="{{ url('/') }}" class="error-btn error-btn-primary">
            <i class="fas fa-home"></i>
            الرئيسية
        </a>
        <a href="{{ url()->previous() }}" class="error-btn error-btn-secondary">
            <i class="fas fa-arrow-right"></i>
            الرجوع
        </a>
    </div>
@endsection
