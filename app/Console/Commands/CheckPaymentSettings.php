<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\DB;

class CheckPaymentSettings extends Command
{
    protected $signature = 'payment:check';
    protected $description = 'Check and fix payment settings';

    public function handle()
    {
        $this->info('Checking payment settings...');
        
        // Check if qris_image setting exists
        $qrisSetting = PaymentSetting::where('key', 'qris_image')->first();
        
        if (!$qrisSetting) {
            $this->warn('QRIS image setting not found. Creating default...');
            
            DB::table('payment_settings')->insert([
                'key' => 'qris_image',
                'value' => 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=DapoerBudessQRISPayment_Mockup',
                'type' => 'image',
                'description' => 'QR Code untuk pembayaran QRIS',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $this->info('✅ Default QRIS image setting created!');
        } else {
            $this->info('✅ QRIS image setting found:');
            $this->line('   Key: ' . $qrisSetting->key);
            $this->line('   Value: ' . $qrisSetting->value);
            $this->line('   Type: ' . $qrisSetting->type);
            
            // Test getQrisImage method
            $imageUrl = PaymentSetting::getQrisImage();
            $this->info('✅ getQrisImage() returns: ' . $imageUrl);
        }
        
        // List all payment settings
        $this->info("\nAll payment settings:");
        $settings = PaymentSetting::all();
        foreach ($settings as $setting) {
            $this->line("  - {$setting->key}: {$setting->value}");
        }
        
        return 0;
    }
}
