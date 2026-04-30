<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->decimal('price_original', 15, 2)->default(0);
            $table->decimal('price_promo', 15, 2)->default(0);
            $table->string('image_main')->nullable();
            $table->string('image_second')->nullable();
            $table->string('image_third')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('discount_badge_text')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_settings');
    }
};
