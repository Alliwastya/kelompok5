<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_name',
        'cost',
        'type',
        'is_active'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
