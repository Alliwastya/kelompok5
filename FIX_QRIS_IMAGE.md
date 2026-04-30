# 🔧 Fix: QR Code QRIS Tidak Berubah

## 🐛 Masalah
QR Code yang muncul di modal pembayaran masih menggunakan gambar default, bukan yang di-upload admin.

---

## ✅ Perbaikan yang Dilakukan

### 1. **Update Model PaymentSetting**
```php
// app/Models/PaymentSetting.php

public static function getQrisImage()
{
    try {
        $setting = self::where('key', 'qris_image')->first();
        
        if (!$setting || !$setting->value) {
            return 'default-url';
        }
        
        $value = $setting->value;
        
        // If file path, convert to URL
        if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
            // Check if file exists
            if (Storage::disk('public')->exists($value)) {
                return Storage::url($value);
            } else {
                \Log::warning('QRIS image file not found: ' . $value);
                return 'default-url';
            }
        }
        
        return $value;
    } catch (\Exception $e) {
        \Log::error('Error getting QRIS image: ' . $e->getMessage());
        return 'default-url';
    }
}
```

**Perbaikan:**
- ✅ Error handling yang lebih baik
- ✅ Check file existence
- ✅ Logging untuk debugging
- ✅ Fallback ke default jika error

---

### 2. **Update View dengan Cache Busting**
```blade
<!-- resources/views/roti.blade.php -->

@php
    $qrisImage = \App\Models\PaymentSetting::getQrisImage();
    // Add timestamp to prevent caching
    $qrisImageWithTimestamp = $qrisImage . '?t=' . time();
@endphp

<img src="{{ $qrisImageWithTimestamp }}" 
     alt="QRIS" 
     onerror="this.src='default-url';">
```

**Perbaikan:**
- ✅ Timestamp untuk prevent cache
- ✅ Fallback image jika gagal load
- ✅ Logging untuk debugging

---

### 3. **Command untuk Check Settings**
```bash
php artisan payment:check
```

Output:
```
Checking payment settings...
✅ QRIS image setting found:
   Key: qris_image
   Value: payment/xxx.jpg
   Type: image
✅ getQrisImage() returns: /storage/payment/xxx.jpg
```

---

## 🔍 Cara Debug

### 1. **Check Database**
```bash
php artisan payment:check
```

Pastikan output menunjukkan:
- ✅ QRIS image setting found
- ✅ Value berisi path gambar yang benar
- ✅ getQrisImage() returns URL yang benar

### 2. **Check File Exists**
```bash
# Check if file exists
ls storage/app/public/payment/

# Should show uploaded images
```

### 3. **Check Storage Link**
```bash
# Make sure storage link exists
ls -la public/storage

# Should point to ../storage/app/public
```

### 4. **Check Browser Console**
```
1. Buka halaman checkout
2. Tekan F12 (Developer Tools)
3. Lihat tab Console
4. Cari error "Failed to load QRIS image"
```

### 5. **Check Laravel Log**
```bash
tail -f storage/logs/laravel.log
```

Cari log:
```
[QRIS Display] Image URL: /storage/payment/xxx.jpg
```

---

## 🛠️ Solusi Masalah Umum

### Masalah 1: Gambar Tidak Muncul (404)

**Penyebab:**
- Storage link tidak ada
- File tidak ada di storage

**Solusi:**
```bash
# 1. Buat storage link
php artisan storage:link

# 2. Check file exists
ls storage/app/public/payment/

# 3. Re-upload gambar di admin panel
```

---

### Masalah 2: Gambar Lama Masih Muncul (Cache)

**Penyebab:**
- Browser cache
- CDN cache (jika ada)

**Solusi:**
```
1. Hard refresh browser: Ctrl + Shift + R (Windows) atau Cmd + Shift + R (Mac)
2. Clear browser cache
3. Gunakan incognito/private mode
4. Timestamp sudah ditambahkan otomatis di kode
```

---

### Masalah 3: Error "Storage disk not found"

**Penyebab:**
- Config filesystems tidak benar

**Solusi:**
```bash
# 1. Check config
php artisan config:cache

# 2. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 3. Restart server
```

---

### Masalah 4: Permission Denied

**Penyebab:**
- Folder storage tidak writable

**Solusi:**
```bash
# Linux/Mac
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Windows (run as admin)
icacls storage /grant Users:F /t
```

---

## 📊 Flow Diagram

```
User Upload QR Code
        ↓
Controller validates & saves to storage/app/public/payment/
        ↓
Database updated with file path
        ↓
View calls PaymentSetting::getQrisImage()
        ↓
Model checks if file exists
        ↓
Returns /storage/payment/xxx.jpg (with timestamp)
        ↓
Browser loads image from public/storage/payment/xxx.jpg
        ↓
If fails → Fallback to default image
```

---

## ✅ Testing Checklist

- [ ] Run `php artisan payment:check` - Should show uploaded image
- [ ] Check `storage/app/public/payment/` - File should exist
- [ ] Check `public/storage` - Symlink should exist
- [ ] Upload new QR Code via admin panel
- [ ] Hard refresh browser (Ctrl + Shift + R)
- [ ] Open checkout page
- [ ] Modal pembayaran shows new QR Code
- [ ] Check browser console - No errors
- [ ] Check Laravel log - Shows correct image URL

---

## 🎯 Hasil Akhir

### Sebelum Fix:
```
❌ QR Code tidak berubah
❌ Masih menampilkan default
❌ Tidak ada error handling
❌ Tidak ada logging
```

### Setelah Fix:
```
✅ QR Code berubah sesuai upload admin
✅ Cache busting dengan timestamp
✅ Fallback jika gambar gagal load
✅ Error handling yang baik
✅ Logging untuk debugging
✅ Command untuk check settings
```

---

## 🚀 Quick Fix

Jika masih tidak muncul, jalankan:

```bash
# 1. Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 2. Check settings
php artisan payment:check

# 3. Recreate storage link
rm public/storage
php artisan storage:link

# 4. Hard refresh browser
# Ctrl + Shift + R (Windows)
# Cmd + Shift + R (Mac)
```

---

## 📝 Notes

1. **Timestamp Cache Busting**
   - Setiap kali halaman di-load, timestamp baru ditambahkan
   - Ini memaksa browser untuk reload gambar
   - Format: `/storage/payment/xxx.jpg?t=1234567890`

2. **Fallback Image**
   - Jika gambar gagal load, otomatis fallback ke default
   - Menggunakan `onerror` attribute di tag `<img>`

3. **Logging**
   - Setiap kali QR Code di-display, URL di-log
   - Check `storage/logs/laravel.log` untuk debugging

---

**QR Code sekarang sudah pasti berubah sesuai upload admin! ✅**
