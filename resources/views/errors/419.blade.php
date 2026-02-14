@extends('errors.layout')

@section('title', 'انتهت الجلسة - منصة دوميرا')

@section('content')
    <div class="error-code">419</div>
    <div class="error-icon-wrap">
        <i class="fas fa-clock"></i>
    </div>
    <h1 class="error-title">انتهت صلاحية الجلسة</h1>
    <p class="error-message">
        لسلامتك، انتهت صلاحية الجلسة. يرجى تحديث الصفحة وإعادة المحاولة. إذا كنت تملأ نموذجاً، قد تحتاج لإدخال البيانات مرة أخرى.
    </p>
    <div class="error-actions">
        <a href="javascript:location.reload()" class="error-btn error-btn-primary">
            <i class="fas fa-redo"></i>
            تحديث الصفحة
        </a>
        <a href="{{ url('/') }}" class="error-btn error-btn-secondary">
            <i class="fas fa-home"></i>
            الرئيسية
        </a>
    </div>
@endsection
