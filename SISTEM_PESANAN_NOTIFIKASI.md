# Sistem Pesanan dan Notifikasi - Dokumentasi

## 📋 Deskripsi Fitur

Sistem ini mengintegrasikan pemesanan produk dengan sistem messaging real-time antara customer dan admin. Fitur utama:

1. **Nomor Antrian Otomatis** - Setiap pesanan mendapat nomor antrian berdasarkan urutan tanggal
2. **Pesan Pesanan ke Admin** - Pesanan secara otomatis dikirim ke admin melalui message thread
3. **Respon Admin dengan Estimasi** - Admin dapat merespon pesanan dengan memberikan estimasi waktu selesai
4. **Notifikasi ke Customer** - Customer menerima notifikasi ketika admin merespon
5. **Status Tracking** - Customer dapat melihat status pesanan mereka

---

## 🔄 Alur Sistem

### 1. Customer Melakukan Checkout
- Customer mengisi form checkout (nama, telepon, alamat, item pesanan)
- Sistem membuat order dengan:
  - Order Number (unik): `ORD-20260205120530-ABC1`
  - Queue Number (antrian hari ini): `#1`, `#2`, `#3`, dst
  - Status awal: `pending`

### 2. Pesan Pesanan Dikirim ke Admin
```
Otomatis, sistem mengirim pesan ke admin dengan format:
📦 PESANAN BARU

Nomor Pesanan: ORD-20260205120530-ABC1
Nomor Antrian: #1

👤 Customer: Budi Santoso
📞 Telepon: 08123456789
📍 Alamat: Jl. Merpati No. 45

🍞 Pesanan:
2x Roti Coklat Keju (Rp 25.000)
1x Roti Tawar (Rp 35.000)

💰 Total: Rp 85.000
💳 Metode Pembayaran: COD

📝 Harap respon dan berikan estimasi waktu penyelesaian pesanan ini.
```

### 3. Admin Merespon Pesanan
Admin masuk ke Admin Panel → Orders → Detail Pesanan
- Admin mengisi form "Respon Pesanan"
- Admin menulis pesan respon (contoh: "Pesanan sedang dipersiapkan, akan siap dalam 30 menit")
- Admin memasukkan estimasi waktu dalam menit (misal: 30)
- Status pesanan berubah menjadi `processing`

### 4. Customer Menerima Notifikasi
```
Customer otomatis menerima notifikasi:

✅ RESPON DARI ADMIN

Pesanan Anda (#1) telah dikonfirmasi!

📋 Respon Admin:
Pesanan sedang dipersiapkan, akan siap dalam 30 menit

⏱️ Estimasi Waktu Selesai:
14:45 (30 menit)

📍 Antrian: #1
Terima kasih atas pesanan Anda!
```

### 5. Admin Menandai Pesanan Selesai
Admin klik tombol "🎉 Pesanan Selesai"
- Status berubah menjadi `delivered`
- Customer menerima notifikasi:
```
🎉 PESANAN SELESAI!

Pesanan Anda #1 sudah siap untuk diambil.

Nomor Pesanan: ORD-20260205120530-ABC1
Estimasi: Sudah tersedia

Terima kasih telah berbelanja di Dapoer Bubess! 🍞
```

---

## 📱 Customer View - Checkout Success

Setelah customer checkout, mereka melihat:
```
✓ Pesanan Berhasil!

📋 Nomor Pesanan: ORD-20260205120530-ABC1
🔔 Nomor Antrian: #1
💬 Status Pesanan: Menunggu konfirmasi dari admin

📱 Cek Status: Hubungi customer service melalui tombol chat
⏱️ Admin akan merespon pesanan Anda dalam beberapa menit.
```

---

## 🔐 Admin Panel - Order Management

### Halaman Order List
Menampilkan semua pesanan dengan informasi:
- Order Number
- Queue Number
- Customer Name
- Status (pending, processing, delivered, cancelled)

### Halaman Order Detail
Menampilkan:
- Informasi pesanan lengkap
- Daftar item pesanan
- **Form Respon Pesanan** (jika belum direspon)
  - Pesan ke Customer (textarea)
  - Estimasi Waktu (input number, 5-480 menit)
  - Tombol "✓ Kirim Respon"
- **Info Respon Admin** (jika sudah direspon)
  - Pesan respon yang dikirim
  - Waktu respon
  - Estimasi waktu selesai
  - Tombol "🎉 Pesanan Selesai" (jika status processing)

---

## 💾 Database Schema

### Orders Table - Kolom Baru
```sql
- queue_number (int): Nomor antrian
- estimated_ready_at (datetime): Estimasi waktu selesai
- admin_response (text): Respon admin ke customer
- responded_at (datetime): Waktu admin merespon
- message_thread_id (foreign key): Link ke message thread
```

### Chat Messages Table - Kolom Baru
```sql
- order_id (foreign key): Link ke order
- message_type (enum): 'message', 'order_notification', 'admin_response', 'completion_notification'
```

---

## 🔗 API Endpoints

### Checkout
```
POST /checkout
Body: {
  customer_name, customer_phone, customer_email, 
  customer_address, payment_method, items
}
Response: { success, order_number, queue_number }
```

### Get Order Status (Customer)
```
GET /order-status/{phone}
Response: {
  customer_name, customer_phone,
  orders: [...],
  notifications: [...]
}
```

### Admin Respond to Order
```
POST /admin/orders/{order}/respond
Body: {
  admin_response, estimated_minutes
}
```

### Admin Complete Order
```
POST /admin/orders/{order}/complete
```

---

## 🛠️ Setup & Migration

Jalankan migrations:
```bash
php artisan migrate
```

Migrations yang ditambahkan:
- `2026_02_05_000001_add_queue_and_response_to_orders.php`
- `2026_02_05_000002_add_order_and_type_to_chat_messages.php`

---

## 📊 Message Types

1. **order_notification** - Pesanan baru dari customer
2. **admin_response** - Respon admin ke pesanan
3. **completion_notification** - Notifikasi pesanan selesai
4. **message** - Chat biasa antara customer dan admin

---

## 🎯 Fitur & Status

✅ Nomor antrian otomatis per hari
✅ Pesan pesanan otomatis ke admin
✅ Respon admin dengan estimasi waktu
✅ Notifikasi ke customer
✅ Admin panel untuk manage pesanan
✅ API untuk cek status pesanan customer
✅ Integration dengan existing message system

---

## 💡 Tips Penggunaan

### Untuk Admin
1. Cek halaman Orders untuk melihat pesanan yang perlu direspon
2. Klik detail pesanan
3. Scroll ke bagian "Respon Pesanan"
4. Tulis pesan dan estimasi waktu
5. Klik "Kirim Respon"
6. Ketika pesanan selesai, klik "Pesanan Selesai"

### Untuk Customer
1. Setelah checkout, mereka dapat nomor antrian
2. Mereka dapat menggunakan tombol chat untuk tanya-tanya
3. Mereka akan otomatis menerima notifikasi ketika admin merespon
4. Mereka dapat cek status melalui chat history

---

## 🔍 Testing

Untuk test sistem:

1. **Checkout**
   ```
   POST /checkout
   Data: Customer A checkout pesanan
   Expected: Order #1 dibuat, nomor antrian #1
   ```

2. **Admin Respond**
   ```
   POST /admin/orders/1/respond
   Data: Admin respon dengan estimasi 30 menit
   Expected: Customer A menerima notifikasi
   ```

3. **Check Status**
   ```
   GET /order-status/08123456789
   Expected: Return semua order dan notifikasi customer A
   ```

---

## 📝 Model Updates

### Order Model
```php
protected $fillable = [
    'order_number',
    'queue_number',          // NEW
    'customer_name',
    'customer_phone',
    'customer_email',
    'customer_address',
    'total_amount',
    'shipping_cost',
    'status',
    'payment_method',
    'notes',
    'estimated_ready_at',     // NEW
    'admin_response',          // NEW
    'responded_at',            // NEW
    'message_thread_id'        // NEW
];

public function messageThread()
{
    return $this->belongsTo(MessageThread::class);
}
```

### ChatMessage Model
```php
protected $fillable = [
    'message_thread_id',
    'order_id',                // NEW
    'sender_type',
    'message_type',            // NEW
    'message',
    'is_read',
];

public function order()
{
    return $this->belongsTo(Order::class);
}
```

---

## 🚀 Keuntungan Sistem Ini

1. **Otomatis** - Pesanan otomatis masuk ke admin
2. **Real-time** - Customer langsung tahu status pesanan
3. **Nomor Antrian** - Customer tahu berapa banyak pesanan sebelumnya
4. **Estimasi Waktu** - Customer tahu kapan pesanan siap
5. **Tracking** - History lengkap pesanan dan komunikasi
6. **Scalable** - Mudah ditambah fitur lain
