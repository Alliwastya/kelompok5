@extends('layouts.admin')

@section('page-title', 'Tambah Produk Baru')

@section('content')
<style>
    .form-container {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        padding: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        color: #FFD700;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        background: #1a1a1a;
        border: 2px solid #FFD700;
        color: #fff;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-family: inherit;
        font-size: 1rem;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
    }
    
    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: #666;
    }
    
    .form-section {
        background: rgba(255, 215, 0, 0.05);
        border: 1px solid #FFD700;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-section h3 {
        color: #FFD700;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .form-group-full {
        grid-column: 1 / -1;
    }
    
    .upload-area {
        border: 2px dashed #FFD700;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .upload-area:hover {
        background: rgba(255, 215, 0, 0.05);
    }
    
    .upload-area svg {
        width: 48px;
        height: 48px;
        color: #FFD700;
        margin: 0 auto 1rem;
    }
    
    .upload-area span {
        display: block;
        color: #FFD700;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .upload-area p {
        color: #999;
        font-size: 0.875rem;
    }
    
    .file-name {
        color: #999;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    
    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #FFD700;
    }
    
    .checkbox-group label {
        margin: 0;
        color: #FFD700;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #FFD700;
    }
    
    .btn {
        flex: 1;
        padding: 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s;
        text-decoration: none;
        text-align: center;
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
    
    .error-text {
        color: #fca5a5;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<a href="{{ route('admin.products.index') }}" class="back-btn">← Kembali ke Produk</a>

<div class="form-container">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nama Produk -->
        <div class="form-group">
            <label>Nama Produk *</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Roti Sosis Jumbo">
            @error('name')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kategori -->
        <div class="form-group">
            <label>Kategori *</label>
            <select name="category" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @if(old('category') === $cat) selected @endif>{{ $cat }}</option>
                @endforeach
            </select>
            @error('category')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="3" placeholder="Jelaskan produk (opsional)">{{ old('description') }}</textarea>
        </div>

        <!-- Harga & Stok -->
        <div class="form-section">
            <h3><span>📦</span> Manajemen Stok Harian</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label>Harga (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" required step="1000" min="0" placeholder="5000">
                    @error('price')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Stok Hari Ini *</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0">
                    @error('stock')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Minimum Stok (Peringatan)</label>
                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 5) }}" min="0">
                    <p style="color: #999; font-size: 0.875rem; margin-top: 0.5rem;">Notifikasi muncul jika stok ≤ nilai ini</p>
                </div>
                <div class="form-group">
                    <label>Status Manual (Override)</label>
                    <select name="manual_status">
                        <option value="">Otomatis (ikuti stok)</option>
                        <option value="ready" {{ old('manual_status') == 'ready' ? 'selected' : '' }}>🟢 Ready</option>
                        <option value="habis" {{ old('manual_status') == 'habis' ? 'selected' : '' }}>🔴 Habis</option>
                        <option value="pre-order" {{ old('manual_status') == 'pre-order' ? 'selected' : '' }}>📅 Pre-Order</option>
                    </select>
                    <p style="color: #999; font-size: 0.875rem; margin-top: 0.5rem;">Override status stok otomatis</p>
                </div>
            </div>
        </div>

        <!-- Diskon -->
        <div class="form-section">
            <h3>Pengaturan Diskon</h3>
            <input type="hidden" name="discount_type" value="fixed">
            <div class="form-group">
                <label>Potongan Harga / Diskon (Rp)</label>
                <input type="number" name="discount_value" value="{{ old('discount_value', 0) }}" min="0" step="1" placeholder="Contoh: 2000">
                <p style="color: #999; font-size: 0.875rem; margin-top: 0.5rem;">Masukkan nominal potongan harga (misal: 2000 untuk potongan Rp 2.000)</p>
            </div>
            <div class="checkbox-group">
                <input type="hidden" name="is_discount_active" value="0">
                <input type="checkbox" name="is_discount_active" value="1" id="discount-check" {{ old('is_discount_active') ? 'checked' : '' }}>
                <label for="discount-check">Aktifkan Diskon</label>
            </div>
        </div>

        <!-- Upload Gambar -->
        <div class="form-group">
            <label>Gambar Produk</label>
            <div class="upload-area">
                <label style="cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <span>Klik untuk upload gambar</span>
                    <p>JPG, PNG, GIF (Max 2MB)</p>
                    <input type="file" name="image" accept="image/*" class="hidden" onchange="document.getElementById('image-name').textContent = this.files[0]?.name || 'No file chosen'">
                </label>
            </div>
            <p id="image-name" class="file-name">No file chosen</p>
            @error('image')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status Ketersediaan -->
        <div class="checkbox-group">
            <input type="checkbox" name="is_available" value="1" id="available-check" checked>
            <label for="available-check">Produk Tersedia untuk Dijual</label>
        </div>

        <!-- Buttons -->
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">
                ✓ Simpan Produk
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                ← Batal
            </a>
        </div>
    </form>
</div>

@endsection
