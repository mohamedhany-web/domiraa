<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SupportChatController extends Controller
{
    /**
     * Get user's tickets or create one
     */
    public function getTickets(Request $request)
    {
        $guestId = $request->input('guest_id');
        
        if (Auth::check()) {
            $tickets = SupportTicket::where('user_id', Auth::id())
                ->with(['messages' => function($query) {
                    $query->latest()->take(1);
                }])
                ->latest()
                ->get();
        } elseif ($guestId) {
            $tickets = SupportTicket::where('guest_id', $guestId)
                ->with(['messages' => function($query) {
                    $query->latest()->take(1);
                }])
                ->latest()
                ->get();
        } else {
            $tickets = collect([]);
        }

        return response()->json([
            'success' => true,
            'tickets' => $tickets,
            'unread_count' => $tickets->sum(function($ticket) {
                return $ticket->messages->where('is_admin', true)->where('is_read', false)->count();
            })
        ]);
    }

    /**
     * Create a new ticket with first message
     */
    public function createTicket(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'required|string|max:2000',
            'subject' => 'nullable|string|max:255',
            'guest_id' => 'nullable|string',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'guest_id' => $validated['guest_id'] ?? Str::uuid()->toString(),
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'],
            'subject' => $validated['subject'] ?? 'استفسار عام',
            'status' => 'open',
            'last_reply_at' => now(),
        ]);

        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_admin' => false,
        ]);

        return response()->json([
            'success' => true,
            'ticket' => $ticket->load('messages'),
            'guest_id' => $ticket->guest_id,
        ]);
    }

    /**
     * Send a message to existing ticket
     */
    public function sendMessage(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'guest_id' => 'nullable|string',
        ]);

        // Verify ownership
        if (Auth::check()) {
            if ($ticket->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
            }
        } else {
            if ($ticket->guest_id !== $validated['guest_id']) {
                return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
            }
        }

        $message = SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_admin' => false,
        ]);

        $ticket->update([
            'status' => 'pending',
            'last_reply_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Get messages for a ticket
     */
    public function getMessages(Request $request, SupportTicket $ticket)
    {
        $guestId = $request->input('guest_id');

        // Verify ownership
        if (Auth::check()) {
            if ($ticket->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
            }
        } else {
            if ($ticket->guest_id !== $guestId) {
                return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
            }
        }

        // Mark admin messages as read
        $ticket->messages()->where('is_admin', true)->where('is_read', false)->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'ticket' => $ticket,
            'messages' => $ticket->messages,
        ]);
    }

    /**
     * Poll for new messages
     */
    public function pollMessages(Request $request, SupportTicket $ticket)
    {
        $guestId = $request->input('guest_id');
        $lastMessageId = $request->input('last_message_id', 0);

        // Verify ownership
        if (Auth::check()) {
            if ($ticket->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
            }
        } else {
            if ($ticket->guest_id !== $guestId) {
                return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
            }
        }

        $newMessages = $ticket->messages()
            ->where('id', '>', $lastMessageId)
            ->get();

        // Mark admin messages as read
        $ticket->messages()
            ->where('is_admin', true)
            ->where('is_read', false)
            ->where('id', '>', $lastMessageId)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $newMessages,
            'ticket_status' => $ticket->fresh()->status,
        ]);
    }
}

