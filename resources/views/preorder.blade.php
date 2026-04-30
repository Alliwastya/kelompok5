<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pre-Order - Dapoer Budess</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lora:wght@500;600&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8B4513;
            --secondary: #D2691E;
            --accent: #F4A460;
            --dark: #2C1810;
            --cream: #FFF8DC;
            --gold: #FFD700;
            --preorder-gold: #FFA500;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lora', serif;
            background: linear-gradient(135deg, #F5EDE3 0%, #EDE4D9 50%, #F5EDE3 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, var(--preorder-gold) 0%, #FF8C00 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .header .badge {
            display: inline-block;
            background: rgba(255,255,255,0.3);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .content {
            padding: 2rem;
        }

        .info-box {
            background: #FFF9E6;
            border-left: 4px solid var(--preorder-gold);
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            border-radius: 8px;
        }

        .info-box h3 {
            color: var(--preorder-gold);
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .info-box p {
            color: #666;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-group label .required {
            color: #E53935;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #E0E0E0;
            border-radius: 8px;
            font-family: 'Lora', serif;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--preorder-gold);
            box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group small {
            display: block;
            color: #999;
            margin-top: 0.25rem;
            font-size: 0.85rem;
        }

        .product-summary {
            background: #F5F5F5;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .product-summary h3 {
            color: var(--dark);
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #E0E0E0;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-name {
            font-weight: 600;
            color: var(--dark);
        }

        .product-price {
            color: var(--preorder-gold);
            font-weight: 700;
        }

        .total-section {
            background: var(--cream);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }

        .total-row.final {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--preorder-gold);
            padding-top: 0.75rem;
            border-top: 2px solid var(--preorder-gold);
        }

        .btn-submit {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--preorder-gold) 0%, #FF8C00 100%);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(255, 165, 0, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(255, 165, 0, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 1rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-back:hover {
            color: var(--preorder-gold);
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem 0.5rem;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .content {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🟡 Pre-Order</h1>
            <div class="badge">Pesanan untuk Hari Berikutnya</div>
        </div>

        <div class="content">
            <a href="/" class="btn-back">← Kembali ke Beranda</a>

            <div class="info-box">
                <h3>📅 Tentang Pre-Order</h3>
                <p>Pre-order memungkinkan Anda memesan roti untuk diambil atau dikirim pada tanggal yang Anda pilih. Pesanan akan diproses sesuai jadwal yang Anda tentukan.</p>
            </div>

            <form id="preorderForm">
                @csrf

                <!-- Product Summary -->
                <div class="product-summary">
                    <h3>🛒 Ringkasan Pesanan</h3>
                    <div id="productList"></div>
                </div>

                <!-- Pickup Date & Time -->
                <div class="form-group">
                    <label>📅 Tanggal Pengambilan <span class="required">*</span></label>
                    <input type="date" name="pickup_date" id="pickup_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    <small>Pilih tanggal minimal besok</small>
                </div>

                <div class="form-group">
                    <label>⏰ Jam Pengambilan (Opsional)</label>
                    <input type="time" name="pickup_time" id="pickup_time">
                    <small>Kosongkan jika tidak ada preferensi waktu tertentu</small>
                </div>

                <!-- Customer Info -->
                <div class="form-group">
                    <label>👤 Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="customer_name" required minlength="3" placeholder="Masukkan nama lengkap">
                </div>

                <div class="form-group">
                    <label>📞 Nomor Telepon <span class="required">*</span></label>
                    <input type="tel" name="customer_phone" required minlength="10" placeholder="08xxxxxxxxxx">
                </div>

                <div class="form-group">
                    <label>📧 Email (Opsional)</label>
                    <input type="email" name="customer_email" placeholder="email@example.com">
                </div>

                <!-- Shipping Method -->
                <div class="form-group">
                    <label>🚚 Metode Pengiriman <span class="required">*</span></label>
                    <select name="shipping_method" id="shipping_method" required>
                        <option value="">Pilih metode pengiriman</option>
                        <option value="pickup">Ambil di Tempat (Bakery)</option>
                        <option value="delivery">Diantar ke Alamat</option>
                    </select>
                </div>

                <!-- Delivery Address (shown only if delivery selected) -->
                <div id="deliveryFields" style="display: none;">
                    <div class="form-group">
                        <label>🏙️ Kota <span class="required">*</span></label>
                        <input type="text" name="city" placeholder="Nama kota">
                    </div>

                    <div class="form-group">
                        <label>🛣️ Nama Jalan <span class="required">*</span></label>
                        <input type="text" name="street" placeholder="Nama jalan">
                    </div>

                    <div class="form-group">
                        <label>🏠 Nomor Rumah <span class="required">*</span></label>
                        <input type="text" name="house_number" placeholder="Nomor rumah">
                    </div>

                    <div class="form-group">
                        <label>📍 RT/RW <span class="required">*</span></label>
                        <input type="text" name="rt_rw" placeholder="RT/RW">
                    </div>

                    <div class="form-group">
                        <label>🏡 Ciri-ciri Rumah (Opsional)</label>
                        <textarea name="house_details" placeholder="Contoh: Rumah cat hijau, pagar putih"></textarea>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="form-group">
                    <label>💳 Metode Pembayaran <span class="required">*</span></label>
                    <select name="payment_method" required>
                        <option value="">Pilih metode pembayaran</option>
                        <option value="COD">COD (Bayar di Tempat)</option>
                        <option value="QRIS">QRIS (Transfer Digital)</option>
                    </select>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label>📝 Catatan Pesanan (Opsional)</label>
                    <textarea name="notes" placeholder="Tambahkan catatan khusus untuk pesanan Anda"></textarea>
                </div>

                <!-- Total -->
                <div class="total-section">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">Rp 0</span>
                    </div>
                    <div class="total-row">
                        <span>Ongkir:</span>
                        <span>Rp 0 <small>(akan dikonfirmasi admin)</small></span>
                    </div>
                    <div class="total-row final">
                        <span>Total:</span>
                        <span id="total">Rp 0</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit">📅 Buat Pre-Order Sekarang</button>
            </form>
        </div>
    </div>

    <script>
        // Get cart data from localStorage or URL parameter
        let cart = [];
        
        // Check if coming from product page with specific product
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('product_id');
        
        if (productId) {
            // Single product pre-order
            const productName = urlParams.get('product_name');
            const productPrice = parseFloat(urlParams.get('product_price'));
            const quantity = parseInt(urlParams.get('quantity') || 1);
            
            cart = [{
                id: productId,
                name: productName,
                price: productPrice,
                quantity: quantity
            }];
        } else {
            // Cart pre-order
            const cartData = localStorage.getItem('cart');
            if (cartData) {
                cart = JSON.parse(cartData);
            }
        }

        // Display products
        function displayProducts() {
            const productList = document.getElementById('productList');
            let subtotal = 0;

            if (cart.length === 0) {
                productList.innerHTML = '<p style="text-align:center;color:#999;">Keranjang kosong</p>';
                return;
            }

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;

                productList.innerHTML += `
                    <div class="product-item">
                        <div>
                            <div class="product-name">${item.name}</div>
                            <div style="color:#999;font-size:0.9rem;">${item.quantity}x @ Rp ${item.price.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="product-price">Rp ${itemTotal.toLocaleString('id-ID')}</div>
                    </div>
                `;
            });

            document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
            document.getElementById('total').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        }

        displayProducts();

        // Toggle delivery fields
        document.getElementById('shipping_method').addEventListener('change', function() {
            const deliveryFields = document.getElementById('deliveryFields');
            if (this.value === 'delivery') {
                deliveryFields.style.display = 'block';
                // Make fields required
                deliveryFields.querySelectorAll('input[name="city"], input[name="street"], input[name="house_number"], input[name="rt_rw"]').forEach(input => {
                    input.required = true;
                });
            } else {
                deliveryFields.style.display = 'none';
                // Remove required
                deliveryFields.querySelectorAll('input').forEach(input => {
                    input.required = false;
                });
            }
        });

        // Form submission
        document.getElementById('preorderForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const pickupDate = document.getElementById('pickup_date').value;
            if (!pickupDate) {
                alert('Silakan pilih tanggal pengambilan');
                return;
            }

            const submitBtn = this.querySelector('.btn-submit');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses...';

            const formData = new FormData(this);
            
            // Add cart items
            formData.append('order_type', 'preorder');
            formData.append('items', JSON.stringify(cart.map(item => ({
                product_id: item.id,
                product_name: item.name,
                price: item.price,
                quantity: item.quantity
            }))));

            try {
                const response = await fetch('/preorder/submit', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert('✅ Pre-order berhasil dijadwalkan!\n\nNomor Pesanan: ' + result.order_number);
                    localStorage.removeItem('cart');
                    window.location.href = '/';
                } else {
                    alert('❌ ' + (result.message || 'Terjadi kesalahan'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ Terjadi kesalahan saat memproses pre-order');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = '📅 Buat Pre-Order Sekarang';
            }
        });
    </script>
</body>
</html>
