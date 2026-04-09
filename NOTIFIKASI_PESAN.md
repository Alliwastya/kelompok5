# 🔔 Notifikasi Pesan Admin ke User

## ✅ Fitur yang Diimplementasikan

Sistem notifikasi pesan otomatis menampilkan badge di tombol "💬 Pesan" ketika admin mengirim pesan ke user.

---

## 📋 Cara Kerja

### 1️⃣ User Membuka Halaman Website
- Sistem otomatis cek apakah ada pesan belum dibaca dari admin
- Cek dilakukan setiap 10 detik

### 2️⃣ Admin Mengirim Pesan
- Pesan masuk ke chat thread user
- Status pesan: `is_read = false`

### 3️⃣ Notifikasi Muncul
- Badge merah dengan angka jumlah pesan muncul di tombol "💬 Pesan"
- Contoh: **💬 Pesan** dengan badge **2** (ada 2 pesan belum dibaca)
- Browser notification juga muncul (jika permission diberikan)

### 4️⃣ User Buka Chat
- User klik tombol "💬 Pesan"
- Pesan dimuat
- Semua pesan ditandai sebagai sudah dibaca (`is_read = true`)
- Badge hilang

---

## 🔧 Technical Implementation

### Backend Endpoints

#### 1. GET /messages/unread/{phone}
Endpoint untuk cek berapa banyak pesan belum dibaca dari admin

**Response:**
```json
{
  "unread_count": 2,
  "unread_messages": [
    {
      "id": 1,
      "message": "✅ RESPON DARI ADMIN...",
      "type": "admin_response",
      "created_at": "14:05",
      "is_read": false
    }
  ]
}
```

#### 2. POST /messages/mark-read
Mark semua pesan dari admin sebagai sudah dibaca

**Body:**
```json
{
  "phone": "08123456789"
}
```

### Frontend Implementation

#### 1. Notification Badge HTML
```html
<button class="message-btn" onclick="openMessageModal()" style="position: relative;">
    💬 Pesan
    <span class="notification-badge" id="notificationBadge" 
          style="display: none; position: absolute; top: -8px; right: -8px; 
                 background: #ff4444; color: white; border-radius: 50%; 
                 width: 24px; height: 24px;">0</span>
</button>
```

#### 2. Auto Check Every 10 Seconds
```javascript
// Check immediately saat page load
checkUnreadNotifications();

// Check setiap 10 detik
notificationCheckInterval = setInterval(checkUnreadNotifications, 10000);
```

#### 3. Mark as Read Saat Dibuka
```javascript
async function loadChatThread() {
    // ... load chat ...
    
    // Mark messages as read
    await fetch('/messages/mark-read', {
        method: 'POST',
        body: JSON.stringify({ phone: phone })
    });
    
    // Hide badge
    const badge = document.getElementById('notificationBadge');
    badge.style.display = 'none';
}
```

---

## 📱 User Experience

### Saat User Membuka Website
```
Header: 💬 Pesan           (Tidak ada notif)
```

### Admin Mengirim Pesan
```
Setelah 10 detik:
Header: 💬 Pesan [2]       (Ada badge merah dengan angka 2)
        └─ Browser notification: "Anda memiliki 2 pesan baru dari admin"
```

### User Klik Tombol Pesan
```
Header: 💬 Pesan           (Badge hilang)
        ↓
Chat modal terbuka
Pesan ditampilkan
Pesan ditandai sebagai sudah dibaca
```

---

## 🎯 Features

- ✅ Real-time notification check
- ✅ Notification badge di tombol chat
- ✅ Browser notification (optional)
- ✅ Auto mark as read saat dibuka
- ✅ Check every 10 seconds
- ✅ Efficient - hanya cek unread messages
- ✅ Mobile responsive

---

## 🔌 Notification Types

Notifikasi muncul untuk pesan dengan type:
- ✅ `order_notification` - Pesanan baru (dari customer)
- ✅ `admin_response` - Respon admin (ke customer)
- ✅ `completion_notification` - Pesanan selesai (dari admin)
- ✅ `message` - Chat biasa (dari admin)

---

## ⚙️ Configuration

### Interval Notifikasi
Ubah interval check di halaman roti.blade.php:
```javascript
// Default: 10 detik
notificationCheckInterval = setInterval(checkUnreadNotifications, 10000);

// Ubah ke 5 detik:
notificationCheckInterval = setInterval(checkUnreadNotifications, 5000);
```

### Browser Notification
Otomatis request permission, tapi bisa disable:
```javascript
// Di checkUnreadNotifications(), comment baris ini:
// new Notification('Pesan Baru', { ... });
```

---

## 🧪 Testing

### Test 1: Buat Pesanan & Check Notifikasi
1. Checkout pesanan dengan nomor: 08123456789
2. Buka website (halaman utama)
3. Lihat badge di tombol "💬 Pesan"
4. Klik pesan untuk buka chat
5. Badge hilang

### Test 2: Admin Respon Pesanan
1. Login admin
2. Respon pesanan dari customer 08123456789
3. Buka halaman utama (sebagai customer)
4. Tunggu 10 detik
5. Lihat badge muncul
6. Klik pesan
7. Lihat notifikasi respon admin

### Test 3: Multiple Unread Messages
1. Admin kirim 3 pesan ke customer 08123456789
2. Badge akan menampilkan "3"
3. Klik pesan 1x → mark all read → badge hilang

---

## 📊 Database

Tidak perlu migration baru, menggunakan kolom existing:
- `chat_messages.is_read` - Status pesan sudah dibaca
- `chat_messages.sender_type` - User atau Admin
- `message_threads.phone` - Nomor telepon customer

---

## 🚀 Deployment

Sudah siap digunakan, tidak perlu konfigurasi tambahan.

Fitur akan otomatis berjalan saat user membuka halaman website.

---

## 📞 Troubleshooting

**Notifikasi tidak muncul?**
- Periksa console browser (F12)
- Pastikan phone number benar di message thread
- Clear browser cache

**Badge tidak hilang saat buka chat?**
- Check API `/messages/mark-read` response
- Periksa phone number di localStorage

**Check terlalu sering?**
- Ubah interval di roti.blade.php baris dengan `10000`
- Ubah ke `30000` untuk 30 detik (lebih hemat bandwidth)

---

## ✅ Status

✅ Fully Implemented
✅ Ready to Use
✅ No additional migration needed
✅ Works on mobile & desktop
