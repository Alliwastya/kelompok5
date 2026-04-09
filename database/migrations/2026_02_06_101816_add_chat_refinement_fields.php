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
        Schema::table('message_threads', function (Blueprint $table) {
            $table->boolean('is_auto_reply_sent')->default(false)->after('status');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable()->after('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('message_threads', function (Blueprint $table) {
            $table->dropColumn('is_auto_reply_sent');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn('read_at');
        });
    }
};
