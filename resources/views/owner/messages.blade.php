@extends('layouts.owner')

@section('title', 'الرسائل - منصة دوميرا')
@section('page-title', 'الرسائل والتواصل')

@section('content')
@if($conversations->count() > 0)
<div style="display: grid; gap: 1.5rem;">
    @foreach($conversations as $otherUserId => $messages)
    @php
        $otherUser = $messages->first()->sender_id == auth()->id() ? $messages->first()->receiver : $messages->first()->sender;
        $unreadCount = $messages->where('receiver_id', auth()->id())->whereNull('read_at')->count();
    @endphp
    <a href="{{ route('owner.messages.show', $messages->first()) }}" style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); text-decoration: none; display: block; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0, 0, 0, 0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.08)'">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div style="flex: 1;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">
                    مستأجر مهتم
                    @if($unreadCount > 0)
                    <span style="background: #EF4444; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; margin-right: 0.5rem;">{{ $unreadCount }}</span>
                    @endif
                </h3>
                <p style="color: #6B7280; font-size: 0.9rem; margin-bottom: 0.5rem;">{{ Str::limit($messages->first()->message, 100) }}</p>
                <span style="color: #9CA3AF; font-size: 0.875rem;">{{ $messages->first()->created_at->diffForHumans() }}</span>
            </div>
            <i class="fas fa-chevron-left" style="color: #9CA3AF;"></i>
        </div>
    </a>
    @endforeach
</div>
@else
<div style="background: white; border-radius: 16px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
    <i class="fas fa-comments" style="font-size: 4rem; color: #9CA3AF; margin-bottom: 1.5rem; opacity: 0.5;"></i>
    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">لا توجد رسائل</h3>
    <p style="color: #6B7280;">لم يتم استلام أي رسائل بعد</p>
</div>
@endif
@endsection



