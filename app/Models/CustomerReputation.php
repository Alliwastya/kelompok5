<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReputation extends Model
{
    protected $fillable = [
        'phone_number',
        'customer_name',
        'total_orders',
        'successful_orders',
        'cancelled_orders',
        'fraud_reports',
        'reputation_score',
        'status',
        'notes',
        'last_order_at',
        'blacklisted_until',
        'is_blacklisted'
    ];

    protected $casts = [
        'last_order_at' => 'datetime',
        'blacklisted_until' => 'datetime',
        'is_blacklisted' => 'boolean'
    ];

    /**
     * Get orders for this customer
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_phone', 'phone_number');
    }

    /**
     * Accessor for phone
     */
    public function getPhoneAttribute()
    {
        return $this->phone_number;
    }

    /**
     * Accessor for name
     */
    public function getNameAttribute()
    {
        return $this->customer_name;
    }

    /**
     * Accessor for email (from message thread)
     */
    public function getEmailAttribute()
    {
        $thread = MessageThread::where('phone', $this->phone_number)->first();
        return $thread?->email ?? null;
    }

    /**
     * Accessor for total_spent
     */
    public function getTotalSpentAttribute()
    {
        return Order::where('customer_phone', $this->phone_number)
            ->sum('total_amount');
    }

    /**
     * Accessor for average_rating
     */
    public function getAverageRatingAttribute()
    {
        return Review::whereHas('order', function($q) {
            $q->where('customer_phone', $this->phone_number);
        })->avg('rating') ?? 0;
    }

    /**
     * Accessor for complaints
     */
    public function getComplaintsAttribute()
    {
        return Order::where('customer_phone', $this->phone_number)
            ->where('status', 'cancelled')
            ->count();
    }

    /**
     * Get or create customer reputation
     */
    public static function getOrCreate($phoneNumber, $customerName = null)
    {
        return self::firstOrCreate(
            ['phone_number' => $phoneNumber],
            ['customer_name' => $customerName]
        );
    }

    /**
     * Update reputation after order
     */
    public static function updateAfterOrder($phoneNumber, $isSuccessful = true)
    {
        $reputation = self::getOrCreate($phoneNumber);
        
        $reputation->increment('total_orders');
        
        if ($isSuccessful) {
            $reputation->increment('successful_orders');
        } else {
            $reputation->increment('cancelled_orders');
        }

        $reputation->update(['last_order_at' => now()]);
        
        // Recalculate reputation score
        $reputation->calculateScore();
        
        return $reputation;
    }

    /**
     * Calculate reputation score
     */
    public function calculateScore()
    {
        $score = 50; // Base score

        // Success rate bonus
        if ($this->total_orders > 0) {
            $successRate = ($this->successful_orders / $this->total_orders) * 100;
            $score += ($successRate / 100) * 30; // Max +30 points
        }

        // Order count bonus (trusted customers)
        if ($this->total_orders >= 10) {
            $score += 15;
        } elseif ($this->total_orders >= 5) {
            $score += 10;
        } elseif ($this->total_orders >= 1) {
            $score += 5;
        }

        // Fraud penalty
        $score -= ($this->fraud_reports * 10);

        // Clamp score between 0-100
        $score = max(0, min(100, $score));

        // Determine status
        if ($score >= 80) {
            $status = 'trusted';
        } elseif ($score >= 60) {
            $status = 'normal';
        } elseif ($score >= 30) {
            $status = 'suspicious';
        } else {
            $status = 'blacklisted';
        }

        $this->update([
            'reputation_score' => $score,
            'status' => $status
        ]);

        return $this;
    }

    /**
     * Check if customer is blacklisted
     */
    public function isBlacklisted()
    {
        if ($this->status === 'blacklisted') {
            if ($this->blacklisted_until && $this->blacklisted_until > now()) {
                return true;
            }
            // Permanent blacklist
            if (!$this->blacklisted_until) {
                return true;
            }
        }
        return false;
    }

    /**
     * Blacklist customer
     */
    public function blacklist($reason = null, $durationDays = null)
    {
        $this->update([
            'status' => 'blacklisted',
            'is_blacklisted' => true,
            'notes' => $reason,
            'blacklisted_until' => $durationDays ? now()->addDays($durationDays) : null
        ]);

        return $this;
    }

    /**
     * Whitelist customer
     */
    public function whitelist()
    {
        $this->update([
            'status' => 'normal',
            'is_blacklisted' => false,
            'blacklisted_until' => null
        ]);

        return $this;
    }

    /**
     * Report fraud
     */
    public function reportFraud()
    {
        $this->increment('fraud_reports');
        $this->calculateScore();

        return $this;
    }
}
