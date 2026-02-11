<!-- Support Chat Widget -->
<div id="supportChatWidget">
    <!-- Chat Toggle Button -->
    <button class="chat-toggle-btn" id="chatToggleBtn" onclick="toggleChat()">
        <i class="fas fa-headset"></i>
        <span class="notification-badge" id="unreadBadge" style="display: none;">0</span>
    </button>
    
    <!-- Chat Bubble Message -->
    <div class="chat-bubble" id="chatBubble" style="display: none;">
        <button class="bubble-close" onclick="closeBubble()">×</button>
        <div class="bubble-content">
            <i class="fas fa-hand-wave"></i>
            <span>كيف يمكننا مساعدتك؟</span>
        </div>
    </div>
    
    <!-- Chat Window -->
    <div class="chat-window" id="chatWindow">
        <!-- Chat Header -->
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="chat-avatar">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <h4>الدعم الفني</h4>
                    <span class="status-text">متصل الآن</span>
                </div>
            </div>
            <div class="chat-header-actions">
                <button onclick="showTicketsList()" title="المحادثات السابقة">
                    <i class="fas fa-history"></i>
                </button>
                <button onclick="toggleChat()" title="إغلاق">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <!-- Chat Views -->
        <div class="chat-body" id="chatBody">
            <!-- Welcome View -->
            <div class="chat-view" id="welcomeView">
                <div class="welcome-content">
                    <div class="welcome-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>مرحباً بك في الدعم الفني</h3>
                    <p>كيف يمكننا مساعدتك اليوم؟</p>
                    
                    <div class="welcome-actions">
                        <button class="action-btn primary" onclick="showNewTicketForm()">
                            <i class="fas fa-paper-plane"></i>
                            إرسال رسالة جديدة
                        </button>
                        <button class="action-btn secondary" onclick="showTicketsList()">
                            <i class="fas fa-history"></i>
                            المحادثات السابقة
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- New Ticket Form -->
            <div class="chat-view" id="newTicketView" style="display: none;">
                <div class="view-header">
                    <button onclick="showWelcome()"><i class="fas fa-arrow-right"></i></button>
                    <span>رسالة جديدة</span>
                </div>
                <form id="newTicketForm" class="ticket-form">
                    <div class="form-group">
                        <label>الاسم *</label>
                        <input type="text" name="name" id="ticketName" required placeholder="أدخل اسمك">
                    </div>
                    <div class="form-group">
                        <label>رقم الهاتف *</label>
                        <input type="tel" name="phone" id="ticketPhone" required placeholder="05xxxxxxxx" dir="ltr">
                    </div>
                    <div class="form-group">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" id="ticketEmail" placeholder="example@email.com" dir="ltr">
                    </div>
                    <div class="form-group">
                        <label>الرسالة *</label>
                        <textarea name="message" id="ticketMessage" required placeholder="اكتب رسالتك هنا..." rows="4"></textarea>
                    </div>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i>
                        إرسال
                    </button>
                </form>
            </div>
            
            <!-- Tickets List -->
            <div class="chat-view" id="ticketsListView" style="display: none;">
                <div class="view-header">
                    <button onclick="showWelcome()"><i class="fas fa-arrow-right"></i></button>
                    <span>المحادثات</span>
                    <button onclick="showNewTicketForm()" class="new-ticket-btn"><i class="fas fa-plus"></i></button>
                </div>
                <div class="tickets-list" id="ticketsList">
                    <div class="loading-spinner">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </div>
            </div>
            
            <!-- Chat Messages -->
            <div class="chat-view" id="chatMessagesView" style="display: none;">
                <div class="view-header">
                    <button onclick="showTicketsList()"><i class="fas fa-arrow-right"></i></button>
                    <span id="currentTicketNumber">المحادثة</span>
                    <span class="ticket-status" id="currentTicketStatus"></span>
                </div>
                <div class="messages-container" id="messagesContainer"></div>
                <form id="replyForm" class="reply-form">
                    <input type="text" id="replyMessage" placeholder="اكتب رسالتك..." autocomplete="off">
                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Chat Widget Styles */
#supportChatWidget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    font-family: 'Cairo', sans-serif;
}

/* Toggle Button */
.chat-toggle-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(29, 49, 63, 0.4);
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chat-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(29, 49, 63, 0.5);
}

.chat-toggle-btn.active {
    background: #DC2626;
}

.chat-toggle-btn.active i:before {
    content: "\f00d";
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #DC2626;
    color: white;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

/* Chat Bubble */
.chat-bubble {
    position: absolute;
    bottom: 75px;
    right: 0;
    background: white;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    min-width: 220px;
    animation: bubbleIn 0.3s ease;
}

.chat-bubble::after {
    content: '';
    position: absolute;
    bottom: -8px;
    right: 25px;
    width: 16px;
    height: 16px;
    background: white;
    transform: rotate(45deg);
    box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.05);
}

.bubble-close {
    position: absolute;
    top: 5px;
    left: 10px;
    background: none;
    border: none;
    color: #9CA3AF;
    font-size: 1.1rem;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    transition: color 0.2s ease;
}

.bubble-close:hover {
    color: #6B7280;
}

.bubble-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.bubble-content i {
    color: var(--primary);
    font-size: 1.25rem;
}

.bubble-content span {
    color: #374151;
    font-weight: 600;
}

@keyframes bubbleIn {
    from {
        opacity: 0;
        transform: translateY(10px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Chat Window */
.chat-window {
    position: absolute;
    bottom: 75px;
    right: 0;
    width: 380px;
    height: 520px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: windowIn 0.3s ease;
}

.chat-window.active {
    display: flex;
}

@keyframes windowIn {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Chat Header */
.chat-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-header-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.chat-avatar {
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.chat-header-info h4 {
    margin: 0;
    font-weight: 700;
    font-size: 1rem;
}

.chat-header-info .status-text {
    font-size: 0.8rem;
    opacity: 0.9;
}

.chat-header-actions {
    display: flex;
    gap: 0.5rem;
}

.chat-header-actions button {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.chat-header-actions button:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Chat Body */
.chat-body {
    flex: 1;
    overflow: hidden;
    background: #F9FAFB;
}

.chat-view {
    height: 100%;
    display: flex;
    flex-direction: column;
}

/* Welcome View */
.welcome-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    text-align: center;
}

.welcome-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.welcome-icon i {
    color: white;
    font-size: 2rem;
}

.welcome-content h3 {
    color: #1F2937;
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
}

.welcome-content p {
    color: #6B7280;
    margin: 0 0 1.5rem 0;
}

.welcome-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    width: 100%;
}

.action-btn {
    padding: 0.875rem 1.5rem;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.95rem;
}

.action-btn.primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
}

.action-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
}

.action-btn.secondary {
    background: #E5E7EB;
    color: #374151;
}

.action-btn.secondary:hover {
    background: #D1D5DB;
}

/* View Header */
.view-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: white;
    border-bottom: 1px solid #E5E7EB;
}

.view-header button {
    background: none;
    border: none;
    color: #6B7280;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.view-header button:hover {
    background: #F3F4F6;
    color: var(--primary);
}

.view-header span {
    flex: 1;
    font-weight: 700;
    color: #1F2937;
}

.new-ticket-btn {
    color: var(--primary) !important;
}

.ticket-status {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
}

/* Ticket Form */
.ticket-form {
    flex: 1;
    padding: 1rem;
    overflow-y: auto;
}

.ticket-form .form-group {
    margin-bottom: 1rem;
}

.ticket-form label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.ticket-form input,
.ticket-form textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    font-family: 'Cairo', sans-serif;
}

.ticket-form input:focus,
.ticket-form textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.ticket-form textarea {
    resize: none;
}

.submit-btn {
    width: 100%;
    padding: 0.875rem;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(29, 49, 63, 0.3);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Tickets List */
.tickets-list {
    flex: 1;
    overflow-y: auto;
    padding: 0.5rem;
}

.ticket-item {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid #E5E7EB;
}

.ticket-item:hover {
    border-color: var(--primary);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.ticket-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.ticket-item-number {
    font-weight: 700;
    color: var(--primary);
    font-size: 0.85rem;
}

.ticket-item-status {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
    font-weight: 600;
}

.ticket-item-status.open { background: #DBEAFE; color: #1E40AF; }
.ticket-item-status.pending { background: #FEF3C7; color: #D97706; }
.ticket-item-status.answered { background: #D1FAE5; color: #059669; }
.ticket-item-status.closed { background: #F3F4F6; color: #6B7280; }

.ticket-item-preview {
    color: #6B7280;
    font-size: 0.85rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ticket-item-time {
    color: #9CA3AF;
    font-size: 0.75rem;
    margin-top: 0.5rem;
}

.empty-tickets {
    text-align: center;
    padding: 3rem 1rem;
    color: #6B7280;
}

.empty-tickets i {
    font-size: 3rem;
    color: #D1D5DB;
    margin-bottom: 1rem;
}

/* Messages */
.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.message {
    max-width: 85%;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    font-size: 0.9rem;
    line-height: 1.5;
}

.message.user {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    align-self: flex-end;
    border-bottom-left-radius: 4px;
}

.message.admin {
    background: white;
    color: #374151;
    align-self: flex-start;
    border-bottom-right-radius: 4px;
    border: 1px solid #E5E7EB;
}

.message-time {
    font-size: 0.7rem;
    opacity: 0.7;
    margin-top: 0.25rem;
}

/* Reply Form */
.reply-form {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    background: white;
    border-top: 1px solid #E5E7EB;
}

.reply-form input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 2px solid #E5E7EB;
    border-radius: 25px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.reply-form input:focus {
    outline: none;
    border-color: var(--primary);
}

.reply-form button {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.reply-form button:hover {
    transform: scale(1.05);
}

/* Loading Spinner */
.loading-spinner {
    text-align: center;
    padding: 2rem;
    color: var(--primary);
}

.loading-spinner i {
    font-size: 2rem;
}

/* Responsive */
@media (max-width: 480px) {
    #supportChatWidget {
        bottom: 15px;
        right: 15px;
    }
    
    .chat-window {
        width: calc(100vw - 30px);
        height: calc(100vh - 100px);
        max-height: 600px;
        bottom: 70px;
        right: 0;
    }
    
    .chat-toggle-btn {
        width: 55px;
        height: 55px;
        font-size: 1.3rem;
    }
    
    .chat-bubble {
        width: calc(100vw - 100px);
        min-width: auto;
    }
}
</style>

<script>
// Support Chat Widget
const SupportChat = {
    guestId: localStorage.getItem('support_guest_id') || null,
    currentTicketId: null,
    lastMessageId: 0,
    pollInterval: null,
    
    init() {
        // Show bubble after 3 seconds if not dismissed in this session
        const bubbleDismissedTime = sessionStorage.getItem('bubble_dismissed_time');
        const now = Date.now();
        
        // Show bubble if never dismissed or dismissed more than 30 minutes ago
        if (!bubbleDismissedTime || (now - parseInt(bubbleDismissedTime)) > 30 * 60 * 1000) {
            setTimeout(() => {
                const bubble = document.getElementById('chatBubble');
                const chatWindow = document.getElementById('chatWindow');
                // Only show if chat window is not open
                if (bubble && !chatWindow.classList.contains('active')) {
                    bubble.style.display = 'block';
                }
            }, 3000);
        }
        
        // Load saved form data
        this.loadFormData();
        
        // Setup form handlers
        document.getElementById('newTicketForm').addEventListener('submit', (e) => this.handleNewTicket(e));
        document.getElementById('replyForm').addEventListener('submit', (e) => this.handleReply(e));
        
        // Auto-save form data
        ['ticketName', 'ticketPhone', 'ticketEmail'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', () => this.saveFormData());
            }
        });
    },
    
    loadFormData() {
        const saved = localStorage.getItem('support_form_data');
        if (saved) {
            const data = JSON.parse(saved);
            if (data.name) document.getElementById('ticketName').value = data.name;
            if (data.phone) document.getElementById('ticketPhone').value = data.phone;
            if (data.email) document.getElementById('ticketEmail').value = data.email;
        }
    },
    
    saveFormData() {
        const data = {
            name: document.getElementById('ticketName').value,
            phone: document.getElementById('ticketPhone').value,
            email: document.getElementById('ticketEmail').value,
        };
        localStorage.setItem('support_form_data', JSON.stringify(data));
    },
    
    async fetchTickets() {
        try {
            const response = await fetch(`/support-chat/tickets?guest_id=${this.guestId}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderTicketsList(data.tickets);
                this.updateUnreadBadge(data.unread_count);
            }
        } catch (error) {
            console.error('Error fetching tickets:', error);
        }
    },
    
    renderTicketsList(tickets) {
        const container = document.getElementById('ticketsList');
        
        if (tickets.length === 0) {
            container.innerHTML = `
                <div class="empty-tickets">
                    <i class="fas fa-inbox"></i>
                    <p>لا توجد محادثات سابقة</p>
                </div>
            `;
            return;
        }
        
        const statusLabels = {
            'open': 'مفتوح',
            'pending': 'قيد الانتظار',
            'answered': 'تم الرد',
            'closed': 'مغلق'
        };
        
        container.innerHTML = tickets.map(ticket => `
            <div class="ticket-item" onclick="SupportChat.openTicket(${ticket.id})">
                <div class="ticket-item-header">
                    <span class="ticket-item-number">${ticket.ticket_number}</span>
                    <span class="ticket-item-status ${ticket.status}">${statusLabels[ticket.status]}</span>
                </div>
                <div class="ticket-item-preview">${ticket.subject || 'استفسار عام'}</div>
                <div class="ticket-item-time">${this.formatDate(ticket.last_reply_at || ticket.created_at)}</div>
            </div>
        `).join('');
    },
    
    async handleNewTicket(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('.submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
        
        try {
            const response = await fetch('/support-chat/tickets', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: document.getElementById('ticketName').value,
                    phone: document.getElementById('ticketPhone').value,
                    email: document.getElementById('ticketEmail').value,
                    message: document.getElementById('ticketMessage').value,
                    guest_id: this.guestId
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.guestId = data.guest_id;
                localStorage.setItem('support_guest_id', data.guest_id);
                
                document.getElementById('ticketMessage').value = '';
                
                this.openTicket(data.ticket.id);
            } else {
                alert(data.message || 'حدث خطأ');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> إرسال';
        }
    },
    
    async openTicket(ticketId) {
        this.currentTicketId = ticketId;
        
        try {
            const response = await fetch(`/support-chat/tickets/${ticketId}/messages?guest_id=${this.guestId}`);
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('currentTicketNumber').textContent = data.ticket.ticket_number;
                
                const statusLabels = { 'open': 'مفتوح', 'pending': 'قيد الانتظار', 'answered': 'تم الرد', 'closed': 'مغلق' };
                const statusEl = document.getElementById('currentTicketStatus');
                statusEl.textContent = statusLabels[data.ticket.status];
                statusEl.className = `ticket-status ticket-item-status ${data.ticket.status}`;
                
                this.renderMessages(data.messages);
                showView('chatMessagesView');
                
                // Start polling
                this.startPolling();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    },
    
    renderMessages(messages) {
        const container = document.getElementById('messagesContainer');
        
        container.innerHTML = messages.map(msg => `
            <div class="message ${msg.is_admin ? 'admin' : 'user'}">
                <div>${this.escapeHtml(msg.message)}</div>
                <div class="message-time">${this.formatTime(msg.created_at)}</div>
            </div>
        `).join('');
        
        // Scroll to bottom
        container.scrollTop = container.scrollHeight;
        
        // Update last message id
        if (messages.length > 0) {
            this.lastMessageId = messages[messages.length - 1].id;
        }
    },
    
    async handleReply(e) {
        e.preventDefault();
        
        const input = document.getElementById('replyMessage');
        const message = input.value.trim();
        
        if (!message || !this.currentTicketId) return;
        
        input.value = '';
        
        try {
            const response = await fetch(`/support-chat/tickets/${this.currentTicketId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message: message,
                    guest_id: this.guestId
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                const container = document.getElementById('messagesContainer');
                container.innerHTML += `
                    <div class="message user">
                        <div>${this.escapeHtml(message)}</div>
                        <div class="message-time">الآن</div>
                    </div>
                `;
                container.scrollTop = container.scrollHeight;
                this.lastMessageId = data.message.id;
            }
        } catch (error) {
            console.error('Error:', error);
            input.value = message;
        }
    },
    
    startPolling() {
        this.stopPolling();
        this.pollInterval = setInterval(() => this.pollNewMessages(), 5000);
    },
    
    stopPolling() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
            this.pollInterval = null;
        }
    },
    
    async pollNewMessages() {
        if (!this.currentTicketId) return;
        
        try {
            const response = await fetch(
                `/support-chat/tickets/${this.currentTicketId}/poll?guest_id=${this.guestId}&last_message_id=${this.lastMessageId}`
            );
            const data = await response.json();
            
            if (data.success && data.messages.length > 0) {
                const container = document.getElementById('messagesContainer');
                
                data.messages.forEach(msg => {
                    container.innerHTML += `
                        <div class="message ${msg.is_admin ? 'admin' : 'user'}">
                            <div>${this.escapeHtml(msg.message)}</div>
                            <div class="message-time">${this.formatTime(msg.created_at)}</div>
                        </div>
                    `;
                    this.lastMessageId = msg.id;
                });
                
                container.scrollTop = container.scrollHeight;
                
                // Update status
                const statusLabels = { 'open': 'مفتوح', 'pending': 'قيد الانتظار', 'answered': 'تم الرد', 'closed': 'مغلق' };
                const statusEl = document.getElementById('currentTicketStatus');
                statusEl.textContent = statusLabels[data.ticket_status];
                statusEl.className = `ticket-status ticket-item-status ${data.ticket_status}`;
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    },
    
    updateUnreadBadge(count) {
        const badge = document.getElementById('unreadBadge');
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    },
    
    formatDate(dateStr) {
        const date = new Date(dateStr);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) return 'الآن';
        if (diff < 3600000) return `منذ ${Math.floor(diff / 60000)} دقيقة`;
        if (diff < 86400000) return `منذ ${Math.floor(diff / 3600000)} ساعة`;
        
        return date.toLocaleDateString('ar-SA');
    },
    
    formatTime(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });
    },
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

// Global functions
function toggleChat() {
    const window = document.getElementById('chatWindow');
    const btn = document.getElementById('chatToggleBtn');
    const bubble = document.getElementById('chatBubble');
    
    window.classList.toggle('active');
    btn.classList.toggle('active');
    bubble.style.display = 'none';
    sessionStorage.setItem('bubble_dismissed_time', Date.now().toString());
    
    if (window.classList.contains('active')) {
        SupportChat.fetchTickets();
    } else {
        SupportChat.stopPolling();
    }
}

function closeBubble() {
    document.getElementById('chatBubble').style.display = 'none';
    sessionStorage.setItem('bubble_dismissed_time', Date.now().toString());
}

function showView(viewId) {
    document.querySelectorAll('.chat-view').forEach(v => v.style.display = 'none');
    document.getElementById(viewId).style.display = 'flex';
}

function showWelcome() {
    SupportChat.stopPolling();
    showView('welcomeView');
}

function showNewTicketForm() {
    showView('newTicketView');
}

function showTicketsList() {
    SupportChat.stopPolling();
    SupportChat.fetchTickets();
    showView('ticketsListView');
}

// Initialize
document.addEventListener('DOMContentLoaded', () => SupportChat.init());
</script>

