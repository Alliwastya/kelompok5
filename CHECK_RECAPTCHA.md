# Quick Check - reCAPTCHA Configuration

## 1. Cek File .env

Buka file `.env` dan pastikan ada:

```env
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
```

### Jika Belum Ada, Gunakan Test Keys:

```env
RECAPTCHA_SITE_KEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
RECAPTCHA_SECRET_KEY=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
```

## 2. Clear Cache

Jalankan command ini:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## 3. Restart Server

```bash
# Stop server (Ctrl+C di terminal)
# Lalu start lagi:
php artisan serve
```

## 4. Test di Browser

1. Buka http://127.0.0.1:8000
2. Buka Browser Console (F12)
3. Tambah produk ke cart
4. Klik "Checkout"
5. Lihat console log

### Yang Harus Muncul:
```
[Modal] Opening captcha modal
[reCAPTCHA] Token received: Yes
[reCAPTCHA] Button enabled
[Verifikasi] Starting verification with token: EXISTS
[Verifikasi] Response status: 200
[Verifikasi] Response data: {success: true, captcha_verified: true}
✅ Verifikasi berhasil!
```

### Jika Error:
- Screenshot error di console
- Check `storage/logs/laravel.log`

## 5. Jika Masih Error

Kemungkinan besar:
- ❌ Keys belum di-set di `.env`
- ❌ Cache belum di-clear
- ❌ Server belum di-restart

**Solusi**: Ulangi step 1-3 di atas.

---

## Quick Commands

```bash
# All in one
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan serve
```

---

**Note**: Dengan test keys, reCAPTCHA akan selalu berhasil tanpa perlu solve puzzle.
