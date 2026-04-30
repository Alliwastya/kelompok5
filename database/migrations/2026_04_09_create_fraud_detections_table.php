<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fraud_detections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('phone_number');
            $table->string('ip_address');
            $table->integer('risk_score')->default(0); // 0-100
            $table->json('risk_factors')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index('phone_number');
            $table->index('status');
            $table->index('risk_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fraud_detections');
    }
};
