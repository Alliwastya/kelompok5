<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Bootstrap
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Check messages table columns
try {
    $columns = DB::select("SHOW COLUMNS FROM messages");
    echo "\n✅ Messages Table Columns:\n";
    echo str_repeat("=", 60) . "\n";
    foreach ($columns as $col) {
        echo "  - {$col->Field} ({$col->Type})\n";
    }
    
    // Get sample data
    echo "\n✅ Sample Messages:\n";
    echo str_repeat("=", 60) . "\n";
    $messages = DB::table('messages')->limit(3)->get();
    
    if ($messages->isEmpty()) {
        echo "  ℹ️  No messages in database yet\n";
    } else {
        foreach ($messages as $msg) {
            echo "  ID: {$msg->id}\n";
            echo "    Name: {$msg->name}\n";
            echo "    Phone: {$msg->phone}\n";
            echo "    User Message: " . substr($msg->user_message ?? '', 0, 50) . "...\n";
            echo "    Admin Message: " . (isset($msg->admin_message) && $msg->admin_message ? substr($msg->admin_message, 0, 50) . "..." : "(empty)") . "\n";
            echo "    Status: " . ($msg->status ?? 'unread') . "\n";
            echo "\n";
        }
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
