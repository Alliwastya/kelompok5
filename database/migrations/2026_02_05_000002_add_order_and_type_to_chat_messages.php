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
        Schema::table('chat_messages', function (Blueprint $table) {
            // Link ke order (jika pesan adalah tentang pesanan)
            $table->foreignId('order_id')->nullable()->after('message_thread_id')->constrained('orders')->cascadeOnDelete();
            
            // Tipe pesan: message, order_notification, admin_response
            $table->string('message_type')->default('message')->after('sender_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['order_id']);
            $table->dropColumn(['order_id', 'message_type']);
        });
    }
};
