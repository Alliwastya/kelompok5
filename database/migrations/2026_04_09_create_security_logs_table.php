<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('phone_number')->nullable();
            $table->string('event_type'); // 'order_attempt', 'failed_captcha', 'suspicious_activity'
            $table->integer('order_count')->default(0);
            $table->text('details')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('blocked_until')->nullable();
            $table->timestamps();
            
            $table->index('ip_address');
            $table->index('phone_number');
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
