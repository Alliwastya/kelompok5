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

        // Generate CAPTCHA for checkout
        $captcha = \App\Services\CaptchaService::generateMath();

        return view('roti', [
            'products' => $products, 
            'reviews' => $reviews,
            'captcha' => $captcha
        ]);
    }

    public function checkout(Request $request)
    {
        try {
            \Log::info('Checkout started', ['data' => $request->all()]);
            
            // Get IP address for security check
            $ipAddress = $this->getClientIp($request);
            
            // If this is just a reCAPTCHA verification (before checkout form)
            if ($request->input('verify_only')) {
                $recaptchaToken = $request->input('recaptcha_token');
                
                if (!$recaptchaToken) {
                    return response()->json([
                        'success' => false,
                        'message' => 'reCAPTCHA verification diperlukan'
                    ], 422);
                }

                // Verify reCAPTCHA token with Google
                $recaptchaResponse = $this->verifyRecaptcha($recaptchaToken);
                
                \Log::info('[reCAPTCHA] Full response', $recaptchaResponse);
                
                if (!($recaptchaResponse['success'] ?? false)) {
                    \Log::warning('[reCAPTCHA] Verification failed', [
                        'response' => $recaptchaResponse
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'reCAPTCHA verification gagal. Silakan coba lagi.'
                    ], 422);
                }

                return response()->json([
                    'success' => true,
                    'captcha_verified' => true,
                    'message' => 'reCAPTCHA verified successfully'
                ]);
            }

            // SECURITY: Check if IP is blocked
            if (\App\Models\SecurityLog::isIpBlocked($ipAddress)) {
                \Log::warning('[Security] Blocked IP attempted checkout', ['ip' => $ipAddress]);
                return response()->json([
                    'success' => false,
                    'blocked' => true,
                    'message' => 'Akses dibatasi sementara. Silakan hubungi admin.'
                ], 403);
            }

            // Manual validation to ensure JSON response for validation errors
            // Decode items_json if provided (from FormData)
            if ($request->has('items_json')) {
                $request->merge([
                    'items' => json_decode($request->items_json, true)
                ]);
            }

            $validated = $request->validate([
                'customer_name' => 'required|string|max:255|min:3',
                'customer_phone' => 'required|string|max:20|min:10',
                'customer_email' => 'nullable|email',
                'shipping_method' => 'required|string|in:delivery,pickup',
                'city' => 'required_if:shipping_method,delivery|nullable|string',
                'street' => 'required_if:shipping_method,delivery|nullable|string',
                'house_number' => 'required_if:shipping_method,delivery|nullable|string',
                'rt_rw' => 'required_if:shipping_method,delivery|nullable|string',
                'house_details' => 'nullable|string',
                'notes' => 'nullable|string',
                'payment_method' => 'required|string',
                'items' => 'required|array|min:1|max:10',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.product_name' => 'required|string',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|integer|min:1|max:10',
                'payment_proof' => 'nullable|image|max:2048', // 2MB max
            ]);
            
            // SECURITY: Validate total items (max 10)
            $totalItems = collect($validated['items'])->sum('quantity');
            if ($totalItems > 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimal 10 item per pesanan.'
                ], 422);
            }
            
            // SECURITY: Validate phone number (only digits)
            $phoneDigits = preg_replace('/[^0-9]/', '', $validated['customer_phone']);
            if (strlen($phoneDigits) < 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon harus minimal 10 digit.'
                ], 422);
            }

            // Normalize to 0 prefix (per user request)
            $phone = preg_replace('/[^0-9]/', '', $validated['customer_phone']);
            if (str_starts_with($phone, '62')) {
                $phone = '0' . substr($phone, 2);
            } elseif (!str_starts_with($phone, '0') && strlen($phone) > 0) {
                $phone = '0' . $phone;
            }
            $validated['customer_phone'] = $phone;
            
            // SECURITY: Check if customer is blacklisted
            $reputation = \App\Models\CustomerReputation::getOrCreate($phone, $validated['customer_name']);
            if ($reputation->isBlacklisted()) {
                \Log::warning('[Security] Blacklisted customer attempted checkout', ['phone' => $phone]);
                return response()->json([
                    'success' => false,
                    'blocked' => true,
                    'message' => 'Akses dibatasi sementara. Silakan hubungi admin.'
                ], 403);
            }

            return DB::transaction(function () use ($validated, $request, $ipAddress) {
                // Security: Log order attempt
                $phoneNumber = $this->normalizePhone($validated['customer_phone']);
                \App\Models\SecurityLog::logOrderAttempt($ipAddress, $phoneNumber, 'Order created');

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

                // Fraud Detection
                $fraud = \App\Models\FraudDetection::analyzeOrder($order, $ipAddress);
                
                // If high risk, set order to pending review
                if ($fraud->risk_score >= 70) {
                    $order->update(['status' => 'pending_review']);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibuat',
                    'order_number' => $orderNumber,
                    'order_id' => $order->id,
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
     * Show payment page
     */
    public function showPaymentPage($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        
        // Get products for the page (same as index)
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
        
        return view('payment', [
            'orderId' => $order->id,
            'orderNumber' => $order->order_number,
            'total' => $order->final_total,
            'products' => $products
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

    private function getClientIp($request)
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return trim($ip);
    }

    /**
     * Send OTP untuk verifikasi nomor telepon
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:15'
        ]);

        $phone = $this->normalizePhone($request->input('phone'));
        
        // Generate OTP
        $verification = \App\Models\PhoneVerification::generateOtp($phone);

        // TODO: Send OTP via WhatsApp/SMS
        // Untuk sekarang, return OTP untuk testing
        \Log::info("OTP generated for $phone: {$verification->otp_code}");

        return response()->json([
            'success' => true,
            'message' => 'OTP telah dikirim ke nomor Anda',
            'otp' => $verification->otp_code // Remove in production
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string|size:6'
        ]);

        $phone = $this->normalizePhone($request->input('phone'));
        $otp = $request->input('otp');

        $result = \App\Models\PhoneVerification::verifyOtp($phone, $otp);

        return response()->json($result);
    }

    /**
     * Generate new CAPTCHA for checkout
     */
    public function generateCaptcha()
    {
        $captcha = \App\Services\CaptchaService::generateMath();
        
        return response()->json([
            'success' => true,
            'token' => $captcha['token'],
            'question' => $captcha['question']
        ]);
    }

    /**
     * Verify reCAPTCHA token with Google
     */
    private function verifyRecaptcha($token)
    {
        $secretKey = env('RECAPTCHA_SECRET_KEY');
        
        // For development/testing with test keys, skip actual verification
        if ($secretKey === '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe') {
            \Log::info('[reCAPTCHA] Using test keys - skipping verification');
            return [
                'success' => true,
                'score' => 0.9,
                'action' => 'checkout'
            ];
        }
        
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $token,
            ]);

            $result = $response->json();
            
            \Log::info('[reCAPTCHA] Verification result', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? null,
                'action' => $result['action'] ?? null,
                'challenge_ts' => $result['challenge_ts'] ?? null,
                'hostname' => $result['hostname'] ?? null,
                'error_codes' => $result['error-codes'] ?? []
            ]);

            return [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? 0,
                'action' => $result['action'] ?? null,
                'error_codes' => $result['error-codes'] ?? []
            ];
        } catch (\Exception $e) {
            \Log::error('[reCAPTCHA] Verification error', [
                'error' => $e->getMessage(),
                'token' => substr($token, 0, 20) . '...'
            ]);
            
            return [
                'success' => false,
                'score' => 0,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Show pre-order page
     */
    public function showPreorderPage()
    {
        return view('preorder');
    }

    /**
     * Submit pre-order
     */
    public function submitPreorder(Request $request)
    {
        try {
            \Log::info('Pre-order started', ['data' => $request->all()]);
            
            // Get IP address for security check
            $ipAddress = $this->getClientIp($request);
            
            // SECURITY: Check if IP is blocked
            if (\App\Models\SecurityLog::isIpBlocked($ipAddress)) {
                \Log::warning('[Security] Blocked IP attempted pre-order', ['ip' => $ipAddress]);
                return response()->json([
                    'success' => false,
                    'blocked' => true,
                    'message' => 'Akses dibatasi sementara. Silakan hubungi admin.'
                ], 403);
            }

            // Decode items from JSON string
            $items = json_decode($request->input('items'), true);
            
            $validated = $request->validate([
                'order_type' => 'required|in:preorder',
                'pickup_date' => 'required|date|after:today',
                'pickup_time' => 'nullable|date_format:H:i',
                'customer_name' => 'required|string|max:255|min:3',
                'customer_phone' => 'required|string|max:20|min:10',
                'customer_email' => 'nullable|email',
                'shipping_method' => 'required|string|in:delivery,pickup',
                'city' => 'required_if:shipping_method,delivery|nullable|string',
                'street' => 'required_if:shipping_method,delivery|nullable|string',
                'house_number' => 'required_if:shipping_method,delivery|nullable|string',
                'rt_rw' => 'required_if:shipping_method,delivery|nullable|string',
                'house_details' => 'nullable|string',
                'notes' => 'nullable|string',
                'payment_method' => 'required|string',
            ]);

            // Validate items
            if (!$items || !is_array($items) || count($items) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong'
                ], 422);
            }

            // SECURITY: Validate total items (max 10)
            $totalItems = collect($items)->sum('quantity');
            if ($totalItems > 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimal 10 item per pesanan.'
                ], 422);
            }
            
            // SECURITY: Validate phone number (only digits)
            $phoneDigits = preg_replace('/[^0-9]/', '', $validated['customer_phone']);
            if (strlen($phoneDigits) < 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon harus minimal 10 digit.'
                ], 422);
            }

            // Normalize to 0 prefix
            $phone = preg_replace('/[^0-9]/', '', $validated['customer_phone']);
            if (str_starts_with($phone, '62')) {
                $phone = '0' . substr($phone, 2);
            } elseif (!str_starts_with($phone, '0') && strlen($phone) > 0) {
                $phone = '0' . $phone;
            }
            $validated['customer_phone'] = $phone;
            
            // SECURITY: Check if customer is blacklisted
            $reputation = \App\Models\CustomerReputation::getOrCreate($phone, $validated['customer_name']);
            if ($reputation->isBlacklisted()) {
                \Log::warning('[Security] Blacklisted customer attempted pre-order', ['phone' => $phone]);
                return response()->json([
                    'success' => false,
                    'blocked' => true,
                    'message' => 'Akses dibatasi sementara. Silakan hubungi admin.'
                ], 403);
            }

            return DB::transaction(function () use ($validated, $items, $request, $ipAddress) {
                // Security: Log order attempt
                $phoneNumber = $this->normalizePhone($validated['customer_phone']);
                \App\Models\SecurityLog::logOrderAttempt($ipAddress, $phoneNumber, 'Pre-order created');

                // Validasi stok - untuk pre-order, kita bisa lebih fleksibel
                // Tapi tetap cek apakah produk ada
                foreach ($items as $item) {
                    $product = Product::find($item['product_id']);
                    if (!$product) {
                        throw new \Exception("Produk dengan ID {$item['product_id']} tidak ditemukan");
                    }
                }

                // Calculate Subtotal
                $customerPhone = $this->normalizePhone($validated['customer_phone']);
                
                $subtotal = 0;
                $itemsDescription = [];
                foreach ($items as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                    $itemsDescription[] = $item['quantity'] . 'x ' . $item['product_name'] . ' (Rp ' . number_format($item['price'], 0, ',', '.') . ')';
                }

                // Shipping is 0 initially, Admin will set it later
                $shippingCost = 0;

                // Final Total
                $totalAmount = $subtotal + $shippingCost;

                $orderNumber = 'PRE-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

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
                $initialStatus = 'pending_preorder'; // Status khusus untuk pre-order

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'order_type' => 'preorder',
                    'pickup_date' => $validated['pickup_date'],
                    'pickup_time' => $validated['pickup_time'] ?? null,
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
                    'total_amount' => $subtotal,
                    'discount_amount' => 0,
                    'shipping_cost' => 0,
                    'promo_id' => null,
                    'status' => $initialStatus,
                    'shipping_method' => $validated['shipping_method'],
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    // TIDAK decrement stock untuk pre-order
                    // Stock akan di-decrement saat admin konfirmasi

                    // Increment total_sold in products table
                    Product::where('id', $item['product_id'])->increment('total_sold', $item['quantity']);
                }

                $this->sendPreorderMessageToAdmin($order, $messageThread, $itemsDescription);

                // Fraud Detection
                $fraud = \App\Models\FraudDetection::analyzeOrder($order, $ipAddress);
                
                // If high risk, set order to pending review
                if ($fraud->risk_score >= 70) {
                    $order->update(['status' => 'pending_review']);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pre-order berhasil dijadwalkan',
                    'order_number' => $orderNumber,
                    'order_id' => $order->id,
                ]);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Pre-order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kirim pesan pre-order ke admin
     */
    private function sendPreorderMessageToAdmin(Order $order, MessageThread $messageThread, array $itemsDescription)
    {
        // Format pesan pre-order
        $itemsList = implode("\n", $itemsDescription);
        
        $pickupDateFormatted = \Carbon\Carbon::parse($order->pickup_date)->format('d M Y');
        $pickupTimeText = $order->pickup_time ? \Carbon\Carbon::parse($order->pickup_time)->format('H:i') : 'Tidak ditentukan';
        
        $orderMessage = "🟡 *PRE-ORDER BARU MASUK*\n\n" .
            "━━━━━━━━━━━━━━━━━━━━━\n" .
            "📋 *Detail Pre-Order*\n" .
            "Nomor Pesanan: `{$order->order_number}`\n" .
            "📅 Tanggal Ambil: *{$pickupDateFormatted}*\n" .
            "⏰ Jam Ambil: {$pickupTimeText}\n\n" .
            "👤 *Informasi Customer*\n" .
            "Nama: {$order->customer_name}\n" .
            "📞 Telepon: {$order->customer_phone}\n" .
            "📍 Alamat Lengkap:\n{$order->customer_address}\n\n" .
            "🛒 *Daftar Pesanan*\n" .
            $itemsList . "\n\n" .
            "💰 *Total Pembayaran*\n" .
            "Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n" .
            "💳 *Metode Pembayaran*\n";
        
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
            "⏳ *Ini adalah PRE-ORDER*\n" .
            "Pesanan akan diproses sesuai jadwal yang dipilih customer.\n" .
            "Mohon konfirmasi ketersediaan dan jadwal produksi.\n\n" .
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
}

