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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_thread_id')->constrained('message_threads')->cascadeOnDelete();
            $table->enum('sender_type', ['user', 'admin']);
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            $table->index('message_thread_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
