<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityLog extends Model
{
    protected $fillable = [
        'ip_address',
        'phone_number',
        'event_type',
        'order_count',
        'details',
        'is_blocked',
        'blocked_until'
    ];

    protected $casts = [
        'blocked_until' => 'datetime',
        'is_blocked' => 'boolean',
    ];

    public static function logOrderAttempt($ipAddress, $phoneNumber, $details = null)
    {
        $log = self::where('ip_address', $ipAddress)
            ->where('phone_number', $phoneNumber)
            ->where('event_type', 'order_attempt')
            ->whereDate('created_at', today())
            ->first();

        if ($log) {
            $log->increment('order_count');
            $log->update(['details' => $details]);
        } else {
            self::create([
                'ip_address' => $ipAddress,
                'phone_number' => $phoneNumber,
                'event_type' => 'order_attempt',
                'order_count' => 1,
                'details' => $details
            ]);
        }
    }

    public static function isIpBlocked($ipAddress)
    {
        $log = self::where('ip_address', $ipAddress)
            ->where('is_blocked', true)
            ->where(function ($query) {
                $query->whereNull('blocked_until')
                    ->orWhere('blocked_until', '>', now());
            })
            ->first();

        return $log !== null;
    }

    public static function blockIp($ipAddress, $minutes = 60)
    {
        // Update all logs with this IP address to blocked status
        self::where('ip_address', $ipAddress)->update([
            'is_blocked' => true,
            'blocked_until' => now()->addMinutes($minutes)
        ]);
        
        \Log::info("[Security] IP blocked: {$ipAddress} for {$minutes} minutes");
    }

    public static function getTodayOrderCount($ipAddress, $phoneNumber)
    {
        $log = self::where('ip_address', $ipAddress)
            ->where('phone_number', $phoneNumber)
            ->where('event_type', 'order_attempt')
            ->whereDate('created_at', today())
            ->first();

        return $log?->order_count ?? 0;
    }
}
