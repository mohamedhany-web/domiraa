@extends('errors.layout')

@section('title', 'الصفحة غير موجودة - منصة دوميرا')

@section('content')
    <div class="error-code">404</div>
    <div class="error-icon-wrap">
        <i class="fas fa-map-marked-alt"></i>
    </div>
    <h1 class="error-title">الصفحة غير موجودة</h1>
    <p class="error-message">
        عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها. تأكد من الرابط أو عد إلى الرئيسية للبحث عن الوحدة المناسبة.
    </p>
    <div class="error-actions">
        <a href="{{ url('/') }}" class="error-btn error-btn-primary">
            <i class="fas fa-home"></i>
            الرئيسية
        </a>
        <a href="{{ url('/search') }}" class="error-btn error-btn-secondary">
            <i class="fas fa-search"></i>
            البحث عن عقارات
        </a>
    </div>
@endsection
