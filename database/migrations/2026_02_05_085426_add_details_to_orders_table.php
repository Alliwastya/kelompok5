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
            $table->string('city')->after('customer_address')->nullable();
            $table->string('street')->after('city')->nullable();
            $table->string('house_number')->after('street')->nullable();
            $table->string('rt_rw')->after('house_number')->nullable();
            $table->string('payment_status')->default('unpaid')->after('payment_method'); // unpaid, pending_confirmation, paid
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
