<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // 'qris_image', 'bank_account', etc
            $table->text('value')->nullable(); // path to image or text value
            $table->string('type')->default('text'); // 'image', 'text'
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Insert default QRIS image setting
        DB::table('payment_settings')->insert([
            'key' => 'qris_image',
            'value' => 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=DapoerBudessQRISPayment_Mockup',
            'type' => 'image',
            'description' => 'QR Code untuk pembayaran QRIS',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
