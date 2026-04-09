@extends('layouts.admin')

@section('page-title', 'Pesan')

@section('content')
<style>
    .messages-header {
        margin-bottom: 2rem;
    }
    
    .messages-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #FFD700;
        margin-bottom: 0.5rem;
    }
    
    .messages-header p {
        color: #999;
        font-size: 0.875rem;
    }
    
    .message-thread {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: block;
        color: inherit;
    }
    
    .message-thread:hover {
        background: linear-gradient(135deg, #333 0%, #3a3a3a 100%);
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.2);
        transform: translateY(-2px);
    }
    
    .thread-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    
    .thread-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1a1a1a;
        font-weight: 700;
        font-size: 1.25rem;
        margin-right: 1rem;
    }
    
    .thread-info {
        flex: 1;
    }
    
    .thread-name {
        font-weight: 700;
        color: #FFD700;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }
    
    .thread-phone {
        color: #999;
        font-size: 0.875rem;
    }
    
    .thread-unread {
        background: #f44336;
        color: #fff;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .thread-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #444;
    }
    
    .thread-message {
        color: #ccc;
        font-size: 0.875rem;
        font-style: italic;
        max-width: 400px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .thread-time {
        color: #999;
        font-size: 0.75rem;
    }
    
    .thread-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .status-open {
        background: rgba(76, 175, 80, 0.2);
        color: #4CAF50;
    }
    
    .status-closed {
        background: rgba(158, 158, 158, 0.2);
        color: #9e9e9e;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #999;
    }
    
    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>

<div class="messages-header">
    <h1>💬 Pesan Pelanggan</h1>
    <p>Kelola percakapan dengan pelanggan Anda</p>
</div>

<div>
    @forelse($threads as $thread)
        <a href="{{ route('admin.messages.show', $thread->id) }}" class="message-thread">
            <div class="thread-header">
                <div style="display: flex; align-items: center; flex: 1;">
                    <div class="thread-avatar">{{ strtoupper(substr($thread->name, 0, 1)) }}</div>
                    <div class="thread-info">
                        <div class="thread-name">
                            {{ $thread->name }}
                            @if($thread->unread_count > 0)
                                <span class="thread-unread">{{ $thread->unread_count }}</span>
                            @endif
                        </div>
                        <div class="thread-phone">{{ $thread->phone }}</div>
                    </div>
                </div>
            </div>
            <div class="thread-meta">
                <div class="thread-message">
                    "{{ $thread->latestMessage?->message ?? 'Belum ada pesan' }}"
                </div>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span class="thread-time">{{ $thread->last_message_at?->diffForHumans() ?? '-' }}</span>
                    <span class="thread-status @if($thread->status === 'open') status-open @else status-closed @endif">
                        {{ $thread->status }}
                    </span>
                </div>
            </div>
        </a>
    @empty
        <div class="empty-state">
            <div class="empty-state-icon">💬</div>
            <p>Belum ada pesan masuk dari pelanggan.</p>
        </div>
    @endforelse
</div>

@if($threads->hasPages())
<div style="margin-top: 2rem;">
    {{ $threads->links() }}
</div>
@endif
@endsection
