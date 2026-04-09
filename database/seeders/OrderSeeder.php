<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::inRandomOrder()->take(3)->get();

        // Create 5 sample orders
        for ($i = 0; $i < 5; $i++) {
            $orderNumber = 'ORD-' . now()->subDays(rand(0,10))->format('YmdHis') . '-' . Str::upper(Str::random(4));
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => 'Customer ' . ($i+1),
                'customer_phone' => '0812' . rand(100000,999999),
                'customer_email' => 'customer' . ($i+1) . '@example.com',
                'customer_address' => 'Jl. Contoh No. ' . ($i+1),
                'payment_method' => 'Transfer Bank (BCA)',
                'total_amount' => 0,
                'status' => ['pending','processing','shipped','delivered'][array_rand([0,1,2,3])],
            ]);

            $total = 0;
            foreach ($products as $prod) {
                $qty = rand(1,3);
                $subtotal = $prod->price * $qty;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $prod->name,
                    'price' => $prod->price,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $order->update(['total_amount' => $total]);
        }
    }
}
