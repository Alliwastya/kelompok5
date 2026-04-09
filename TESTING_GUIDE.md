# 🧪 Testing Guide - Sistem Pesanan & Notifikasi

## 🎯 Overview

Testing guide untuk memverifikasi semua fitur sistem pesanan dan notifikasi berjalan dengan baik.

---

## ✅ Pre-Test Checklist

- [ ] Database sudah fresh migration (run: `php artisan migrate:fresh`)
- [ ] Laravel server running (run: `php artisan serve`)
- [ ] Admin user sudah di-create dengan is_admin = 1
- [ ] Postman atau curl ready untuk API testing

---

## 📋 Test Cases

### TEST 1: Customer Checkout dengan Nomor Antrian

**Objective**: Verifikasi queue number dibuat otomatis

**Steps**:
1. Buka halaman utama: `http://localhost:8000/`
2. Pilih produk (misal: Roti Coklat Keju 2x, Roti Tawar 1x)
3. Klik "Lanjut ke Checkout"
4. Isi form:
   - Nama: Budi Santoso
   - Telepon: 08123456789
   - Alamat: Jl. Merpati 45
   - Metode Pembayaran: COD
5. Klik "Proses Pesanan"

**Expected Result**:
```
✓ Modal success muncul menampilkan:
  📋 Nomor Pesanan: ORD-xxx
  🔔 Nomor Antrian: #1
  💬 Status: Menunggu konfirmasi dari admin
```

**Verify di Database**:
```sql
SELECT * FROM orders WHERE customer_phone = '08123456789';
-- Result harus memiliki:
-- queue_number = 1
-- status = pending
-- message_thread_id = (ada value)
```

---

### TEST 2: Pesan Otomatis ke Admin

**Objective**: Verifikasi order message otomatis masuk ke admin

**Steps**:
1. Lakukan TEST 1
2. Login sebagai admin
3. Admin Panel → Messages

**Expected Result**:
```
Chat thread dengan "Budi Santoso" muncul dengan pesan:

📦 PESANAN BARU

Nomor Pesanan: ORD-xxx
Nomor Antrian: #1

👤 Customer: Budi Santoso
📞 Telepon: 08123456789
📍 Alamat: Jl. Merpati 45

🍞 Pesanan:
2x Roti Coklat Keju (Rp 25.000)
1x Roti Tawar (Rp 35.000)

💰 Total: Rp 85.000
💳 Metode Pembayaran: COD

📝 Harap respon dan berikan estimasi waktu penyelesaian pesanan ini.
```

**Verify di Database**:
```sql
SELECT * FROM chat_messages 
WHERE message_type = 'order_notification' 
AND order_id = 1;
-- Result: message_type = order_notification, sender_type = user
```

---

### TEST 3: Admin Respond Order

**Objective**: Verifikasi admin dapat merespon pesanan dengan estimasi

**Steps**:
1. Login sebagai admin
2. Admin Panel → Orders
3. Lihat pesanan dari TEST 1
4. Klik detail pesanan
5. Scroll ke "Respon Pesanan"
6. Isi form:
   - Pesan: "Pesanan sedang dipersiapkan, akan siap dalam 30 menit"
   - Estimasi: 30 menit
7. Klik "✓ Kirim Respon"

**Expected Result**:
```
✅ Flash message: "Respon pesanan berhasil dikirim"
Halaman diupdate:
- Section "Respon Pesanan" hilang
- Section "Respon Admin" muncul dengan pesan & estimasi
- Button "🎉 Pesanan Selesai" muncul
- Order status berubah ke "processing"
```

**Verify di Database**:
```sql
SELECT * FROM orders WHERE id = 1;
-- Result:
-- status = processing
-- responded_at = (current timestamp)
-- estimated_ready_at = (now + 30 minutes)
-- admin_response = (pesan yang diisi)
```

---

### TEST 4: Notifikasi ke Customer

**Objective**: Verifikasi customer menerima notifikasi

**Steps**:
1. Lakukan TEST 3
2. Login sebagai Budi (atau buka message thread Budi)
3. Admin Panel → Messages → Chat dengan Budi

**Expected Result**:
```
Di chat thread Budi, muncul pesan baru:

✅ RESPON DARI ADMIN

Pesanan Anda (#1) telah dikonfirmasi!

📋 Respon Admin:
Pesanan sedang dipersiapkan, akan siap dalam 30 menit

⏱️ Estimasi Waktu Selesai:
14:45 (30 menit)

📍 Antrian: #1
Terima kasih atas pesanan Anda!
```

**Verify di Database**:
```sql
SELECT * FROM chat_messages 
WHERE message_type = 'admin_response' 
AND order_id = 1;
-- Result: sender_type = admin, is_read = false
```

---

### TEST 5: Admin Mark Pesanan Selesai

**Objective**: Verifikasi pesanan dapat ditandai selesai

**Steps**:
1. Masih di halaman order detail dari TEST 3
2. Lihat section "Respon Admin"
3. Klik tombol "🎉 Pesanan Selesai"
4. Confirm di popup

**Expected Result**:
```
✅ Flash message: "Notifikasi penyelesaian telah dikirim"
Order status berubah ke "delivered"
```

**Verify di Database**:
```sql
SELECT * FROM orders WHERE id = 1;
-- Result: status = delivered
```

---

### TEST 6: Notifikasi Pesanan Selesai

**Objective**: Verifikasi customer dapat notifikasi selesai

**Steps**:
1. Lakukan TEST 5
2. Lihat chat thread customer

**Expected Result**:
```
Pesan baru muncul:

🎉 PESANAN SELESAI!

Pesanan Anda #1 sudah siap untuk diambil.

Nomor Pesanan: ORD-xxx
Estimasi: Sudah tersedia

Terima kasih telah berbelanja di Dapoer Bubess! 🍞
```

**Verify di Database**:
```sql
SELECT * FROM chat_messages 
WHERE message_type = 'completion_notification' 
AND order_id = 1;
-- Result: sender_type = admin
```

---

### TEST 7: API - Get Order Status

**Objective**: Verifikasi API untuk customer cek status

**Using Curl**:
```bash
curl -X GET "http://localhost:8000/order-status/08123456789"
```

**Expected Response**:
```json
{
  "success": true,
  "customer_name": "Budi Santoso",
  "customer_phone": "08123456789",
  "orders": [
    {
      "id": 1,
      "order_number": "ORD-xxx",
      "queue_number": 1,
      "status": "delivered",
      "created_at": "05 Feb 2026 14:00",
      "responded_at": "05 Feb 2026 14:05",
      "estimated_ready_at": "14:35",
      "admin_response": "Pesanan sedang dipersiapkan...",
      "total_amount": "Rp 85.000",
      "items": [
        {
          "product_name": "Roti Coklat Keju",
          "quantity": 2,
          "price": "Rp 25.000"
        }
      ]
    }
  ],
  "notifications": [
    {
      "type": "order_notification",
      "sender": "user",
      "message": "📦 PESANAN BARU...",
      "created_at": "05 Feb 2026 14:00",
      "is_read": true
    },
    {
      "type": "admin_response",
      "sender": "admin",
      "message": "✅ RESPON DARI ADMIN...",
      "created_at": "05 Feb 2026 14:05",
      "is_read": false
    },
    {
      "type": "completion_notification",
      "sender": "admin",
      "message": "🎉 PESANAN SELESAI...",
      "created_at": "05 Feb 2026 14:35",
      "is_read": false
    }
  ]
}
```

**Test Invalid Phone**:
```bash
curl -X GET "http://localhost:8000/order-status/08999999999"
```

**Expected Response**:
```json
{
  "success": false,
  "message": "Tidak ditemukan pesanan dengan nomor telepon ini"
}
```

---

### TEST 8: Multiple Customers Queue Number

**Objective**: Verifikasi nomor antrian increment untuk setiap customer

**Steps**:
1. Buat checkout baru dengan customer 2 (08987654321)
   - Verifikasi queue_number = 2
2. Buat checkout baru dengan customer 3 (08765432109)
   - Verifikasi queue_number = 3
3. Esok hari, checkout customer baru
   - Verifikasi queue_number reset ke 1

**Verify di Database**:
```sql
-- Same day
SELECT queue_number, customer_phone, DATE(created_at) as date 
FROM orders 
WHERE DATE(created_at) = CURDATE()
ORDER BY queue_number;

-- Result: queue_number 1, 2, 3, ... increment
```

---

### TEST 9: Nomor Antrian Reset Setiap Hari

**Objective**: Verifikasi queue_number reset harian

**Steps**:
1. Buat beberapa pesanan hari ini (queue: #1, #2, #3)
2. Ubah tanggal sistem ke besok (testing saja)
3. Buat pesanan baru
4. Verifikasi queue_number = 1 (reset)

**Verify di Database**:
```sql
SELECT DATE(created_at) as date, COUNT(*) as order_count
FROM orders
GROUP BY DATE(created_at)
ORDER BY DATE(created_at);

-- Result: 
-- | 2026-02-05 | 3 | (queue 1,2,3)
-- | 2026-02-06 | 1 | (queue 1 - RESET)
```

---

### TEST 10: Error Handling

**Test 10a: Invalid Response Data**
```bash
curl -X POST "http://localhost:8000/admin/orders/999/respond" \
  -H "Content-Type: application/json" \
  -d '{"admin_response": "", "estimated_minutes": 0}'
```

**Expected**: Error 422 (Validation error)

**Test 10b: Missing Required Field**
```bash
curl -X POST "http://localhost:8000/admin/orders/1/respond" \
  -H "Content-Type: application/json" \
  -d '{"admin_response": "OK"}'
```

**Expected**: Error 422 (estimated_minutes required)

**Test 10c: Out of Range Estimasi**
```bash
curl -X POST "http://localhost:8000/admin/orders/1/respond" \
  -H "Content-Type: application/json" \
  -d '{"admin_response": "OK", "estimated_minutes": 2000}'
```

**Expected**: Error 422 (max 480)

---

## 📊 Test Checklist

- [ ] TEST 1: Queue number created
- [ ] TEST 2: Order message sent to admin
- [ ] TEST 3: Admin can respond with estimation
- [ ] TEST 4: Customer receives notification
- [ ] TEST 5: Admin can mark complete
- [ ] TEST 6: Customer receives completion notification
- [ ] TEST 7: API returns correct data
- [ ] TEST 8: Multiple queue numbers increment
- [ ] TEST 9: Queue number resets daily
- [ ] TEST 10: Error handling works

---

## 🐛 Debug Tips

**View Database Data**:
```sql
-- Orders
SELECT id, order_number, queue_number, status, created_at FROM orders ORDER BY id DESC;

-- Chat Messages
SELECT id, message_thread_id, order_id, message_type, sender_type, created_at FROM chat_messages ORDER BY id DESC;

-- Message Threads
SELECT id, phone, name, status FROM message_threads;
```

**View Logs**:
```bash
tail -f storage/logs/laravel.log
```

**Check Server Status**:
```bash
curl http://localhost:8000/
```

---

## 🔍 Common Issues & Solutions

### Issue 1: Queue number tidak auto-increment
**Solution**: Pastikan migration sudah run. Check: `php artisan migrate --step`

### Issue 2: Admin response tidak masuk ke customer
**Solution**: Pastikan message_thread_id ada di order. Check database.

### Issue 3: Notifikasi tidak muncul
**Solution**: Check message type dan order_id di chat_messages table.

### Issue 4: API order-status return error 404
**Solution**: Pastikan phone number match dengan yang di database.

### Issue 5: Queue number tidak reset setiap hari
**Solution**: Check `generateQueueNumber()` logic. Harus gunakan `now()->toDateString()`.

---

## 📈 Performance Testing

**Test Large Volume**:
```bash
# Create 100 orders in batch
for i in {1..100}; do
  curl -X POST "http://localhost:8000/checkout" \
    -H "Content-Type: application/json" \
    -d '{...}'
done
```

**Monitor**:
- Database query time
- API response time
- Memory usage

---

## ✅ UAT Checklist (User Acceptance Testing)

- [ ] Customer dapat melihat nomor antrian
- [ ] Admin dapat melihat semua pesanan
- [ ] Admin dapat merespon pesanan dengan pesan & estimasi
- [ ] Customer menerima notifikasi otomatis
- [ ] Customer dapat cek status via API
- [ ] Nomor antrian increment setiap pesanan
- [ ] Nomor antrian reset setiap hari
- [ ] Tidak ada error di production
- [ ] Loading time acceptable
- [ ] Mobile responsive

---

## 🚀 Production Deployment

Sebelum deploy ke production:

1. **Test all test cases** ✅
2. **Check error logs** ✅
3. **Backup database** 
4. **Run migrations** (production database)
5. **Monitor first 24 hours**
6. **Have rollback plan ready**

---

## 📞 Support

Jika ada error saat testing:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser console: F12 → Console
3. Check database query: `SELECT * FROM orders LIMIT 5`
4. Re-read dokumentasi: `SISTEM_PESANAN_NOTIFIKASI.md`

---

**Test Status**: Ready for UAT ✅
**Last Updated**: 05 February 2026
