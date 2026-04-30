<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\ChatMessage;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // HARIAN (Last 30 Days)
        $harian = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $total = Order::whereDate('created_at', $date->format('Y-m-d'))->where('status', '!=', 'cancelled')->sum('total_amount');
            $harian->push(['label' => $date->format('d M'), 'total' => (float)$total]);
        }

        // MINGGUAN (Last 12 Weeks)
        $mingguan = collect();
        for ($i = 11; $i >= 0; $i--) {
            $start = Carbon::now()->subWeeks($i)->startOfWeek();
            $end = Carbon::now()->subWeeks($i)->endOfWeek();
            $total = Order::whereBetween('created_at', [$start, $end])->where('status', '!=', 'cancelled')->sum('total_amount');
            $mingguan->push(['label' => 'W' . $start->format('W'), 'total' => (float)$total]);
        }

        // BULANAN (Last 12 Months)
        $bulanan = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $total = Order::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->where('status', '!=', 'cancelled')->sum('total_amount');
            $bulanan->push(['label' => $date->translatedFormat('M'), 'total' => (float)$total]);
        }

        // TAHUNAN (Last 5 Years)
        $tahunan = collect();
        for ($i = 4; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i)->year;
            $total = Order::whereYear('created_at', $year)->where('status', '!=', 'cancelled')->sum('total_amount');
            $tahunan->push(['label' => (string)$year, 'total' => (float)$total]);
        }

        $salesStats = [
            'harian' => $harian,
            'mingguan' => $mingguan,
            'bulanan' => $bulanan,
            'tahunan' => $tahunan
        ];

        // Stats for cards (Weekly)
        $weeklyRevenue = Order::whereBetween('created_at', [$thisWeek, Carbon::now()])->sum('total_amount');
        $weeklyOrdersCount = Order::whereBetween('created_at', [$thisWeek, Carbon::now()])->count();
        $totalItemsSold = OrderItem::sum('quantity');

        $revenueGrowth = 60;
        $ordersGrowth = 10;
        $recentSoldIncrement = 150;

        // Current view (default harian)
        $salesChart = $harian;

        // Best Sellers
        $bestSellers = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderBy('total_sold', 'desc')
            ->take(6)
            ->get();

        // Recent orders
        $recentOrders = Order::latest()->take(10)->get();

        // Recent message threads
        $recentMessages = MessageThread::with('latestMessage')
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('sender_type', 'user')->where('is_read', false);
            }])
            ->orderBy('last_message_at', 'desc')
            ->take(5)
            ->get();

        $unreadMessagesCount = MessageThread::whereHas('messages', function($q) {
            $q->where('sender_type', 'user')->where('is_read', false);
        })->count();

        return view('admin.dashboard', [
            'weeklyRevenue' => $weeklyRevenue,
            'weeklyOrdersCount' => $weeklyOrdersCount,
            'totalItemsSold' => $totalItemsSold,
            'revenueGrowth' => $revenueGrowth,
            'ordersGrowth' => $ordersGrowth,
            'recentSoldIncrement' => $recentSoldIncrement,
            'salesChart' => $salesChart, 
            'salesStats' => $salesStats,  
            'bestSellers' => $bestSellers,
            'recentOrders' => $recentOrders,
            'recentMessages' => $recentMessages,
            'unreadMessagesCount' => $unreadMessagesCount,
            'totalRevenue' => $weeklyRevenue,
            'monthlyRevenue' => Order::whereBetween('created_at', [$thisMonth, Carbon::now()])->sum('total_amount'),
            'totalOrders' => $weeklyOrdersCount,
            'totalMessages' => $unreadMessagesCount,
        ]);
    }

    // Orders List
    public function orders(Request $request)
    {
        $query = Order::with('items');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%')
                ->orWhere('customer_name', 'like', '%' . $request->search . '%');
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', [
            'orders' => $orders,
        ]);
    }

    // Order Detail
    public function showOrder($id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        // Mark associated chat messages as read
        if ($order->message_thread_id) {
            $order->messageThread->messages()
                ->where('sender_type', 'user')
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        return view('admin.orders.show', [
            'order' => $order,
        ]);
    }

    // Update Order Status
    public function updateOrderStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,pending_admin,shipping_set,pending_payment,payment_confirmed,processing,scheduled,out_for_delivery,shipped,delivered,cancelled,ready_for_pickup,picked_up',
            'notes' => 'nullable|string'
        ]);

        $order = Order::findOrFail($id);
        $order->update($validated);

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    // Respond to Order (Admin response dengan estimasi waktu)
    public function respondToOrder(Request $request, $orderId)
    {
        $validated = $request->validate([
            'admin_response' => 'nullable|string|max:500',
            'estimated_minutes' => 'nullable|integer|min:5|max:480',
            'estimated_delivery_date' => 'required|date|after_or_equal:today',
            'estimated_delivery_time' => 'required|string|max:100', // e.g., "15.00 - 16.00"
        ]);

        $order = Order::findOrFail($orderId);
        
        // Set locale manually to Indonesian for Carbon inside this function
        \Carbon\Carbon::setLocale('id');
        $deliveryDate = \Carbon\Carbon::parse($validated['estimated_delivery_date']);
        $dayName = $deliveryDate->translatedFormat('l'); // Senin, Selasa, etc.
        $formattedDate = $deliveryDate->translatedFormat('d F Y'); // 12 Februari 2026

        // Update order dengan respon dan estimasi
        $order->update([
            'admin_response' => $validated['admin_response'],
            'estimated_delivery_date' => $validated['estimated_delivery_date'],
            'estimated_delivery_time' => $validated['estimated_delivery_time'],
            'responded_at' => now(),
            'status' => 'scheduled',
        ]);

        // Mark associated chat messages as read
        if ($order->message_thread_id) {
            $order->messageThread->messages()
                ->where('sender_type', 'user')
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        // New Message Template
        $responseMessage = "Pesanan Anda sedang kami proses 🍞\n" .
            "Estimasi pengantaran:\n" .
            "📅 Hari: {$dayName}, {$formattedDate}\n" .
            "⏰ Jam: {$validated['estimated_delivery_time']} WIB\n\n";
        
        if ($validated['admin_response']) {
            $responseMessage .= "💬 Pesan dari admin:\n{$validated['admin_response']}\n\n";
        }
            
        $responseMessage .= "Terima kasih telah berbelanja di Dapoer Budess 🙏";

        ChatMessage::create([
            'message_thread_id' => $order->message_thread_id,
            'order_id' => $order->id,
            'sender_type' => 'admin',
            'message_type' => 'admin_response',
            'message' => $responseMessage,
            'is_read' => false,
        ]);

        // Update thread last message
        $order->messageThread()->update(['last_message_at' => now()]);

        return redirect()->back()->with('success', 'Estimasi pengantaran berhasil dikirim ke customer');
    }

    // Confirm Order Completion (Pesanan selesai)
    public function completeOrder(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // Update status
        $order->update([
            'status' => 'delivered',
        ]);

        // Mark associated chat messages as read
        if ($order->message_thread_id) {
            $order->messageThread->messages()
                ->where('sender_type', 'user')
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        return redirect()->back()->with('success', 'Pesanan ditandai sebagai selesai');
    }

    // Confirm Payment (Terima/Tolak Bukti Pembayaran)
    public function confirmPayment(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $action = $request->input('action'); // 'approve' or 'reject'

        if ($action === 'approve') {
            // Terima pembayaran
            $order->update([
                'payment_status' => 'paid',
                'status' => $order->shipping_method === 'pickup' ? 'processing' : 'shipping_set',
            ]);

            // Send notification to customer
            if ($order->message_thread_id) {
                $message = "✅ *PEMBAYARAN DITERIMA*\n\n" .
                    "Pembayaran Anda untuk pesanan *{$order->order_number}* telah kami terima dan diverifikasi.\n\n" .
                    "Pesanan Anda akan segera kami proses. 🍞\n\n" .
                    "Terima kasih! 🙏";

                \App\Models\ChatMessage::create([
                    'message_thread_id' => $order->message_thread_id,
                    'order_id' => $order->id,
                    'sender_type' => 'admin',
                    'message_type' => 'payment_confirmation',
                    'message' => $message,
                    'is_read' => false,
                ]);

                $order->messageThread()->update(['last_message_at' => now()]);
            }

            return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi');
        } elseif ($action === 'reject') {
            // Tolak pembayaran
            $order->update([
                'payment_status' => 'unpaid',
                'payment_proof' => null, // Hapus bukti pembayaran
            ]);

            // Send notification to customer
            if ($order->message_thread_id) {
                $message = "❌ *PEMBAYARAN DITOLAK*\n\n" .
                    "Maaf, bukti pembayaran untuk pesanan *{$order->order_number}* tidak dapat diverifikasi.\n\n" .
                    "Mohon upload ulang bukti pembayaran yang valid atau hubungi admin untuk informasi lebih lanjut.\n\n" .
                    "Terima kasih.";

                ChatMessage::create([
                    'message_thread_id' => $order->message_thread_id,
                    'order_id' => $order->id,
                    'sender_type' => 'admin',
                    'message_type' => 'payment_rejection',
                    'message' => $message,
                    'is_read' => false,
                ]);

                $order->messageThread()->update(['last_message_at' => now()]);
            }

            return redirect()->back()->with('warning', 'Pembayaran ditolak. Customer akan diminta upload ulang.');
        }

        return redirect()->back()->with('error', 'Invalid action');
    }

    // Mark Message as Replied
    public function markReply($id)
    {
        $thread = MessageThread::findOrFail($id);
        $thread->update(['status' => 'replied']);

        return redirect()->back()->with('success', 'Message marked as replied');
    }

    // Reports
    public function reports(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        // 1. PRODUCT REVENUE (Bar Chart)
        $productRevenue = OrderItem::whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', '!=', 'cancelled');
            })
            ->selectRaw('product_name, SUM(subtotal) as revenue')
            ->groupBy('product_name')
            ->orderBy('revenue', 'desc')
            ->get();

        // 2. REVENUE BREAKDOWN (Pie Chart)
        $totalRev = $productRevenue->sum('revenue');
        $revenueBreakdown = $productRevenue->map(function($item) use ($totalRev) {
            return [
                'name' => $item->product_name,
                'percentage' => $totalRev > 0 ? round(($item->revenue / $totalRev) * 100, 1) : 0,
                'revenue' => $item->revenue
            ];
        });

        // 3. TOTAL INCOME PER ITEM trend (Line Chart) -> Daily Revenue
        $dailyRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Table Data (Export source)
        $reportTable = OrderItem::whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', '!=', 'cancelled');
            })
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->selectRaw('DATE(orders.created_at) as date, order_items.product_name, SUM(order_items.quantity) as sold, SUM(order_items.subtotal) as revenue')
            ->groupBy('date', 'order_items.product_name')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.reports', [
            'productRevenue' => $productRevenue,
            'revenueBreakdown' => $revenueBreakdown,
            'dailyRevenue' => $dailyRevenue,
            'reportTable' => $reportTable,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    // Create Order
    public function createOrder()
    {
        return Inertia::render('Admin/Orders/Create');
    }

    // Store Order
    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'customer_address' => 'required|string',
            'payment_method' => 'required|string',
            'items' => 'required|array',
            'items.*.product_name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $orderNumber = 'ORD-' . now()->format('YmdHis');
        $totalAmount = 0;

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'customer_address' => $validated['customer_address'],
            'payment_method' => $validated['payment_method'],
            'total_amount' => 0, // Will update after items
            'status' => 'pending'
        ]);

        foreach ($validated['items'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['product_name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal
            ]);
            $totalAmount += $subtotal;
        }

        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order created successfully');
    }
    // List Reviews
    public function reviews(Request $request)
    {
        $reviews = \App\Models\Review::with('order')->latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    // Toggle Review Visibility
    public function toggleReviewVisibility($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $review->is_visible = !$review->is_visible;
        $review->save();

        $status = $review->is_visible ? 'ditampilkan' : 'disembunyikan';
        return redirect()->back()->with('success', "Ulasan berhasil {$status}");
    }

    // Update Order Shipping Cost (Manual for COD)
    public function updateOrderShipping(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        $order->update([
            'shipping_cost' => $validated['shipping_cost'],
            'status' => 'shipping_set' // Ongkir Ditentukan
        ]);

        // Send automated notification to chat thread
        if ($order->message_thread_id) {
            $formattedShipping = number_format($validated['shipping_cost'], 0, ',', '.');
            $formattedTotal = number_format($order->final_total, 0, ',', '.');
            
            $notificationMessage = "🚚 *UPDATE ONGKOS KIRIM*\n\n" .
                "Ongkos kirim untuk pesanan *{$order->order_number}* telah ditetapkan sebesar:\n" .
                "*Rp {$formattedShipping}*\n\n" .
                "Total pembayaran menjadi: *Rp {$formattedTotal}*\n\n" .
                "Terima kasih!";

            ChatMessage::create([
                'message_thread_id' => $order->message_thread_id,
                'order_id' => $order->id,
                'sender_type' => 'admin',
                'message_type' => 'text',
                'message' => $notificationMessage,
                'is_read' => false,
            ]);

            // Update thread timestamp
            $order->messageThread->update(['last_message_at' => now()]);
        }

        return back()->with('success', 'Ongkos kirim berhasil diupdate dan notifikasi dikirim ke pelanggan.');
    }
}
