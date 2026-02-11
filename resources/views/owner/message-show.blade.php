@extends('layouts.owner')

@section('title', 'الرسالة - منصة دوميرا')
@section('page-title', 'الرسالة')

@section('content')
<div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); margin-bottom: 2rem;">
    <div style="margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">{{ $message->subject ?? 'رسالة' }}</h3>
        <div style="display: flex; gap: 1rem; color: #6B7280; font-size: 0.9rem;">
            <span><i class="fas fa-user ml-1"></i> مستأجر مهتم</span>
            <span><i class="fas fa-calendar ml-1"></i> {{ $message->created_at->format('Y-m-d H:i') }}</span>
        </div>
    </div>
    
    <div style="padding: 1.5rem; background: #F9FAFB; border-radius: 12px; margin-bottom: 1.5rem;">
        <p style="color: #374151; line-height: 1.8; white-space: pre-wrap;">{{ $message->message }}</p>
    </div>
</div>

<!-- Conversation -->
<div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); margin-bottom: 2rem;">
    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1F2937; margin-bottom: 1.5rem;">المحادثة</h3>
    
    <div style="display: grid; gap: 1rem; max-height: 500px; overflow-y: auto;">
        @foreach($conversation->reverse() as $msg)
        <div style="padding: 1rem; background: {{ $msg->sender_id == auth()->id() ? '#DBEAFE' : '#F9FAFB' }}; border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="font-weight: 700; color: #1F2937;">{{ $msg->sender_id == auth()->id() ? 'أنت' : 'مستأجر مهتم' }}</span>
                <span style="color: #9CA3AF; font-size: 0.875rem;">{{ $msg->created_at->format('H:i') }}</span>
            </div>
            <p style="color: #374151; line-height: 1.6;">{{ $msg->message }}</p>
        </div>
        @endforeach
    </div>
</div>

<!-- Reply Form -->
<div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1F2937; margin-bottom: 1.5rem;">إرسال رد</h3>
    
    <form action="{{ route('owner.messages.reply', $message) }}" method="POST">
        @csrf
        
        <textarea name="message" required placeholder="اكتب رسالتك هنا..." style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 8px; min-height: 150px; font-size: 1rem; margin-bottom: 1rem;"></textarea>
        
        <button type="submit" style="background: linear-gradient(135deg, #1d313f 0%, #6b8980 100%); color: white; padding: 0.875rem 2rem; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);">
            <i class="fas fa-paper-plane ml-2"></i>
            إرسال
        </button>
    </form>
</div>
@endsection



