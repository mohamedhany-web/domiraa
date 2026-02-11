@extends('layouts.admin')

@section('title', 'إدارة الاستفسارات')
@section('page-title', 'إدارة الاستفسارات')

@push('styles')
<style>
    /* Stats Grid - Same as Dashboard */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
        min-height: 140px;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .stat-icon.orange {
        background: linear-gradient(135deg, #FB923C 0%, #F97316 100%);
        color: white;
    }
    
    .stat-icon.green {
        background: linear-gradient(135deg, #8aa69d 0%, #6b8980 100%);
        color: white;
    }
    
    .stat-icon.blue {
        background: linear-gradient(135deg, #60A5FA 0%, #2a4456 100%);
        color: white;
    }
    
    .stat-content {
        flex: 1;
        margin-left: 1rem;
    }
    
    .stat-label {
        color: #6B7280;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #1F2937;
        margin-bottom: 0.5rem;
        line-height: 1;
    }
    
    /* Section */
    .inquiries-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .inquiry-card {
        background: #F9FAFB;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border-right: 4px solid #1d313f;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .inquiry-card:hover {
        background: #F3F4F6;
        transform: translateX(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .inquiry-card.pending {
        border-right-color: #F59E0B;
    }
    
    .inquiry-card.answered {
        border-right-color: #6b8980;
    }
    
    .inquiry-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .inquiry-info h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.5rem;
    }
    
    .inquiry-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
        color: #6B7280;
        flex-wrap: wrap;
    }
    
    .inquiry-message {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        color: #4B5563;
        line-height: 1.6;
    }
    
    .inquiry-answer {
        background: #E0E7FF;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
        border-right: 3px solid #1d313f;
    }
    
    .inquiry-answer h4 {
        font-size: 0.875rem;
        font-weight: 700;
        color: #1d313f;
        margin-bottom: 0.5rem;
    }
    
    .inquiry-answer p {
        color: #374151;
        font-size: 0.875rem;
    }
    
    .answer-form {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #E5E7EB;
    }
    
    .form-textarea {
        width: 100%;
        padding: 0.875rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.875rem;
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }
    
    .form-textarea:focus {
        outline: none;
        border-color: #1d313f;
        box-shadow: 0 0 0 3px rgba(29, 49, 63, 0.1);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%);
        color: white;
        font-weight: 700;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.75rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 49, 63, 0.3);
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .badge-answered {
        background: #D1FAE5;
        color: #536b63;
    }
    
    @media (max-width: 1400px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.875rem;
        }
        
        .stat-card {
            min-height: 110px;
            padding: 1rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
        
        .inquiry-header {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .inquiry-meta {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">قيد الانتظار</div>
                <div class="stat-value">{{ $pendingInquiries }}</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">تم الرد عليها</div>
                <div class="stat-value">{{ $answeredInquiries }}</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">إجمالي</div>
                <div class="stat-value">{{ $inquiries->count() }}</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-comments"></i>
            </div>
        </div>
    </div>
</div>

<!-- Inquiries List -->
<div class="inquiries-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-comments"></i>
            قائمة الاستفسارات
        </h2>
    </div>
    
    <div>
        @forelse($inquiries as $inquiry)
        <div class="inquiry-card {{ $inquiry->status }}">
            <div class="inquiry-header">
                <div class="inquiry-info">
                    <h3>
                        <i class="fas fa-user" style="margin-left: 0.5rem;"></i>
                        {{ $inquiry->user ? $inquiry->user->name : $inquiry->name }}
                    </h3>
                    <div class="inquiry-meta">
                        <span><i class="fas fa-phone" style="margin-left: 0.25rem;"></i> {{ $inquiry->user ? $inquiry->user->phone : $inquiry->phone }}</span>
                        <span><i class="fas fa-building" style="margin-left: 0.25rem;"></i> كود: {{ $inquiry->property_code }}</span>
                        <span><i class="fas fa-clock" style="margin-left: 0.25rem;"></i> {{ $inquiry->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
                <div>
                    <span class="badge {{ $inquiry->status === 'pending' ? 'badge-pending' : 'badge-answered' }}">
                        {{ $inquiry->status === 'pending' ? 'قيد الانتظار' : 'تم الرد' }}
                    </span>
                </div>
            </div>
            
            <div class="inquiry-message">
                {{ $inquiry->message }}
            </div>
            
            @if($inquiry->answer)
            <div class="inquiry-answer">
                <h4><i class="fas fa-reply" style="margin-left: 0.5rem;"></i> الرد:</h4>
                <p>{{ $inquiry->answer }}</p>
            </div>
            @else
            <form method="POST" action="{{ route('admin.inquiries.answer', $inquiry) }}" class="answer-form">
                @csrf
                <textarea name="answer" class="form-textarea" placeholder="اكتب ردك هنا..." required></textarea>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane" style="margin-left: 0.5rem;"></i>
                    إرسال الرد
                </button>
            </form>
            @endif
        </div>
        @empty
        <div style="text-align: center; padding: 3rem; color: #9CA3AF;">
            <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p style="font-size: 1rem; font-weight: 600;">لا توجد استفسارات</p>
        </div>
        @endforelse
    </div>
</div>
@endsection


