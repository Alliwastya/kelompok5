<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $table = 'chat_messages';
    
    protected $fillable = [
        'message_thread_id',
        'order_id',
        'sender_type',
        'message_type',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function thread()
    {
        return $this->belongsTo(MessageThread::class, 'message_thread_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
