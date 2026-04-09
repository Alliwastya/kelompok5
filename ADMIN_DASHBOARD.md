# Admin Dashboard - Dokumentasi

## Overview
Halaman Admin Dashboard adalah pusat kontrol untuk mengelola aplikasi Roti. Dashboard ini menyediakan visualisasi real-time dari data bisnis penting dan akses cepat ke fitur-fitur administrasi.

## Akses Dashboard

### URL
- **Production**: `http://localhost:8000/admin`
- **Route Name**: `admin.dashboard`

### Requirement
- User harus **login**
- User harus memiliki **status `is_admin = true`**
- User harus melewati middleware `auth` dan `is_admin`

### Contoh Login Admin
```php
// Login dengan user yang memiliki is_admin = true
$user = User::where('is_admin', true)->first();
Auth::login($user);
```

---

## Fitur-Fitur Dashboard

### 1. **Statistics Cards** (Kartu Statistik)
Menampilkan 4 metrik utama dalam bentuk kartu:

#### a. Pendapatan Hari Ini 💰
- Menampilkan total revenue dari semua pesanan yang dibuat hari ini
- Format: Rupiah (ID-locale)
- Warna: Biru
- Data: `totalRevenue`

#### b. Pendapatan Bulan Ini 📊
- Menampilkan total revenue dari awal bulan hingga sekarang
- Format: Rupiah (ID-locale)
- Warna: Hijau
- Data: `monthlyRevenue`

#### c. Pesanan Hari Ini 📦
- Menampilkan jumlah pesanan yang dibuat hari ini
- Format: Angka bulat
- Warna: Orange
- Data: `totalOrders`

#### d. Pesan Baru 💬
- Menampilkan jumlah thread pesan yang masih terbuka
- Format: Angka bulat
- Warna: Ungu
- Data: `totalMessages`

---

### 2. **Action Buttons** (Tombol Aksi Cepat)
Dua tombol cepat untuk aksi umum:

- **+ Buat Pesanan Baru** → Mengarah ke form pembuatan pesanan baru
  - Route: `admin.orders.create`
  
- **📈 Lihat Laporan** → Menampilkan halaman laporan penjualan
  - Route: `admin.reports`

---

### 3. **Sales Chart** (Grafik Penjualan)
Menampilkan visualisasi penjualan bulanan:

**Fitur:**
- Toggle view antara Minggu dan Bulan
- Menampilkan total penjualan dan jumlah pesanan per hari
- Summary stats:
  - Total Penjualan (bulan ini)
  - Total Pesanan (bulan ini)
  - Rata-rata per Hari

**Data:** `salesChart` (array of objects dengan struktur):
```json
[
  {
    "date": "2026-02-01",
    "total": 150000,
    "count": 2
  },
  {
    "date": "2026-02-02",
    "total": 300000,
    "count": 5
  }
]
```

**Quick Links** (Menu Cepat):
- 📋 Kelola Pesanan → `admin.orders.index`
- 💬 Kelola Pesan → `admin.messages.index`
- 📊 Laporan Penjualan → `admin.reports`

---

### 4. **Recent Orders** (Pesanan Terbaru)
Tabel menampilkan 10 pesanan terbaru dengan informasi:

| Kolom | Deskripsi |
|-------|-----------|
| No. Pesanan | Order number (berwarna biru) |
| Pelanggan | Nama pelanggan |
| Total | Total amount (format Rupiah) |
| Status | Status pesanan dengan badge berwarna |
| Aksi | Link "Detail →" untuk melihat detail pesanan |

**Status Badges:**
- 🟢 **Terkirim** (delivered) - Hijau
- 🔵 **Dikirim** (shipped) - Biru
- 🟡 **Diproses** (processing) - Kuning
- 🔴 **Dibatalkan** (cancelled) - Merah
- ⚪ **Menunggu** (pending) - Abu-abu

**Empty State:**
Jika tidak ada pesanan: "Belum ada pesanan hari ini"

---

### 5. **Recent Messages** (Pesan Terbaru)
Tabel menampilkan 5 thread pesan terbaru dengan informasi:

| Kolom | Deskripsi |
|-------|-----------|
| Nama | Nama pengirim pesan |
| Pesan | Preview pesan (truncated) |
| Status | Status pesan dengan badge |
| Aksi | Link "Balas →" untuk membalas pesan |

**Status Badges:**
- 🟢 **Dibalas** (replied) - Hijau
- 🔵 **Dibaca** (read) - Biru
- 🟡 **Baru** (unread) - Kuning

**Empty State:**
Jika tidak ada pesan: "Tidak ada pesan baru"

---

## Data Flow

### Controller Method: `AdminController@dashboard`

```php
public function dashboard()
{
    // 1. Set tanggal referensi
    $today = Carbon::today();
    $thisMonth = Carbon::now()->startOfMonth();

    // 2. Hitung metrik utama
    $totalRevenue = Order::whereDate('created_at', $today)->sum('total_amount');
    $monthlyRevenue = Order::whereBetween('created_at', [$thisMonth, Carbon::now()])->sum('total_amount');
    $totalOrders = Order::whereDate('created_at', $today)->count();
    $totalMessages = MessageThread::where('status', 'open')->count();

    // 3. Buat chart data
    $salesChart = Order::whereBetween('created_at', [$thisMonth, Carbon::now()])
        ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as count')
        ->groupBy('date')
        ->get()
        ->map(function ($item) {
            return [
                'date' => $item->date,
                'total' => (float)$item->total,
                'count' => $item->count
            ];
        });

    // 4. Ambil data terbaru
    $recentOrders = Order::latest()->take(10)->get();
    $recentMessages = MessageThread::orderBy('last_message_at', 'desc')->take(5)->get();

    // 5. Render ke React component
    return Inertia::render('Admin/Dashboard', [
        'totalRevenue' => $totalRevenue,
        'monthlyRevenue' => $monthlyRevenue,
        'totalOrders' => $totalOrders,
        'totalMessages' => $totalMessages,
        'salesChart' => $salesChart,
        'recentOrders' => $recentOrders,
        'recentMessages' => $recentMessages,
    ]);
}
```

---

## Styling & Layout

### Framework
- **CSS Framework**: Tailwind CSS
- **Component Library**: Headless UI
- **Layout**: AuthenticatedLayout (dengan sidebar/navigation)
- **Icons**: Unicode Emojis

### Responsive Design
- **Mobile (xs)**: 1 kolom
- **Tablet (md)**: 2 kolom (4 stat cards)
- **Desktop (lg)**: Full layout dengan 3-kolom untuk chart section

### Color Scheme
- **Primary**: Blue (#3B82F6)
- **Success**: Green (#10B981)
- **Warning**: Yellow (#F59E0B)
- **Danger**: Red (#EF4444)
- **Neutral**: Gray

---

## Fitur Interaktif

### Time Range Toggle
Tombol untuk mengubah rentang waktu chart:
- **Minggu** - Menampilkan data minggu ini
- **Bulan** - Menampilkan data bulan ini (default)

**Note**: Implementasi filter belum sepenuhnya terintegrasi. Perlu menambahkan query parameter pada controller.

---

## Peningkatan Masa Depan

Beberapa fitur yang dapat ditambahkan:

### 1. Chart Library Integration
- Install `chart.js` atau `recharts`
- Implementasi visualisasi grafik yang interaktif
- Export chart ke PDF/PNG

### 2. Real-time Updates
- WebSocket atau Polling untuk data real-time
- Notification badge untuk pesanan/pesan baru

### 3. Advanced Filtering
- Filter berdasarkan date range
- Filter berdasarkan kategori produk
- Search/pagination yang lebih canggih

### 4. Performance Metrics
- Customer retention rate
- Average order value
- Conversion rate
- Product popularity chart

### 5. Export Features
- Export data ke Excel
- Export laporan ke PDF
- Email scheduling untuk laporan harian/mingguan

---

## Database Models yang Digunakan

### Order Model
```php
Model: App\Models\Order
Relasi: belongsTo('App\Models\User')
Relations: hasMany('OrderItem')

Kolom penting:
- id
- order_number (string, unique)
- customer_name (string)
- total_amount (decimal)
- status (enum: pending, processing, shipped, delivered, cancelled)
- created_at (timestamp)
```

### MessageThread Model
```php
Model: App\Models\MessageThread
Relations: hasMany('ChatMessage')

Kolom penting:
- id
- name (string)
- message (text)
- status (enum: open, replied, archived)
- last_message_at (timestamp)
```

---

## Security Considerations

### Middleware Stack
1. **auth** - Verifikasi user sudah login
2. **is_admin** - Verifikasi user adalah admin

### Implementation
```php
// Di routes/web.php
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    // ...
});
```

### Best Practices
- ✓ Selalu check admin status sebelum menampilkan admin panel
- ✓ Log aksi admin untuk audit trail
- ✓ Rate limit akses API admin
- ✓ Validasi semua input dari form

---

## Troubleshooting

### Dashboard tidak tampil
**Problem**: 403 Forbidden atau halaman blank
**Solution**: Pastikan user memiliki `is_admin = true`

### Data tidak muncul
**Problem**: Stat cards atau table kosong
**Solution**: Check apakah ada data di database:
```php
// Di tinker
Order::count();
MessageThread::count();
```

### Chart tidak muncul
**Problem**: Area chart kosong
**Solution**: Normal - chart.js belum diintegrasikan. Bisa install dari npm:
```bash
npm install chart.js react-chartjs-2
```

---

## Route Reference

| Method | Route | Controller | Description |
|--------|-------|-----------|-------------|
| GET | `/admin` | `AdminController@dashboard` | Admin Dashboard |
| GET | `/admin/orders` | `AdminController@orders` | Orders List |
| GET | `/admin/orders/create` | `AdminController@createOrder` | Create Order Form |
| GET | `/admin/orders/{id}` | `AdminController@showOrder` | Order Detail |
| GET | `/admin/messages` | `AdminController@messages` | Messages List |
| GET | `/admin/messages/{id}` | `AdminController@showMessage` | Message Detail |
| GET | `/admin/reports` | `AdminController@reports` | Reports Page |

---

## File Reference

- **Component**: [resources/js/Pages/Admin/Dashboard.jsx](resources/js/Pages/Admin/Dashboard.jsx)
- **Controller**: [app/Http/Controllers/AdminController.php](app/Http/Controllers/AdminController.php)
- **Routes**: [routes/web.php](routes/web.php)
- **Middleware**: [app/Http/Middleware/IsAdmin.php](app/Http/Middleware/IsAdmin.php)

---

*Last Updated: February 2, 2026*
