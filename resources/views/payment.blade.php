<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran QRIS - Dapoer Budess</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #8B4513 0%, #6B3410 100%);
            padding: 1.25rem 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .back-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateX(-2px);
        }

        .header-title {
            color: white;
            font-size: 1.125rem;
            font-weight: 600;
            flex: 1;
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* Order Summary */
        .order-summary {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .order-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .order-row:last-child {
            margin-bottom: 0;
            padding-top: 1rem;
            border-top: 2px dashed #e9ecef;
        }

        .order-label {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .order-value {
            color: #212529;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .order-number {
            background: #f8f9fa;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 0.8rem;
        }

        .total-amount {
            color: #D2691E;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        /* Success Message */
        .success-alert {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.875rem;
            display: none;
            animation: slideDown 0.3s ease-out;
        }

        .success-alert.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* QRIS Card - Main Focus */
        .qris-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            text-align: center;
            transition: all 0.3s ease;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .qris-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .qris-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .qris-subtitle {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 2rem;
        }

        /* QR Code Container */
        .qr-container {
            position: relative;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .qr-wrapper {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 0 0 3px #f8f9fa, 0 0 0 6px #D2691E;
            display: inline-block;
            position: relative;
        }

        .qr-image {
            width: 300px;
            height: 300px;
            max-width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: contain;
            display: block;
            border-radius: 8px;
        }

        /* QR Info */
        .qr-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .qris-logo {
            height: 32px;
            width: auto;
        }

        .qr-info-text {
            font-weight: 700;
            color: #212529;
            font-size: 1.125rem;
        }

        .merchant-name {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .payment-methods {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, rgba(210, 105, 30, 0.1), rgba(139, 69, 19, 0.1));
            padding: 0.625rem 1.25rem;
            border-radius: 25px;
            font-size: 0.8rem;
            color: #8B4513;
            font-weight: 600;
            border: 1px solid rgba(210, 105, 30, 0.2);
        }

        /* Instructions */
        .instructions-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .instructions-title {
            font-weight: 700;
            font-size: 1rem;
            color: #212529;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .instruction-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: flex-start;
        }

        .instruction-item:last-child {
            margin-bottom: 0;
        }

        .step-number {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #D2691E, #8B4513);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .step-text {
            color: #495057;
            font-size: 0.875rem;
            line-height: 1.6;
            padding-top: 4px;
        }

        /* Upload Card */
        .upload-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .upload-title {
            font-weight: 700;
            font-size: 1rem;
            color: #212529;
            margin-bottom: 1rem;
        }

        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 2.5rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f8f9fa;
            position: relative;
        }

        .upload-area:hover {
            border-color: #D2691E;
            background: white;
        }

        .upload-area.has-file {
            border-color: #D2691E;
            background: white;
            padding: 0;
        }

        .upload-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .upload-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #D2691E, #8B4513);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(210, 105, 30, 0.3);
        }

        .upload-icon svg {
            width: 32px;
            height: 32px;
            stroke: white;
            stroke-width: 2.5;
            fill: none;
        }

        .upload-text {
            font-weight: 600;
            color: #212529;
            font-size: 1rem;
        }

        .upload-hint {
            color: #6c757d;
            font-size: 0.8rem;
        }

        #proofPreview {
            display: none;
            width: 100%;
            max-height: 350px;
            object-fit: contain;
            border-radius: 12px;
        }

        /* Alert */
        .alert-info {
            background: #fff3cd;
            border: 1px solid #ffe69c;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .alert-icon {
            flex-shrink: 0;
            font-size: 1.25rem;
        }

        .alert-text {
            color: #856404;
            font-size: 0.875rem;
            line-height: 1.5;
            font-weight: 500;
        }

        /* Buttons */
        .button-group {
            display: flex;
            gap: 1rem;
            max-width: 600px;
            margin: 0 auto 1.5rem auto;
        }

        .btn {
            flex: 1;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }

        .btn-primary {
            flex: 2;
            background: linear-gradient(135deg, #D2691E 0%, #8B4513 100%);
            color: white;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(210, 105, 30, 0.3);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(210, 105, 30, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .back-link {
            text-align: center;
            padding: 1rem 0;
        }

        .back-link a {
            color: #8B4513;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .back-link a:hover {
            gap: 0.75rem;
        }

        /* Responsive Design */
        
        /* Desktop Large (1200px+) */
        @media (min-width: 1200px) {
            .container {
                padding: 3rem 2rem;
            }

            .qris-card {
                padding: 4rem 3rem;
            }

            .qr-image {
                width: 350px;
                height: 350px;
            }

            .total-amount {
                font-size: 2.5rem;
            }
        }

        /* Tablet (768px - 1199px) */
        @media (min-width: 768px) and (max-width: 1199px) {
            .container {
                padding: 2.5rem 2rem;
            }

            .qris-card {
                padding: 3.5rem 2.5rem;
            }

            .qr-image {
                width: 320px;
                height: 320px;
            }

            .total-amount {
                font-size: 2.25rem;
            }
        }

        /* Mobile Large (481px - 767px) */
        @media (min-width: 481px) and (max-width: 767px) {
            .container {
                padding: 1.5rem 1.25rem;
            }

            .qris-card {
                padding: 2.5rem 1.5rem;
            }

            .qr-image {
                width: 280px;
                height: 280px;
            }

            .total-amount {
                font-size: 1.875rem;
            }

            .qris-title {
                font-size: 1.125rem;
            }
        }

        /* Mobile Small (max 480px) */
        @media (max-width: 480px) {
            .header {
                padding: 1rem 1rem;
            }

            .header-title {
                font-size: 1rem;
            }

            .back-btn {
                width: 36px;
                height: 36px;
            }

            .container {
                padding: 1.25rem 1rem;
            }

            .order-summary {
                padding: 1.25rem;
            }

            .qris-card {
                padding: 2rem 1.25rem;
                border-radius: 16px;
            }

            .qris-title {
                font-size: 1rem;
            }

            .qris-subtitle {
                font-size: 0.8rem;
            }

            .qr-wrapper {
                padding: 1rem;
            }

            .qr-image {
                width: 240px;
                height: 240px;
            }

            .qris-logo {
                height: 28px;
            }

            .qr-info-text {
                font-size: 1rem;
            }

            .merchant-name {
                font-size: 0.8rem;
            }

            .payment-methods {
                font-size: 0.75rem;
                padding: 0.5rem 1rem;
            }

            .total-amount {
                font-size: 1.5rem;
            }

            .instructions-card,
            .upload-card {
                padding: 1.5rem;
            }

            .upload-area {
                padding: 2rem 1.25rem;
            }

            .upload-icon {
                width: 56px;
                height: 56px;
            }

            .upload-icon svg {
                width: 28px;
                height: 28px;
            }

            .button-group {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn {
                padding: 0.875rem 1.25rem;
            }

            .btn-primary {
                flex: 1;
            }
        }

        /* Extra Small (max 360px) */
        @media (max-width: 360px) {
            .qr-image {
                width: 200px;
                height: 200px;
            }

            .qris-card {
                padding: 1.75rem 1rem;
            }

            .total-amount {
                font-size: 1.375rem;
            }
        }

        /* Touch Optimization */
        @media (hover: none) and (pointer: coarse) {
            .btn,
            .upload-area,
            .back-btn {
                -webkit-tap-highlight-color: transparent;
                touch-action: manipulation;
            }

            .btn:active {
                transform: scale(0.98);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="window.location.href='/'">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
            </button>
            <h1 class="header-title">Pembayaran QRIS</h1>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Order Summary -->
        <div class="order-summary">
            <div class="order-row">
                <span class="order-label">Nomor Pesanan</span>
                <span class="order-number">#{{ $orderNumber ?? 'Loading...' }}</span>
            </div>
            <div class="order-row">
                <span class="order-label">Total Pembayaran</span>
                <span class="total-amount">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Success Message -->
        <div class="success-alert" id="successMessage">
            ✓ Bukti pembayaran berhasil dikirim! Menunggu verifikasi admin.
        </div>

        <!-- QRIS Card - Main Focus -->
        <div class="qris-card">
            <h2 class="qris-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                Scan untuk Bayar
            </h2>
            <p class="qris-subtitle">Gunakan aplikasi e-wallet atau mobile banking Anda</p>
            
            <div class="qr-container">
                <div class="qr-wrapper">
                    @php
                        $qrisImage = \App\Models\PaymentSetting::getQrisImage();
                    @endphp
                    <img src="{{ $qrisImage }}" 
                         alt="QRIS Payment Code" 
                         class="qr-image"
                         onerror="this.style.border='2px dashed #ccc'; this.style.padding='20px'; this.alt='QR Code tidak tersedia';">
                </div>
            </div>
            
            <div class="qr-info">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1200px-Logo_QRIS.svg.png" 
                     alt="QRIS" 
                     class="qris-logo">
                <span class="qr-info-text">QRIS Payment</span>
            </div>
            
            <p class="merchant-name">🏪 Dapoer Budess Bakery</p>
            
            <div class="payment-methods">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                Semua E-Wallet & Mobile Banking
            </div>
        </div>

        <!-- Instructions -->
        <div class="instructions-card">
            <h3 class="instructions-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                Cara Pembayaran
            </h3>
            <div class="instruction-item">
                <span class="step-number">1</span>
                <span class="step-text">Buka aplikasi e-wallet atau mobile banking Anda (GoPay, OVO, Dana, ShopeePay, dll)</span>
            </div>
            <div class="instruction-item">
                <span class="step-number">2</span>
                <span class="step-text">Pilih menu "Scan QR" atau "QRIS"</span>
            </div>
            <div class="instruction-item">
                <span class="step-number">3</span>
                <span class="step-text">Arahkan kamera ke kode QR di atas</span>
            </div>
            <div class="instruction-item">
                <span class="step-number">4</span>
                <span class="step-text">Konfirmasi pembayaran dan simpan bukti transfer</span>
            </div>
            <div class="instruction-item">
                <span class="step-number">5</span>
                <span class="step-text">Upload bukti pembayaran di bawah ini</span>
            </div>
        </div>

        <!-- Upload Card -->
        <form id="uploadForm" onsubmit="handleSubmit(event)">
            <input type="hidden" name="order_id" value="{{ $orderId ?? '' }}">
            
            <div class="upload-card">
                <h3 class="upload-title">Upload Bukti Pembayaran</h3>
                
                <div class="upload-area" id="uploadArea" onclick="document.getElementById('fileInput').click()">
                    <div class="upload-placeholder" id="uploadPlaceholder">
                        <div class="upload-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        <div class="upload-text">Klik untuk upload bukti transfer</div>
                        <div class="upload-hint">Format: JPG, PNG (Maksimal 2MB)</div>
                    </div>
                    
                    <img id="proofPreview" alt="Preview">
                </div>
                
                <input type="file" 
                       id="fileInput" 
                       name="payment_proof" 
                       accept="image/png,image/jpeg,image/jpg" 
                       style="display: none;" 
                       onchange="previewImage()"
                       required>
            </div>
            
            <!-- Alert -->
            <div class="alert-info">
                <span class="alert-icon">⏳</span>
                <span class="alert-text">Pesanan Anda akan diproses setelah admin memverifikasi pembayaran. Biasanya memakan waktu 5-15 menit.</span>
            </div>
            
            <!-- Buttons -->
            <div class="button-group">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='/'">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    Kirim Bukti Pembayaran
                </button>
            </div>
        </form>

        <!-- Back Link -->
        <div class="back-link">
            <a href="/">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        function previewImage() {
            const input = document.getElementById('fileInput');
            const preview = document.getElementById('proofPreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            const uploadArea = document.getElementById('uploadArea');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                    uploadArea.classList.add('has-file');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function handleSubmit(event) {
            event.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(event.target);
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/upload-payment-proof', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').classList.add('show');
                    document.getElementById('uploadForm').reset();
                    document.getElementById('proofPreview').style.display = 'none';
                    document.getElementById('uploadPlaceholder').style.display = 'flex';
                    document.getElementById('uploadArea').classList.remove('has-file');
                    
                    // Scroll to success message
                    document.getElementById('successMessage').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2500);
                } else {
                    alert('Gagal upload: ' + (data.message || 'Terjadi kesalahan'));
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Kirim Bukti Pembayaran';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat upload');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kirim Bukti Pembayaran';
            });
        }
    </script>
</body>
</html>
