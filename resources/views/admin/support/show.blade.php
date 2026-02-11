@extends('layouts.admin')

@section('title', 'تذكرة ' . $ticket->ticket_number)
@section('page-title', 'تفاصيل التذكرة')

@push('styles')
<style>
    .ticket-container {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.5rem;
    }
    
    /* Ticket Header */
    .ticket-header {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .ticket-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .ticket-number {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary);
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-badge.open { background: #DBEAFE; color: #1E40AF; }
    .status-badge.pending { background: #FEF3C7; color: #D97706; }
    .status-badge.answered { background: #D1FAE5; color: #059669; }
    .status-badge.closed { background: #F3F4F6; color: #6B7280; }
    
    .header-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-back {
        background: #F3F4F6;
        color: #374151;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    
    .btn-back:hover {
        background: #E5E7EB;
    }
    
    /* Chat Section */
    .chat-section {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 280px);
        min-height: 500px;
    }
    
    .chat-header {
        padding: 1rem 1.25rem;
        background: #F9FAFB;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .chat-header h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        background: #F9FAFB;
    }
    
    .message {
        max-width: 75%;
        padding: 1rem 1.25rem;
        border-radius: 16px;
        position: relative;
    }
    
    .message.user {
        background: white;
        align-self: flex-start;
        border: 1px solid #E5E7EB;
        border-bottom-right-radius: 4px;
    }
    
    .message.admin {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        align-self: flex-end;
        border-bottom-left-radius: 4px;
    }
    
    .message-sender {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        opacity: 0.8;
    }
    
    .message-content {
        line-height: 1.6;
        white-space: pre-wrap;
    }
    
    .message-time {
        font-size: 0.75rem;
        opacity: 0.7;
        margin-top: 0.5rem;
    }
    
    /* Reply Form */
    .reply-section {
        padding: 1rem;
        background: white;
        border-top: 1px solid #E5E7EB;
    }
    
    .reply-form {
        display: flex;
        gap: 0.75rem;
    }
    
    .reply-form textarea {
        flex: 1;
        padding: 0.875rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        resize: none;
        font-size: 0.95rem;
        font-family: 'Cairo', sans-serif;
        transition: all 0.2s ease;
    }
    
    .reply-form textarea:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .reply-form button {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border: none;
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .reply-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
    }
    
    /* Sidebar */
    .ticket-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .sidebar-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
    }
    
    .sidebar-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .sidebar-title i {
        color: var(--primary);
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        color: #6B7280;
        font-size: 0.9rem;
    }
    
    .info-value {
        font-weight: 600;
        color: #1F2937;
    }
    
    /* Actions */
    .action-group {
        margin-top: 1rem;
    }
    
    .action-group label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .action-group select {
        width: 100%;
        padding: 0.625rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .action-group select:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .priority-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .priority-badge.low { background: #F3F4F6; color: #6B7280; }
    .priority-badge.medium { background: #FEF3C7; color: #D97706; }
    .priority-badge.high { background: #FEE2E2; color: #DC2626; }
    
    .delete-btn {
        width: 100%;
        padding: 0.75rem;
        background: #FEE2E2;
        color: #DC2626;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .delete-btn:hover {
        background: #FECACA;
    }
    
    @media (max-width: 1024px) {
        .ticket-container {
            grid-template-columns: 1fr;
        }
        
        .ticket-sidebar {
            order: -1;
            flex-direction: row;
            flex-wrap: wrap;
        }
        
        .sidebar-card {
            flex: 1;
            min-width: 280px;
        }
        
        .chat-section {
            height: 600px;
        }
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="ticket-header">
    <div class="ticket-title">
        <span class="ticket-number">{{ $ticket->ticket_number }}</span>
        <span class="status-badge {{ $ticket->status }}">{{ $ticket->status_label }}</span>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.support.index') }}" class="btn-back">
            <i class="fas fa-arrow-right"></i>
            العودة للقائمة
        </a>
    </div>
</div>

@if(session('success'))
<div style="background: #D1FAE5; border: 1px solid #6b8980; color: #536b63; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="ticket-container">
    <!-- Chat Section -->
    <div class="chat-section">
        <div class="chat-header">
            <h3>
                <i class="fas fa-comments"></i>
                المحادثة
            </h3>
            <span style="font-size: 0.85rem; color: #6B7280;">
                {{ $ticket->messages->count() }} رسالة
            </span>
        </div>
        
        <div class="messages-container" id="messagesContainer">
            @foreach($ticket->messages as $message)
            <div class="message {{ $message->is_admin ? 'admin' : 'user' }}">
                <div class="message-sender">
                    @if($message->is_admin)
                        {{ $message->user->name ?? 'الدعم الفني' }}
                    @else
                        {{ $ticket->name }}
                    @endif
                </div>
                <div class="message-content">{{ $message->message }}</div>
                <div class="message-time">{{ $message->created_at->format('Y/m/d h:i A') }}</div>
            </div>
            @endforeach
        </div>
        
        @if($ticket->status !== 'closed')
        <div class="reply-section">
            <form action="{{ route('admin.support.reply', $ticket) }}" method="POST" class="reply-form">
                @csrf
                <textarea name="message" rows="2" placeholder="اكتب ردك هنا..." required></textarea>
                <button type="submit">
                    <i class="fas fa-paper-plane"></i>
                    إرسال
                </button>
            </form>
        </div>
        @else
        <div style="padding: 1rem; background: #F3F4F6; text-align: center; color: #6B7280;">
            <i class="fas fa-lock"></i>
            هذه التذكرة مغلقة
        </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="ticket-sidebar">
        <!-- Customer Info -->
        <div class="sidebar-card">
            <h4 class="sidebar-title">
                <i class="fas fa-user"></i>
                معلومات العميل
            </h4>
            <div class="info-item">
                <span class="info-label">الاسم</span>
                <span class="info-value">{{ $ticket->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">الهاتف</span>
                <span class="info-value" dir="ltr">{{ $ticket->phone }}</span>
            </div>
            @if($ticket->email)
            <div class="info-item">
                <span class="info-label">البريد</span>
                <span class="info-value" style="font-size: 0.85rem;">{{ $ticket->email }}</span>
            </div>
            @endif
            @if($ticket->user)
            <div class="info-item">
                <span class="info-label">نوع الحساب</span>
                <span class="info-value">{{ $ticket->user->role === 'owner' ? 'مؤجر' : ($ticket->user->role === 'admin' ? 'أدمن' : 'مستأجر') }}</span>
            </div>
            @else
            <div class="info-item">
                <span class="info-label">نوع الحساب</span>
                <span class="info-value" style="color: #6B7280;">زائر</span>
            </div>
            @endif
        </div>
        
        <!-- Ticket Info -->
        <div class="sidebar-card">
            <h4 class="sidebar-title">
                <i class="fas fa-info-circle"></i>
                معلومات التذكرة
            </h4>
            <div class="info-item">
                <span class="info-label">الموضوع</span>
                <span class="info-value">{{ $ticket->subject ?? 'استفسار عام' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">تاريخ الإنشاء</span>
                <span class="info-value">{{ $ticket->created_at->format('Y/m/d') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">آخر تحديث</span>
                <span class="info-value">{{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : '-' }}</span>
            </div>
            @if($ticket->assignedTo)
            <div class="info-item">
                <span class="info-label">المسؤول</span>
                <span class="info-value">{{ $ticket->assignedTo->name }}</span>
            </div>
            @endif
        </div>
        
        <!-- Actions -->
        <div class="sidebar-card">
            <h4 class="sidebar-title">
                <i class="fas fa-cog"></i>
                الإجراءات
            </h4>
            
            <form action="{{ route('admin.support.status', $ticket) }}" method="POST" class="action-group">
                @csrf
                @method('PATCH')
                <label>تغيير الحالة</label>
                <select name="status" onchange="this.form.submit()">
                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>مفتوح</option>
                    <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="answered" {{ $ticket->status === 'answered' ? 'selected' : '' }}>تم الرد</option>
                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>مغلق</option>
                </select>
            </form>
            
            <form action="{{ route('admin.support.priority', $ticket) }}" method="POST" class="action-group">
                @csrf
                @method('PATCH')
                <label>الأولوية</label>
                <select name="priority" onchange="this.form.submit()">
                    <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>منخفضة</option>
                    <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>متوسطة</option>
                    <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>عالية</option>
                </select>
            </form>
            
            <form action="{{ route('admin.support.destroy', $ticket) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه التذكرة؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn">
                    <i class="fas fa-trash"></i>
                    حذف التذكرة
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Scroll to bottom on load
document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight;

// Poll for new messages
let lastMessageId = {{ $ticket->messages->last()?->id ?? 0 }};

setInterval(() => {
    fetch('{{ route("admin.support.new-messages", $ticket) }}?last_message_id=' + lastMessageId)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.messages.length > 0) {
                const container = document.getElementById('messagesContainer');
                data.messages.forEach(msg => {
                    const div = document.createElement('div');
                    div.className = 'message ' + (msg.is_admin ? 'admin' : 'user');
                    div.innerHTML = `
                        <div class="message-sender">${msg.is_admin ? 'الدعم الفني' : '{{ $ticket->name }}'}</div>
                        <div class="message-content">${msg.message}</div>
                        <div class="message-time">الآن</div>
                    `;
                    container.appendChild(div);
                    lastMessageId = msg.id;
                });
                container.scrollTop = container.scrollHeight;
            }
        });
}, 5000);
</script>
@endsection

