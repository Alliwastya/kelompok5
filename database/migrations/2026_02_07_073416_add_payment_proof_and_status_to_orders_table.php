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
        Schema::table('orders', function (Blueprint $table) {
            // Payment proof image path (for QRIS)
            $table->string('payment_proof')->nullable()->after('payment_status');
            
            // Note: payment_status was already added in 2026_02_05_085426_add_details_to_orders_table.php
            // If it wasn't, we would add it here:
            // $table->string('payment_status')->default('unpaid')->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
        });
    }
};
