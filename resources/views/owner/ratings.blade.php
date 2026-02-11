@extends('layouts.owner')

@section('title', 'التقييمات - منصة دوميرا')
@section('page-title', 'التقييمات والمراجعات')

@section('content')
<!-- Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); text-align: center;">
        <div style="font-size: 3rem; font-weight: 800; color: #F59E0B; margin-bottom: 0.5rem;">{{ number_format($stats['average_rating'], 1) }}</div>
        <div style="color: #6B7280; font-weight: 600;">متوسط التقييم</div>
        <div style="margin-top: 0.5rem;">
            @for($i = 1; $i <= 5; $i++)
            <i class="fas fa-star" style="color: {{ $i <= $stats['average_rating'] ? '#F59E0B' : '#E5E7EB' }};"></i>
            @endfor
        </div>
    </div>
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #1F2937; margin-bottom: 0.5rem;">{{ $stats['total_ratings'] }}</div>
        <div style="color: #6B7280; font-weight: 600;">إجمالي التقييمات</div>
    </div>
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="font-size: 2rem; font-weight: 800; color: #F59E0B; margin-bottom: 0.5rem;">{{ $stats['five_star'] }}</div>
        <div style="color: #6B7280; font-weight: 600;">تقييمات 5 نجوم</div>
    </div>
</div>

@if($ratings->count() > 0)
<div style="display: grid; gap: 1.5rem;">
    @foreach($ratings as $rating)
    <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
            <div style="flex: 1;">
                <h3 style="font-size: 1.125rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">{{ $rating->property->address }}</h3>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star" style="color: {{ $i <= $rating->rating ? '#F59E0B' : '#E5E7EB' }}; font-size: 1.25rem;"></i>
                    @endfor
                    <span style="color: #6B7280; font-size: 0.875rem;">بواسطة {{ $rating->user->name }}</span>
                </div>
                @if($rating->comment)
                <p style="color: #374151; line-height: 1.6;">{{ $rating->comment }}</p>
                @endif
            </div>
            <span style="color: #9CA3AF; font-size: 0.875rem;">{{ $rating->created_at->diffForHumans() }}</span>
        </div>
        
        @if($rating->owner_reply)
        <div style="margin-top: 1rem; padding: 1rem; background: #F9FAFB; border-radius: 8px; border-right: 3px solid #1d313f;">
            <div style="font-weight: 600; color: #1F2937; margin-bottom: 0.5rem;">ردك:</div>
            <p style="color: #374151; line-height: 1.6;">{{ $rating->owner_reply }}</p>
        </div>
        @else
        <form action="{{ route('owner.ratings.reply', $rating) }}" method="POST">
            @csrf
            <textarea name="reply" required placeholder="اكتب ردك هنا..." style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; min-height: 100px; margin-bottom: 0.75rem;"></textarea>
            <button type="submit" style="background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; font-weight: 600; cursor: pointer;">
                <i class="fas fa-reply ml-1"></i> إرسال رد
            </button>
        </form>
        @endif
    </div>
    @endforeach
</div>

{{ $ratings->links() }}
@else
<div style="background: white; border-radius: 16px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
    <i class="fas fa-star" style="font-size: 4rem; color: #9CA3AF; margin-bottom: 1.5rem; opacity: 0.5;"></i>
    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">لا توجد تقييمات</h3>
    <p style="color: #6B7280;">لم يتم تقييم أي من وحداتك بعد</p>
</div>
@endif
@endsection



