<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Dapoer Budess</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #1a1a1a;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .admin-sidebar {
            width: 200px;
            background: #1a1a1a;
            border-right: 1px solid #333;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 600"><defs><pattern id="bread" x="0" y="0" width="200" height="300" patternUnits="userSpaceOnUse"><image href="data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Ccircle cx=%2250%22 cy=%2250%22 r=%2240%22 fill=%22%23D2691E%22 opacity=%220.1%22/%3E%3C/svg%3E" x="0" y="0" width="200" height="300"/%3E</pattern></defs><rect width="400" height="600" fill="%231a1a1a"/><rect width="400" height="600" fill="url(%23bread)"/></svg>');
        }
        
        .admin-sidebar .logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
            padding: 1.5rem 1rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .admin-sidebar .logo-icon {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .admin-sidebar .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .admin-sidebar .logo-text {
            display: none;
        }
        
        .admin-sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        
        .admin-sidebar nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            color: #999;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9rem;
            font-weight: 500;
            border-left: 3px solid transparent;
        }
        
        .admin-sidebar nav a:hover {
            color: #FFD700;
            background: rgba(255, 215, 0, 0.05);
            border-left-color: #FFD700;
        }
        
        .admin-sidebar nav a.active {
            color: #FFD700;
            background: rgba(255, 215, 0, 0.1);
            border-left-color: #FFD700;
        }
        
        .admin-sidebar nav a span:first-child {
            font-size: 1.25rem;
            width: 24px;
        }
        
        /* Main Content */
        .admin-main {
            margin-left: 200px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #252525 100%);
            border-bottom: 1px solid #FFD700;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .admin-header-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .admin-header-title h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #FFD700;
            font-family: 'Playfair Display', serif;
        }
        
        .admin-header-icons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .admin-header-icons button {
            background: none;
            border: none;
            color: #FFD700;
            cursor: pointer;
            font-size: 1.25rem;
            padding: 0.5rem;
            transition: all 0.3s;
        }
        
        .admin-header-icons button:hover {
            transform: scale(1.1);
        }
        
        .admin-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }
        
        /* Cards */
        .card {
            background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
            border: 1px solid #FFD700;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1.5rem;
        }
        
        /* Table */
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table thead {
            background: #1a1a1a;
            border-bottom: 1px solid #FFD700;
        }
        
        table th {
            padding: 1rem;
            text-align: left;
            color: #FFD700;
            font-weight: 700;
            font-size: 0.875rem;
            text-transform: uppercase;
        }
        
        table td {
            padding: 1rem;
            border-bottom: 1px solid #333;
            color: #ccc;
        }
        
        table tbody tr:hover {
            background: rgba(255, 215, 0, 0.05);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: #1a1a1a;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(255, 215, 0, 0.3);
        }
        
        .btn-secondary {
            background: #333;
            color: #FFD700;
            border: 1px solid #FFD700;
        }
        
        .btn-secondary:hover {
            background: rgba(255, 215, 0, 0.1);
        }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        
        .badge-success {
            background: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
        }
        
        .badge-warning {
            background: rgba(255, 193, 7, 0.2);
            color: #FFC107;
        }
        
        .badge-danger {
            background: rgba(244, 67, 54, 0.2);
            color: #f44336;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 150px;
            }
            
            .admin-main {
                margin-left: 150px;
            }
            
            .admin-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="logo">
                <div class="logo-icon">
                    <img src="{{ asset('images/budess.jpg') }}" alt="Dapoer Budess">
                </div>
            </div>
            
            <nav>
                <a href="{{ route('admin.dashboard') }}" class="@if(Route::currentRouteName() === 'admin.dashboard') active @endif">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="@if(str_contains(Route::currentRouteName(), 'products')) active @endif">
                    <span>🍞</span>
                    <span>Produk</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="@if(str_contains(Route::currentRouteName(), 'orders')) active @endif">
                    <span>📦</span>
                    <span>Pesanan</span>
                </a>
                <a href="{{ route('admin.messages.index') }}" class="@if(str_contains(Route::currentRouteName(), 'messages')) active @endif">
                    <span>💬</span>
                    <span>Pesan</span>
                </a>
                <a href="{{ route('admin.reports') }}" class="@if(Route::currentRouteName() === 'admin.reports') active @endif">
                    <span>📈</span>
                    <span>Laporan</span>
                </a>
                <a href="{{ route('admin.security.index') }}" class="@if(Route::currentRouteName() === 'admin.security.index') active @endif">
                    <span>🔒</span>
                    <span>Keamanan</span>
                </a>
                <a href="{{ route('admin.fraud.index') }}" class="@if(Route::currentRouteName() === 'admin.fraud.index') active @endif">
                    <span>🚨</span>
                    <span>Fraud</span>
                </a>
                <a href="{{ route('admin.payment-settings.index') }}" class="@if(str_contains(Route::currentRouteName(), 'payment-settings')) active @endif">
                    <span>💳</span>
                    <span>Pembayaran</span>
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span>👋</span>
                    <span>Keluar</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <div class="admin-header-title">
                    <span style="font-size: 1.5rem;"></span>
                    <h1>@yield('page-title', 'Admin Panel')</h1>
                </div>
            </div>
            
            <!-- Content -->
            <div class="admin-content">
                @section('content')
                @show
            </div>
        </div>
    </div>
</body>
</html>
