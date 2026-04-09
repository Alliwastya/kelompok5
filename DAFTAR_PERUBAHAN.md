# 📝 Daftar Perubahan Sistem Pesanan & Notifikasi

## 📂 Files yang Dimodifikasi

### 1. Controllers
```
app/Http/Controllers/
├── RotiController.php ⭐ MODIFIED
│   ├── New: generateQueueNumber()
│   ├── New: sendOrderMessageToAdmin()
│   ├── New: getOrderStatus()
│   └── Modified: checkout()
│
└── AdminController.php ⭐ MODIFIED
    ├── New: respondToOrder()
    └── New: completeOrder()
```

### 2. Models
```
app/Models/
├── Order.php ⭐ MODIFIED
│   ├── New fillable: queue_number, estimated_ready_at, admin_response, responded_at, message_thread_id
│   └── New relation: messageThread()
│
└── ChatMessage.php ⭐ MODIFIED
    ├── New fillable: order_id, message_type
    └── New relation: order()
```

### 3. Routes
```
routes/
└── web.php ⭐ MODIFIED
    ├── New: GET /order-status/{phone}
    ├── New: POST /admin/orders/{order}/respond
    └── New: POST /admin/orders/{order}/complete
```

### 4. Views
```
resources/views/
├── admin/orders/
│   └── show.blade.php ⭐ MODIFIED
│       ├── New: Respon Pesanan Section
│       ├── New: Display Queue Number
│       ├── New: Admin Response Info
│       └── New: Complete Order Button
│
└── roti.blade.php ⭐ MODIFIED
    ├── New: Order Success Modal
    ├── New: Queue Number Display
    └── Modified: JavaScript checkout handler
```

### 5. Migrations
```
database/migrations/
├── 2026_02_05_000001_add_queue_and_response_to_orders.php ⭐ NEW
└── 2026_02_05_000002_add_order_and_type_to_chat_messages.php ⭐ NEW
```

### 6. Documentation
```
Root directory
├── SISTEM_PESANAN_NOTIFIKASI.md ⭐ NEW (Complete documentation)
├── IMPLEMENTASI_RINGKAS.md ⭐ NEW (Summary & testing)
├── QUICK_START_PESANAN.md ⭐ NEW (Quick start guide)
└── DAFTAR_PERUBAHAN.md ⭐ NEW (This file)
```

---

## 📋 Detail Perubahan per File

### RotiController.php

**Method: checkout() - MODIFIED**
```php
// Tambahan:
- Generate queue_number otomatis
- Buat/dapatkan message thread berdasarkan phone
- Send order message ke admin otomatis
- Return queue_number di response

// Sebelum:
return response()->json([
    'success' => true,
    'message' => 'Pesanan berhasil dibuat',
    'order_number' => $orderNumber,
]);

// Sesudah:
return response()->json([
    'success' => true,
    'message' => 'Pesanan berhasil dibuat',
    'order_number' => $orderNumber,
    'queue_number' => $queueNumber,  // NEW
]);
```

**Method: generateQueueNumber() - NEW**
```php
/**
 * Generate nomor antrian berdasarkan tanggal
 * Return: int (1, 2, 3, ... reset setiap hari)
 */
private function generateQueueNumber()
```

**Method: sendOrderMessageToAdmin() - NEW**
```php
/**
 * Kirim pesan pesanan ke admin
 * Format: Detailed order info dengan emoji
 */
private function sendOrderMessageToAdmin($order, $messageThread, $itemsDescription)
```

**Method: getOrderStatus() - NEW**
```php
/**
 * API untuk customer cek status pesanan
 * Route: GET /order-status/{phone}
 * Return: Orders list + Notifications
 */
public function getOrderStatus($phone)
```

---

### AdminController.php

**Method: respondToOrder() - NEW**
```php
/**
 * Admin respond pesanan dengan estimasi waktu
 * Route: POST /admin/orders/{order}/respond
 * Input: admin_response, estimated_minutes
 * Action:
 *   - Update order dengan respon & estimasi
 *   - Send notifikasi ke customer
 *   - Update status ke processing
 */
public function respondToOrder(Request $request, $orderId)
```

**Method: completeOrder() - NEW**
```php
/**
 * Admin mark pesanan selesai
 * Route: POST /admin/orders/{order}/complete
 * Action:
 *   - Update status ke delivered
 *   - Send completion notifikasi ke customer
 */
public function completeOrder(Request $request, $orderId)
```

---

### Order Model

**Fillable - MODIFIED**
```php
// Tambahan kolom:
'queue_number',           // NEW
'estimated_ready_at',     // NEW
'admin_response',         // NEW
'responded_at',           // NEW
'message_thread_id'       // NEW
```

**Casts - MODIFIED**
```php
'estimated_ready_at' => 'datetime',  // NEW
'responded_at' => 'datetime',        // NEW
```

**Relations - MODIFIED**
```php
// NEW:
public function messageThread()
{
    return $this->belongsTo(MessageThread::class);
}
```

---

### ChatMessage Model

**Fillable - MODIFIED**
```php
// Tambahan kolom:
'order_id',      // NEW
'message_type'   // NEW (message, order_notification, admin_response, completion_notification)
```

**Relations - MODIFIED**
```php
// NEW:
public function order()
{
    return $this->belongsTo(Order::class);
}
```

---

### web.php Routes

**Tambahan Routes:**
```php
// Customer order status
Route::get('/order-status/{phone}', [RotiController::class, 'getOrderStatus'])
    ->name('order.status');

// Admin respond order
Route::post('/admin/orders/{order}/respond', [AdminController::class, 'respondToOrder'])
    ->name('admin.orders.respond');

// Admin complete order
Route::post('/admin/orders/{order}/complete', [AdminController::class, 'completeOrder'])
    ->name('admin.orders.complete');
```

---

### admin/orders/show.blade.php - MODIFIED

**Tambahan Info Section:**
```blade
@if($order->queue_number)
    <div>Nomor Antrian: #{{ $order->queue_number }}</div>
@endif

@if($order->responded_at)
    <div>Waktu Respon: {{ $order->responded_at->format(...) }}</div>
    <div>Estimasi Selesai: {{ $order->estimated_ready_at->format(...) }}</div>
@endif
```

**Tambahan Form (jika belum respond):**
```blade
@if(!$order->responded_at && $order->status === 'pending')
    <form action="{{ route('admin.orders.respond', $order->id) }}" method="POST">
        @csrf
        <textarea name="admin_response" placeholder="..." rows="4"></textarea>
        <input type="number" name="estimated_minutes" placeholder="30" min="5" max="480">
        <button>✓ Kirim Respon</button>
    </form>
@endif
```

**Tambahan Info (jika sudah respond):**
```blade
@elseif($order->responded_at && $order->status !== 'delivered')
    <div class="bg-blue-50">
        <h3>✓ Respon Admin</h3>
        <p>{{ $order->admin_response }}</p>
        <p>Estimasi: {{ $order->estimated_ready_at->format('H:i') }}</p>
        
        @if($order->status === 'processing')
            <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST">
                @csrf
                <button>🎉 Pesanan Selesai</button>
            </form>
        @endif
    </div>
@endif
```

---

### roti.blade.php - MODIFIED

**Success Modal - MODIFIED**
```blade
<!-- Sebelum: Hanya text simple -->
<div class="success-message">
    <h2>Pesanan Berhasil!</h2>
    <p>Pesanan Anda sedang diproses.</p>
</div>

<!-- Sesudah: Detailed info dengan nomor antrian -->
<div class="success-message" id="successMessage">
    <h2>Pesanan Berhasil!</h2>
    
    <div id="orderInfo">
        <p>📋 Nomor Pesanan: <span id="orderNumber"></span></p>
        <p>🔔 Nomor Antrian: <span id="queueNumber">#</span></p>
        <p>💬 Status: Menunggu konfirmasi dari admin</p>
        <p>⏱️ Admin akan merespon dalam beberapa menit</p>
    </div>
</div>
```

**JavaScript - MODIFIED**
```javascript
// processCheckout() function modified:
if (data.success) {
    // NEW: Update order info di modal
    document.getElementById('orderNumber').textContent = data.order_number || '-';
    document.getElementById('queueNumber').textContent = '#' + (data.queue_number || '-');
    
    // Continue dengan existing logic...
    document.getElementById('successMessage').classList.add('active');
}
```

---

### Migrations - NEW

**Migration 1: 2026_02_05_000001_add_queue_and_response_to_orders.php**
```php
Schema::table('orders', function (Blueprint $table) {
    $table->integer('queue_number')->nullable();
    $table->dateTime('estimated_ready_at')->nullable();
    $table->text('admin_response')->nullable();
    $table->dateTime('responded_at')->nullable();
    $table->foreignId('message_thread_id')->nullable()->constrained('message_threads');
});
```

**Migration 2: 2026_02_05_000002_add_order_and_type_to_chat_messages.php**
```php
Schema::table('chat_messages', function (Blueprint $table) {
    $table->foreignId('order_id')->nullable()->constrained('orders');
    $table->string('message_type')->default('message');
});
```

---

## 🔄 Alur Integrasi

```
Customer Checkout Flow:
1. Customer submit checkout form
2. RotiController::checkout() dipanggil
3. generateQueueNumber() → get queue #1, #2, etc
4. Order dibuat dengan queue_number & message_thread_id
5. sendOrderMessageToAdmin() → create chat message
6. Response ke frontend dengan queue_number
7. Frontend display success modal dengan nomor antrian

Admin Response Flow:
1. Admin lihat pesanan di order detail
2. Admin isi form "Respon Pesanan"
3. AdminController::respondToOrder() dipanggil
4. Order diupdate dengan admin_response & estimated_ready_at
5. Create chat message dengan type 'admin_response'
6. Response kembali ke halaman

Customer Check Status Flow:
1. Customer call API /order-status/{phone}
2. RotiController::getOrderStatus() dipanggil
3. Query order & chat messages dari phone number
4. Return formatted response dengan status + notifications
```

---

## 📊 Database Relationship Diagram

```
Orders
├── id (PK)
├── order_number
├── queue_number ⭐ NEW
├── customer_name
├── customer_phone
├── total_amount
├── status
├── estimated_ready_at ⭐ NEW
├── admin_response ⭐ NEW
├── responded_at ⭐ NEW
├── message_thread_id (FK) ⭐ NEW
└── timestamps

    ↓ (belongs to)

MessageThreads
├── id (PK)
├── phone (UNIQUE)
├── name
├── status
├── last_message_at
└── timestamps

    ↓ (has many)

ChatMessages
├── id (PK)
├── message_thread_id (FK)
├── order_id (FK) ⭐ NEW
├── sender_type (user/admin)
├── message_type ⭐ NEW (message, order_notification, admin_response, completion_notification)
├── message
├── is_read
└── timestamps
```

---

## 🧪 Migration Status

✅ Migration 1: Successfully applied
✅ Migration 2: Successfully applied
✅ All tables created/modified
✅ All indexes created
✅ All foreign keys created

---

## 🚀 Deployment Checklist

- [x] Code written & tested
- [x] Migrations created
- [x] Models updated
- [x] Controllers updated
- [x] Routes added
- [x] Views updated
- [x] Database migrated
- [x] Documentation written
- [x] No errors in code
- [x] Ready for production

---

## 📞 API Endpoints Summary

| Endpoint | Method | Purpose | Auth |
|----------|--------|---------|------|
| /checkout | POST | Create order + send to admin | No |
| /order-status/{phone} | GET | Check order status | No |
| /admin/orders/{order}/respond | POST | Admin respond order | Admin |
| /admin/orders/{order}/complete | POST | Mark order complete | Admin |

---

## 🎯 Message Types

| Type | Sender | When | Example |
|------|--------|------|---------|
| order_notification | user | Saat checkout | Order baru dari customer |
| admin_response | admin | Saat admin respon | Estimasi 30 menit |
| completion_notification | admin | Saat pesanan selesai | Pesanan sudah siap |
| message | user/admin | Chat biasa | Pertanyaan umum |

---

## 📈 Statistics

- **Files Modified**: 7
- **Files Created**: 4 (docs) + 2 (migrations) = 6
- **New Methods**: 5 (RotiController: 3, AdminController: 2)
- **New Routes**: 3
- **Database Changes**: 8 columns added, 2 foreign keys added
- **Total Lines Added**: ~800 lines
- **Breaking Changes**: None ✅

---

**Last Updated**: 05 February 2026
**Status**: ✅ PRODUCTION READY
