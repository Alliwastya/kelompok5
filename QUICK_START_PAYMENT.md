# ⚡ Quick Start - Pengaturan Pembayaran

## 🎯 Fitur Baru
Admin sekarang bisa **mengganti gambar QR Code pembayaran** melalui halaman admin!

---

## 🚀 Cara Menggunakan (3 Langkah)

### Langkah 1: Login ke Admin
```
1. Buka http://127.0.0.1:8000/admin/login
2. Login dengan akun admin
```

### Langkah 2: Buka Menu Pembayaran
```
1. Klik menu "💳 Pembayaran" di sidebar kiri
2. Halaman pengaturan pembayaran akan terbuka
```

### Langkah 3: Upload QR Code
```
1. Klik "Pilih Gambar QR Code"
2. Pilih file gambar QR Code Anda
3. Preview akan muncul
4. Klik "Upload Gambar QRIS"
5. Selesai! ✅
```

---

## 📸 Hasil

### Sebelum:
```
Pelanggan melihat QR Code default (mockup)
```

### Sesudah:
```
Pelanggan melihat QR Code yang Anda upload
```

---

## 💡 Tips

### Format File:
- ✅ JPG, JPEG, PNG
- ✅ Maksimal 2MB
- ✅ Ukuran disarankan: 500x500px atau lebih

### Cara Mendapatkan QR Code QRIS:
1. Daftar merchant QRIS di bank Anda
2. Download QR Code dari aplikasi merchant
3. Upload di halaman admin
4. QR Code langsung aktif!

---

## 🔧 Fitur Tambahan

### Hapus & Reset ke Default
```
1. Klik tombol "Hapus & Reset ke Default"
2. Konfirmasi
3. QR Code kembali ke default
```

### Preview Real-time
```
- Preview muncul sebelum upload
- Lihat gambar sebelum di-save
```

---

## 📁 File yang Dibuat

```
✅ Migration: create_payment_settings_table
✅ Model: PaymentSetting
✅ Controller: PaymentSettingController
✅ View: admin/payment-settings.blade.php
✅ Routes: admin/payment-settings/*
✅ Menu: Sidebar admin (💳 Pembayaran)
```

---

## ✅ Testing

- [ ] Login sebagai admin
- [ ] Klik menu "💳 Pembayaran"
- [ ] Upload gambar QR Code
- [ ] Cek di halaman pelanggan (checkout)
- [ ] QR Code baru muncul ✅

---

## 🐛 Troubleshooting

### Gambar tidak muncul?
```bash
php artisan storage:link
```

### Error saat upload?
- Cek format file (JPG, PNG)
- Cek ukuran file (<2MB)

---

## 📚 Dokumentasi Lengkap

Lihat file **`PAYMENT_SETTINGS_GUIDE.md`** untuk:
- Penjelasan teknis lengkap
- Cara kerja sistem
- Database schema
- Troubleshooting detail

---

## 🎉 Selesai!

Fitur pengaturan pembayaran sudah siap!

**Admin bisa:**
- ✅ Upload QR Code sendiri
- ✅ Hapus & reset ke default
- ✅ Preview sebelum upload

**Pelanggan melihat:**
- ✅ QR Code yang di-upload admin
- ✅ Update real-time

**Happy Managing! 🚀**
