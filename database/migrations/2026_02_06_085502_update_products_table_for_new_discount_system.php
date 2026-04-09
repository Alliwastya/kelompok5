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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('discount_type', ['none', 'percentage', 'fixed'])->default('none')->after('price');
            $table->decimal('discount_value', 15, 2)->default(0)->after('discount_type');
            $table->boolean('is_discount_active')->default(false)->after('discount_value');
            
            if (Schema::hasColumn('products', 'discount_price')) {
                $table->dropColumn('discount_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
