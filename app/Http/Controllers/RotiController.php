<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Message;
use App\Models\Product;
use App\Models\MessageThread;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RotiController extends Controller
{
    public function index()
    {
        $productsData = Product::where('is_available', true)
            ->orderBy('total_sold', 'desc')
            ->get();
        
        $bestsellerIds = $productsData->where('total_sold', '>', 0)->take(8)->pluck('id')->toArray();

        $products = $productsData->map(function($product) use ($bestsellerIds) {
            $stockStatus = $product->stock_status;
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float)$product->price,
                'description' => $product->description ?? 'Produk berkualitas dari Dapoer Budess',
                'image' => $product->image ? asset('storage/' . $product->image) : null,
                'category' => $product->category,
                'total_sold' => $product->total_sold,
                'bestseller' => in_array($product->id, $bestsellerIds),
                'discount_type' => $product->discount_type,
                'discount_value' => (float)$product->discount_value,
                'is_discount_active' => (bool)$product->is_discount_active,
                'effective_price' => (float)$product->effective_price,
                'stock' => $product->stock,
                'stock_status' => $stockStatus,
            ];
        })->toArray();
        
        // Fetch approved reviews for server-side rendering
        $reviews = \App\Models\Review::where('is_visible', true)
            ->with('order')
            ->latest()
            ->take(10)
            ->get();

        return view('roti', [
            'products' => $products, 
            'reviews' => $reviews
        ]);
    }

    public function checkout(Request $request)
    {
        try {
            \Log::info('Checkout started', ['data' => $request->all()]);
            
            // Manual validation to ensure JSON response for validation errors
            // Decode items_json if provided (from FormData)
            if ($request->has('items_json')) {
                $request->merge([
                    'items' => json_decode($request->items_json, true)
                ]);
            }

            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20',
                'customer_email' => 'nullable|email',
                'shipping_method' => 'required|string|in:delivery,pickup',
                'city' => 'required_if:shipping_method,delivery|nullable|string',
                'street' => 'required_if:shipping_method,delivery|nullable|string',
                'house_number' => 'required_if:shipping_method,delivery|nullable|string',
                'rt_rw' => 'required_if:shipping_method,delivery|nullable|string',
                'house_details' => 'nullable|string',
                'notes' => 'nullable|string',
                'payment_method' => 'required|string',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.product_name' => 'required|string',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|integer|min:1',
                'payment_proof' => 'nullable|image|max:2048', // 2MB max
            ]);

            // Normalize to 0 prefix (per user request)
            $phone = preg_replace('/[^0-9]/', '', $validated['customer_phone']);
            if (str_starts_with($phone, '62')) {
                $phone = '0' . substr($phone, 2);
            } elseif (!str_starts_with($phone, '0') && strlen($phone) > 0) {
                $phone = '0' . $phone;
            }
            $validated['customer_phone'] = $phone;

            return DB::transaction(function () use ($validated, $request) {
                // Validasi stok sebelum membuat order
                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    \Log::info("Stock validation for {$item['product_name']}: stock={$product?->stock}, quantity={$item['quantity']}");
                    
                    if (!$product) {
                        throw new \Exception("Produk dengan ID {$item['product_id']} tidak ditemukan");
                    }
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok {$item['product_name']} tidak cukup! Stok tersedia: {$product->stock}, Anda memesan: {$item['quantity']}");
                    }
                }

                // Calculate Subtotal
                $customerPhone = $this->normalizePhone($validated['customer_phone']);
                
                $subtotal = 0;
                $itemsDescription = [];
                foreach ($validated['items'] as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                    $itemsDescription[] = $item['quantity'] . 'x ' . $item['product_name'] . ' (Rp ' . number_format($item['price'], 0, ',', '.') . ')';
                }

                // Shipping is 0 initially, Admin will set it later for COD
                $shippingCost = 0;

                // Final Total
                $totalAmount = $subtotal + $shippingCost;

                $orderNumber = 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

                $messageThread = MessageThread::firstOrCreate(
                    ['phone' => $customerPhone],
                    [
                        'name' => $validated['customer_name'],
                        'status' => 'open'
                    ]
                );

                // Construct Full Address
                if ($validated['shipping_method'] === 'pickup') {
                    $fullAddress = "Ambil di Tempat (Bakery)";
                } else {
                    $fullAddress = "{$validated['street']} No. {$validated['house_number']}, RT/RW {$validated['rt_rw']}, {$validated['city']}";
                }

                if (!empty($validated['house_details'])) {
                    $fullAddress .= "\n(Ciri-ciri: {$validated['house_details']})";
                }
                if (!empty($validated['notes'])) {
                    $fullAddress .= "\n(Catatan: {$validated['notes']})";
                }

                $paymentStatus = 'unpaid';
                $initialStatus = 'pending'; // Default

                if ($validated['payment_method'] === 'QRIS') {
                    $paymentStatus = 'pending_confirmation';
                    $initialStatus = 'pending_payment'; // Menunggu Konfirmasi Pembayaran
                } else {
                    $initialStatus = 'pending_admin'; // Menunggu Konfirmasi Admin
                }

                // Handle payment proof upload
                $proofPath = null;
                if ($request->hasFile('payment_proof')) {
                    $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
                }

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'message_thread_id' => $messageThread->id,
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $customerPhone,
                    'customer_email' => $validated['customer_email'] ?? null,
                    'customer_address' => $fullAddress,
                    'house_details' => $validated['house_details'] ?? null,
                    'city' => $validated['city'] ?? '-',
                    'street' => $validated['street'] ?? '-',
                    'house_number' => $validated['house_number'] ?? '-',
                    'rt_rw' => $validated['rt_rw'] ?? '-',
                    'notes' => $validated['notes'] ?? null,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $paymentStatus,
                    'payment_proof' => $proofPath,
                    'total_amount' => $subtotal, // Use subtotal as amount (shipping added later)
                    'discount_amount' => 0, // Individual product discounts already in items price
                    'shipping_cost' => 0,
                    'promo_id' => null,
                    'status' => $initialStatus,
                    'shipping_method' => $validated['shipping_method'],
                ]);

                foreach ($validated['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    // Decrement stock
                    Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);

                    // Increment total_sold in products table
                    Product::where('id', $item['product_id'])->increment('total_sold', $item['quantity']);
                }

                $this->sendOrderMessageToAdmin($order, $messageThread, $itemsDescription);

                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibuat',
                    'order_number' => $orderNumber,
                ]);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Checkout Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function validatePromo(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $promo = \App\Models\Promo::active()->where('code', $request->code)->first();
        
        if (!$promo) {
            return response()->json(['valid' => false, 'message' => 'Kode promo tidak valid atau sudah kadaluarsa'], 404);
        }

        return response()->json([
            'valid' => true,
            'promo' => $promo,
            'message' => 'Promo berhasil diterapkan!'
        ]);
    }

    public function getShippingRates()
    {
        $rates = \App\Models\ShippingRate::active()->get();
        return response()->json($rates);
    }

    /**
     * Kirim pesan pesanan ke admin
     */
    private function sendOrderMessageToAdmin(Order $order, MessageThread $messageThread, array $itemsDescription)
    {
        // Format pesan pesanan
        $itemsList = implode("\n", $itemsDescription);
        
        $orderMessage = "🔔 *PESANAN BARU MASUK*\n\n" .
            "━━━━━━━━━━━━━━━━━━━━━\n" .
            "📋 *Detail Pesanan*\n" .
            "Nomor Pesanan: `{$order->order_number}`\n\n" .
            "👤 *Informasi Customer*\n" .
            "Nama: {$order->customer_name}\n" .
            "📞 Telepon: {$order->customer_phone}\n" .
            "📍 Alamat Lengkap:\n{$order->customer_address}\n\n" .
            "🛒 *Daftar Pesanan*\n" .
            $itemsList . "\n\n" .
            "💰 *Total Pembayaran*\n" .
            "Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n" .
            "💳 *Metode Pembayaran*\n";
        
        // Emphasize payment method
        if ($order->payment_method === 'COD') {
            $orderMessage .= "✅ *COD (BAYAR DI TEMPAT)*\n" .
                "💵 Customer akan membayar saat roti diterima\n\n";
        } elseif ($order->payment_method === 'QRIS') {
            $orderMessage .= "📱 *QRIS (Transfer Digital)*\n" .
                "⏳ Menunggu konfirmasi pembayaran\n\n";
        } else {
            $orderMessage .= "{$order->payment_method}\n\n";
        }
        
        $orderMessage .= "━━━━━━━━━━━━━━━━━━━━━\n" .
            "📝 Mohon segera konfirmasi dan berikan estimasi waktu penyelesaian pesanan ini.\n\n" .
            "Terima kasih! 🙏";

        ChatMessage::create([
            'message_thread_id' => $messageThread->id,
            'order_id' => $order->id,
            'sender_type' => 'user',
            'message_type' => 'order_notification',
            'message' => $orderMessage,
            'is_read' => false,
        ]);

        // Update last_message_at pada thread
        $messageThread->update(['last_message_at' => now()]);
    }

    /**
     * Get order status untuk customer
     */
    public function getOrderStatus($phone)
    {
        \Log::info('[OrderStatus] Request with phone:', ['raw_phone' => $phone]);
        
        // Normalize phone to 0 prefix
        $phone = $this->normalizePhone($phone);
        \Log::info('[OrderStatus] Normalized phone:', ['normalized_phone' => $phone]);

        // Get the message thread berdasarkan phone
        $thread = MessageThread::where('phone', $phone)->first();
        \Log::info('[OrderStatus] Thread lookup result:', ['found' => $thread ? 'YES' : 'NO', 'thread_id' => $thread?->id]);
        
        if (!$thread) {
            // Fallback: Check if there are orders for this phone but no thread yet
            // Now we search for 0 prefix by default, but also check 62 if existing data is migrated
            $altPhone = $phone;
            if (str_starts_with($phone, '0')) {
                $altPhone = '62' . substr($phone, 1);
            }
            
            \Log::info('[OrderStatus] No thread, checking for orders:', ['phone' => $phone, 'alt_phone' => $altPhone]);

            $existingOrder = Order::whereIn('customer_phone', [$phone, $altPhone])->latest()->first();
            \Log::info('[OrderStatus] Order lookup result:', ['found' => $existingOrder ? 'YES' : 'NO', 'order_id' => $existingOrder?->id]);
            
            if ($existingOrder) {
                \Log::info('[OrderStatus] Creating new thread from existing order');
                // Create thread for this customer from order info
                $thread = MessageThread::create([
                    'phone' => $phone,
                    'name' => $existingOrder->customer_name,
                    'status' => 'open'
                ]);

                // Link old orders to this thread (optional but good for consistency)
                Order::where('customer_phone', $phone)->update(['message_thread_id' => $thread->id]);
                Order::where('customer_phone', $altPhone)->update(['message_thread_id' => $thread->id]);
            } else {
                \Log::warning('[OrderStatus] No order found, returning 404');
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ditemukan pesanan dengan nomor telepon ini'
                ], 404);
            }
        }
        
        \Log::info('[OrderStatus] Success, returning data', ['thread_id' => $thread->id]);

        // Get semua order dari customer ini
        $orders = Order::where('message_thread_id', $thread->id)
            ->with('items')
            ->latest()
            ->get();

        // Get messages (notifikasi)
        $messages = ChatMessage::where('message_thread_id', $thread->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Format response dengan status info
        $ordersFormatted = $orders->map(function($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_label' => $this->formatStatusLabel($order->status),
                'created_at' => $order->created_at->format('d M Y H:i'),
                'responded_at' => $order->responded_at?->format('d M Y H:i'),
                'estimated_ready_at' => $order->estimated_ready_at?->format('H:i'),
                'estimated_delivery_date' => $order->estimated_delivery_date?->format('d M Y'),
                'estimated_delivery_time' => $order->estimated_delivery_time,
                'admin_response' => $order->admin_response,
                'payment_method' => $order->payment_method,
                'shipping_method' => $order->shipping_method,
                'payment_status' => $order->payment_status,
                'subtotal' => number_format($order->items->sum('subtotal'), 0, ',', '.'),
                'shipping_cost' => number_format($order->shipping_cost, 0, ',', '.'),
                'total_amount' => number_format($order->final_total, 0, ',', '.'),
                'has_reviewed' => $order->review()->exists(),
                'items' => $order->items->map(function($item) {
                    return [
                        'product_name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => number_format($item->price, 0, ',', '.'),
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'customer_name' => $thread->name,
            'customer_phone' => $thread->phone,
            'orders' => $ordersFormatted,
            'notifications' => $messages->map(function($msg) {
                return [
                    'type' => $msg->message_type,
                    'sender' => $msg->sender_type,
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->format('d M Y H:i'),
                    'is_read' => $msg->is_read,
                ];
            })
        ]);
    }
    /**
     * Upload bukti pembayaran untuk QRIS
     */
    public function uploadPaymentProof(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_proof' => 'required|image|max:2048', // Max 2MB
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            
            $order->update([
                'payment_proof' => $path,
                'payment_status' => 'pending_confirmation', // Update status to waiting for admin
                // Status order stays pending until admin confirms
            ]);

            // Notify Admin
            if ($order->message_thread_id) {
                // Determine sender type - system notification
                $message = "📸 *BUKTI PEMBAYARAN DIUPLOAD*\n\n" .
                    "Customer telah mengupload bukti pembayaran untuk pesanan *{$order->order_number}*.\n\n" .
                    "Mohon cek dan konfirmasi pembayaran.";

                ChatMessage::create([
                    'message_thread_id' => $order->message_thread_id,
                    'order_id' => $order->id,
                    'sender_type' => 'user', 
                    'message_type' => 'image', // Or specific type
                    'message' => $message,
                    'is_read' => false,
                    // If we want to store the image in chat too, we might need a separate field or logic
                    // For now, admin checks order details
                ]);
                
                $order->messageThread->update(['last_message_at' => now()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diupload',
                'payment_proof_url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengupload bukti pembayaran'], 400);
    }

    /**
     * Format status order ke label yang lebih rapi
     */
    private function formatStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu Konfirmasi',
            'processing' => 'Sedang Diproses',
            'shipped' => 'Dalam Pengiriman',
            'delivered' => 'Sudah Sampai',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Normalisasi nomor telepon ke format 08...
     */
    private function normalizePhone($phone)
    {
        if (!$phone) return '';
        $normalized = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($normalized, '62')) {
            $normalized = '0' . substr($normalized, 2);
        } elseif (!str_starts_with($normalized, '0') && strlen($normalized) > 0) {
            $normalized = '0' . $normalized;
        }
        return $normalized;
    }
}
