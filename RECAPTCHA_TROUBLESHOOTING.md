# reCAPTCHA Troubleshooting Guide

## Error: "Terjadi kesalahan saat verifikasi. Silakan coba lagi."

### Kemungkinan Penyebab & Solusi

---

## 1. **reCAPTCHA Keys Tidak Dikonfigurasi**

### Cek File `.env`:
```bash
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
```

### Cara Mendapatkan Keys:
1. Buka https://www.google.com/recaptcha/admin
2. Login dengan Google Account
3. Klik "+" untuk register site baru
4. Pilih **reCAPTCHA v2** → "I'm not a robot" Checkbox
5. Tambahkan domain: `127.0.0.1` dan `localhost`
6. Copy **Site Key** dan **Secret Key**
7. Paste ke file `.env`

### Restart Server:
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 2. **Test Keys (Development)**

Untuk testing, gunakan Google's test keys:

```env
RECAPTCHA_SITE_KEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
RECAPTCHA_SECRET_KEY=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
```

**Note**: Test keys akan selalu return success, tidak perlu solve captcha.

---

## 3. **CSRF Token Issue**

### Cek Meta Tag di `<head>`:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### Cek di Browser Console:
```javascript
document.querySelector('meta[name="csrf-token"]').getAttribute('content')
```

Harus return token string, bukan null.

---

## 4. **Network/CORS Issue**

### Cek Browser Console:
- Buka Developer Tools (F12)
- Tab "Console"
- Lihat error merah

### Common Errors:
- `Failed to fetch` → Server tidak running
- `CORS error` → Domain tidak match
- `404 Not Found` → Route tidak ada
- `419 Page Expired` → CSRF token expired

### Solusi:
```bash
# Restart Laravel server
php artisan serve

# Clear cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

---

## 5. **reCAPTCHA Script Tidak Load**

### Cek di Browser:
```javascript
typeof grecaptcha
```

Harus return `"object"`, bukan `"undefined"`.

### Cek Script Tag:
```html
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
```

Harus ada di bagian bawah `<body>`.

---

## 6. **Domain Tidak Terdaftar**

### Error di Console:
```
ERROR for site owner: Invalid domain for site key
```

### Solusi:
1. Buka https://www.google.com/recaptcha/admin
2. Pilih site Anda
3. Klik "Settings"
4. Tambahkan domain:
   - `127.0.0.1`
   - `localhost`
   - `yourdomain.com`

---

## 7. **Backend Verification Gagal**

### Cek Laravel Log:
```bash
tail -f storage/logs/laravel.log
```

### Cek RotiController:
File: `app/Http/Controllers/RotiController.php`

Method: `verifyRecaptcha($token)`

### Test Manual:
```bash
curl -X POST https://www.google.com/recaptcha/api/siteverify \
  -d "secret=YOUR_SECRET_KEY" \
  -d "response=TOKEN_FROM_FRONTEND"
```

---

## 8. **Debugging Steps**

### Step 1: Cek Frontend
Buka Browser Console dan jalankan:
```javascript
// Cek reCAPTCHA loaded
console.log('grecaptcha:', typeof grecaptcha);

// Cek token
console.log('Token:', window.recaptchaToken);

// Cek CSRF
console.log('CSRF:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
```

### Step 2: Cek Backend
Tambahkan logging di `RotiController::checkout()`:
```php
\Log::info('[Checkout] Request data:', $request->all());
\Log::info('[Checkout] Verify only:', $request->input('verify_only'));
\Log::info('[Checkout] Token:', $request->input('recaptcha_token'));
```

### Step 3: Test Endpoint
```bash
# Test dengan curl
curl -X POST http://127.0.0.1:8000/checkout \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_TOKEN" \
  -d '{"verify_only":true,"recaptcha_token":"test"}'
```

---

## 9. **Quick Fix Checklist**

- [ ] File `.env` ada `RECAPTCHA_SITE_KEY` dan `RECAPTCHA_SECRET_KEY`
- [ ] Keys sudah benar (bukan placeholder)
- [ ] Domain sudah terdaftar di Google reCAPTCHA admin
- [ ] Server Laravel running (`php artisan serve`)
- [ ] Cache sudah di-clear
- [ ] Browser console tidak ada error
- [ ] `grecaptcha` object ada (typeof !== 'undefined')
- [ ] CSRF token ada di meta tag
- [ ] Route `/checkout` ada dan berfungsi

---

## 10. **Improved Error Messages**

Saya sudah update `verifyCaptchaAndCheckout()` dengan:
- ✅ Logging lebih detail
- ✅ Error message lebih spesifik
- ✅ Check content-type response
- ✅ Better error handling

### Sekarang Error Message Akan Menunjukkan:
- "Server tidak mengembalikan JSON response" → Backend error
- "Verifikasi gagal" → reCAPTCHA verification failed
- Specific error dari server

---

## 11. **Testing Flow**

### Manual Test:
1. Buka halaman roti
2. Tambah produk ke cart
3. Klik "Checkout"
4. Modal captcha muncul
5. **Buka Browser Console (F12)**
6. Solve reCAPTCHA
7. Lihat log:
   ```
   [reCAPTCHA] Token received: Yes
   [reCAPTCHA] Button enabled
   ```
8. Klik "✓ Verifikasi"
9. Lihat log:
   ```
   [Verifikasi] Starting verification with token: EXISTS
   [Verifikasi] Response status: 200
   [Verifikasi] Response data: {success: true, ...}
   ```
10. Modal tutup, redirect ke checkout form

### Jika Error:
- Lihat error message di console
- Lihat error di `storage/logs/laravel.log`
- Follow troubleshooting steps di atas

---

## 12. **Common Solutions**

### Solution 1: Use Test Keys
```env
RECAPTCHA_SITE_KEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
RECAPTCHA_SECRET_KEY=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
```

### Solution 2: Clear Everything
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

### Solution 3: Restart Server
```bash
# Stop server (Ctrl+C)
php artisan serve
```

### Solution 4: Check Firewall
- Pastikan port 8000 tidak diblock
- Pastikan Google reCAPTCHA API tidak diblock

---

## 13. **Production Checklist**

Sebelum deploy ke production:

- [ ] Ganti test keys dengan production keys
- [ ] Tambahkan production domain di reCAPTCHA admin
- [ ] Test di production environment
- [ ] Enable HTTPS (reCAPTCHA require HTTPS di production)
- [ ] Set proper CORS headers
- [ ] Monitor error logs

---

## Contact & Support

Jika masih error setelah follow semua steps:

1. **Check Browser Console** - Screenshot error
2. **Check Laravel Log** - Copy error message
3. **Check Network Tab** - Lihat request/response
4. **Provide Details**:
   - Laravel version
   - PHP version
   - Browser & version
   - Error message lengkap
   - Steps to reproduce

---

**Last Updated**: April 16, 2026
**Status**: Enhanced error handling implemented
