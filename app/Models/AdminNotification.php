<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'link',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    protected $appends = ['time_ago'];

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public static function createNotification($type, $title, $message, $icon = '🔔', $link = null)
    {
        return self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'link' => $link,
        ]);
    }
}
