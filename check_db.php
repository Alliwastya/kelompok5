<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== RECENT ORDERS ===\n";
$orders = \App\Models\Order::latest()->limit(3)->get(['id', 'customer_phone', 'customer_name', 'order_number']);
foreach ($orders as $order) {
    echo "Phone: {$order->customer_phone} | Name: {$order->customer_name} | Order: {$order->order_number}\n";
}

echo "\n=== MESSAGE THREADS ===\n";
$threads = \App\Models\MessageThread::latest()->limit(3)->get(['id', 'phone', 'name']);
foreach ($threads as $thread) {
    echo "Phone: {$thread->phone} | Name: {$thread->name} | ID: {$thread->id}\n";
}

echo "\n=== PHONE FORMAT CHECK ===\n";
if ($orders->count() > 0 && $threads->count() > 0) {
    $orderPhone = $orders->first()->customer_phone;
    $threadPhone = $threads->first()->phone;
    echo "Latest Order Phone: '{$orderPhone}' (length: " . strlen($orderPhone) . ")\n";
    echo "Latest Thread Phone: '{$threadPhone}' (length: " . strlen($threadPhone) . ")\n";
    echo "Match: " . ($orderPhone === $threadPhone ? 'YES' : 'NO') . "\n";
}
