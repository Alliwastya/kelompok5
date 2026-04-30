# 📅 Sistem Pre-Order - Dokumentasi Lengkap

## 🎯 Tujuan
Memisahkan alur Pre-Order dari Checkout biasa agar lebih jelas, logis, dan user-friendly seperti toko roti asli.

---

## ✅ Fitur yang Sudah Diimplementasikan

### 1. **Database Schema**
Field baru di tabel `orders`:
- `order_type` ENUM('instant', 'preorder') - Tipe pesanan
- `pickup_date` DATE - Tanggal pengambilan (wajib untuk pre-order)
- `pickup_time` TIME - Jam pengambilan (opsional)

### 2. **Halaman Pre-Order Terpisah** (`/preorder`)
- **URL**: `/preorder`
- **View**: `resources/views/preorder.blade.php`
- **Fitur**:
  - Form khusus pre-order dengan UI berbeda (warna gold/kuning)
  - Badge "🟡 Pre-Order - Pesanan untuk Hari Berikutnya"
  - Field tanggal pengambilan (WAJIB, minimal besok)
  - Field jam pengambilan (opsional)
  - Ringkasan produk yang dipesan
  - Info customer (nama, telepon, email)
  - Metode pengiriman (pickup/delivery)
  - Metode pembayaran (COD/QRIS)
  - Catatan pesanan

### 3. **Alur Pre-Order**
```
User klik "📅 Pre-Order untuk Besok"
    ↓
Produk disimpan ke localStorage
    ↓
Redirect ke /preorder
    ↓
User isi form (tanggal, jam, data customer)
    ↓
Submit ke /preorder/submit
    ↓
Order dibuat dengan order_type = 'preorder'
    ↓
Notifikasi ke admin dengan label 🟡 PRE-ORDER
```

### 4. **Perbedaan Pre-Order vs Instant Order**

| Fitur | Instant Order | Pre-Order |
|-------|--------------|-----------|
| **Warna UI** | Oranye | Gold/Kuning |
| **Label** | 🟢 Instant Order | 🟡 Pre-Order |
| **Tanggal Ambil** | ❌ Tidak ada | ✅ Wajib |
| **Jam Ambil** | ❌ Tidak ada | ✅ Opsional |
| **Stok** | Langsung dikurangi | Tidak dikurangi (dikurangi saat admin konfirmasi) |
| **Nomor Order** | ORD-YYYYMMDDHHMMSS-XXXX | PRE-YYYYMMDDHHMMSS-XXXX |
| **Status Awal** | pending_admin | pending_preorder |
| **Notifikasi Admin** | 🔔 PESANAN BARU MASUK | 🟡 PRE-ORDER BARU MASUK |

### 5. **Admin Panel**
- **Badge Tipe Order**: 
  - 🟢 Instant Order (hijau)
  - 🟡 Pre-Order (gold)
- **Tampilan Tanggal & Jam Ambil**: Ditampilkan dengan highlight warna gold
- **Status Khusus**: `pending_preorder` untuk pre-order yang menunggu konfirmasi

### 6. **Security & Validation**
- ✅ IP blocking check
- ✅ Customer blacklist check
- ✅ Phone validation (min 10 digit)
- ✅ Name validation (min 3 karakter)
- ✅ Max 10 items per order
- ✅ Tanggal minimal besok
- ✅ Fraud detection

---

## 🚀 Cara Menggunakan

### **Untuk Customer:**

1. **Pilih Produk Pre-Order**
   - Klik tombol "📅 Pre-Order untuk Besok" pada produk yang tersedia
   
2. **Isi Form Pre-Order**
   - Pilih tanggal pengambilan (minimal besok)
   - Pilih jam pengambilan (opsional)
   - Isi data customer (nama, telepon, email)
   - Pilih metode pengiriman (ambil di tempat / diantar)
   - Pilih metode pembayaran (COD / QRIS)
   - Tambahkan catatan jika perlu

3. **Submit Pre-Order**
   - Klik "📅 Buat Pre-Order Sekarang"
   - Tunggu konfirmasi dari admin

### **Untuk Admin:**

1. **Melihat Pre-Order**
   - Pre-order akan muncul di dashboard dengan badge 🟡 Pre-Order
   - Nomor order dimulai dengan "PRE-"
   - Status awal: "🟡 Pre-Order Menunggu Konfirmasi"

2. **Detail Pre-Order**
   - Tipe Pesanan: 🟡 Pre-Order (badge gold)
   - Tanggal Ambil: Ditampilkan dengan highlight
   - Jam Ambil: Ditampilkan jika customer memilih

3. **Konfirmasi Pre-Order**
   - Cek ketersediaan bahan
   - Cek jadwal produksi
   - Konfirmasi ke customer via chat/WhatsApp
   - Update status order sesuai progress

---

## 📁 File yang Dimodifikasi/Dibuat

### **Baru:**
1. `resources/views/preorder.blade.php` - Halaman pre-order
2. `PREORDER_SYSTEM_DOCUMENTATION.md` - Dokumentasi ini

### **Dimodifikasi:**
1. `database/migrations/XXXX_add_preorder_fields_to_orders_table.php` - Migration
2. `app/Models/Order.php` - Tambah field order_type, pickup_date, pickup_time
3. `app/Http/Controllers/RotiController.php` - Tambah method:
   - `showPreorderPage()`
   - `submitPreorder()`
   - `sendPreorderMessageToAdmin()`
4. `routes/web.php` - Tambah route:
   - `GET /preorder`
   - `POST /preorder/submit`
5. `resources/views/roti.blade.php` - Update fungsi `addToCart()` untuk redirect ke pre-order
6. `resources/views/admin/orders/show.blade.php` - Tambah badge dan info pre-order

---

## 🔧 Technical Details

### **Routes:**
```php
Route::get('/preorder', [RotiController::class, 'showPreorderPage'])->name('preorder.show');
Route::post('/preorder/submit', [RotiController::class, 'submitPreorder'])->name('preorder.submit');
```

### **Controller Methods:**
```php
// Show pre-order page
public function showPreorderPage()

// Submit pre-order
public function submitPreorder(Request $request)

// Send pre-order notification to admin
private function sendPreorderMessageToAdmin(Order $order, MessageThread $messageThread, array $itemsDescription)
```

### **Validation Rules:**
```php
'order_type' => 'required|in:preorder',
'pickup_date' => 'required|date|after:today',
'pickup_time' => 'nullable|date_format:H:i',
'customer_name' => 'required|string|max:255|min:3',
'customer_phone' => 'required|string|max:20|min:10',
// ... dan lainnya
```

---

## 🎨 UI/UX Design

### **Warna:**
- **Pre-Order**: Gold/Orange (#FFA500, #FF8C00)
- **Instant Order**: Green (#10b981, #059669)

### **Badge:**
- Pre-Order: 🟡 dengan background gradient gold
- Instant Order: 🟢 dengan background gradient green

### **Form Layout:**
- Header dengan gradient gold
- Info box dengan border gold
- Button submit dengan gradient gold
- Responsive untuk mobile

---

## 📊 Status Order

### **Status Khusus Pre-Order:**
- `pending_preorder` - Pre-order menunggu konfirmasi admin
- Setelah dikonfirmasi, status berubah seperti order biasa:
  - `processing` - Sedang diproses
  - `scheduled` - Dijadwalkan
  - `ready_for_pickup` - Siap diambil
  - `picked_up` - Sudah diambil
  - `delivered` - Selesai

---

## ⚠️ Penting!

### **Yang TIDAK Berubah:**
- ❌ Sistem checkout biasa tetap sama
- ❌ Cart/keranjang tidak berubah
- ❌ Sistem pembayaran tidak berubah
- ❌ Fraud detection tetap berjalan

### **Yang Berubah:**
- ✅ Pre-order punya alur terpisah
- ✅ Pre-order tidak langsung kurangi stok
- ✅ Pre-order punya UI berbeda
- ✅ Pre-order punya notifikasi berbeda ke admin

---

## 🐛 Troubleshooting

### **Pre-order tidak muncul di admin:**
- Cek apakah order_type = 'preorder'
- Cek apakah status = 'pending_preorder'

### **Tanggal tidak bisa dipilih:**
- Pastikan min date = besok (tomorrow)
- Cek format date di browser

### **Redirect tidak jalan:**
- Cek localStorage browser
- Cek console untuk error JavaScript

---

## 🚀 Future Improvements

1. **Auto-reminder** untuk admin H-1 sebelum tanggal pickup
2. **Calendar view** untuk admin melihat semua pre-order
3. **Batch processing** untuk pre-order di tanggal yang sama
4. **SMS/WhatsApp notification** otomatis ke customer
5. **Pre-order discount** untuk encourage pre-order

---

## 📞 Support

Jika ada pertanyaan atau issue, silakan hubungi developer atau buat issue di repository.

---

**Dibuat pada:** 30 April 2026  
**Versi:** 1.0  
**Status:** ✅ Production Ready
