@extends('layouts.admin')

@section('page-title', 'Pesan Detail')

@section('content')
<style>
    .message-container {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        height: 600px;
    }
    
    .message-header {
        padding: 1.5rem;
        border-bottom: 1px solid #FFD700;
        background: #1a1a1a;
        border-radius: 12px 12px 0 0;
    }
    
    .message-header h1 {
        color: #FFD700;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .message-header p {
        color: #999;
        font-size: 0.875rem;
    }
    
    .messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .message {
        display: flex;
        gap: 0.75rem;
    }
    
    .message.admin {
        justify-content: flex-end;
    }
    
    .message-bubble {
        max-width: 75%;
        padding: 1rem;
        border-radius: 8px;
        word-wrap: break-word;
    }
    
    .message-bubble.admin {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #1a1a1a;
        border-radius: 8px 0 8px 8px;
    }
    
    .message-bubble.user {
        background: #1a1a1a;
        color: #ccc;
        border: 1px solid #FFD700;
        border-radius: 0 8px 8px 8px;
    }
    
    .message-time {
        font-size: 0.75rem;
        margin-top: 0.5rem;
        opacity: 0.7;
    }
    
    .reply-area {
        padding: 1.5rem;
        border-top: 1px solid #FFD700;
        background: #1a1a1a;
        border-radius: 0 0 12px 12px;
    }
    
    .reply-form {
        display: flex;
        gap: 1rem;
    }
    
    .reply-form textarea {
        flex: 1;
        background: #2a2a2a;
        border: 1px solid #FFD700;
        color: #fff;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-family: inherit;
        resize: none;
    }
    
    .reply-form textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
    }
    
    .reply-form button {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #1a1a1a;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .reply-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
    }
    
    .back-btn {
        color: #FFD700;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }
    
    .back-btn:hover {
        color: #FFA500;
    }
</style>

<a href="{{ route('admin.messages.index') }}" class="back-btn">← Kembali ke Pesan</a>

<div class="message-container">
    <!-- Header -->
    <div class="message-header">
        <h1>{{ $thread->name }}</h1>
        <p>{{ $thread->phone }}</p>
    </div>
    
    <!-- Messages -->
    <div class="messages-area" id="messages-container">
        @foreach($messages as $message)
        <div class="message @if($message->sender_type === 'admin') admin @endif">
            <div class="message-bubble @if($message->sender_type === 'admin') admin @else user @endif">
                <p style="white-space: pre-wrap;">{{ $message->message }}</p>
                <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Reply -->
    <div class="reply-area">
        <form action="{{ route('admin.messages.reply', $thread->id) }}" method="POST" class="reply-form">
            @csrf
            <textarea name="message" rows="1" placeholder="Ketik balasan Anda..." required></textarea>
            <button type="submit">Kirim</button>
        </form>
    </div>
</div>

<script>
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
</script>
@endsection
