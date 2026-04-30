@extends('layouts.admin')

@section('page-title', 'Produk')

@section('content')
<style>
    .products-header {
        margin-bottom: 2rem;
    }
    
    .products-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #FFD700;
        margin-bottom: 0.5rem;
    }
    
    .products-header p {
        color: #999;
        font-size: 0.875rem;
    }
    
    .add-btn {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #1a1a1a;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(255, 215, 0, 0.3);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        padding: 1.5rem;
    }
    
    .stat-label {
        color: #FFD700;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
    }
    
    .stat-value {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
    }
    
    .stat-desc {
        color: #999;
        font-size: 0.875rem;
    }
    
    .search-filter {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: grid;
        grid-template-columns: 1fr 200px auto;
        gap: 1rem;
        align-items: end;
    }
    
    .search-filter input,
    .search-filter select {
        background: #1a1a1a;
        border: 1px solid #FFD700;
        color: #fff;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .search-filter input:focus,
    .search-filter select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
    }
    
    .search-filter button {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #1a1a1a;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .search-filter button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(255, 215, 0, 0.3);
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .product-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .product-card:hover {
        box-shadow: 0 8px 24px rgba(255, 215, 0, 0.2);
        transform: translateY(-4px);
    }
    
    .product-image {
        width: 100%;
        height: 200px;
        background: #1a1a1a;
        overflow: hidden;
        position: relative;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    .product-badge {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        background: rgba(244, 67, 54, 0.9);
        color: #fff;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .product-info {
        padding: 1.5rem;
    }
    
    .product-name {
        color: #FFD700;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-price {
        color: #fff;
        font-size: 1.25rem;
        font-weight: 900;
        margin-bottom: 1rem;
    }
    
    .product-stats {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 0.5rem;
        padding: 1rem 0;
        border-top: 1px solid #444;
        border-bottom: 1px solid #444;
        margin-bottom: 1rem;
        font-size: 0.75rem;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-item-label {
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }
    
    .stat-item-value {
        color: #FFD700;
        font-weight: 700;
        font-size: 1rem;
    }
    
    .product-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-edit,
    .btn-delete {
        flex: 1;
        padding: 0.75rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.875rem;
    }
    
    .btn-edit {
        background: rgba(255, 215, 0, 0.2);
        color: #FFD700;
    }
    
    .btn-edit:hover {
        background: rgba(255, 215, 0, 0.3);
    }
    
    .btn-delete {
        background: rgba(244, 67, 54, 0.2);
        color: #f44336;
    }
    
    .btn-delete:hover {
        background: rgba(244, 67, 54, 0.3);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        color: #999;
    }
    
    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>

<div class="products-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>🍞 Daftar Produk</h1>
            <p>Kelola katalog produk roti Anda di sini</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="add-btn">
            <span>+</span>
            <span>Tambah Produk</span>
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Produk</div>
        <div class="stat-value">{{ $products->total() }}</div>
        <div class="stat-desc">Item tersedia</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Terjual Total</div>
        <div class="stat-value">{{ number_format($totalItemsSold ?? 0, 0, ',', '.') }}</div>
        <div class="stat-desc">Pcs terjual seluruh waktu</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Best Seller</div>
        <div class="stat-value" style="font-size: 1rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $topProduct ?? '-' }}</div>
        <div class="stat-desc">Produk paling diminati</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Omzet Minggu Ini</div>
        <div class="stat-value" style="font-size: 1.25rem;">Rp{{ number_format($weeklyRevenue ?? 0, 0, ',', '.') }}</div>
        <div class="stat-desc">Pendapatan 7 hari terakhir</div>
    </div>
</div>

<!-- Search & Filter -->
<div class="search-filter">
    <form action="{{ route('admin.products.index') }}" method="GET" style="display: contents;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk...">
        <select name="sort" onchange="this.form.submit()">
            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
            <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>Terlaris</option>
            <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Termurah</option>
            <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Termahal</option>
        </select>
        <button type="submit">Filter</button>
    </form>
</div>

<!-- Products Grid -->
<div class="products-grid">
    @forelse ($products as $product)
    <div class="product-card">
        <div class="product-image">
            @if($product->image)
                <img src="/storage/{{ $product->image }}" alt="{{ $product->name }}">
            @else
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #666;">
                    📷
                </div>
            @endif
            @if(!$product->is_available)
                <div class="product-badge">Habis</div>
            @elseif($product->is_discount_active)
                <div class="product-badge">Diskon</div>
            @endif
        </div>
        <div class="product-info">
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-price">
                @if($product->is_discount_active)
                    <div style="color: #999; font-size: 0.875rem; text-decoration: line-through;">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                    Rp{{ number_format($product->effective_price, 0, ',', '.') }}
                @else
                    Rp{{ number_format($product->price, 0, ',', '.') }}
                @endif
            </div>
            <div class="product-stats">
                <div class="stat-item">
                    <div class="stat-item-label">Stok</div>
                    <div class="stat-item-value">{{ $product->stock }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-item-label">Hari Ini</div>
                    <div class="stat-item-value">{{ $product->today_sales }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-item-label">Total</div>
                    <div class="stat-item-value">{{ $product->total_sold ?? 0 }}</div>
                </div>
            </div>
            <div class="product-actions">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-edit">✏️ Edit</a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Hapus produk ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" style="width: 100%;">🗑️ Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state" style="grid-column: 1 / -1;">
        <div class="empty-state-icon">🍞</div>
        <p>Belum ada produk</p>
        <p style="font-size: 0.875rem; margin-top: 0.5rem;">Mulai dengan menambahkan produk pertama Anda.</p>
        <a href="{{ route('admin.products.create') }}" class="add-btn" style="margin-top: 1rem;">Tambah Produk</a>
    </div>
    @endforelse
</div>

@endsection
