<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value
     */
    public static function setValue($key, $value, $type = 'text', $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );
    }

    /**
     * Get QRIS image URL
     */
    public static function getQrisImage()
    {
        try {
            $setting = self::where('key', 'qris_image')->first();
            
            if (!$setting || !$setting->value) {
                // Return default if no setting found
                return 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=DapoerBudessQRISPayment_Mockup';
            }
            
            $value = $setting->value;
            
            // If it's a file path (not URL), convert to URL
            if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
                // Check if file exists
                if (Storage::disk('public')->exists($value)) {
                    return Storage::url($value);
                } else {
                    // File not found, return default
                    \Log::warning('[PaymentSetting] QRIS image file not found: ' . $value);
                    return 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=DapoerBudessQRISPayment_Mockup';
                }
            }
            
            // Return URL as is
            return $value;
        } catch (\Exception $e) {
            \Log::error('[PaymentSetting] Error getting QRIS image: ' . $e->getMessage());
            return 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=DapoerBudessQRISPayment_Mockup';
        }
    }
}
