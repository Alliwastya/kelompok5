<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraudDetection extends Model
{
    protected $fillable = [
        'order_id',
        'phone_number',
        'ip_address',
        'risk_score',
        'risk_factors',
        'status',
        'notes',
        'reviewed_at'
    ];

    protected $casts = [
        'risk_factors' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Analyze order untuk fraud detection dengan sistem 3 level
     */
    public static function analyzeOrder($order, $ipAddress)
    {
        $riskFactors = [];
        $riskScore = 0;

        try {
            // Get customer reputation
            $reputation = \App\Models\CustomerReputation::getOrCreate(
                $order->customer_phone,
                $order->customer_name
            );

            // LEVEL 1: Check if BLOCKED (manual or auto)
            if ($reputation->isBlacklisted()) {
                $riskFactors[] = 'Customer blacklisted';
                $riskScore = 100;
                
                return self::create([
                    'order_id' => $order->id,
                    'phone_number' => $order->customer_phone,
                    'ip_address' => $ipAddress,
                    'risk_score' => 100,
                    'risk_factors' => $riskFactors,
                    'status' => 'rejected'
                ]);
            }

            // Check IP blocked
            if (\App\Models\SecurityLog::isIpBlocked($ipAddress)) {
                $riskFactors[] = 'IP address blocked';
                $riskScore = 100;
                
                return self::create([
                    'order_id' => $order->id,
                    'phone_number' => $order->customer_phone,
                    'ip_address' => $ipAddress,
                    'risk_score' => 100,
                    'risk_factors' => $riskFactors,
                    'status' => 'rejected'
                ]);
            }

            // LEVEL 2: Check SUSPICIOUS patterns (tidak auto-block)
            
            // 1. Check order frequency (> 3x dalam 1 menit)
            $recentOrders = \App\Models\Order::where('customer_phone', $order->customer_phone)
                ->where('created_at', '>', now()->subMinute())
                ->count();
            
            if ($recentOrders > 3) {
                $riskFactors[] = 'Multiple orders in 1 minute';
                $riskScore += 30;
            }

            // 2. Check same IP orders (> 5x hari ini)
            $sameIpOrders = \App\Models\SecurityLog::where('ip_address', $ipAddress)
                ->where('event_type', 'order_attempt')
                ->whereDate('created_at', today())
                ->sum('order_count');

            if ($sameIpOrders > 5) {
                $riskFactors[] = 'Multiple orders from same IP today';
                $riskScore += 20;
            }

            // 3. Check phone verification
            if (!\App\Models\PhoneVerification::isPhoneVerified($order->customer_phone)) {
                $riskFactors[] = 'Phone not verified';
                $riskScore += 15;
            }

            // 4. First time customer (minor risk)
            $previousOrders = \App\Models\Order::where('customer_phone', $order->customer_phone)
                ->where('id', '!=', $order->id)
                ->where('status', '!=', 'cancelled')
                ->count();

            if ($previousOrders === 0) {
                $riskFactors[] = 'First time customer';
                $riskScore += 10;
            } else {
                // Trusted customer bonus
                if ($reputation->status === 'trusted') {
                    $riskScore -= 20; // Reduce risk
                }
            }

            // 5. Check suspicious name patterns
            if (preg_match('/test|fake|dummy|xxx|asdf|qwerty/i', $order->customer_name)) {
                $riskFactors[] = 'Suspicious customer name';
                $riskScore += 25;
            }

            // 6. Check address validity (terlalu pendek)
            if (strlen($order->customer_address) < 15) {
                $riskFactors[] = 'Address too short';
                $riskScore += 10;
            }

            // 7. Check duplicate orders (sama persis hari ini)
            $duplicateOrders = \App\Models\Order::where('customer_phone', $order->customer_phone)
                ->where('customer_address', $order->customer_address)
                ->where('id', '!=', $order->id)
                ->whereDate('created_at', today())
                ->count();

            if ($duplicateOrders > 0) {
                $riskFactors[] = 'Duplicate order detected';
                $riskScore += 20;
            }

            // 8. Check customer reputation
            if ($reputation->status === 'suspicious') {
                $riskFactors[] = 'Customer has suspicious history';
                $riskScore += 10;
            }

            // Ensure score tidak negatif
            $riskScore = max(0, min($riskScore, 100));

            // Determine status based on risk score
            $status = 'pending';
            if ($riskScore >= 70) {
                $status = 'pending'; // High risk tapi tidak auto-reject
            } elseif ($riskScore >= 40) {
                $status = 'pending'; // Medium risk
            }

            // Create fraud detection record
            $fraud = self::create([
                'order_id' => $order->id,
                'phone_number' => $order->customer_phone,
                'ip_address' => $ipAddress,
                'risk_score' => $riskScore,
                'risk_factors' => $riskFactors,
                'status' => $status
            ]);

            return $fraud;
            
        } catch (\Exception $e) {
            // FAIL-SAFE: Jika error, anggap NORMAL
            \Log::error('[FraudDetection] Error: ' . $e->getMessage());
            
            return self::create([
                'order_id' => $order->id,
                'phone_number' => $order->customer_phone,
                'ip_address' => $ipAddress,
                'risk_score' => 0,
                'risk_factors' => ['System check passed'],
                'status' => 'approved'
            ]);
        }
    }

    /**
     * Get risk level
     */
    public function getRiskLevel()
    {
        if ($this->risk_score >= 70) {
            return 'HIGH';
        } elseif ($this->risk_score >= 40) {
            return 'MEDIUM';
        }
        return 'LOW';
    }
}
