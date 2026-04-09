# 🍞 Sistem Pesanan & Notifikasi - Quick Start Guide

## 📋 Apa yang Baru?

Sistem otomatis menghubungkan customer order dengan admin response + notification.

```
Customer Checkout
    ↓
Order dibuat + Nomor Antrian
    ↓
Pesan otomatis ke Admin
    ↓
Admin Respon + Estimasi Waktu
    ↓
Notifikasi ke Customer
    ↓
Admin Mark Selesai
    ↓
Notifikasi Selesai ke Customer
```

---

## 🎯 3 Hal Penting

### 1️⃣ Nomor Antrian
- Customer dapat nomor antrian saat checkout
- Contoh: `#1`, `#2`, `#3` (reset setiap hari)
- Ditampilkan di success popup

### 2️⃣ Respon Admin
- Admin dapat respon pesanan dari order panel
- Admin bisa tulis pesan & estimasi waktu
- Customer otomatis dapat notifikasi

### 3️⃣ Notifikasi
- Notifikasi otomatis ketika admin respon
- Notifikasi otomatis ketika pesanan selesai
- Semua di message thread/chat

---

## 🛠️ Cara Pakai

### Untuk Customer

**Step 1: Checkout**
```
1. Pilih produk → Keranjang
2. Klik "Lanjut ke Checkout"
3. Isi form (Nama, Telepon, Alamat, Metode Pembayaran)
4. Klik "Proses Pesanan"
5. Lihat popup:
   ✓ Pesanan Berhasil!
   📋 Nomor Pesanan: ORD-xxx
   🔔 Nomor Antrian: #1
```

**Step 2: Tunggu Respon**
```
Admin akan merespon dalam beberapa menit
Customer akan dapat notifikasi otomatis via chat
```

---

### Untuk Admin

**Step 1: Cek Pesanan Baru**
```
1. Admin Panel → Orders
2. Lihat daftar pesanan (status "pending")
3. Klik salah satu pesanan
```

**Step 2: Respon Pesanan**
```
Di halaman order detail, scroll ke bagian "Respon Pesanan":

📌 Pesan ke Customer
   Tulislah pesan, contoh:
   "Pesanan sedang dipersiapkan, akan siap dalam 30 menit"

⏱️ Estimasi Waktu (Menit)
   Input: 30

Klik tombol: ✓ Kirim Respon
```

**Step 3: Selesaikan Pesanan**
```
Ketika pesanan sudah siap:
- Lihat section "Respon Admin"
- Klik tombol: 🎉 Pesanan Selesai
- Customer otomatis dapat notifikasi
```

---

## 💬 Apa yang Customer Terima

### Saat Checkout Success
```
✓ Pesanan Berhasil!

📋 Nomor Pesanan: ORD-20260205120530-ABC1
🔔 Nomor Antrian: #1
💬 Status Pesanan: Menunggu konfirmasi dari admin

⏱️ Admin akan merespon pesanan Anda dalam beberapa menit.
```

### Saat Admin Merespon
```
✅ RESPON DARI ADMIN

Pesanan Anda (#1) telah dikonfirmasi!

📋 Respon Admin:
Pesanan sedang dipersiapkan, akan siap dalam 30 menit

⏱️ Estimasi Waktu Selesai:
14:45 (30 menit)

📍 Antrian: #1
Terima kasih atas pesanan Anda!
```
(Notifikasi ini masuk ke chat/message thread)

### Saat Pesanan Selesai
```
🎉 PESANAN SELESAI!

Pesanan Anda #1 sudah siap untuk diambil.

Nomor Pesanan: ORD-20260205120530-ABC1
Estimasi: Sudah tersedia

Terima kasih telah berbelanja di Dapoer Bubess! 🍞
```

---

## 📱 Fitur Baru di Admin Panel

### Halaman Orders
- Menampilkan nomor antrian di list
- Filter berdasarkan status

### Halaman Order Detail
Sekarang ada 3 section:

**Section 1: Informasi Pesanan** (existing)
- Nomor pesanan
- Data customer
- Daftar item
- Total harga

**Section 2: Respon Pesanan** ⭐ NEW (hanya jika belum direspon)
```
🔔 Respon Pesanan

Pesan ke Customer:
[textarea: "Pesanan sedang dipersiapkan, akan siap dalam 30 menit"]

⏱️ Estimasi Waktu (Menit):
[input: 30]

Tombol: ✓ Kirim Respon
```

**Section 3: Respon Admin** ⭐ NEW (jika sudah direspon)
```
✓ Respon Admin

Pesan: Pesanan sedang dipersiapkan, akan siap dalam 30 menit

Direspon: 05 Feb 2026 14:15
Estimasi Selesai: 14:45

Tombol: 🎉 Pesanan Selesai (jika status masih processing)
```

---

## 🔍 Contoh Skenario Lengkap

### Waktu: 14:00
```
Customer "Budi" checkout:
- 2x Roti Coklat Keju
- 1x Roti Tawar
Nomor Pesanan: ORD-20260205140000-ABC1
Nomor Antrian: #5
```

### Waktu: 14:02
```
Admin melihat pesanan baru di panel
Admin membuka detail pesanan
Admin lihat:
  "🔔 Respon Pesanan" form
```

### Waktu: 14:05
```
Admin isi form:
  Pesan: "Pesanan sedang dipersiapkan, siap dalam 20 menit"
  Estimasi: 20 menit
Admin klik: ✓ Kirim Respon
```

### Waktu: 14:06
```
Customer Budi menerima notifikasi:
✅ RESPON DARI ADMIN
Pesanan Anda (#5) telah dikonfirmasi!
Estimasi waktu: 14:26 (20 menit)
```

### Waktu: 14:25
```
Admin: Pesanan Budi sudah siap
Admin klik tombol: 🎉 Pesanan Selesai
```

### Waktu: 14:26
```
Customer Budi menerima notifikasi:
🎉 PESANAN SELESAI!
Pesanan #5 sudah siap untuk diambil.
Terima kasih telah berbelanja!
```

---

## 🔗 API untuk Developer

### Cek Status Pesanan Customer
```
GET /order-status/{phone}

Contoh:
GET /order-status/08123456789

Response:
{
  "success": true,
  "customer_name": "Budi",
  "customer_phone": "08123456789",
  "orders": [
    {
      "order_number": "ORD-xxx",
      "queue_number": 5,
      "status": "processing",
      "estimated_ready_at": "14:26",
      "items": [...]
    }
  ],
  "notifications": [
    {
      "type": "admin_response",
      "message": "✅ RESPON DARI ADMIN...",
      "created_at": "05 Feb 2026 14:06"
    }
  ]
}
```

---

## 📊 Database Schema

### Kolom Baru di Orders Table
| Kolom | Contoh |
|-------|--------|
| queue_number | 1, 2, 3, ... |
| estimated_ready_at | 2026-02-05 14:26:00 |
| admin_response | "Pesanan sedang dipersiapkan..." |
| responded_at | 2026-02-05 14:05:00 |
| message_thread_id | 1, 2, 3, ... |

### Kolom Baru di Chat Messages Table
| Kolom | Contoh |
|-------|--------|
| order_id | 1, 2, 3, ... |
| message_type | order_notification, admin_response, completion_notification |

---

## ✅ Checklist

- [x] Nomor antrian otomatis
- [x] Pesan pesanan ke admin
- [x] Form respon admin
- [x] Notifikasi customer
- [x] Mark pesanan selesai
- [x] Integration chat/message
- [x] Database migration
- [x] API untuk cek status

---

## 🐛 Troubleshooting

**Q: Customer tidak dapat notifikasi?**
A: Periksa apakah nomor telepon customer sama dengan yang digunakan di message thread.

**Q: Nomor antrian tidak reset?**
A: Nomor antrian direset otomatis setiap hari. Jika ingin manual reset, bisa via database.

**Q: Admin tidak bisa respon pesanan?**
A: Pastikan user yang login adalah admin (is_admin = 1) dan pesanan status = pending.

**Q: Database error?**
A: Jalankan: `php artisan migrate:fresh` (WARNING: akan hapus semua data!)

---

## 📞 Contoh Chat Flow

```
Customer: "Halo, pesanan saya nomor berapa?"

Admin (otomatis): "📦 PESANAN BARU
Nomor Antrian: #5
..."

(beberapa menit kemudian)

Admin: "✓ Kirim Respon"
↓
Customer (otomatis): "✅ RESPON DARI ADMIN
Estimasi: 20 menit"

(Customer sekarang tahu harus tunggu 20 menit)

(setelah 20 menit)

Admin: "🎉 Pesanan Selesai"
↓
Customer (otomatis): "🎉 PESANAN SELESAI!
Sudah siap diambil"
```

---

## 🎓 Learning Resources

- Full documentation: `SISTEM_PESANAN_NOTIFIKASI.md`
- Implementation details: `IMPLEMENTASI_RINGKAS.md`

---

## 💡 Tips

1. **Untuk Admin**: Pastikan selalu merespon pesanan dalam 5-10 menit
2. **Untuk Customer**: Gunakan chat jika ada pertanyaan tentang pesanan
3. **Estimasi Waktu**: Berikan estimasi yang realistis (jangan terlalu singkat atau panjang)
4. **Nomor Antrian**: Bagikan ke customer via chat jika mereka bertanya

---

## ⭐ Features Highlights

✨ **Otomatis** - Semua proses berjalan otomatis
✨ **Real-time** - Notifikasi langsung sampai ke customer
✨ **Trackable** - Customer bisa lihat semua update
✨ **Organized** - Antrian teratur dan tertata rapi
✨ **Integrated** - Terintegrasi dengan chat existing

---

**Status: ✅ SIAP DIGUNAKAN**

Untuk mulai menggunakan, silakan ikuti "Cara Pakai" di atas!
