@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Produk</h1>
                <p class="text-gray-500 mt-1">{{ $product->name }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.products.edit', $product->id) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                    ✎ Edit
                </a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus produk ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                        🗑️ Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- Product Details -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                <!-- Product Image -->
                <div>
                    @if($product->image)
                        <img src="/storage/{{ $product->image }}" alt="{{ $product->name }}" 
                             class="w-full h-auto object-cover rounded-lg border border-gray-300">
                    @else
                        <div class="w-full h-96 bg-gray-300 rounded-lg flex items-center justify-center">
                            <span class="text-gray-600">Tidak ada gambar</span>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                        <p class="text-2xl font-bold text-gray-900">{{ $product->name }}</p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <p class="text-gray-600">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm inline-block">
                            {{ $product->category }}
                        </span>
                    </div>

                    <!-- Harga -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                        <p class="text-3xl font-bold text-green-600">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Stok -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <p class="text-2xl font-bold text-gray-900">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($product->stock > 20) bg-green-100 text-green-800
                                @elseif($product->stock > 5) bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $product->stock }} unit
                            </span>
                        </p>
                    </div>

                    <!-- Status Ketersediaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($product->is_available) bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($product->is_available) ✓ Tersedia @else ✕ Tidak Tersedia @endif
                        </span>
                    </div>

                    <!-- Tanggal -->
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Dibuat</label>
                            <p class="text-sm text-gray-900">{{ $product->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Diperbarui</label>
                            <p class="text-sm text-gray-900">{{ $product->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.products.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition inline-block">
                ← Kembali ke Daftar Produk
            </a>
        </div>
    </div>
</div>
@endsection
