## Halaman Admin Panel - Dokumentasi Lengkap

Sistem admin panel untuk Dapoer Budess telah berhasil dibuat dengan fitur lengkap mencakup CRUD pesanan, laporan penjualan, dan manajemen pesan dari pelanggan.

### 📋 Fitur-Fitur Admin Panel

#### 1. **Dashboard Admin**
   - **URL**: `/admin`
   - **Akses**: Hanya user yang sudah login
   - **Menampilkan**:
     - Total pendapatan hari ini
     - Total pendapatan bulan ini
     - Jumlah pesanan hari ini
     - Jumlah pesan baru yang belum dibaca
     - Grafik penjualan bulan ini
     - Daftar pesanan terbaru (10 pesanan)
     - Daftar pesan terbaru (5 pesan)

#### 2. **Manajemen Pesanan (Orders)**
   - **URL**: `/admin/orders`
   - **Fitur**:
     - Lihat semua pesanan dengan pagination
     - Cari pesanan berdasarkan nomor pesanan atau nama pelanggan
     - Filter pesanan berdasarkan status (pending, processing, shipped, delivered, cancelled)
     - Lihat detail pesanan lengkap
     - Update status pesanan
     - Lihat informasi pelanggan dan daftar produk yang dipesan
     - Buat pesanan baru manual

#### 3. **Manajemen Pesan (Messages)**
   - **URL**: `/admin/messages`
   - **Fitur**:
     - Lihat semua pesan dari pelanggan
     - Status pesan: unread, read, replied
     - Cari pesan berdasarkan nama atau nomor telepon
     - Filter berdasarkan status
     - Balas pesan pelanggan
     - Link langsung ke WhatsApp untuk chat dengan pelanggan
     - Riwayat balasan yang sudah dikirim

#### 4. **Laporan Penjualan (Reports)**
   - **URL**: `/admin/reports`
   - **Fitur**:
     - Filter laporan berdasarkan rentang tanggal
     - Lihat penjualan per hari
     - Lihat penjualan per bulan
     - Total pendapatan dalam periode
     - Total jumlah pesanan
     - Total biaya pengiriman
     - Rata-rata pendapatan per pesanan

### 🗄️ Database Structure

#### Tabel `orders`
```sql
- id (Primary Key)
- order_number (unique)
- customer_name
- customer_phone
- customer_email (nullable)
- customer_address
- total_amount (decimal)
- shipping_cost (decimal)
- status (enum: pending, processing, shipped, delivered, cancelled)
- payment_method
- notes (nullable)
- created_at, updated_at
```

#### Tabel `order_items`
```sql
- id (Primary Key)
- order_id (Foreign Key -> orders)
- product_name
- price (decimal)
- quantity (integer)
- subtotal (decimal)
- created_at, updated_at
```

#### Tabel `messages` (dengan update)
```sql
- id (Primary Key)
- name
- phone
- message
- status (enum: unread, read, replied)
- admin_reply (nullable)
- replied_at (timestamp nullable)
- created_at, updated_at
```

### 🔐 Routes yang Tersedia

```php
// Dashboard
GET /admin → AdminController@dashboard

// Orders Management
GET /admin/orders → AdminController@orders
GET /admin/orders/create → AdminController@createOrder
POST /admin/orders → AdminController@storeOrder
GET /admin/orders/{order} → AdminController@showOrder
PATCH /admin/orders/{order}/status → AdminController@updateOrderStatus

// Messages Management
GET /admin/messages → AdminController@messages
GET /admin/messages/{message} → AdminController@showMessage
POST /admin/messages/{message}/reply → AdminController@replyMessage

// Reports
GET /admin/reports → AdminController@reports
```

### 📱 Fitur Logistik

1. **Tracking Status Pesanan**:
   - Status dapat diupdate melalui halaman detail pesanan
   - Status tersedia: Pending, Processing, Shipped, Delivered, Cancelled
   - Setiap update status dapat ditambahkan dengan catatan

2. **Informasi Pengiriman**:
   - Menampilkan metode pembayaran
   - Menampilkan status logistik (dalam proses/tersampaikan)
   - Informasi pelanggan dan alamat lengkap

### 💬 Sistem Messaging

1. **Menerima Pesan**:
   - Pelanggan dapat mengirim pesan melalui form di website
   - Pesan tersimpan di database dengan status "unread"

2. **Membalas Pesan**:
   - Admin dapat melihat semua pesan di dashboard
   - Dapat membalas pesan langsung melalui sistem atau WhatsApp
   - Status pesan berubah menjadi "replied" setelah dibalas

3. **Integrasi WhatsApp**:
   - Tombol "Kirim via WhatsApp" untuk mengirim balasan langsung
   - Link otomatis membuka WhatsApp dengan nomor pelanggan

### 📊 Laporan & Analytics

1. **Penjualan Harian**:
   - Melihat jumlah pesanan dan total pendapatan per hari
   - Dapat filter berdasarkan rentang tanggal

2. **Penjualan Bulanan**:
   - Ringkasan penjualan per bulan
   - Bandingkan performa antar bulan

3. **Statistik Penting**:
   - Total pendapatan
   - Total pesanan
   - Total biaya pengiriman
   - Rata-rata pendapatan per pesanan

### 🚀 Cara Mengakses Admin Panel

1. Login dengan akun yang sudah terdaftar
2. Akses `/admin` untuk membuka dashboard
3. Gunakan menu navigasi untuk mengakses berbagai fitur

### 💰 Perhitungan Pendapatan

- **Total Pendapatan** = Total dari semua order.total_amount
- **Ongkos Kirim** = Total dari semua order.shipping_cost
- **Pendapatan Bersih** = Total Pendapatan - Ongkos Kirim
- **Rata-rata per Pesanan** = Total Pendapatan / Jumlah Pesanan

### ⚠️ Catatan Penting

- Hanya user yang sudah login dapat mengakses admin panel
- Semua data pesanan dan pesan tersimpan secara aman di database
- Laporan dapat didownload atau dicetak langsung dari browser
- Integrasi WhatsApp memerlukan nomor yang valid (format: 6281234567890)

---

**Dibuat**: February 1, 2026
**Aplikasi**: Dapoer Budess - Toko Roti Premium
