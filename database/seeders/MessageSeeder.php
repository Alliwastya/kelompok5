<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MessageThread;
use App\Models\ChatMessage;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        // Create 4 sample threads with messages
        $threads = [
            [
                'name' => 'Andi',
                'phone' => '081234567890',
                'message' => 'Apakah roti ini tersedia hari ini?'
            ],
            [
                'name' => 'Budi',
                'phone' => '081298765432',
                'message' => 'Bisa order khusus 50 buah untuk acara?'
            ],
        ];

        foreach ($threads as $t) {
            $thread = MessageThread::create([
                'name' => $t['name'],
                'phone' => $t['phone'],
                'status' => 'open',
                'last_message_at' => now(),
            ]);

            ChatMessage::create([
                'message_thread_id' => $thread->id,
                'sender_type' => 'user',
                'message' => $t['message'],
                'is_read' => false,
            ]);
        }
    }
}
