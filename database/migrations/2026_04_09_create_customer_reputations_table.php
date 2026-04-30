<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_reputations', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->unique();
            $table->string('customer_name')->nullable();
            $table->integer('total_orders')->default(0);
            $table->integer('successful_orders')->default(0);
            $table->integer('cancelled_orders')->default(0);
            $table->integer('fraud_reports')->default(0);
            $table->decimal('reputation_score', 5, 2)->default(50); // 0-100
            $table->enum('status', ['trusted', 'normal', 'suspicious', 'blacklisted'])->default('normal');
            $table->text('notes')->nullable();
            $table->timestamp('last_order_at')->nullable();
            $table->timestamp('blacklisted_until')->nullable();
            $table->timestamps();
            
            $table->index('phone_number');
            $table->index('status');
            $table->index('reputation_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_reputations');
    }
};
