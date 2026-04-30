<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->unique();
            $table->string('otp_code');
            $table->integer('attempt_count')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index('phone_number');
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_verifications');
    }
};
