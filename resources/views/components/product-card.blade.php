@props(['product'])

<div class="bg-white rounded-2xl overflow-visible shadow-lg transition-all duration-400 hover:-translate-y-2 hover:shadow-2xl relative border-3 border-yellow-600 product-card">
    @if($product['bestseller'] ?? false)
        <div class="absolute top-4 left-4 bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide shadow-lg z-10 flex items-center gap-2 animate-bounce">
            ⭐ TERLARIS
        </div>
    @endif
    
    <div class="relative pt-[75%] overflow-hidden bg-gray-100">
        <div class="absolute inset-0 flex items-center justify-center text-6xl">
            {{ $product['image'] ?? '🍞' }}
        </div>
        
        @if(($product['discount'] ?? 0) < ($product['price'] ?? 0))
            @php
                $discountPercent = round((($product['price'] - $product['discount']) / $product['price']) * 100);
            @endphp
            <div class="absolute top-4 left-4 bg-red-500 text-white px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide shadow-lg z-10">
                HEMAT {{ $discountPercent }}%
            </div>
        @endif
    </div>
    
    <div class="p-6">
        <h3 class="font-playfair text-xl font-bold text-gray-800 mb-2">
            {{ $product['name'] ?? 'Produk' }}
        </h3>
        
        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
            {{ $product['description'] ?? '' }}
        </p>
        
        <div class="inline-block bg-orange-50 text-orange-700 text-xs px-3 py-1 rounded font-semibold mb-4">
            Stok: {{ $product['stock'] ?? 0 }}
        </div>
        
        <div class="flex items-baseline gap-3 mb-4">
            @if(($product['discount'] ?? 0) < ($product['price'] ?? 0))
                <span class="text-sm text-gray-400 line-through">
                    Rp {{ number_format($product['price'], 0, ',', '.') }}
                </span>
            @endif
            <span class="text-xl font-bold text-amber-800">
                Rp {{ number_format($product['discount'] ?? $product['price'], 0, ',', '.') }}
            </span>
        </div>
        
        <button 
            onclick="addToCart({{ $product['id'] }})" 
            @if(($product['stock'] ?? 0) === 0) disabled @endif
            class="w-full py-3 rounded-xl bg-amber-800 text-white font-semibold hover:bg-amber-700 transition-all duration-300 flex items-center justify-center gap-2 disabled:bg-gray-300 disabled:cursor-not-allowed disabled:hover:bg-gray-300">
            🛒 Tambah ke Keranjang
        </button>
    </div>
</div>
