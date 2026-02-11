@extends('layouts.app')

@section('title', 'استفساراتي - منصة دوميرا')

@push('styles')
<style>
    .inquiry-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .inquiry-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .inquiry-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .inquiry-property {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.5rem;
    }
    
    .inquiry-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        color: #6B7280;
        font-size: 0.875rem;
    }
    
    .inquiry-message {
        color: #374151;
        line-height: 1.8;
        margin-bottom: 1rem;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 8px;
    }
    
    .inquiry-answer {
        margin-top: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, #DBEAFE 0%, #EFF6FF 100%);
        border-radius: 8px;
        border-right: 4px solid #1d313f;
    }
    
    .inquiry-answer h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .inquiry-answer p {
        color: #374151;
        line-height: 1.8;
        margin: 0;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .badge-answered {
        background: #D1FAE5;
        color: #065F46;
    }
    
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #9CA3AF;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="font-size: 2rem; font-weight: 800; color: #1F2937;">استفساراتي</h1>
        </div>
        
        @if($inquiries->count() > 0)
        <div>
            @foreach($inquiries as $inquiry)
            <div class="inquiry-card">
                <div class="inquiry-header">
                    <div style="flex: 1;">
                        <div class="inquiry-property">
                            <i class="fas fa-building" style="margin-left: 0.5rem; color: #1d313f;"></i>
                            {{ $inquiry->property->address ?? 'وحدة محذوفة' }}
                        </div>
                        <div class="inquiry-meta">
                            <span><i class="fas fa-hashtag" style="margin-left: 0.25rem;"></i> {{ $inquiry->property_code }}</span>
                            <span><i class="fas fa-clock" style="margin-left: 0.25rem;"></i> {{ $inquiry->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                    <span class="badge {{ $inquiry->status === 'pending' ? 'badge-pending' : 'badge-answered' }}">
                        {{ $inquiry->status === 'pending' ? 'قيد الانتظار' : 'تم الرد' }}
                    </span>
                </div>
                
                <div class="inquiry-message">
                    {{ $inquiry->message }}
                </div>
                
                @if($inquiry->answer)
                <div class="inquiry-answer">
                    <h4>
                        <i class="fas fa-reply"></i>
                        الرد من المالك:
                    </h4>
                    <p>{{ $inquiry->answer }}</p>
                </div>
                @else
                <div style="padding: 1rem; background: #FEF3C7; border-radius: 8px; color: #D97706; text-align: center;">
                    <i class="fas fa-clock" style="margin-left: 0.5rem;"></i>
                    في انتظار الرد من المالك
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-comments"></i>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">لا توجد استفسارات</h3>
            <p style="color: #6B7280; margin-bottom: 2rem;">لم تقم بإرسال أي استفسارات بعد</p>
            <a href="{{ route('search') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2rem; background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 700;">
                <i class="fas fa-search"></i>
                تصفح الوحدات
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

