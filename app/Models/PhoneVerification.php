<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    protected $fillable = [
        'phone_number',
        'otp_code',
        'attempt_count',
        'is_verified',
        'verified_at',
        'expires_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Generate OTP untuk nomor telepon
     */
    public static function generateOtp($phoneNumber)
    {
        $otp = rand(100000, 999999);
        
        $verification = self::updateOrCreate(
            ['phone_number' => $phoneNumber],
            [
                'otp_code' => $otp,
                'attempt_count' => 0,
                'is_verified' => false,
                'expires_at' => now()->addMinutes(10)
            ]
        );

        return $verification;
    }

    /**
     * Verify OTP
     */
    public static function verifyOtp($phoneNumber, $otp)
    {
        $verification = self::where('phone_number', $phoneNumber)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return ['success' => false, 'message' => 'OTP sudah expired'];
        }

        if ($verification->attempt_count >= 3) {
            return ['success' => false, 'message' => 'Terlalu banyak percobaan. Coba lagi nanti.'];
        }

        if ((string)$verification->otp_code !== (string)$otp) {
            $verification->increment('attempt_count');
            return ['success' => false, 'message' => 'OTP tidak valid'];
        }

        $verification->update([
            'is_verified' => true,
            'verified_at' => now()
        ]);

        return ['success' => true, 'message' => 'Nomor telepon berhasil diverifikasi'];
    }

    /**
     * Check if phone is verified
     */
    public static function isPhoneVerified($phoneNumber)
    {
        return self::where('phone_number', $phoneNumber)
            ->where('is_verified', true)
            ->where('verified_at', '>', now()->subDays(30))
            ->exists();
    }
}
