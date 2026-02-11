@extends('layouts.admin')

@section('title', 'الشكاوى والبلاغات')
@section('page-title', 'الشكاوى والبلاغات')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
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
    
    .stat-icon.red {
        background: linear-gradient(135deg, #F87171 0%, #EF4444 100%);
        color: white;
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
        line-height: 1;
    }
    
    .complaints-section {
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
    
    .complaint-card {
        background: #F9FAFB;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border-right: 4px solid #EF4444;
        transition: all 0.3s ease;
    }
    
    .complaint-card:hover {
        background: #F3F4F6;
        transform: translateX(-5px);
    }
    
    .complaint-card.new {
        border-right-color: #EF4444;
    }
    
    .complaint-card.under_review {
        border-right-color: #F59E0B;
    }
    
    .complaint-card.resolved {
        border-right-color: #6b8980;
    }
    
    .complaint-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .complaint-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.5rem;
    }
    
    .complaint-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
        color: #6B7280;
        flex-wrap: wrap;
    }
    
    .complaint-description {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        color: #4B5563;
        line-height: 1.6;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .badge-new {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .badge-under_review {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .badge-resolved {
        background: #D1FAE5;
        color: #536b63;
    }
    
    .btn-view {
        background: #DBEAFE;
        color: #1d313f;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-view:hover {
        background: #BFDBFE;
    }
    
    @media (max-width: 1400px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .complaint-header {
            flex-direction: column;
            gap: 0.75rem;
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
                <div class="stat-label">جديدة</div>
                <div class="stat-value">{{ $newComplaints }}</div>
            </div>
            <div class="stat-icon red">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">قيد المراجعة</div>
                <div class="stat-value">{{ $underReview }}</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-content">
                <div class="stat-label">تم الحل</div>
                <div class="stat-value">{{ $resolved }}</div>
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
                <div class="stat-value">{{ $complaints->count() }}</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-list"></i>
            </div>
        </div>
    </div>
</div>

<!-- Complaints List -->
<div class="complaints-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-exclamation-triangle"></i>
            قائمة البلاغات
        </h2>
    </div>
    
    <div>
        @forelse($complaints as $complaint)
        <div class="complaint-card {{ $complaint->status }}">
            <div class="complaint-header">
                <div style="flex: 1;">
                    <h3 class="complaint-title">{{ $complaint->title }}</h3>
                    <div class="complaint-meta">
                        <span><i class="fas fa-user" style="margin-left: 0.25rem;"></i> من: {{ $complaint->user->name }}</span>
                        @if($complaint->property)
                        <span><i class="fas fa-building" style="margin-left: 0.25rem;"></i> وحدة: {{ $complaint->property->code }}</span>
                        @endif
                        @if($complaint->reportedUser)
                        <span><i class="fas fa-user-slash" style="margin-left: 0.25rem;"></i> ضد: {{ $complaint->reportedUser->name }}</span>
                        @endif
                        <span><i class="fas fa-clock" style="margin-left: 0.25rem;"></i> {{ $complaint->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
                <div>
                    <span class="badge badge-{{ $complaint->status }}">
                        {{ $complaint->status === 'new' ? 'جديد' : ($complaint->status === 'under_review' ? 'قيد المراجعة' : ($complaint->status === 'resolved' ? 'تم الحل' : 'مرفوض')) }}
                    </span>
                </div>
            </div>
            
            <div class="complaint-description">
                {{ $complaint->description }}
            </div>
            
            <div style="text-align: left;">
                <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn-view">
                    <i class="fas fa-eye"></i>
                    عرض التفاصيل
                </a>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 3rem; color: #9CA3AF;">
            <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p style="font-size: 1rem; font-weight: 600;">لا توجد بلاغات</p>
        </div>
        @endforelse
    </div>
</div>
@endsection



