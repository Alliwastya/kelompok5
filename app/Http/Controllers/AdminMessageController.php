<?php

namespace App\Http\Controllers;

use App\Models\MessageThread;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminMessageController extends Controller
{
    public function index()
    {
        $threads = MessageThread::withCount(['messages as unread_count' => function ($query) {
            $query->where('sender_type', 'user')->where('is_read', false);
        }])
        ->with('latestMessage')
        ->orderBy('last_message_at', 'desc')
        ->paginate(20);

        return view('admin.messages.index', compact('threads'));
    }

    public function show($id)
    {
        $thread = MessageThread::findOrFail($id);
        
        // Mark messages as read when admin opens the thread
        $thread->messages()->where('sender_type', 'user')->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        $messages = $thread->messages()->orderBy('created_at', 'asc')->get();

        return view('admin.messages.show', compact('thread', 'messages'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $thread = MessageThread::findOrFail($id);

        ChatMessage::create([
            'message_thread_id' => $thread->id,
            'sender_type' => 'admin',
            'message_type' => 'text',
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Reset auto reply flag so it can be sent again if user replies and admin is away
        $thread->update([
            'is_auto_reply_sent' => false,
            'last_message_at' => now(),
        ]);

        return back()->with('success', 'Balasan dikirim');
    }
}
