@extends('layouts.admin')

@section('page-title', 'Pesanan')

@section('content')
<style>
    .orders-header {
        margin-bottom: 2rem;
    }
    
    .filter-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .filter-card label {
        color: #FFD700;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
        display: block;
    }
    
    .filter-card select,
    .filter-card input {
        background: #1a1a1a;
        border: 1px solid #FFD700;
        color: #fff;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .filter-card select:focus,
    .filter-card input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
    }
    
    .filter-card button {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #1a1a1a;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .filter-card button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(255, 215, 0, 0.3);
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .orders-table thead {
        background: #1a1a1a;
        border-bottom: 2px solid #FFD700;
    }
    
    .orders-table th {
        padding: 1rem;
        text-align: left;
        color: #FFD700;
        font-weight: 700;
        font-size: 0.875rem;
        text-transform: uppercase;
    }
    
    .orders-table td {
        padding: 1rem;
        border-bottom: 1px solid #333;
        color: #ccc;
    }
    
    .orders-table tbody tr {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.3s;
    }
    
    .orders-table tbody tr:hover {
        background: linear-gradient(135deg, #333 0%, #3a3a3a 100%);
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.2);
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .status-pending {
        background: rgba(255, 193, 7, 0.2);
        color: #FFC107;
    }
    
    .status-processing {
        background: rgba(33, 150, 243, 0.2);
        color: #2196F3;
    }
    
    .status-completed {
        background: rgba(76, 175, 80, 0.2);
        color: #4CAF50;
    }
    
    .status-cancelled {
        background: rgba(244, 67, 54, 0.2);
        color: #f44336;
    }
    
    .detail-link {
        color: #FFD700;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .detail-link:hover {
        color: #FFA500;
    }
</style>

<!-- Orders Section -->
<div class="orders-header">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #FFD700; margin-bottom: 0.5rem;">📦 Daftar Pesanan</h2>
    <p style="color: #999; font-size: 0.875rem;">Kelola semua pesanan pelanggan Anda</p>
</div>

<!-- Filters & Search -->
<div class="filter-card">
    <form action="{{ route('admin.orders.index') }}" method="GET" style="display: grid; grid-template-columns: 1fr 2fr auto; gap: 1rem; align-items: end;">
        <div>
            <label>Filter Status</label>
            <select name="status">
                <option value="">Semua Status</option>
                @foreach(['pending', 'processing', 'shipped', 'completed', 'cancelled'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Cari Pesanan</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="No. pesanan atau nama pelanggan">
        </div>
        <button type="submit">Cari</button>
    </form>
</div>

<!-- Orders Table -->
<div style="overflow-x: auto;">
    <table class="orders-table">
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td style="font-weight: 700; color: #FFD700;">{{ $order->id }}</td>
                <td>{{ $order->customer_name }}</td>
                <td style="font-weight: 600;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td>
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}" class="detail-link">Detail →</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem; color: #999;">
                    Tidak ada pesanan ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
