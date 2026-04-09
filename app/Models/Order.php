<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'queue_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'house_details',
        'city',
        'street',
        'house_number',
        'rt_rw',
        'total_amount',
        'discount_amount',
        'shipping_cost',
        'promo_id',
        'status',
        'payment_method',
        'payment_status',
        'payment_proof',
        'notes',
        'message_thread_id',
        'admin_response',
        'estimated_ready_at',
        'responded_at',
        'estimated_delivery_date',
        'estimated_delivery_time',
        'estimated_delivery_message',
        'shipping_method',
    ];

    protected $casts = [
        'estimated_ready_at' => 'datetime',
        'responded_at' => 'datetime',
        'estimated_delivery_date' => 'date',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function getFinalTotalAttribute()
    {
        return $this->total_amount - $this->discount_amount + $this->shipping_cost;
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function messageThread()
    {
        return $this->belongsTo(MessageThread::class);
    }
}
