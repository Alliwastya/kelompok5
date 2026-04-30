@extends('layouts.admin')

@section('title', 'Pengaturan Pembayaran')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">⚙️ Pengaturan Pembayaran</h1>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> 
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- QRIS Image Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-qrcode"></i> QR Code Pembayaran QRIS</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Current Image Preview -->
                        <div class="col-md-5">
                            <div class="text-center mb-3">
                                <h6 class="text-muted mb-3">Gambar Saat Ini:</h6>
                                <div class="border rounded p-3 bg-light" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                                    @if($qrisImage && $qrisImage->value)
                                        @if(filter_var($qrisImage->value, FILTER_VALIDATE_URL))
                                            <img src="{{ $qrisImage->value }}" alt="QRIS" class="img-fluid" style="max-width: 250px; max-height: 250px;">
                                        @else
                                            <img src="{{ Storage::url($qrisImage->value) }}" alt="QRIS" class="img-fluid" style="max-width: 250px; max-height: 250px;">
                                        @endif
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-image fa-3x mb-2"></i>
                                            <p>Belum ada gambar</p>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($qrisImage && $qrisImage->value && !filter_var($qrisImage->value, FILTER_VALIDATE_URL))
                                    <form action="{{ route('admin.payment-settings.delete-qris') }}" method="POST" class="mt-3" onsubmit="return confirm('Yakin ingin menghapus gambar ini dan kembali ke default?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Hapus & Reset ke Default
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- Upload Form -->
                        <div class="col-md-7">
                            <h6 class="text-muted mb-3">Upload Gambar Baru:</h6>
                            
                            <form action="{{ route('admin.payment-settings.update-qris') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="qris_image" class="form-label">Pilih Gambar QR Code</label>
                                    <input type="file" class="form-control @error('qris_image') is-invalid @enderror" 
                                           id="qris_image" name="qris_image" accept="image/*" required
                                           onchange="previewImage(event)">
                                    @error('qris_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Format: JPG, JPEG, PNG. Maksimal 2MB.
                                    </small>
                                </div>

                                <!-- Preview -->
                                <div id="imagePreview" class="mb-3" style="display: none;">
                                    <label class="form-label">Preview:</label>
                                    <div class="border rounded p-3 bg-light text-center">
                                        <img id="preview" src="" alt="Preview" class="img-fluid" style="max-width: 250px; max-height: 250px;">
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> <strong>Tips:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Gunakan gambar QR Code yang jelas dan mudah di-scan</li>
                                        <li>Ukuran yang disarankan: 500x500 pixel atau lebih</li>
                                        <li>Pastikan QR Code sudah ditest dan berfungsi dengan baik</li>
                                    </ul>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-upload"></i> Upload Gambar QRIS
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Tentang QR Code Pembayaran</h6>
                    <p class="small text-muted">
                        QR Code ini akan ditampilkan kepada pelanggan saat mereka memilih metode pembayaran QRIS.
                    </p>

                    <hr>

                    <h6 class="fw-bold">Cara Mendapatkan QR Code QRIS:</h6>
                    <ol class="small text-muted">
                        <li>Daftar merchant QRIS di bank Anda</li>
                        <li>Download QR Code dari aplikasi merchant</li>
                        <li>Upload QR Code di halaman ini</li>
                        <li>QR Code akan langsung aktif untuk pelanggan</li>
                    </ol>

                    <hr>

                    <h6 class="fw-bold">Status Saat Ini:</h6>
                    <div class="d-flex align-items-center">
                        @if($qrisImage && $qrisImage->value && !filter_var($qrisImage->value, FILTER_VALIDATE_URL))
                            <span class="badge bg-success me-2">
                                <i class="fas fa-check-circle"></i> Custom Image
                            </span>
                            <small class="text-muted">Menggunakan gambar upload</small>
                        @else
                            <span class="badge bg-warning text-dark me-2">
                                <i class="fas fa-exclamation-triangle"></i> Default
                            </span>
                            <small class="text-muted">Menggunakan gambar default</small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm w-100 mb-2">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-shopping-cart"></i> Lihat Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}
</script>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.alert {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection
