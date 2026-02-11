<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the tickets.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'latestMessage', 'assignedTo'])
            ->withCount('unreadMessages');
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by priority
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $tickets = $query->latest('last_reply_at')->paginate(20);
        
        // Statistics
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'pending' => SupportTicket::where('status', 'pending')->count(),
            'answered' => SupportTicket::where('status', 'answered')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
            'unread' => SupportMessage::where('is_admin', false)->where('is_read', false)->count(),
        ];
        
        return view('admin.support.index', compact('tickets', 'stats'));
    }

    /**
     * Display the specified ticket.
     */
    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'messages', 'assignedTo']);
        
        // Mark all messages as read
        $ticket->messages()->where('is_admin', false)->where('is_read', false)->update(['is_read' => true]);
        
        return view('admin.support.show', compact('ticket'));
    }

    /**
     * Send a reply to a ticket.
     */
    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_admin' => true,
        ]);

        $ticket->update([
            'status' => 'answered',
            'last_reply_at' => now(),
            'assigned_to' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'تم إرسال الرد بنجاح');
    }

    /**
     * Update ticket status.
     */
    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,pending,answered,closed',
        ]);

        $ticket->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'تم تحديث حالة التذكرة');
    }

    /**
     * Update ticket priority.
     */
    public function updatePriority(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket->update(['priority' => $validated['priority']]);

        return redirect()->back()->with('success', 'تم تحديث أولوية التذكرة');
    }

    /**
     * Get new messages for real-time updates (AJAX).
     */
    public function getNewMessages(Request $request, SupportTicket $ticket)
    {
        $lastMessageId = $request->input('last_message_id', 0);
        
        $newMessages = $ticket->messages()
            ->where('id', '>', $lastMessageId)
            ->get();
        
        return response()->json([
            'success' => true,
            'messages' => $newMessages,
            'ticket' => $ticket->fresh(),
        ]);
    }

    /**
     * Get all unread count for dashboard.
     */
    public function getUnreadCount()
    {
        $count = SupportMessage::where('is_admin', false)
            ->where('is_read', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Delete a ticket.
     */
    public function destroy(SupportTicket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.support.index')->with('success', 'تم حذف التذكرة بنجاح');
    }
}

