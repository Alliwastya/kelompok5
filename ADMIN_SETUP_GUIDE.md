# Admin Dashboard - Setup Guide

## 🚀 Panduan Lengkap Setup Admin Dashboard

### Daftar Isi
1. [Prerequisites](#prerequisites)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Testing](#testing)
5. [Troubleshooting](#troubleshooting)

---

## Prerequisites

Sebelum memulai, pastikan Anda sudah memiliki:

- ✅ PHP 8.1+
- ✅ Laravel 11.x
- ✅ React 18.x
- ✅ Node.js 18+
- ✅ npm atau yarn
- ✅ Database (MySQL/SQLite)
- ✅ Laragon atau environment PHP lainnya

### Check Versions
```bash
php --version
node --version
npm --version
```

---

## Installation

### 1. Clone atau Setup Project

```bash
# Navigate ke project directory
cd c:\laragon\www\roti

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2. Database Setup

```bash
# Configure .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=roti_db
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

### 3. Create Admin User

#### Method 1: Using Tinker
```bash
php artisan tinker
```

```php
User::create([
    'name' => 'Admin Roti',
    'email' => 'admin@roti.local',
    'password' => bcrypt('password123'),
    'is_admin' => true,
]);

exit
```

#### Method 2: Using Factory
```bash
php artisan tinker
```

```php
User::factory()->create([
    'email' => 'admin@roti.local',
    'is_admin' => true,
]);

exit
```

#### Method 3: Direct Database Query
```sql
INSERT INTO users (name, email, password, is_admin, created_at, updated_at) 
VALUES (
    'Admin Roti', 
    'admin@roti.local', 
    '$2y$10$...hashed_password...', 
    1, 
    NOW(), 
    NOW()
);
```

### 4. Build Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### 5. Start Development Server

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

---

## Configuration

### .env Configuration

```env
# Application
APP_NAME="Roti"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=roti_db
DB_USERNAME=root
DB_PASSWORD=

# Cache
CACHE_DRIVER=file

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail (if needed)
MAIL_DRIVER=log
```

### Admin Middleware Check

Verifikasi middleware `is_admin` sudah terdaftar:

File: `app/Http/Kernel.php`

```php
protected $routeMiddleware = [
    // ... other middleware ...
    'is_admin' => \App\Http\Middleware\IsAdmin::class,
];
```

### Routes Check

Verifikasi routes sudah terdaftar:

File: `routes/web.php`

```php
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages.index');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    // ... other routes ...
});
```

---

## Testing

### 1. Manual Testing

#### Test Login dengan Admin

```bash
# 1. Open browser
http://localhost:8000/login

# 2. Login dengan credentials
Email: admin@roti.local
Password: password123

# 3. Navigate to admin dashboard
http://localhost:8000/admin
```

#### Verify Dashboard Load

Halaman harus menampilkan:
- ✅ Header "Admin Dashboard"
- ✅ 4 Stat Cards (Revenue, Orders, Messages)
- ✅ Action Buttons
- ✅ Sales Chart
- ✅ Recent Orders Table
- ✅ Recent Messages Table

### 2. Automated Testing

#### Run Feature Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AdminDashboardTest.php

# Run with verbose output
php artisan test --verbose

# Run with coverage
php artisan test --coverage
```

#### Test Admin Dashboard Test File
```bash
php artisan test tests/Feature/AdminDashboardTest.php
```

**Expected Output:**
```
✓ test_admin_dashboard_accessible_for_admin
✓ test_admin_dashboard_not_accessible_for_non_admin
✓ test_admin_dashboard_requires_authentication
✓ test_dashboard_displays_todays_revenue
✓ test_dashboard_displays_monthly_revenue
✓ test_dashboard_displays_todays_orders_count
✓ test_dashboard_displays_open_messages_count
✓ test_dashboard_displays_recent_orders
✓ test_dashboard_displays_recent_messages
✓ test_sales_chart_data_generated_correctly
✓ test_revenue_from_previous_month_not_included

11 tests passed
```

### 3. Database Verification

```bash
# Check admin users
php artisan tinker
User::where('is_admin', true)->get();

# Check orders count
Order::count();

# Check today's orders
Order::whereDate('created_at', today())->count();

# Check messages
MessageThread::count();

exit
```

### 4. API Testing

#### Using cURL
```bash
# Login first
curl -X POST http://localhost:8000/login \
  -d "email=admin@roti.local&password=password123" \
  -c cookies.txt

# Access dashboard
curl -X GET http://localhost:8000/admin \
  -b cookies.txt
```

#### Using Postman
1. Set URL: `http://localhost:8000/admin`
2. Set Method: `GET`
3. Add Header: `Authorization: Bearer {token}`
4. Send request

---

## Common Issues & Troubleshooting

### Issue 1: Admin Dashboard Returns 403

**Symptoms:**
```
403 Forbidden
```

**Solutions:**
```bash
# Check if user is admin
php artisan tinker
$user = User::where('email', 'admin@roti.local')->first();
$user->is_admin; // Should be 1 or true

# If false, update user
$user->update(['is_admin' => true]);
```

### Issue 2: Dashboard Page Blank

**Symptoms:**
- Halaman blank atau tidak loading
- No error in browser console

**Solutions:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Rebuild assets
npm run build
php artisan optimize

# Check logs
tail -f storage/logs/laravel.log
```

### Issue 3: Stats Showing Zero

**Symptoms:**
- Pendapatan = 0
- Pesanan = 0
- Pesan = 0

**Solutions:**
```bash
# Create sample data
php artisan tinker

# Create orders
Order::factory()->count(5)->create(['total_amount' => 150000]);

# Create messages
MessageThread::factory()->count(3)->create(['status' => 'open']);

exit
```

### Issue 4: Database Connection Error

**Symptoms:**
```
SQLSTATE[HY000]: General error: unable to open database file
```

**Solutions:**
```bash
# Check .env file
cat .env | grep DB_

# Verify database exists
# For MySQL: mysql -u root -p
# For SQLite: ls database/database.sqlite

# Run migrations if needed
php artisan migrate --force
```

### Issue 5: Assets Not Loading (CSS/JS)

**Symptoms:**
- Page tampil tapi tidak styled
- Console errors tentang JS

**Solutions:**
```bash
# Rebuild assets
npm run build

# Or in development
npm run dev

# Check if public/build directory exists
ls -la public/build/
```

### Issue 6: 419 Token Mismatch

**Symptoms:**
```
419 | Page Expired
CSRF token mismatch
```

**Solutions:**
```bash
# Check CSRF setup in .env
SESSION_DRIVER=file

# Clear sessions
php artisan session:table
php artisan migrate

# Clear cache
php artisan cache:clear
```

---

## Development Workflow

### Starting Development

**Terminal 1: Laravel Server**
```bash
cd c:\laragon\www\roti
php artisan serve
# App running at http://localhost:8000
```

**Terminal 2: Vite Dev Server**
```bash
cd c:\laragon\www\roti
npm run dev
# Vite dev server running at http://localhost:5173
```

**Terminal 3: MySQL (if needed)**
```bash
# Already running in Laragon
# Or start manually:
mysql -u root -p
```

### Making Changes

#### Backend Changes (Controller/Model)
```bash
# Edit file
# Change automatically detected
# No restart needed, just refresh page
```

#### Frontend Changes (React/CSS)
```bash
# Edit file
# Vite automatically recompiles
# Page auto-refreshes via HMR
```

#### Database Changes
```bash
# Create migration
php artisan make:migration add_column_to_table

# Run migration
php artisan migrate

# Rollback if needed
php artisan migrate:rollback
```

### Debugging

#### Laravel Debugging
```php
// In controller
dd($variable); // Dump and die
dump($variable); // Dump
ray($variable); // Ray debugging (if installed)
```

#### React Debugging
```jsx
// In component
console.log('variable:', variable);
debugger; // Browser debugger breakpoint
```

#### Database Debugging
```bash
php artisan tinker
# Run queries directly
Order::latest()->first();
```

---

## Performance Optimization

### Caching
```bash
# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Clear all
php artisan cache:clear
```

### Database Optimization
```php
// In AdminController
// Avoid N+1 queries
$recentOrders = Order::with('items')->latest()->take(10)->get();

// Index frequently queried columns
// In migration: $table->index('status');
```

### Asset Optimization
```bash
# Minify CSS/JS
npm run build

# Check bundle size
npm run build -- --report
```

---

## Production Deployment

### Before Deployment Checklist

- [ ] All tests passing: `php artisan test`
- [ ] No console errors
- [ ] No browser warnings
- [ ] Database migrations done: `php artisan migrate`
- [ ] Assets built: `npm run build`
- [ ] .env configured properly
- [ ] APP_DEBUG=false in .env
- [ ] Admin credentials created

### Deployment Commands

```bash
# 1. Install dependencies
composer install --no-dev --optimize-autoloader

# 2. Build assets
npm run build

# 3. Run migrations
php artisan migrate --force

# 4. Cache configuration
php artisan config:cache
php artisan route:cache

# 5. Set permissions
chmod -R 755 storage bootstrap/cache

# 6. Restart services
systemctl restart php-fpm
```

---

## Additional Resources

### Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [Inertia.js Guide](https://inertiajs.com)
- [React Documentation](https://react.dev)
- [Tailwind CSS](https://tailwindcss.com)

### Tools
- [Laravel Telescope](https://laravel.com/docs/telescope) - Debug tool
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar) - Performance monitoring
- [Ray](https://spatie.be/products/ray) - Debugging tool

### Support Channels
- GitHub Issues
- Stack Overflow (tag: laravel, react)
- Laravel Discord Community

---

## Next Steps

1. ✅ Setup admin user
2. ✅ Run the application
3. ✅ Access admin dashboard
4. ✅ Create sample data
5. ✅ Run automated tests
6. 📝 Customize for your needs
7. 🚀 Deploy to production

---

*Last Updated: February 2, 2026*
