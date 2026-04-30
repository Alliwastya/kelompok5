<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SecurityLog;

class CheckOrderSecurity
{
    public function handle(Request $request, Closure $next)
    {
        // Only check on checkout endpoint
        if (!$request->is('api/checkout') && !$request->is('checkout')) {
            return $next($request);
        }

        $ipAddress = $this->getClientIp($request);
        $phoneNumber = $request->input('customer_phone');

        // Check if IP is blocked
        if (SecurityLog::isIpBlocked($ipAddress)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Terlalu banyak percobaan order. Coba lagi nanti.'
            ], 429);
        }

        // Check order frequency (max 5 orders per IP per day)
        $todayOrderCount = SecurityLog::getTodayOrderCount($ipAddress, $phoneNumber);
        if ($todayOrderCount >= 5) {
            SecurityLog::blockIp($ipAddress, 120); // Block for 2 hours
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak order dari IP ini. Akses diblokir sementara.'
            ], 429);
        }

        // Check for suspicious patterns
        if ($this->isSuspiciousOrder($request)) {
            SecurityLog::logOrderAttempt($ipAddress, $phoneNumber, 'Suspicious pattern detected');
            return response()->json([
                'success' => false,
                'message' => 'Order tidak valid. Silakan coba lagi.'
            ], 400);
        }

        return $next($request);
    }

    private function isSuspiciousOrder(Request $request)
    {
        // Check for extremely large quantities
        $items = $request->input('items', []);
        foreach ($items as $item) {
            if ($item['quantity'] > 100) {
                return true;
            }
        }

        // Check for empty/invalid customer data
        if (empty($request->input('customer_name')) || strlen($request->input('customer_name')) < 3) {
            return true;
        }

        // Check for invalid phone format
        $phone = $request->input('customer_phone');
        if (!preg_match('/^(\+62|0)[0-9]{9,12}$/', preg_replace('/[^0-9+]/', '', $phone))) {
            return true;
        }

        return false;
    }

    private function getClientIp(Request $request)
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return trim($ip);
    }
}
