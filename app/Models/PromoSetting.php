<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoSetting extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'price_original',
        'price_promo',
        'image_main',
        'image_second',
        'image_third',
        'badge_text',
        'discount_badge_text',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public static function getActive()
    {
        return self::where('is_active', true)->first() ?: self::first();
    }
}
