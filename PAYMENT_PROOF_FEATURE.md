# Fitur Bukti Pembayaran - Admin Panel

## 📸 Overview

Fitur ini memungkinkan admin untuk melihat, memverifikasi, dan mengkonfirmasi bukti pembayaran yang diupload oleh customer untuk pembayaran QRIS.

## 🎯 Fitur Utama

### 1. **Tampilan Bukti Pembayaran**
- ✅ Gambar bukti pembayaran ditampilkan di halaman detail order
- ✅ Thumbnail dengan border emas (max 500px height)
- ✅ Hover effect untuk visual feedback
- ✅ Tanggal upload ditampilkan
- ✅ Card khusus dengan border hijau untuk highlight

### 2. **Modal Full Screen**
- ✅ Klik gambar untuk melihat full size
- ✅ Modal dengan background hitam (95% opacity)
- ✅ Gambar centered dan responsive
- ✅ Tombol close (X) di pojok kanan atas
- ✅ Klik di luar gambar untuk close
- ✅ ESC key untuk close modal
- ✅ Smooth animation (fade in)

### 3. **Action Buttons**
- 🔍 **Lihat Full Size** - Buka gambar di tab baru
- 💾 **Download** - Download gambar ke komputer
- ✓ **Terima Pembayaran** - Konfirmasi pembayaran valid
- ✕ **Tolak Pembayaran** - Tolak dan minta upload ulang

### 4. **Status Pembayaran**
- ⏳ **Menunggu Konfirmasi** - Bukti sudah diupload, menunggu admin
- ✓ **Lunas** - Pembayaran sudah dikonfirmasi
- ⏳ **Belum Bayar** - Belum ada bukti pembayaran

## 🔄 Workflow

### **Customer Side:**
```
1. Customer checkout dengan QRIS
2. Customer scan QR code dan bayar
3. Customer upload bukti pembayaran
4. Status: "Menunggu Konfirmasi"
```

### **Admin Side:**
```
1. Admin buka detail order
2. Admin lihat bukti pembayaran
3. Admin verifikasi:
   ├─ Valid → Klik "Terima Pembayaran"
   │         ├─ Status: "Lunas"
   │         ├─ Order status: "Processing" (pickup) atau "Shipping Set" (delivery)
   │         └─ Notifikasi ke customer via chat
   │
   └─ Invalid → Klik "Tolak Pembayaran"
             ├─ Status: "Belum Bayar"
             ├─ Bukti dihapus
             └─ Notifikasi ke customer untuk upload ulang
```

## 📋 Tampilan di Admin Panel

### **Section Bukti Pembayaran**
```
┌─────────────────────────────────────────┐
│  📸 Bukti Pembayaran                    │
├─────────────────────────────────────────┤
│                                         │
│         [Gambar Bukti Transfer]         │
│         (Click to enlarge)              │
│                                         │
│  📅 Diupload: 26 Apr 2026 15:30        │
│                                         │
│  [🔍 Lihat Full Size] [💾 Download]    │
│                                         │
│  ⚠️ Menunggu Konfirmasi                 │
│  Silakan verifikasi bukti pembayaran    │
│                                         │
│  [✓ Terima Pembayaran] [✕ Tolak]       │
└─────────────────────────────────────────┘
```

### **Setelah Dikonfirmasi**
```
┌─────────────────────────────────────────┐
│  📸 Bukti Pembayaran                    │
├─────────────────────────────────────────┤
│                                         │
│         [Gambar Bukti Transfer]         │
│                                         │
│  📅 Diupload: 26 Apr 2026 15:30        │
│                                         │
│  [🔍 Lihat Full Size] [💾 Download]    │
│                                         │
│  ✓ Pembayaran Telah Dikonfirmasi       │
└─────────────────────────────────────────┘
```

## 💬 Notifikasi ke Customer

### **Pembayaran Diterima**
```
✅ PEMBAYARAN DITERIMA

Pembayaran Anda untuk pesanan ORD-20260426-ABCD 
telah kami terima dan diverifikasi.

Pesanan Anda akan segera kami proses. 🍞

Terima kasih! 🙏
```

### **Pembayaran Ditolak**
```
❌ PEMBAYARAN DITOLAK

Maaf, bukti pembayaran untuk pesanan ORD-20260426-ABCD 
tidak dapat diverifikasi.

Mohon upload ulang bukti pembayaran yang valid atau 
hubungi admin untuk informasi lebih lanjut.

Terima kasih.
```

## 🎨 Styling

### **Colors**
- Border Card: `#86efac` (hijau muda)
- Border Gambar: `#FFD700` (emas)
- Background Modal: `rgba(0, 0, 0, 0.95)`
- Button Terima: Hijau dengan opacity
- Button Tolak: Orange dengan opacity

### **Hover Effects**
- Gambar: Scale 1.02 + shadow enhancement
- Buttons: Background opacity change
- Close button: Color change + scale 1.1

### **Responsive**
- Desktop: Max 500px height
- Tablet: Responsive width
- Mobile: Full width, auto height

## 🔧 Technical Details

### **Routes**
```php
PATCH /admin/orders/{order}/confirm-payment
```

### **Controller Method**
```php
AdminController::confirmPayment($request, $orderId)
```

### **Request Parameters**
```php
action: 'approve' | 'reject'
```

### **Database Updates**

**Approve:**
```php
payment_status: 'paid'
status: 'processing' (pickup) | 'shipping_set' (delivery)
```

**Reject:**
```php
payment_status: 'unpaid'
payment_proof: null
```

### **JavaScript Functions**
```javascript
openImageModal(imageSrc)  // Buka modal
closeImageModal()         // Tutup modal
ESC key listener          // Close dengan keyboard
```

## 📱 Mobile Responsive

### **Modal di Mobile**
- Full screen modal
- Pinch to zoom (native browser)
- Swipe down to close (optional)
- Touch-optimized close button

### **Buttons di Mobile**
- Full width buttons
- Stacked vertically
- Touch-friendly size (min 44px)

## 🔒 Security

### **File Validation**
- ✅ Image only (JPG, PNG)
- ✅ Max 2MB file size
- ✅ Stored in `storage/app/public/payment_proofs`
- ✅ Symlink to `public/storage`

### **Access Control**
- ✅ Only admin can view
- ✅ Only admin can confirm/reject
- ✅ CSRF protection
- ✅ Authentication required

## 📊 Use Cases

### **Case 1: Valid Payment**
```
Customer upload bukti transfer yang jelas
→ Admin lihat dan verifikasi
→ Admin klik "Terima Pembayaran"
→ Status berubah jadi "Lunas"
→ Order diproses
```

### **Case 2: Invalid Payment**
```
Customer upload gambar yang blur/salah
→ Admin lihat dan tidak bisa verifikasi
→ Admin klik "Tolak Pembayaran"
→ Bukti dihapus
→ Customer diminta upload ulang
```

### **Case 3: Duplicate Payment**
```
Customer upload bukti yang sama dengan order lain
→ Admin deteksi duplikasi
→ Admin tolak dan hubungi customer
→ Customer upload bukti yang benar
```

## 🎯 Benefits

### **Untuk Admin:**
- ✅ Verifikasi pembayaran lebih mudah
- ✅ Lihat bukti dengan jelas (full screen)
- ✅ Download untuk arsip
- ✅ Konfirmasi dengan 1 klik
- ✅ Otomatis kirim notifikasi ke customer

### **Untuk Customer:**
- ✅ Tahu status pembayaran real-time
- ✅ Dapat notifikasi jika ditolak
- ✅ Bisa upload ulang jika perlu
- ✅ Transparansi proses verifikasi

### **Untuk Bisnis:**
- ✅ Reduce manual verification time
- ✅ Better payment tracking
- ✅ Audit trail (tanggal upload)
- ✅ Prevent fraud (visual verification)

## 🐛 Troubleshooting

### **Gambar tidak muncul**
```
1. Cek symlink: php artisan storage:link
2. Cek file exists: storage/app/public/payment_proofs/
3. Cek permissions: chmod 755
4. Cek path di database: orders.payment_proof
```

### **Modal tidak buka**
```
1. Cek JavaScript console
2. Cek function openImageModal() exists
3. Cek onclick attribute di img tag
4. Clear browser cache
```

### **Button tidak berfungsi**
```
1. Cek CSRF token
2. Cek route exists
3. Cek method di controller
4. Cek form method (PATCH)
```

---

**Status**: ✅ Implemented
**Version**: 1.0
**Last Updated**: 2026-04-26
