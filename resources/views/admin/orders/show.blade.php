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
                <span class="status-badge status-@if($order->status === 'pending_admin') pending @elseif($order->status === 'shipping_set') shipping @elseif($order->status === 'processing') processing @elseif($order->status === 'scheduled') scheduled @elseif($order->status === 'out_for_delivery') delivery @elseif($order->status === 'ready_for_pickup') ready @elseif($order->status === 'picked_up') delivered @elseif($order->status === 'delivered') delivered @elseif($order->status === 'cancelled') cancelled @else pending @endif">
                    @if($order->status === 'pending_admin') ⏳ Menunggu Konfirmasi Admin
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
        </div>
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

@endsection
