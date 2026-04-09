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
        Schema::table('messages', function (Blueprint $table) {
            // Rename columns untuk system dua arah
            $table->renameColumn('message', 'user_message');
            
            // Admin reply columns
            $table->text('admin_message')->nullable()->after('user_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->renameColumn('user_message', 'message');
            $table->dropColumn('admin_message');
        });
    }
};
