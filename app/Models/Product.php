<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'minimum_stock',
        'image',
        'discount_type',
        'discount_value',
        'is_discount_active',
        'category',
        'is_available',
        'total_sold',
        'manual_status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'is_discount_active' => 'boolean',
        'is_available' => 'boolean'
    ];

    public function getEffectivePriceAttribute()
    {
        if (!$this->is_discount_active) {
            return $this->price;
        }

        if ($this->discount_type === 'percentage') {
            return $this->price * (1 - ($this->discount_value / 100));
        }

        if ($this->discount_type === 'fixed') {
            return max(0, $this->price - $this->discount_value);
        }

        return $this->price;
    }

    /**
     * Get stock status for user display
     */
    public function getStockStatusAttribute()
    {
        // Check manual status override first
        if ($this->manual_status === 'habis') {
            return [
                'label' => '❌ Habis – Bisa Pre Order untuk Besok',
                'color' => 'red',
                'can_order' => true,
                'is_preorder' => true
            ];
        }

        if ($this->manual_status === 'pre-order') {
            return [
                'label' => '📅 Pre-Order untuk Besok',
                'color' => 'orange',
                'can_order' => true,
                'is_preorder' => true
            ];
        }

        // Check actual stock
        if ($this->stock <= 0) {
            return [
                'label' => '❌ Habis – Bisa Pre Order untuk Besok',
                'color' => 'red',
                'can_order' => true,
                'is_preorder' => true
            ];
        }

        if ($this->stock <= $this->minimum_stock) {
            return [
                'label' => '🔥 Stok Terbatas Hari Ini',
                'color' => 'yellow',
                'can_order' => true,
                'is_preorder' => false
            ];
        }

        // Stock is good
        return [
            'label' => 'Ready Hari Ini 🍞',
            'color' => 'green',
            'can_order' => true,
            'is_preorder' => false
        ];
    }

    /**
     * Get stock badge color for admin
     */
    public function getStockBadgeColorAttribute()
    {
        if ($this->stock <= 0) {
            return 'red';
        }
        if ($this->stock <= $this->minimum_stock) {
            return 'yellow';
        }
        return 'green';
    }

    /**
     * Get today's sales count
     */
    public function getTodaySalesAttribute()
    {
        return $this->orderItems()
            ->whereHas('order', function($q) {
                $q->whereDate('created_at', today())
                  ->where('status', '!=', 'cancelled');
            })
            ->sum('quantity');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
