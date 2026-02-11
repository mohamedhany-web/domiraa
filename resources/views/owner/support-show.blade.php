@extends('layouts.owner')

@section('title', 'تفاصيل الشكوى - منصة دوميرا')
@section('page-title', 'تفاصيل الشكوى')

@section('content')
<div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">{{ $complaint->title }}</h2>
            <div style="display: flex; gap: 1rem; color: #6B7280; font-size: 0.9rem;">
                <span><i class="fas fa-calendar ml-1"></i> {{ $complaint->created_at->format('Y-m-d H:i') }}</span>
                <span><i class="fas fa-tag ml-1"></i> {{ $complaint->complaint_type }}</span>
            </div>
        </div>
        <div>
            @if($complaint->status == 'new')
            <span style="background: #FEF3C7; color: #D97706; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">جديدة</span>
            @elseif($complaint->status == 'under_review')
            <span style="background: #DBEAFE; color: #2563EB; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">قيد المراجعة</span>
            @elseif($complaint->status == 'resolved')
            <span style="background: #D1FAE5; color: #536b63; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">تم الحل</span>
            @else
            <span style="background: #FEE2E2; color: #DC2626; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">مرفوضة</span>
            @endif
        </div>
    </div>
    
    <div style="padding: 1.5rem; background: #F9FAFB; border-radius: 12px; margin-bottom: 1.5rem;">
        <h3 style="font-weight: 700; color: #1F2937; margin-bottom: 0.75rem;">الوصف</h3>
        <p style="color: #374151; line-height: 1.8; white-space: pre-wrap;">{{ $complaint->description }}</p>
    </div>
    
    @if($complaint->admin_response)
    <div style="padding: 1.5rem; background: #DBEAFE; border-radius: 12px; margin-bottom: 1.5rem;">
        <h3 style="font-weight: 700; color: #1d313f; margin-bottom: 0.75rem;">رد الإدارة</h3>
        <p style="color: #152431; line-height: 1.8; white-space: pre-wrap;">{{ $complaint->admin_response }}</p>
    </div>
    @endif
    
    @if($complaint->action_taken != 'none')
    <div style="padding: 1.5rem; background: #FEF3C7; border-radius: 12px;">
        <h3 style="font-weight: 700; color: #D97706; margin-bottom: 0.75rem;">الإجراء المتخذ</h3>
        <p style="color: #92400E;">
            @if($complaint->action_taken == 'warning') تحذير
            @elseif($complaint->action_taken == 'suspend_property') إيقاف الوحدة
            @elseif($complaint->action_taken == 'suspend_account') إيقاف الحساب
            @endif
        </p>
    </div>
    @endif
</div>
@endsection



