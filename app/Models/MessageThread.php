<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageThread extends Model
{
    protected $table = 'message_threads';
    
    protected $fillable = [
        'phone',
        'name',
        'status',
        'is_auto_reply_sent',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'is_auto_reply_sent' => 'boolean',
    ];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }
}
