@extends('layouts.admin')

@section('title', 'Pengaturan Promo')
@section('page-title', 'Pengaturan Promo Banner & Modal')

@section('content')
<form action="{{ route('admin.promo.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @if(session('success'))
        <div style="background: rgba(76, 175, 80, 0.1); color: #4CAF50; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid rgba(76, 175, 80, 0.2);">
            {{ session('success') }}
        </div>
    @endif

    <!-- SECTION 1: BANNER PROMO -->
    <div class="card" style="max-width: 1000px; margin: 0 auto 2rem;">
        <h2 style="color: #FFD700; margin-bottom: 2rem; font-family: 'Playfair Display', serif; border-bottom: 1px solid #333; padding-bottom: 1rem;">1. Pengaturan Banner Hero</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <!-- Left Column: Text Content -->
            <div>
                <div style="margin-bottom: 1.2rem;">
                    <label style="display: block; color: #999; margin-bottom: 0.5rem; font-size: 0.85rem;">Judul Promo</label>
                    <input type="text" name="title" value="{{ old('title', $promo->title) }}" required style="width: 100%; background: #1a1a1a; border: 1px solid #333; color: #fff; padding: 0.8rem; border-radius: 8px;">
                </div>

                <div style="margin-bottom: 1.2rem;">
                    <label style="display: block; color: #999; margin-bottom: 0.5rem; font-size: 0.85rem;">Sub-judul / Deskripsi</label>
                    <textarea name="subtitle" rows="3" required style="width: 100%; background: #1a1a1a; border: 1px solid #333; color: #fff; padding: 0.8rem; border-radius: 8px;">{{ old('subtitle', $promo->subtitle) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.2rem;">
                    <div>
                        <label style="display: block; color: #999; margin-bottom: 0.5rem; font-size: 0.85rem;">Harga Normal</label>
                        <input type="number" name="price_original" value="{{ old('price_original', $promo->price_original) }}" required style="width: 100%; background: #1a1a1a; border: 1px solid #333; color: #fff; padding: 0.8rem; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; color: #999; margin-bottom: 0.5rem; font-size: 0.85rem;">Harga Promo</label>
                        <input type="number" name="price_promo" value="{{ old('price_promo', $promo->price_promo) }}" required style="width: 100%; background: #1a1a1a; border: 1px solid #333; color: #fff; padding: 0.8rem; border-radius: 8px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.2rem;">
                    <div>
                        <label style="display: block; color: #999; margin-bottom: 0.5rem; font-size: 0.85rem;">Teks Badge (Kiri)</label>
                        <input type="text" name="badge_text" value="{{ old('badge_text', $promo->badge_text) }}" style="width: 100%; background: #1a1a1a; border: 1px solid #333; color: #fff; padding: 0.8rem; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; color: #999; margin-bottom: 0.5rem; font-size: 0.85rem;">Teks Diskon (Kanan)</label>
                        <input type="text" name="discount_badge_text" value="{{ old('discount_badge_text', $promo->discount_badge_text) }}" style="width: 100%; background: #1a1a1a; border: 1px solid #333; color: #fff; padding: 0.8rem; border-radius: 8px;">
                    </div>
                </div>

                <div style="margin-bottom: 1.2rem;">
                    <label style="display: block; color: #999; margin-bottom: 0.5rem; font-size: 0.85rem;">Waktu Berakhir (Countdown)</label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time', $promo->end_time ? $promo->end_time->format('Y-m-d\TH:i') : '') }}" required style="width: 100%; background: #1a1a1a; border: 1px solid #333; color: #fff; padding: 0.8rem; border-radius: 8px;">
                </div>

                <div style="margin-top: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #fff; cursor: pointer;">
                        <input type="checkbox" name="is_active" {{ $promo->is_active ? 'checked' : '' }} style="width: 18px; height: 18px;">
                        Aktifkan Banner Promo
                    </label>
                </div>
            </div>

            <!-- Right Column: Images -->
            <div>
                <div style="margin-bottom: 1.5rem; padding: 1rem; background: #1a1a1a; border-radius: 12px; border: 1px dashed #444;">
                    <label style="display: block; color: #999; margin-bottom: 0.8rem; font-size: 0.85rem;">Foto Utama (Besar)</label>
                    @if($promo->image_main)
                        <img src="{{ asset($promo->image_main) }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #333;">
                    @endif
                    <input type="file" name="image_main" accept="image/*" style="color: #666; font-size: 0.8rem;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="padding: 1rem; background: #1a1a1a; border-radius: 12px; border: 1px dashed #444;">
                        <label style="display: block; color: #999; margin-bottom: 0.8rem; font-size: 0.85rem;">Foto Kedua (Kecil)</label>
                        @if($promo->image_second)
                            <img src="{{ asset($promo->image_second) }}" style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #333;">
                        @endif
                        <input type="file" name="image_second" accept="image/*" style="color: #666; font-size: 0.8rem; width: 100%;">
                    </div>
                    <div style="padding: 1rem; background: #1a1a1a; border-radius: 12px; border: 1px dashed #444;">
                        <label style="display: block; color: #999; margin-bottom: 0.8rem; font-size: 0.85rem;">Foto Ketiga (Kecil)</label>
                        @if($promo->image_third)
                            <img src="{{ asset($promo->image_third) }}" style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #333;">
                        @endif
                        <input type="file" name="image_third" accept="image/*" style="color: #666; font-size: 0.8rem; width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: MODAL PROMO PRODUCTS -->
    <div class="card" style="max-width: 1000px; margin: 0 auto 2rem;">
        <h2 style="color: #FFD700; margin-bottom: 2rem; font-family: 'Playfair Display', serif; border-bottom: 1px solid #333; padding-bottom: 1rem;">2. Pengaturan Produk di Modal Promo</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            @foreach($modalProducts as $product)
            <div style="background: #1a1a1a; padding: 1.5rem; border-radius: 16px; border: 1px solid #333;">
                <h4 style="color: #ea580c; margin-bottom: 1.5rem; border-bottom: 1px solid #222; padding-bottom: 0.5rem;">Produk #{{ $loop->iteration }}</h4>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: #999; margin-bottom: 0.4rem; font-size: 0.8rem;">Nama Produk</label>
                    <input type="text" name="modal_products[{{ $product->id }}][name]" value="{{ $product->name }}" required style="width: 100%; background: #000; border: 1px solid #333; color: #fff; padding: 0.6rem; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: #999; margin-bottom: 0.4rem; font-size: 0.8rem;">Sub-judul</label>
                    <input type="text" name="modal_products[{{ $product->id }}][subtitle]" value="{{ $product->subtitle }}" style="width: 100%; background: #000; border: 1px solid #333; color: #fff; padding: 0.6rem; border-radius: 6px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; color: #999; margin-bottom: 0.4rem; font-size: 0.8rem;">Harga Asli</label>
                        <input type="number" name="modal_products[{{ $product->id }}][price_original]" value="{{ $product->price_original }}" style="width: 100%; background: #000; border: 1px solid #333; color: #fff; padding: 0.6rem; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="display: block; color: #999; margin-bottom: 0.4rem; font-size: 0.8rem;">Harga Promo</label>
                        <input type="number" name="modal_products[{{ $product->id }}][price_promo]" value="{{ $product->price_promo }}" required style="width: 100%; background: #000; border: 1px solid #333; color: #fff; padding: 0.6rem; border-radius: 6px;">
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: #999; margin-bottom: 0.4rem; font-size: 0.8rem;">Badge (e.g. PROMO)</label>
                    <input type="text" name="modal_products[{{ $product->id }}][badge]" value="{{ $product->badge }}" style="width: 100%; background: #000; border: 1px solid #333; color: #fff; padding: 0.6rem; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: #999; margin-bottom: 0.4rem; font-size: 0.8rem;">Label Stok</label>
                    <input type="text" name="modal_products[{{ $product->id }}][stock_label]" value="{{ $product->stock_label }}" style="width: 100%; background: #000; border: 1px solid #333; color: #fff; padding: 0.6rem; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: #999; margin-bottom: 0.4rem; font-size: 0.8rem;">Label Bawah</label>
                    <input type="text" name="modal_products[{{ $product->id }}][bottom_label]" value="{{ $product->bottom_label }}" style="width: 100%; background: #000; border: 1px solid #333; color: #fff; padding: 0.6rem; border-radius: 6px;">
                </div>

                <div style="padding: 1rem; background: #000; border-radius: 8px; border: 1px dashed #444;">
                    <label style="display: block; color: #999; margin-bottom: 0.8rem; font-size: 0.8rem;">Foto Produk</label>
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 6px; margin-bottom: 0.8rem; border: 1px solid #222;">
                    @endif
                    <input type="file" name="modal_products[{{ $product->id }}][image]" accept="image/*" style="color: #555; font-size: 0.75rem; width: 100%;">
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div style="max-width: 1000px; margin: 0 auto; display: flex; justify-content: flex-end; padding-bottom: 5rem;">
        <button type="submit" class="btn btn-primary" style="padding: 1rem 4rem; font-weight: 800; font-size: 1.1rem; box-shadow: 0 10px 20px rgba(232, 130, 26, 0.2);">
            💾 SIMPAN SEMUA PENGATURAN
        </button>
    </div>
</form>
@endsection
