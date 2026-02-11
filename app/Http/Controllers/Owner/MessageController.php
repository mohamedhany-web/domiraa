<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $conversations = Message::where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with('sender', 'receiver', 'property')
            ->latest()
            ->get()
            ->groupBy(function($message) use ($user) {
                $otherUserId = $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
                return $otherUserId;
            });
        
        $unreadCount = Message::where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->count();
        
        return view('owner.messages', compact('conversations', 'unreadCount'));
    }

    public function show(Message $message)
    {
        $user = Auth::user();
        
        if ($message->receiver_id != $user->id && $message->sender_id != $user->id) {
            abort(403);
        }
        
        // Mark as read
        if ($message->receiver_id == $user->id && !$message->read_at) {
            $message->update(['read_at' => now()]);
        }
        
        // Get conversation
        $conversation = Message::where(function($q) use ($message, $user) {
            $q->where('sender_id', $message->sender_id)
              ->where('receiver_id', $message->receiver_id);
        })->orWhere(function($q) use ($message, $user) {
            $q->where('sender_id', $message->receiver_id)
              ->where('receiver_id', $message->sender_id);
        })->with('sender', 'receiver', 'property')
        ->latest()
        ->get();
        
        return view('owner.message-show', compact('message', 'conversation'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'property_id' => 'nullable|exists:properties,id',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);
        
        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'property_id' => $request->property_id,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        
        return redirect()->route('owner.messages')->with('success', 'تم إرسال الرسالة بنجاح');
    }

    public function reply(Request $request, Message $message)
    {
        $user = Auth::user();
        
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);
        
        $receiverId = $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
        
        Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'property_id' => $message->property_id,
            'subject' => 'Re: ' . ($message->subject ?? 'رسالة'),
            'message' => $request->message,
        ]);
        
        return redirect()->route('owner.messages.show', $message)->with('success', 'تم إرسال الرد بنجاح');
    }
}
