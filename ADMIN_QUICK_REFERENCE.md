# Admin Dashboard - Quick Reference

## 🚀 Quick Start (5 Menit)

### 1. Login Admin
```
URL: http://localhost:8000/login
Email: admin@roti.local
Password: password123
```

### 2. Akses Dashboard
```
URL: http://localhost:8000/admin
```

### 3. Fitur Utama di Dashboard
```
📊 Statistik Real-time
├─ 💰 Pendapatan Hari Ini
├─ 📈 Pendapatan Bulan Ini
├─ 📦 Pesanan Hari Ini
└─ 💬 Pesan Baru

📋 Menu Cepat
├─ ➕ Buat Pesanan Baru
└─ 📊 Lihat Laporan

🔗 Link Cepat
├─ 📋 Kelola Pesanan
├─ 💬 Kelola Pesan
└─ 📊 Laporan Penjualan

📊 Grafik & Tabel
├─ Penjualan Bulanan
├─ 10 Pesanan Terbaru
└─ 5 Pesan Terbaru
```

---

## 📱 Fitur Per Halaman

### Dashboard (`/admin`)
| Fitur | Fungsi |
|-------|--------|
| Stats Cards | Menampilkan metrik bisnis utama |
| Sales Chart | Visualisasi penjualan per hari |
| Recent Orders | Tabel 10 pesanan terbaru |
| Recent Messages | Tabel 5 pesan terbaru |
| Quick Links | Shortcut ke halaman lain |

### Orders (`/admin/orders`)
| Fungsi | Tombol |
|--------|--------|
| List pesanan | Lihat semua |
| Filter status | Dropdown status |
| Search | Cari order number/customer |
| Create | + Buat Pesanan Baru |
| Detail | Klik pesanan untuk detail |
| Update Status | Edit status pesanan |

### Messages (`/admin/messages`)
| Fungsi | Tombol |
|--------|--------|
| List pesan | Lihat semua |
| Filter status | Dropdown status |
| Search | Cari nama/email |
| Detail | Klik untuk baca |
| Reply | Balas pesan |

### Reports (`/admin/reports`)
| Fitur | Tampilan |
|-------|---------|
| Sales Summary | Total revenue, orders |
| Top Products | Produk terlaris |
| Customer Analytics | Customer stats |
| Status Distribution | Pie chart |
| Revenue Trend | Line chart |
| Export | PDF, Excel, CSV |

---

## 🎯 Common Tasks

### Membuat Pesanan Baru
```
1. Klik "+ Buat Pesanan Baru"
2. Pilih/Buat Pelanggan
3. Tambah Produk
4. Input Kuantitas
5. Review Total
6. Submit
```

### Update Status Pesanan
```
1. Klik "Lihat Semua" → Orders
2. Cari pesanan
3. Klik "Detail"
4. Update status
5. Tambah notes (optional)
6. Save
```

### Balas Pesan Pelanggan
```
1. Klik "Lihat Semua" → Messages
2. Cari pesan
3. Klik "Balas"
4. Tulis balasan
5. Submit
```

### Melihat Laporan
```
1. Klik "📈 Lihat Laporan"
2. Pilih periode
3. View charts
4. Export jika perlu
```

---

## 🔐 Security Quick Guide

### Admin Access Requirements
- ✅ Must be logged in
- ✅ Must have is_admin = true
- ✅ Session must be valid

### What Admin Can Do
- ✅ View all orders and messages
- ✅ Create new orders
- ✅ Update order status
- ✅ Reply to customer messages
- ✅ View reports and analytics

### What Admin Cannot Do
- ✅ Delete users
- ✅ Change another admin's status
- ✅ Access database directly
- ✅ Modify system settings

---

## 🐛 Troubleshooting Quick Fix

### Problem | Solution
---|---
Dashboard tidak tampil | Clear cache: `php artisan cache:clear`
Data tidak muncul | Create sample data: `php artisan tinker` → `Order::factory()->count(5)->create()`
Tombol tidak berfungsi | Refresh page atau check browser console
404 error | Verify URL, check routes: `php artisan route:list`
Database error | Check .env, verify connection: `php artisan tinker` → `DB::connection()->getPdo()`

---

## 📊 Status Badges Reference

### Order Status
```
🟢 Terkirim    = delivered  (Order sudah sampai)
🔵 Dikirim     = shipped    (Order dalam pengiriman)
🟡 Diproses    = processing (Order sedang dikerjakan)
🔴 Dibatalkan  = cancelled  (Order dibatalkan)
⚪ Menunggu    = pending    (Order menunggu konfirmasi)
```

### Message Status
```
🟢 Dibalas  = replied  (Admin sudah balas)
🔵 Dibaca   = read     (Admin sudah baca)
🟡 Baru     = unread   (Pesan baru belum dibaca)
```

---

## ⌨️ Keyboard Shortcuts

| Shortcut | Fungsi |
|----------|--------|
| `Ctrl + K` | Search (jika implemented) |
| `Ctrl + /` | Help menu |
| `Tab` | Navigate between items |
| `Enter` | Submit form |
| `Esc` | Close modal |

---

## 📞 Common Queries

### Q: Bagaimana cara membuat admin baru?
A: 
```bash
php artisan tinker
User::create(['name' => 'Admin 2', 'email' => 'admin2@roti.local', 'password' => bcrypt('pass'), 'is_admin' => true]);
```

### Q: Bagaimana cara mengubah status admin?
A:
```bash
php artisan tinker
$user = User::find(1);
$user->update(['is_admin' => true]); // atau false
```

### Q: Bagaimana cara reset password admin?
A:
```bash
php artisan tinker
$user = User::where('email', 'admin@roti.local')->first();
$user->update(['password' => bcrypt('new_password')]);
```

### Q: Bagaimana cara lihat data di database?
A:
```bash
php artisan tinker
User::where('is_admin', true)->get();
Order::latest()->get();
MessageThread::all();
```

---

## 📈 Performance Tips

### Untuk Admin
- Refresh halaman jika data lama
- Clear cache jika ada error
- Export report untuk analytics offline
- Archive pesan lama untuk performa

### Untuk Developer
- Use eager loading: `.with('items')`
- Add indexes: `$table->index('status')`
- Cache expensive queries
- Optimize database queries

---

## 🎨 UI Elements Reference

### Colors Used
```
Primary Blue:     #3B82F6  (Actions, links)
Success Green:    #10B981  (Delivered, replied)
Warning Yellow:   #F59E0B  (Processing, unread)
Danger Red:       #EF4444  (Cancelled)
Neutral Gray:     #6B7280  (Pending, secondary)
```

### Icons (Emojis)
```
💰 Money/Revenue
📊 Chart/Analytics
📦 Package/Orders
💬 Messages
📋 List/Details
🚚 Shipping
⏳ Waiting/Processing
✓  Confirmed/Delivered
✕  Cancelled/Error
🔔 Notification
👀 Viewed/Read
```

---

## 📚 Documentation Links

| File | Konten |
|------|--------|
| ADMIN_DASHBOARD.md | Feature documentation lengkap |
| ADMIN_PANEL_FEATURES.md | Semua fitur admin panel |
| ADMIN_SETUP_GUIDE.md | Setup & deployment guide |
| ADMIN_IMPLEMENTATION_SUMMARY.md | Implementation summary |

---

## 🔗 URL Quick Links

| Page | URL | Shortcut |
|------|-----|----------|
| Dashboard | /admin | Main page |
| Orders | /admin/orders | Manage orders |
| Create Order | /admin/orders/create | New order form |
| Messages | /admin/messages | Manage messages |
| Reports | /admin/reports | Analytics |
| Logout | /logout | Sign out |

---

## ⚙️ System Requirements

### Browser
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### Server
- PHP 8.1+
- Laravel 11+
- MySQL 5.7+ or SQLite

### Network
- Stable internet connection
- HTTPS for production
- Cookies enabled
- JavaScript enabled

---

## 📋 Daily Admin Checklist

```
☐ Check new orders
☐ Reply to new messages
☐ Update order statuses
☐ Review daily revenue
☐ Export reports
☐ Check for errors in logs
☐ Monitor system performance
```

---

## 🆘 Emergency Contacts

### For Issues
1. Check ADMIN_SETUP_GUIDE.md → Troubleshooting
2. Check browser console (F12)
3. Check Laravel logs: `storage/logs/laravel.log`
4. Contact development team

### Common Error Messages
```
403 Forbidden → Not admin user
419 Token Mismatch → Session expired, refresh page
404 Not Found → Invalid URL or route
500 Server Error → Check logs, restart server
```

---

## 🎓 Learning Resources

| Topic | Resource |
|-------|----------|
| Laravel | laravel.com/docs |
| React | react.dev |
| Tailwind | tailwindcss.com/docs |
| Inertia | inertiajs.com |

---

## 🔄 Maintenance Schedule

| Waktu | Task |
|-------|------|
| Daily | Check new orders & messages |
| Weekly | Review sales reports |
| Monthly | Update dependencies |
| Monthly | Optimize database |
| Quarterly | Review logs |

---

## 💡 Pro Tips

1. **Bulk Actions**: Filter orders by status, then update multiple at once
2. **Search**: Use order number or customer name for quick search
3. **Export**: Export reports for offline analysis
4. **Keyboard**: Use Tab to navigate faster
5. **Mobile**: Dashboard is mobile-friendly, check from phone
6. **Shortcuts**: Bookmark common pages in browser
7. **Data**: Keep sensitive data in environment variables

---

*Last Updated: February 2, 2026*
*Version: Quick Reference v1.0*
*Status: Ready for Use*
