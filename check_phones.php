<?php
use App\Models\Order;
use App\Models\MessageThread;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$phones = ['0826726277', '0282727999'];

foreach ($phones as $phone) {
    echo "--- Checking Phone: $phone ---\n";
    $thread = MessageThread::where('phone', $phone)->first();
    if ($thread) {
        echo "Thread Found: ID={$thread->id}, Name={$thread->name}, Phone={$thread->phone}\n";
        $orders = Order::where('message_thread_id', $thread->id)->get();
        echo "Orders linked via thread_id (" . $orders->count() . "):\n";
        foreach ($orders as $o) {
            echo "  - ORD: {$o->order_number}, Status: {$o->status}\n";
        }
    } else {
        echo "Thread NOT Found\n";
    }

    $ordersByPhone = Order::where('customer_phone', $phone)->get();
    echo "Orders found by phone literal (" . $ordersByPhone->count() . "):\n";
    foreach ($ordersByPhone as $o) {
        echo "  - ORD: {$o->order_number}, Status: {$o->status}, ThreadID: " . ($o->message_thread_id ?? 'NULL') . "\n";
    }

    $altPhone = '62' . substr($phone, 1);
    $ordersByAltPhone = Order::where('customer_phone', $altPhone)->get();
    echo "Orders found by alt phone ($altPhone) (" . $ordersByAltPhone->count() . "):\n";
    foreach ($ordersByAltPhone as $o) {
        echo "  - ORD: {$o->order_number}, Status: {$o->status}, ThreadID: " . ($o->message_thread_id ?? 'NULL') . "\n";
    }
    echo "\n";
}
