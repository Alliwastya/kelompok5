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
            // Nomor antrian
            $table->integer('queue_number')->nullable()->after('order_number');
            
            // Waktu estimasi selesai
            $table->dateTime('estimated_ready_at')->nullable()->after('notes');
            
            // Respon admin
            $table->text('admin_response')->nullable()->after('estimated_ready_at');
            
            // Waktu admin merespon
            $table->dateTime('responded_at')->nullable()->after('admin_response');
            
            // Link ke message thread untuk komunikasi
            $table->foreignId('message_thread_id')->nullable()->after('responded_at')->constrained('message_threads')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['message_thread_id']);
            $table->dropColumn([
                'queue_number',
                'estimated_ready_at',
                'admin_response',
                'responded_at',
                'message_thread_id'
            ]);
        });
    }
};
