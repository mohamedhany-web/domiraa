@extends('layouts.owner')

@section('title', 'الدعم الفني - منصة دوميرا')
@section('page-title', 'الدعم الفني')

@section('content')
<!-- New Complaint Form -->
<div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 1.5rem;">
        <i class="fas fa-headset ml-2"></i>
        إرسال شكوى جديدة
    </h2>
    
    <form action="{{ route('owner.support.store') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">نوع الشكوى</label>
            <select name="complaint_type" required style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem;">
                <option value="property">شكوى على وحدة</option>
                <option value="owner">شكوى على مؤجر</option>
                <option value="tenant">شكوى على مستأجر</option>
                <option value="other">أخرى</option>
            </select>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">الموضوع</label>
            <input type="text" name="title" required style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 1rem;">
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">الوصف</label>
            <textarea name="description" required style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; min-height: 150px; font-size: 1rem;"></textarea>
        </div>
        
        <button type="submit" style="background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; padding: 0.875rem 2rem; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);">
            <i class="fas fa-paper-plane ml-2"></i>
            إرسال الشكوى
        </button>
    </form>
</div>

<!-- Previous Complaints -->
@if($complaints->count() > 0)
<div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 1.5rem;">
        <i class="fas fa-list ml-2"></i>
        الشكاوى السابقة
    </h2>
    
    <div style="display: grid; gap: 1.5rem;">
        @foreach($complaints as $complaint)
        <a href="{{ route('owner.support.show', $complaint) }}" style="display: block; padding: 1.5rem; background: #F9FAFB; border-radius: 12px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                <h3 style="font-size: 1.125rem; font-weight: 700; color: #1F2937;">{{ $complaint->title }}</h3>
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
            <p style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.5rem;">{{ Str::limit($complaint->description, 100) }}</p>
            <span style="color: #9CA3AF; font-size: 0.875rem;">{{ $complaint->created_at->diffForHumans() }}</span>
        </a>
        @endforeach
    </div>
    
    {{ $complaints->links() }}
</div>
@endif
@endsection



