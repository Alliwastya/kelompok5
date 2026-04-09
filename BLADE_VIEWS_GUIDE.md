# Blade Views - Admin Dashboard

## 📁 File Structure

```
resources/views/
├── admin/
│   ├── dashboard.blade.php
│   ├── orders/
│   │   └── index.blade.php
│   ├── messages/
│   │   └── index.blade.php
│   └── reports.blade.php
```

---

## 📄 File-file Blade yang Telah Dibuat

### 1. **admin/dashboard.blade.php**
**Route**: `GET /admin`
**Description**: Main admin dashboard page
**Data Requirements**:
```php
$totalRevenue        // Today's revenue (integer)
$monthlyRevenue      // This month's revenue (integer)
$totalOrders         // Today's orders count (integer)
$totalMessages       // Open messages count (integer)
$salesChart          // Array of daily sales data
$recentOrders        // Collection of recent orders
$recentMessages      // Collection of recent messages
```

**Features**:
- Statistics cards (4 metrics)
- Sales chart section
- Quick action buttons
- Quick links menu
- Recent orders table
- Recent messages table

---

### 2. **admin/orders/index.blade.php**
**Route**: `GET /admin/orders`
**Description**: Orders management list page
**Data Requirements**:
```php
$orders              // Paginated orders collection
```

**Features**:
- Filter by status
- Search by order number or customer
- Status badges with colors
- Pagination
- Link to order detail

**Filters Available**:
- Status: pending, processing, shipped, delivered, cancelled
- Search: order number or customer name

---

### 3. **admin/messages/index.blade.php**
**Route**: `GET /admin/messages`
**Description**: Messages management list page
**Data Requirements**:
```php
$messages            // Paginated messages collection
```

**Features**:
- Filter by status
- Search by name or email
- Status badges with colors
- Pagination
- Link to message detail/reply

**Filters Available**:
- Status: open, read, replied
- Search: name or email

---

### 4. **admin/reports.blade.php**
**Route**: `GET /admin/reports`
**Description**: Reports and analytics page
**Data Requirements**:
```php
$totalRevenue        // Monthly revenue total (integer)
$totalOrders         // Monthly orders count (integer)
$salesChart          // Array of daily sales data
```

**Features**:
- Summary cards
- Daily sales chart
- Export buttons (Excel, PDF, Email)

**Data Display**:
- Date
- Number of orders
- Total sales
- Average per order

---

## 🔗 How to Use These Views

### In Controller

Modify `AdminController` to return Blade views instead of Inertia:

```php
// Option 1: Return Blade view for dashboard
public function dashboard()
{
    // ... existing code to calculate data ...
    
    return view('admin.dashboard', [
        'totalRevenue' => $totalRevenue,
        'monthlyRevenue' => $monthlyRevenue,
        'totalOrders' => $totalOrders,
        'totalMessages' => $totalMessages,
        'salesChart' => $salesChart,
        'recentOrders' => $recentOrders,
        'recentMessages' => $recentMessages,
    ]);
}

// Option 2: Return Blade view for orders
public function orders(Request $request)
{
    $query = Order::with('items');
    
    if ($request->status) {
        $query->where('status', $request->status);
    }
    
    if ($request->search) {
        $query->where('order_number', 'like', '%' . $request->search . '%')
            ->orWhere('customer_name', 'like', '%' . $request->search . '%');
    }
    
    $orders = $query->latest()->paginate(15);
    
    return view('admin.orders.index', [
        'orders' => $orders,
    ]);
}

// Option 3: Return Blade view for messages
public function messages(Request $request)
{
    $query = MessageThread::query();
    
    if ($request->status) {
        $query->where('status', $request->status);
    }
    
    if ($request->search) {
        $query->where('name', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%');
    }
    
    $messages = $query->latest()->paginate(15);
    
    return view('admin.messages.index', [
        'messages' => $messages,
    ]);
}

// Option 4: Return Blade view for reports
public function reports()
{
    $today = Carbon::today();
    $thisMonth = Carbon::now()->startOfMonth();
    
    $totalRevenue = Order::whereBetween('created_at', [$thisMonth, Carbon::now()])
        ->sum('total_amount');
    $totalOrders = Order::whereBetween('created_at', [$thisMonth, Carbon::now()])
        ->count();
    
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
    
    return view('admin.reports', [
        'totalRevenue' => $totalRevenue,
        'totalOrders' => $totalOrders,
        'salesChart' => $salesChart,
    ]);
}
```

---

## 🎨 Styling

All Blade views use **Tailwind CSS** classes:
- Colors: Blue (primary), Green (success), Yellow (warning), Red (danger)
- Responsive: Mobile-first design
- Components: Cards, tables, buttons, badges

### Color Scheme

**Status Colors**:
```
delivered: Green (bg-green-100 text-green-800)
shipped: Blue (bg-blue-100 text-blue-800)
processing: Yellow (bg-yellow-100 text-yellow-800)
cancelled: Red (bg-red-100 text-red-800)
pending: Gray (bg-gray-100 text-gray-800)
```

---

## 🔗 Links & Routes

### Navigation Links

All Blade views include navigation links using Laravel route helpers:

```blade
<!-- Dashboard link -->
<a href="{{ route('admin.dashboard') }}">Dashboard</a>

<!-- Orders link -->
<a href="{{ route('admin.orders.index') }}">Orders</a>

<!-- Messages link -->
<a href="{{ route('admin.messages.index') }}">Messages</a>

<!-- Reports link -->
<a href="{{ route('admin.reports') }}">Reports</a>

<!-- Create order link -->
<a href="{{ route('admin.orders.create') }}">Create Order</a>

<!-- Order detail link -->
<a href="{{ route('admin.orders.show', $order->id) }}">Order Detail</a>

<!-- Message detail link -->
<a href="{{ route('admin.messages.show', $message->id) }}">Message Detail</a>
```

---

## 📊 Data Formatting

### Currency Format
```blade
Rp {{ number_format($amount, 0, ',', '.') }}
```

### Date Format
```blade
{{ $date->format('d M Y') }}
{{ $date->format('d M Y H:i') }}
```

### Conditional Classes
```blade
@if($order->status === 'delivered') bg-green-100 text-green-800
@elseif($order->status === 'shipped') bg-blue-100 text-blue-800
@endif
```

---

## 🔐 Security Features

All views are protected by:
- Blade middleware: `@extends('layouts.app')`
- Controller middleware: `['auth', 'is_admin']`
- Route protection in `routes/web.php`

---

## 📝 Template Structure

Each Blade file follows this structure:

```blade
@extends('layouts.app')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h1>Title</h1>
    </div>

    <!-- Content -->
    <div>
        <!-- Your content here -->
    </div>
@endsection
```

---

## ✨ Features

### Dashboard View
- Statistics cards
- Sales chart
- Recent orders table
- Recent messages table
- Quick links
- Action buttons

### Orders View
- Order list table
- Filter by status
- Search functionality
- Status badges
- Pagination
- Create order button

### Messages View
- Message list table
- Filter by status
- Search functionality
- Status badges
- Pagination

### Reports View
- Summary cards
- Daily sales table
- Export options
- Analytics display

---

## 📌 Notes

1. **Alternative to React**: These Blade views provide a traditional server-rendered alternative to the React dashboard
2. **Data Structure**: Ensure controller passes correct data structure
3. **Styling**: Uses Tailwind CSS classes
4. **Responsive**: Works on mobile, tablet, and desktop
5. **Pagination**: Built-in with Laravel pagination helper
6. **Forms**: Includes filter and search forms

---

## 🔄 Switching Between React and Blade

You can use either:

**React Dashboard** (Inertia.js):
```
Route: GET /admin
File: resources/js/Pages/Admin/Dashboard.jsx
```

**Blade Dashboard**:
```
Route: GET /admin
File: resources/views/admin/dashboard.blade.php
```

Modify controller `dashboard()` method to return either:
- `Inertia::render('Admin/Dashboard', [...])` → React
- `view('admin.dashboard', [...])` → Blade

---

## 📚 Files Reference

| File | Route | View Location |
|------|-------|---------------|
| Dashboard | /admin | `admin/dashboard.blade.php` |
| Orders | /admin/orders | `admin/orders/index.blade.php` |
| Messages | /admin/messages | `admin/messages/index.blade.php` |
| Reports | /admin/reports | `admin/reports.blade.php` |

---

*Blade Views Documentation*
*Created: February 2, 2026*
*Status: Ready to Use*
