@extends('errors.layout')

@section('title', 'الموقع تحت الصيانة - منصة دوميرا')

@section('content')
    <div class="error-code">503</div>
    <div class="error-icon-wrap">
        <i class="fas fa-tools"></i>
    </div>
    <h1 class="error-title">الموقع تحت الصيانة</h1>
    <p class="error-message">
        نعمل على تحسين تجربتك. سنعود خلال وقت قصير. شكراً لصبرك.
    </p>
    <div class="error-actions">
        <a href="javascript:location.reload()" class="error-btn error-btn-primary">
            <i class="fas fa-redo"></i>
            إعادة المحاولة
        </a>
        <a href="{{ url('/') }}" class="error-btn error-btn-secondary">
            <i class="fas fa-home"></i>
            الرئيسية
        </a>
    </div>
@endsection
