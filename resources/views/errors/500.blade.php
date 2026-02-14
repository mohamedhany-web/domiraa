@extends('errors.layout')

@section('title', 'خطأ في الخادم - منصة دوميرا')

@section('content')
    <div class="error-code">500</div>
    <div class="error-icon-wrap">
        <i class="fas fa-server"></i>
    </div>
    <h1 class="error-title">حدث خطأ غير متوقع</h1>
    <p class="error-message">
        عذراً، حدث خطأ من جانبنا. فريقنا يعمل على حل المشكلة. يرجى المحاولة بعد قليل أو العودة للرئيسية.
    </p>
    <div class="error-actions">
        <a href="{{ url('/') }}" class="error-btn error-btn-primary">
            <i class="fas fa-home"></i>
            الرئيسية
        </a>
        <a href="javascript:location.reload()" class="error-btn error-btn-secondary">
            <i class="fas fa-redo"></i>
            إعادة المحاولة
        </a>
    </div>
@endsection
