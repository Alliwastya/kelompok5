# Admin Panel - Panduan Fitur Lengkap

## 📋 Daftar Isi
1. [Dashboard Overview](#dashboard-overview)
2. [Manajemen Pesanan](#manajemen-pesanan)
3. [Manajemen Pesan](#manajemen-pesan)
4. [Laporan & Analytics](#laporan--analytics)
5. [Konfigurasi Admin](#konfigurasi-admin)

---

## Dashboard Overview

### URL Access
- **Admin Dashboard**: `http://localhost:8000/admin`
- **Requirement**: Login + is_admin = true

### Fitur Utama di Dashboard

#### 📊 Real-time Statistics
```
┌─────────────────────────────────────────────────────────────┐
│  Admin Dashboard                                              │
├─────────────────────────────────────────────────────────────┤
│ 💰 Pendapatan Hari Ini      │ 📊 Pendapatan Bulan Ini        │
│ Rp X.XXX.XXX                │ Rp Y.YYY.YYY                    │
├─────────────────────────────┼────────────────────────────────┤
│ 📦 Pesanan Hari Ini         │ 💬 Pesan Baru                  │
│ N Orders                    │ M Messages                      │
└─────────────────────────────────────────────────────────────┘
```

#### 🎯 Quick Actions
- **+ Buat Pesanan Baru** - Form untuk membuat pesanan manual
- **📈 Lihat Laporan** - Halaman analytics dan reporting

#### 📈 Sales Chart
- Visualisasi penjualan bulanan
- Toggle: Minggu / Bulan
- Summary: Total | Pesanan | Rata-rata

#### 🔗 Quick Links
- 📋 Kelola Pesanan
- 💬 Kelola Pesan  
- 📊 Laporan Penjualan

#### 📝 Recent Orders Table
Menampilkan 10 pesanan terbaru dengan:
- No. Pesanan
- Nama Pelanggan
- Total Amount (Rp format)
- Status (dengan badge)
- Link Detail

**Status Pesanan:**
- 🟢 Terkirim (delivered)
- 🔵 Dikirim (shipped)
- 🟡 Diproses (processing)
- 🔴 Dibatalkan (cancelled)
- ⚪ Menunggu (pending)

#### 💌 Recent Messages Table
Menampilkan 5 pesan terbaru dengan:
- Nama Pengirim
- Preview Pesan
- Status
- Link Balas

**Status Pesan:**
- 🟢 Dibalas (replied)
- 🔵 Dibaca (read)
- 🟡 Baru (unread)

---

## Manajemen Pesanan

### URL: `/admin/orders`
### Route: `admin.orders.index`

### Fitur
- **List Pesanan** dengan pagination
- **Filter** berdasarkan status
- **Search** berdasarkan order number atau customer name
- **Bulk Actions** (akan ditambahkan)

### Kolom Tabel
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| Order Number | String | ID pesanan unik |
| Customer | String | Nama pelanggan |
| Items | Number | Jumlah item |
| Total | Currency | Total harga |
| Status | Badge | Status pesanan |
| Date | Date | Tanggal pemesanan |
| Actions | Link | Detail/Edit |

### Filter Options
- **Status**: All, Pending, Processing, Shipped, Delivered, Cancelled
- **Search**: Cari berdasarkan order number atau nama pelanggan
- **Date Range**: Filter berdasarkan tanggal (optional)

### Detail Pesanan
URL: `/admin/orders/{id}`

Menampilkan:
- Informasi Pelanggan
- Daftar Produk (dengan qty, harga, subtotal)
- Status Pesanan
- Total Amount
- Tombol Update Status
- History Perubahan Status

### Update Status Pesanan
```json
Request:
{
  "status": "shipped",
  "notes": "Pesanan sudah dikirim melalui Gojek"
}

Status Valid: pending, processing, shipped, delivered, cancelled
```

### Buat Pesanan Manual
URL: `/admin/orders/create`
Route: `admin.orders.create`

Form untuk membuat pesanan baru:
- Pilih Pelanggan (atau buat baru)
- Tambah Produk
- Input Kuantitas
- Hitung Subtotal
- Pilih Payment Status
- Submit

---

## Manajemen Pesan

### URL: `/admin/messages`
### Route: `admin.messages.index`

### Fitur
- **List Thread Pesan** dari customer
- **Search** berdasarkan nama/email
- **Filter** berdasarkan status
- **Pagination**

### Kolom Tabel
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| Name | String | Nama pengirim |
| Email | String | Email pengirim |
| Subject | String | Subject pesan |
| Message Preview | Text | Ringkas pesan |
| Status | Badge | read/unread/replied |
| Date | Date | Tanggal pesan |
| Actions | Link | Detail/Reply |

### Detail Pesan
URL: `/admin/messages/{id}`
Route: `admin.messages.show`

Menampilkan:
- Thread lengkap percakapan
- Informasi pengirim
- Message history
- Form reply

### Balas Pesan
```json
Request: POST /admin/messages/{id}/reply
{
  "message": "Terima kasih atas pertanyaannya..."
}

Response:
{
  "success": true,
  "message": "Reply sent successfully"
}
```

### Status Pesan
- 🟡 **Baru** (unread) - Pesan baru belum dibaca
- 🔵 **Dibaca** (read) - Pesan sudah dibaca admin
- 🟢 **Dibalas** (replied) - Sudah ada reply dari admin

---

## Laporan & Analytics

### URL: `/admin/reports`
### Route: `admin.reports`

### Fitur Reports
1. **Sales Summary**
   - Total Revenue (Today/This Month)
   - Average Order Value
   - Number of Orders
   - Growth Rate

2. **Top Products**
   - Produk paling laris
   - Total quantity sold
   - Revenue contribution

3. **Customer Analytics**
   - Total customers
   - New customers (this month)
   - Repeat customers
   - Customer retention rate

4. **Order Status Distribution**
   - Pie chart status
   - Pending orders count
   - Processing orders count
   - Shipped orders count
   - Delivered orders count

5. **Revenue Trend**
   - Line chart revenue per day/week/month
   - Comparison with previous period
   - Export to CSV/PDF

### Export Options
- 📊 Export to Excel
- 📄 Export to PDF
- 📧 Email Report
- 📥 Download CSV

---

## Konfigurasi Admin

### User Admin Setup

#### 1. Create Admin User (Database)
```php
// Via tinker
User::create([
    'name' => 'Admin Roti',
    'email' => 'admin@roti.local',
    'password' => bcrypt('password123'),
    'is_admin' => true,
]);

// Or via factory
User::factory()->admin()->create([
    'email' => 'admin@roti.local'
]);
```

#### 2. Check Admin Status
```php
// Di tinker
$user = User::where('email', 'admin@roti.local')->first();
$user->is_admin; // true/false
```

#### 3. Update Admin Status
```php
$user = User::find(1);
$user->update(['is_admin' => true]);
```

### Admin Middleware Setup

File: `app/Http/Middleware/IsAdmin.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Unauthorized access');
    }
}
```

Register di `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ...
    'is_admin' => \App\Http\Middleware\IsAdmin::class,
];
```

### Admin Routes

File: `routes/web.php`

```php
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/create', [AdminController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [AdminController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    
    // Messages
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages.index');
    Route::get('/messages/{message}', [AdminController::class, 'showMessage'])->name('messages.show');
    Route::post('/messages/{message}/reply', [AdminController::class, 'replyMessage'])->name('messages.reply');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
```

---

## Admin Controller Methods

### `AdminController@dashboard()`
```php
// Return dashboard statistics
return Inertia::render('Admin/Dashboard', [
    'totalRevenue' => $totalRevenue,
    'monthlyRevenue' => $monthlyRevenue,
    'totalOrders' => $totalOrders,
    'totalMessages' => $totalMessages,
    'salesChart' => $salesChart,
    'recentOrders' => $recentOrders,
    'recentMessages' => $recentMessages,
]);
```

### `AdminController@orders()`
```php
// List orders dengan filter dan pagination
$orders = Order::with('items')
    ->when($request->status, fn($q) => $q->where('status', $request->status))
    ->when($request->search, fn($q) => $q->where('order_number', 'like', '%'.$request->search.'%'))
    ->latest()
    ->paginate(15);

return Inertia::render('Admin/Orders/Index', [
    'orders' => $orders,
    'filters' => $request->only('status', 'search'),
]);
```

### `AdminController@showOrder($id)`
```php
// Show order detail
$order = Order::with('items')->findOrFail($id);

return Inertia::render('Admin/Orders/Show', [
    'order' => $order,
]);
```

### `AdminController@updateOrderStatus()`
```php
// Update order status
$validated = $request->validate([
    'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
    'notes' => 'nullable|string'
]);

$order = Order::findOrFail($id);
$order->update($validated);

return back()->with('success', 'Order status updated');
```

---

## Security Best Practices

### ✅ Implemented
- [x] Admin middleware check
- [x] Route protection dengan auth + is_admin
- [x] User role-based access

### 🔒 Recommended
- [ ] Add audit logging untuk semua admin actions
- [ ] Add rate limiting pada admin endpoints
- [ ] Add IP whitelisting untuk akses admin
- [ ] Add 2FA (Two Factor Authentication)
- [ ] Add session timeout
- [ ] Encrypt sensitive data

### Implementation Example
```php
// Add audit logging
public function updateOrderStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);
    $oldStatus = $order->status;
    
    $order->update($request->validated());
    
    // Log audit
    \App\Models\AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'update_order_status',
        'model' => 'Order',
        'model_id' => $id,
        'old_value' => ['status' => $oldStatus],
        'new_value' => ['status' => $order->status],
    ]);
    
    return back()->with('success', 'Order status updated');
}
```

---

## Quick Commands

### Terminal Commands

```bash
# Start Laravel development server
php artisan serve

# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Create admin user
php artisan tinker
User::create([...])

# Check logs
tail -f storage/logs/laravel.log

# Clear cache
php artisan cache:clear
```

### Database Queries

```php
// Check admin users
User::where('is_admin', true)->get();

// Count today orders
Order::whereDate('created_at', today())->count();

// Get revenue today
Order::whereDate('created_at', today())->sum('total_amount');

// Count open messages
MessageThread::where('status', 'open')->count();
```

---

## Troubleshooting

### Problem: Admin Dashboard tidak bisa diakses
**Solution**: 
1. Pastikan sudah login: `Auth::check()`
2. Pastikan user adalah admin: `auth()->user()->is_admin === true`
3. Check middleware di routes: `['auth', 'is_admin']`

### Problem: Data tidak muncul di dashboard
**Solution**:
1. Check database: `Order::count()`, `MessageThread::count()`
2. Check dates: `Order::whereDate('created_at', today())->count()`
3. Check if data exists

### Problem: Error 403 Forbidden
**Solution**:
1. User tidak login
2. User bukan admin (is_admin = false)
3. Route tidak ada dalam admin group

---

## Future Enhancements

### Phase 1 (Next)
- [ ] Chart.js integration untuk visualisasi data
- [ ] Export reports (PDF, Excel, CSV)
- [ ] Email notifications

### Phase 2
- [ ] Advanced analytics (customer lifetime value, churn rate)
- [ ] Product management page
- [ ] Inventory tracking
- [ ] User management interface

### Phase 3
- [ ] Mobile app untuk admin
- [ ] Real-time notifications (WebSocket)
- [ ] Custom reports builder
- [ ] API documentation

---

## Links & Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Inertia.js Documentation](https://inertiajs.com)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [React Documentation](https://react.dev)

---

*Last Updated: February 2, 2026*
