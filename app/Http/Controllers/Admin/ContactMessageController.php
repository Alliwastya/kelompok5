<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.messages.inbox', compact('messages'));
    }

    public function getUnread()
    {
        $messages = ContactMessage::where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $unreadCount = ContactMessage::where('is_read', false)->count();
        
        return response()->json([
            'messages' => $messages,
            'unread_count' => $unreadCount
        ]);
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => true]);
        
        return view('admin.messages.detail', compact('message'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);

        $message = ContactMessage::findOrFail($id);
        
        $message->update([
            'reply' => $request->reply,
            'replied_at' => now()
        ]);

        // Send email reply (optional - configure mail settings first)
        try {
            Mail::raw($request->reply, function ($mail) use ($message) {
                $mail->to($message->sender_email)
                    ->subject('Re: ' . $message->subject);
            });
        } catch (\Exception $e) {
            // Log error but don't fail
        }

        return redirect()->back()->with('success', 'Balasan berhasil dikirim!');
    }

    public function delete($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return response()->json(['success' => true]);
    }

    // Public endpoint untuk form kontak di website
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        ContactMessage::create([
            'sender_name' => $request->name,
            'sender_email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan Anda berhasil dikirim!'
        ]);
    }
}
