<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoModalProduct extends Model
{
    protected $fillable = [
        'name',
        'subtitle',
        'badge',
        'price_original',
        'price_promo',
        'image',
        'stock_label',
        'bottom_label',
        'order',
    ];
}
