<?php

namespace App\Http\Controllers;

use App\Models\MessageThread;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private function normalizePhone($phone)
    {
        if (!$phone) return '';
        $normalized = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($normalized, '62')) {
            $normalized = '0' . substr($normalized, 2);
        } elseif (!str_starts_with($normalized, '0') && strlen($normalized) > 0) {
            $normalized = '0' . $normalized;
        }
        return $normalized;
    }

    public function getThread(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $phone = $this->normalizePhone($request->phone);
        $thread = MessageThread::where('phone', $phone)->first();

        if (!$thread) {
            return response()->json([
                'thread' => null,
                'messages' => []
            ]);
        }

        $messages = $thread->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'message' => $msg->message,
                    'is_read' => $msg->is_read,
                    'created_at_formatted' => $msg->created_at->format('H:i'),
                    'created_at' => $msg->created_at->format('d M Y H:i'),
                ];
            });

        return response()->json([
            'thread' => $thread,
            'messages' => $messages
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        $phone = $this->normalizePhone($validated['phone']);

        // Get or create thread
        $thread = MessageThread::where('phone', $phone)->firstOrCreate([
            'phone' => $phone,
        ], [
            'name' => $validated['name'],
            'status' => 'open',
        ]);

        // Create chat message
        $chatMessage = ChatMessage::create([
            'message_thread_id' => $thread->id,
            'sender_type' => 'user',
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        // Auto-reply logic
        $autoReplyMessage = null;
        if (!$thread->is_auto_reply_sent) {
            $autoReplyContent = "Terima kasih sudah menghubungi kami 😊,Kami akan memberi tahu admin terlebih dahulu dan admin akan segera membalas pertanyaan Anda. \nMohon ditunggu ya 🙏";
            
            $autoReplyMessage = ChatMessage::create([
                'message_thread_id' => $thread->id,
                'sender_type' => 'admin',
                'message_type' => 'text',
                'message' => $autoReplyContent,
                'is_read' => true,
            ]);

            $thread->update([
                'is_auto_reply_sent' => true,
                'last_message_at' => now(),
            ]);
        } else {
            // Update thread timestamp anyway
            $thread->update(['last_message_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim!',
            'thread_id' => $thread->id,
            'chat_message' => $chatMessage,
            'auto_reply' => $autoReplyMessage
        ]);
    }

    /**
     * Get unread messages count & latest unread messages
     */
    public function getUnreadNotifications(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $phone = $this->normalizePhone($request->phone);
        $thread = MessageThread::where('phone', $phone)->first();

        if (!$thread) {
            return response()->json([
                'unread_count' => 0,
                'unread_messages' => []
            ]);
        }

        // Get unread messages dari admin saja
        $unreadMessages = ChatMessage::where('message_thread_id', $thread->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'unread_count' => $unreadMessages->count(),
            'unread_messages' => $unreadMessages->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'type' => $msg->message_type,
                    'created_at' => $msg->created_at->format('H:i'),
                    'is_read' => $msg->is_read,
                ];
            })
        ]);
    }

    /**
     * Mark messages as read untuk user
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $phone = $this->normalizePhone($request->phone);
        $thread = MessageThread::where('phone', $phone)->first();

        if (!$thread) {
            return response()->json([
                'success' => false,
                'message' => 'Thread tidak ditemukan'
            ], 404);
        }

        // Mark semua pesan dari admin sebagai read
        ChatMessage::where('message_thread_id', $thread->id)
            ->where('sender_type', 'admin')
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan ditandai sebagai sudah dibaca'
        ]);
    }
}
