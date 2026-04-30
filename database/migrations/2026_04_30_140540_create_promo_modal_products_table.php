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
        Schema::create('promo_modal_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->string('badge')->nullable();
            $table->decimal('price_original', 15, 2)->nullable();
            $table->decimal('price_promo', 15, 2);
            $table->string('image')->nullable();
            $table->string('stock_label')->default('Ready Hari Ini 📦');
            $table->string('bottom_label')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_modal_products');
    }
};
