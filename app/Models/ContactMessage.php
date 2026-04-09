<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'sender_name',
        'sender_email',
        'subject',
        'message',
        'is_read',
        'reply',
        'replied_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $appends = ['time_ago'];

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
