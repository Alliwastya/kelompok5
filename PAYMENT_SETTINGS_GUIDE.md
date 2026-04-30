# 💳 Panduan Pengaturan Pembayaran

## 🎯 Fitur Baru: Upload QR Code Pembayaran

Sekarang admin dapat mengganti gambar QR Code pembayaran QRIS melalui halaman admin!

---

## 📦 Yang Telah Dibuat

### 1. **Database**
```
✅ Tabel: payment_settings
├── id
├── key (unique) - 'qris_image', dll
├── value - path gambar atau URL
├── type - 'image' atau 'text'
├── description
└── timestamps
```

### 2. **Model**
```
✅ app/Models/PaymentSetting.php
├── getValue($key, $default)
├── setValue($key, $value, $type, $description)
└── getQrisImage()
```

### 3. **Controller**
```
✅ app/Http/Controllers/Admin/PaymentSettingController.php
├── index() - Tampilkan halaman settings
├── updateQrisImage() - Upload gambar baru
└── deleteQrisImage() - Hapus & reset ke default
```

### 4. **View**
```
✅ resources/views/admin/payment-settings.blade.php
├── Preview gambar saat ini
├── Form upload gambar baru
├── Tombol hapus gambar
└── Informasi & tips
```

### 5. **Routes**
```
✅ routes/web.php
├── GET  /admin/payment-settings
├── POST /admin/payment-settings/qris
└── DELETE /admin/payment-settings/qris
```

---

## 🚀 Cara Menggunakan

### Untuk Admin:

#### 1. **Akses Halaman Pengaturan**
```
1. Login ke admin panel
2. Klik menu "💳 Pembayaran" di sidebar
3. Halaman pengaturan pembayaran akan terbuka
```

#### 2. **Upload QR Code Baru**
```
1. Klik tombol "Pilih Gambar QR Code"
2. Pilih file gambar QR Code Anda (JPG, PNG)
3. Preview akan muncul otomatis
4. Klik "Upload Gambar QRIS"
5. Gambar akan langsung aktif untuk pelanggan
```

#### 3. **Hapus & Reset ke Default**
```
1. Klik tombol "Hapus & Reset ke Default"
2. Konfirmasi penghapusan
3. Gambar akan kembali ke default
```

---

## 📸 Screenshot Fitur

### Halaman Admin - Payment Settings
```
┌─────────────────────────────────────────────────┐
│  ⚙️ Pengaturan Pembayaran                       │
├─────────────────────────────────────────────────┤
│                                                  │
│  📱 QR Code Pembayaran QRIS                     │
│  ┌──────────────┬──────────────────────────┐   │
│  │ Gambar Saat  │  Upload Gambar Baru      │   │
│  │ Ini:         │                          │   │
│  │              │  [Pilih File]            │   │
│  │  [QR CODE]   │                          │   │
│  │              │  Preview:                │   │
│  │              │  [Preview Image]         │   │
│  │ [Hapus]      │                          │   │
│  │              │  [Upload Gambar QRIS]    │   │
│  └──────────────┴──────────────────────────┘   │
│                                                  │
└─────────────────────────────────────────────────┘
```

### Tampilan Pelanggan
```
Sebelum: Gambar default dari API
Sesudah: Gambar QR Code yang di-upload admin
```

---

## 🔧 Cara Kerja Teknis

### 1. **Upload Gambar**
```php
// Controller
public function updateQrisImage(Request $request)
{
    // Validasi file
    $request->validate([
        'qris_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
    ]);
    
    // Hapus gambar lama
    $oldSetting = PaymentSetting::where('key', 'qris_image')->first();
    if ($oldSetting && $oldSetting->value) {
        Storage::disk('public')->delete($oldSetting->value);
    }
    
    // Upload gambar baru
    $path = $request->file('qris_image')->store('payment', 'public');
    
    // Simpan ke database
    PaymentSetting::setValue('qris_image', $path, 'image', 'QR Code QRIS');
}
```

### 2. **Tampilkan di Frontend**
```blade
<!-- resources/views/roti.blade.php -->
@php
    $qrisImage = \App\Models\PaymentSetting::getQrisImage();
@endphp
<img src="{{ $qrisImage }}" alt="QRIS">
```

### 3. **Get Image URL**
```php
// Model
public static function getQrisImage()
{
    $value = self::getValue('qris_image');
    
    // Jika file path, convert ke URL
    if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
        return Storage::url($value);
    }
    
    // Jika URL atau default
    return $value ?: 'default-url';
}
```

---

## 📁 Struktur File

```
project/
├── app/
│   ├── Http/Controllers/Admin/
│   │   └── PaymentSettingController.php ✅
│   └── Models/
│       └── PaymentSetting.php ✅
│
├── database/migrations/
│   └── 2026_04_16_034336_create_payment_settings_table.php ✅
│
├── resources/views/
│   ├── admin/
│   │   └── payment-settings.blade.php ✅
│   ├── layouts/
│   │   └── admin.blade.php (updated) ✅
│   └── roti.blade.php (updated) ✅
│
├── routes/
│   └── web.php (updated) ✅
│
└── public/storage/payment/ (folder untuk upload)
```

---

## 🎨 Fitur UI

### 1. **Preview Real-time**
- Preview gambar muncul sebelum upload
- Ukuran preview: 250x250px

### 2. **Validasi**
- Format: JPG, JPEG, PNG
- Ukuran maksimal: 2MB
- Error message yang jelas

### 3. **Status Indicator**
```
✅ Custom Image - Menggunakan gambar upload
⚠️ Default - Menggunakan gambar default
```

### 4. **Quick Actions**
- Kembali ke Dashboard
- Lihat Pesanan
- Hapus & Reset

---

## 🔒 Keamanan

### 1. **Middleware**
```php
Route::middleware(['auth', 'is_admin'])
```
Hanya admin yang bisa akses

### 2. **Validasi File**
```php
'qris_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
```
Hanya gambar yang diizinkan

### 3. **Storage**
```php
Storage::disk('public')->store('payment')
```
File disimpan di storage/app/public/payment

---

## 📊 Database Schema

```sql
CREATE TABLE payment_settings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    key VARCHAR(255) UNIQUE NOT NULL,
    value TEXT NULL,
    type VARCHAR(255) DEFAULT 'text',
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Default data
INSERT INTO payment_settings VALUES (
    1,
    'qris_image',
    'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=DapoerBudessQRISPayment_Mockup',
    'image',
    'QR Code untuk pembayaran QRIS',
    NOW(),
    NOW()
);
```

---

## 🐛 Troubleshooting

### Masalah: Gambar tidak muncul setelah upload

**Solusi:**
```bash
# Buat symbolic link storage
php artisan storage:link
```

### Masalah: Error "The qris image must be an image"

**Solusi:**
- Pastikan file yang di-upload adalah gambar (JPG, PNG)
- Cek ukuran file (maksimal 2MB)

### Masalah: Gambar lama tidak terhapus

**Solusi:**
- Cek permission folder storage/app/public/payment
- Pastikan folder bisa di-write

---

## ✅ Testing Checklist

- [ ] Login sebagai admin
- [ ] Akses menu "💳 Pembayaran"
- [ ] Halaman payment settings terbuka
- [ ] Upload gambar QR Code baru
- [ ] Preview muncul sebelum upload
- [ ] Gambar berhasil di-upload
- [ ] Gambar muncul di halaman pelanggan
- [ ] Hapus gambar & reset ke default
- [ ] Gambar kembali ke default
- [ ] Test dengan berbagai format (JPG, PNG)
- [ ] Test dengan file besar (>2MB) - harus error

---

## 🎯 Fitur Tambahan (Future)

Fitur yang bisa ditambahkan di masa depan:

1. **Multiple Payment Methods**
   - Upload QR Code untuk berbagai e-wallet
   - Bank transfer details
   - COD settings

2. **Payment Instructions**
   - Custom instruksi pembayaran
   - Multi-language support

3. **Payment Analytics**
   - Track payment method usage
   - Success rate per method

4. **Auto-verification**
   - Integrasi dengan payment gateway
   - Auto-confirm payment

---

## 📝 Changelog

### Version 1.0.0 (16 April 2026)
- ✅ Fitur upload QR Code QRIS
- ✅ Preview real-time
- ✅ Hapus & reset ke default
- ✅ Validasi file upload
- ✅ Menu di admin sidebar
- ✅ Dokumentasi lengkap

---

## 🎉 Selesai!

Fitur pengaturan pembayaran sudah siap digunakan!

**Admin sekarang bisa:**
- ✅ Upload QR Code QRIS sendiri
- ✅ Preview sebelum upload
- ✅ Hapus & reset ke default
- ✅ Gambar langsung aktif untuk pelanggan

**Pelanggan akan melihat:**
- ✅ QR Code yang di-upload admin
- ✅ Gambar yang jelas dan mudah di-scan
- ✅ Update real-time tanpa perlu refresh

---

**Happy Managing! 🚀**
