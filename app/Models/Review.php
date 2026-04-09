<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'display_name',
        'rating',
        'comment',
        'media_urls',
        'is_visible',
    ];

    protected $casts = [
        'media_urls' => 'array',
        'is_visible' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
