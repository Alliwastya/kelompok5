@extends('layouts.admin')

@section('page-title', 'Detail Pesanan')

@section('content')
<style>
    .order-container {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 2rem;
    }
    
    .order-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .order-card h2 {
        color: #FFD700;
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    
    .order-info {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #333;
        color: #ccc;
    }
    
    .order-info:last-child {
        border-bottom: none;
    }
    
    .order-info span:first-child {
        color: #999;
    }
    
    .order-info span:last-child {
        font-weight: 600;
        color: #fff;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 700;
    }
    
    .status-pending { background: rgba(107, 114, 128, 0.2); color: #d1d5db; }
    .status-shipping { background: rgba(59, 130, 246, 0.2); color: #93c5fd; }
    .status-processing { background: rgba(251, 146, 60, 0.2); color: #fed7aa; }
    .status-scheduled { background: rgba(96, 165, 250, 0.2); color: #bfdbfe; }
    .status-delivery { background: rgba(168, 85, 247, 0.2); color: #d8b4fe; }
    .status-ready { background: rgba(34, 197, 94, 0.2); color: #86efac; }
    .status-delivered { background: rgba(34, 197, 94, 0.2); color: #86efac; }
    .status-cancelled { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
    
    .btn-group {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    
    .btn-group button, .btn-group a {
        flex: 1;
        padding: 0.75rem 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #1a1a1a;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(255, 215, 0, 0.3);
    }
    
    .btn-secondary {
        background: #333;
        color: #FFD700;
        border: 1px solid #FFD700;
    }
    
    .btn-secondary:hover {
        background: rgba(255, 215, 0, 0.1);
    }
    
    .btn-success {
        background: rgba(34, 197, 94, 0.2);
        color: #86efac;
        border: 1px solid #86efac;
    }
    
    .btn-success:hover {
        background: rgba(34, 197, 94, 0.3);
    }
    
    .btn-warning {
        background: rgba(251, 146, 60, 0.2);
        color: #fed7aa;
        border: 1px solid #fed7aa;
    }
    
    .btn-warning:hover {
        background: rgba(251, 146, 60, 0.3);
    }
    
    .back-btn {
        color: #FFD700;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }
    
    .back-btn:hover {
        color: #FFA500;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group label {
        display: block;
        color: #FFD700;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        background: #1a1a1a;
        border: 1px solid #FFD700;
        color: #fff;
        padding: 0.75rem;
        border-radius: 8px;
        font-family: inherit;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
    }
    
    .sidebar-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .sidebar-card h3 {
        color: #FFD700;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    table thead {
        background: #1a1a1a;
        border-bottom: 1px solid #FFD700;
    }
    
    table th {
        padding: 0.75rem;
        text-align: left;
        color: #FFD700;
        font-weight: 700;
        font-size: 0.875rem;
    }
    
    table td {
        padding: 0.75rem;
        border-bottom: 1px solid #333;
        color: #ccc;
    }
    
    table tbody tr:hover {
        background: rgba(255, 215, 0, 0.05);
    }
    
    @media (max-width: 768px) {
        .order-container {
            grid-template-columns: 1fr;
        }
    }

    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.95);
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .image-modal-content {
        position: relative;
        margin: auto;
        padding: 20px;
        width: 90%;
        max-width: 1200px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-modal-content img {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 8px 32px rgba(255, 215, 0, 0.3);
    }

    .close-modal {
        position: absolute;
        top: 20px;
        right: 40px;
        color: #FFD700;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
        transition: all 0.3s;
    }

    .close-modal:hover {
        color: #FFA500;
        transform: scale(1.1);
    }

    .payment-proof-thumbnail {
        transition: all 0.3s;
    }

    .payment-proof-thumbnail:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 24px rgba(255, 215, 0, 0.4) !important;
    }
</style>

<a href="{{ route('admin.orders.index') }}" class="back-btn">← Kembali ke Pesanan</a>

<div class="order-container">
    <!-- Main Content -->
    <div>
        <div class="order-card">
            <h2>📋 Informasi Pesanan</h2>
            
            <div class="order-info">
                <span>No. Pesanan:</span>
                <span>{{ $order->order_number }}</span>
            </div>
            
            <!-- Order Type Badge -->
            <div class="order-info">
                <span>Tipe Pesanan:</span>
                <span>
                    @if($order->order_type === 'preorder')
                        <span style="background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%); color: white; padding: 0.4rem 1rem; border-radius: 50px; font-weight: 700; font-size: 0.9rem;">
                            🟡 Pre-Order
                        </span>
                    @else
                        <span style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.4rem 1rem; border-radius: 50px; font-weight: 700; font-size: 0.9rem;">
                            🟢 Instant Order
                        </span>
                    @endif
                </span>
            </div>
            
            <!-- Pickup Date & Time for Pre-Order -->
            @if($order->order_type === 'preorder')
            <div class="order-info">
                <span>📅 Tanggal Ambil:</span>
                <span style="font-weight: 700; color: #FFA500;">
                    {{ \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') }}
                </span>
            </div>
            @if($order->pickup_time)
            <div class="order-info">
                <span>⏰ Jam Ambil:</span>
                <span style="font-weight: 700; color: #FFA500;">
                    {{ \Carbon\Carbon::parse($order->pickup_time)->format('H:i') }}
                </span>
            </div>
            @endif
            @endif
            
            <div class="order-info">
                <span>Nama Pelanggan:</span>
                <span>{{ $order->customer_name }}</span>
            </div>
            
            <div class="order-info">
                <span>Tanggal Pesanan:</span>
                <span>{{ $order->created_at->format('d M Y H:i') }}</span>
            </div>
            
            <div class="order-info">
                <span>Metode Pengambilan:</span>
                <span>
                    @if($order->shipping_method === 'pickup')
                        🏪 Ambil Sendiri di Toko
                    @else
                        🚚 Diantar ke Alamat
                    @endif
                </span>
            </div>
            
            <div class="order-info">
                <span>Status:</span>
                <span class="status-badge status-@if($order->status === 'pending_admin') pending @elseif($order->status === 'pending_preorder') pending @elseif($order->status === 'shipping_set') shipping @elseif($order->status === 'processing') processing @elseif($order->status === 'scheduled') scheduled @elseif($order->status === 'out_for_delivery') delivery @elseif($order->status === 'ready_for_pickup') ready @elseif($order->status === 'picked_up') delivered @elseif($order->status === 'delivered') delivered @elseif($order->status === 'cancelled') cancelled @else pending @endif">
                    @if($order->status === 'pending_admin') ⏳ Menunggu Konfirmasi Admin
                    @elseif($order->status === 'pending_preorder') 🟡 Pre-Order Menunggu Konfirmasi
                    @elseif($order->status === 'shipping_set') 🚚 Ongkir Ditentukan
                    @elseif($order->status === 'processing') 👨‍🍳 Diproses
                    @elseif($order->status === 'scheduled') 📅 Dijadwalkan
                    @elseif($order->status === 'out_for_delivery') 🚚 Dalam Pengantaran
                    @elseif($order->status === 'ready_for_pickup') ✅ Siap Diambil di Toko
                    @elseif($order->status === 'picked_up') ✔️ Sudah Diambil
                    @elseif($order->status === 'delivered') ✓ Selesai
                    @elseif($order->status === 'cancelled') ✕ Dibatalkan
                    @else ⏳ Menunggu
                    @endif
                </span>
            </div>

            @if($order->responded_at)
            <div class="order-info">
                <span>Waktu Respon:</span>
                <span>{{ $order->responded_at->format('d M Y H:i') }}</span>
            </div>
            @if($order->estimated_ready_at)
            <div class="order-info">
                <span>Estimasi Selesai:</span>
                <span>{{ $order->estimated_ready_at->format('H:i') }}</span>
            </div>
            @endif
            @endif
            
            @if($order->message_thread_id)
            <div class="btn-group">
                <a href="{{ route('admin.messages.show', $order->message_thread_id) }}" class="btn-primary">
                    💬 Chat dengan Pelanggan
                </a>
            </div>
            @endif
        </div>

        <!-- Items -->
        <div class="order-card">
            <h2>📦 Item Pesanan</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name ?? 'Product' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #999;">Tidak ada item</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Shipping & Payment Info -->
        <div class="order-card">
            <h2>🚚 Informasi Pengiriman & Pembayaran</h2>
            
            <div class="order-info">
                <span>Alamat:</span>
                <span>{{ $order->customer_address }}</span>
            </div>
            
            @if($order->house_details)
            <div class="order-info">
                <span>Detail Rumah:</span>
                <span>{{ $order->house_details }}</span>
            </div>
            @endif
            
            <div class="order-info">
                <span>Metode Bayar:</span>
                <span>{{ $order->payment_method }}</span>
            </div>

            <div class="order-info">
                <span>Status Pembayaran:</span>
                <span>
                    @if($order->payment_status === 'paid')
                        <span style="color: #86efac;">✓ Lunas</span>
                    @elseif($order->payment_status === 'pending_confirmation')
                        <span style="color: #fed7aa;">⏳ Menunggu Konfirmasi</span>
                    @else
                        <span style="color: #d1d5db;">⏳ Belum Bayar</span>
                    @endif
                </span>
            </div>
        </div>

        <!-- Payment Proof Section -->
        @if($order->payment_proof)
        <div class="order-card" style="border: 2px solid #86efac;">
            <h2>📸 Bukti Pembayaran</h2>
            
            <div style="background: #1a1a1a; border-radius: 12px; padding: 1rem; margin-bottom: 1rem;">
                <div style="text-align: center; margin-bottom: 1rem;">
                    <img src="/storage/{{ $order->payment_proof }}" 
                         alt="Bukti Pembayaran" 
                         class="payment-proof-thumbnail"
                         style="max-width: 100%; max-height: 500px; border-radius: 8px; cursor: pointer; border: 2px solid #FFD700;"
                         onclick="openImageModal(this.src)">
                </div>
                
                <div style="font-size: 0.875rem; color: #999; text-align: center; margin-bottom: 1rem;">
                    📅 Diupload: {{ $order->updated_at->format('d M Y H:i') }}
                </div>
                
                <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
                    <a href="/storage/{{ $order->payment_proof }}" 
                       target="_blank" 
                       class="btn-secondary" 
                       style="flex: 1; display: inline-block;">
                        🔍 Lihat Ukuran Penuh
                    </a>
                    <a href="/storage/{{ $order->payment_proof }}" 
                       download 
                       class="btn-secondary" 
                       style="flex: 1; display: inline-block;">
                        📥 Download Gambar
                    </a>
                </div>
            </div>

            @if($order->payment_status === 'pending_confirmation')
            <div style="background: rgba(251, 146, 60, 0.1); border: 1px solid #fed7aa; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                <div style="color: #fed7aa; font-weight: 600; margin-bottom: 0.5rem;">⚠️ Menunggu Konfirmasi</div>
                <div style="color: #ccc; font-size: 0.875rem;">Silakan verifikasi bukti pembayaran dan konfirmasi status pembayaran.</div>
            </div>

            <form action="{{ route('admin.orders.confirm-payment', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="btn-group">
                    <button type="submit" name="action" value="approve" class="btn-success" onclick="return confirm('Konfirmasi pembayaran ini sebagai VALID?')">
                        ✓ Terima Pembayaran
                    </button>
                    <button type="submit" name="action" value="reject" class="btn-warning" onclick="return confirm('Tolak pembayaran ini?')">
                        ✕ Tolak Pembayaran
                    </button>
                </div>
            </form>
            @elseif($order->payment_status === 'paid')
            <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #86efac; border-radius: 8px; padding: 1rem;">
                <div style="color: #86efac; font-weight: 600; text-align: center;">✓ Pembayaran Telah Dikonfirmasi</div>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Total Layout -->
        <div class="sidebar-card">
            <h3>💰 Rincian Pembayaran</h3>
            
            <div class="order-info">
                <span>Subtotal Item</span>
                <span>Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</span>
            </div>
            @if($order->shipping_method !== 'pickup')
            <div class="order-info">
                <span>Ongkos Kirim</span>
                <span>+ Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @endif
            
            <!-- Manual Shipping Input for Admin (hanya untuk delivery) -->
            @if($order->payment_method === 'COD' && $order->status === 'pending_admin' && $order->shipping_method !== 'pickup')
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #FFD700;">
                <form action="{{ route('admin.orders.update-shipping', $order->id) }}" method="POST" style="display: flex; gap: 0.5rem;">
                    @csrf
                    @method('PATCH')
                    <input type="number" name="shipping_cost" value="{{ (int)$order->shipping_cost }}" 
                           style="flex: 1; background: #1a1a1a; border: 1px solid #FFD700; color: #fff; padding: 0.5rem; border-radius: 6px; font-size: 0.875rem;"
                           placeholder="Set Ongkir...">
                    <button type="submit" style="background: #333; color: #FFD700; border: 1px solid #FFD700; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.875rem;">Set Ongkir</button>
                </form>
                @error('shipping_cost')
                    <span style="color: #fca5a5; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>
            @endif
            
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #FFD700; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 700; color: #FFD700;">Total Akhir</span>
                <span style="font-size: 1.5rem; font-weight: 700; color: #FFD700;">Rp {{ number_format($order->final_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Respond to Order (Jika belum direspon) -->
        @if(!$order->estimated_delivery_date && $order->status === 'shipping_set' && $order->shipping_method !== 'pickup')
            <div class="sidebar-card" style="border: 2px solid #FFA500;">
                <h3>📅 Estimasi Pengantaran</h3>
                <form action="{{ route('admin.orders.respond', $order->id) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label>Pesan ke Customer (Opsional)</label>
                        <textarea name="admin_response" placeholder="Contoh: Pesanan Anda sedang kami siapkan..." style="height: 80px; resize: vertical;"></textarea>
                        @error('admin_response')
                            <span style="color: #fca5a5; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>📅 Tanggal Pengantaran</label>
                        <input type="date" name="estimated_delivery_date" required value="{{ date('Y-m-d') }}">
                        @error('estimated_delivery_date')
                            <span style="color: #fca5a5; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>⏰ Jam Pengantaran</label>
                        <input type="text" name="estimated_delivery_time" placeholder="Contoh: 15.00 - 16.00" required>
                        @error('estimated_delivery_time')
                            <span style="color: #fca5a5; font-size: 0.875rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%;">
                        ✓ Kirim Estimasi ke Customer
                    </button>
                </form>
            </div>
        @elseif($order->estimated_delivery_date && $order->status !== 'delivered' && $order->shipping_method !== 'pickup')
            <!-- Delivery Estimation Info -->
            <div class="sidebar-card" style="border: 2px solid #93c5fd;">
                <h3>🚚 Estimasi Pengantaran</h3>
                <div style="background: #1a1a1a; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                    <div style="font-weight: 600; color: #FFD700; margin-bottom: 0.5rem;">
                        📅 {{ is_string($order->estimated_delivery_date) ? $order->estimated_delivery_date : $order->estimated_delivery_date->format('d M Y') }}
                    </div>
                    <div style="color: #ccc; font-size: 0.875rem;">
                        ⏰ Jam: {{ $order->estimated_delivery_time }} WIB
                    </div>
                    @if($order->admin_response)
                        <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #333; color: #ccc; font-size: 0.875rem; font-style: italic;">
                            "{{ $order->admin_response }}"
                        </div>
                    @endif
                </div>
                <div style="font-size: 0.75rem; color: #999; margin-bottom: 1rem;">
                    Estimasi dikirim pada: {{ $order->responded_at ? $order->responded_at->format('d M Y H:i') : '-' }}
                </div>

                <!-- Complete/Next Actions -->
                @if($order->status === 'scheduled' || $order->status === 'processing')
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" style="margin-bottom: 0.75rem;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="out_for_delivery">
                        <button type="submit" class="btn-warning" style="width: 100%;">
                            🚚 Set Dalam Pengantaran
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini sebagai selesai?')">
                        @csrf
                        <button type="submit" class="btn-success" style="width: 100%;">
                            🎉 Pesanan Selesai
                        </button>
                    </form>
                @elseif($order->status === 'out_for_delivery')
                    <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini sebagai selesai?')">
                        @csrf
                        <button type="submit" class="btn-success" style="width: 100%;">
                            🎉 Pesanan Selesai (Diterima)
                        </button>
                    </form>
                @elseif($order->status === 'picked_up')
                    <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini sebagai selesai?')">
                        @csrf
                        <button type="submit" class="btn-success" style="width: 100%;">
                            🎉 Pesanan Selesai
                        </button>
                    </form>
                @endif
            </div>
        @endif

        <!-- Update Status -->
        @if($order->status !== 'delivered' && $order->status !== 'cancelled' && $order->status !== 'picked_up')
            <div class="sidebar-card">
                <h3>⚙️ Update Status</h3>
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="form-group">
                        <select name="status">
                            @if($order->shipping_method === 'pickup')
                                <!-- Status untuk Ambil Sendiri di Toko -->
                                <option value="pending_admin" @if($order->status === 'pending_admin') selected @endif>⏳ Menunggu Konfirmasi Admin</option>
                                @if($order->payment_method === 'QRIS')
                                <option value="pending_payment" @if($order->status === 'pending_payment') selected @endif>🕒 Menunggu Konfirmasi Pembayaran</option>
                                <option value="payment_confirmed" @if($order->status === 'payment_confirmed') selected @endif>✅ Pembayaran Diterima</option>
                                @endif
                                <option value="processing" @if($order->status === 'processing') selected @endif>👨‍🍳 Diproses</option>
                                <option value="ready_for_pickup" @if($order->status === 'ready_for_pickup') selected @endif>✅ Siap Diambil di Toko</option>
                                <option value="picked_up" @if($order->status === 'picked_up') selected @endif>✔️ Sudah Diambil</option>
                                <option value="cancelled" @if($order->status === 'cancelled') selected @endif>❌ Dibatalkan</option>
                            @else
                                <!-- Status untuk Diantar (COD) -->
                                <option value="pending_admin" @if($order->status === 'pending_admin') selected @endif>⏳ Menunggu Konfirmasi Admin</option>
                                <option value="shipping_set" @if($order->status === 'shipping_set') selected @endif>🚚 Ongkir Ditentukan</option>
                                <option value="processing" @if($order->status === 'processing') selected @endif>👨‍🍳 Diproses</option>
                                <option value="scheduled" @if($order->status === 'scheduled') selected @endif>📅 Dijadwalkan</option>
                                <option value="out_for_delivery" @if($order->status === 'out_for_delivery') selected @endif>🚚 Dalam Pengantaran</option>
                                <option value="delivered" @if($order->status === 'delivered') selected @endif>✓ Selesai</option>
                                <option value="cancelled" @if($order->status === 'cancelled') selected @endif>❌ Dibatalkan</option>
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <textarea name="notes" placeholder="Catatan..." style="height: 80px; resize: vertical;"></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary" style="width: 100%;">
                        Update Status
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close-modal" onclick="closeImageModal()">&times;</span>
    <div class="image-modal-content" onclick="event.stopPropagation()">
        <img id="modalImage" src="" alt="Bukti Pembayaran">
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modal.style.display = 'block';
    modalImg.src = imageSrc;
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal with ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
    }
});
</script>

@endsection
