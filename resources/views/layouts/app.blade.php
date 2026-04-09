<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dapoer Budess</title>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E5E7EB; }
        .sidebar { background-color: #F3F4F6; border-right: 1px solid #D1D5DB; }
        .sidebar-item { color: #374151; font-weight: 500; display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.5rem; transition: all 0.2s; }
        .sidebar-item:hover { background-color: #E5E7EB; }
        .sidebar-item-active { background-color: #E5E7EB; font-weight: 700; color: #111827; }
        .main-content { background-color: #E5E7EB; }
        .card { background: white; border-radius: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .btn-tambah { background-color: #FDE68A; color: #111827; font-weight: 600; padding: 0.5rem 1.5rem; border-radius: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
    </style>
</head>
    <div class="flex h-screen bg-[#F5F5F5] font-sans overflow-hidden">
        <!-- Sidebar (WHITE) -->
        <aside class="w-64 bg-white flex flex-col shrink-0 z-50 border-r border-gray-200">
            <!-- Logo Section -->
            <div class="h-24 flex items-center justify-center p-4">
                <!-- Logo Image -->
                <div class="flex items-center justify-center">
                    <img src="{{ asset('images/budess.jpg') }}" alt="Dapoer Budess" class="h-16 w-auto object-contain rounded-lg shadow-sm">
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-2 px-3 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                    <span class="text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-gray-900' : 'text-gray-700' }}">Dashboard</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-blue-500' : 'text-gray-600' }}" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                </a>

                <!-- Produk -->
                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.products.*') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                    <span class="text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'text-gray-900' : 'text-gray-700' }}">Produk</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('admin.products.*') ? 'text-blue-500' : 'text-gray-600' }}" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                    </svg>
                </a>

                <!-- Pesanan -->
                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.orders.*') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                    <span class="text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'text-gray-900' : 'text-gray-700' }}">Pesanan</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('admin.orders.*') ? 'text-blue-500' : 'text-gray-600' }}" viewBox="0 0 20 20" fill="currentColor">
                         <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                         <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                    </svg>
                </a>

                 <!-- Pesan -->
                <a href="{{ route('admin.messages.index') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.messages.*') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                    <span class="text-sm font-medium {{ request()->routeIs('admin.messages.*') ? 'text-gray-900' : 'text-gray-700' }}">Pesan</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('admin.messages.*') ? 'text-blue-500' : 'text-gray-600' }}" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                    </svg>
                </a>

                <!-- Laporan -->
                <a href="{{ route('admin.reports') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.reports*') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                    <span class="text-sm font-medium {{ request()->routeIs('admin.reports*') ? 'text-gray-900' : 'text-gray-700' }}">Laporan</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('admin.reports*') ? 'text-blue-500' : 'text-gray-600' }}" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                    </svg>
                </a>

                 <!-- Keluar -->
                 <div class="pt-4 border-t border-gray-100 mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors group">
                            <span class="text-sm font-medium">Keluar</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden bg-gray-100 relative">
            <!-- Scrollable Page Content -->
            <main class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <!-- Session Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-emerald-100 border-l-4 border-emerald-500 p-4 rounded-r shadow-sm flex items-center gap-3">
                        <p class="text-emerald-800 font-bold">{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 bg-rose-100 border-l-4 border-rose-500 p-4 rounded-r shadow-sm flex items-center gap-3">
                        <p class="text-rose-800 font-bold">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fade-in-down 0.4s ease-out; }
    </style>
</body>
</html>
