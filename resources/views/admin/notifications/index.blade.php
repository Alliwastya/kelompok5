@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-8 py-6">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Semua Notifikasi</h1>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.notifications.read-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                        Tandai Semua Dibaca
                    </button>
                </form>
                <form action="{{ route('admin.notifications.delete-all') }}" method="POST" class="inline" onsubmit="return confirm('Hapus semua notifikasi?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                        Hapus Semua
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-8 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @forelse($notifications as $notification)
                <div class="p-6 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                    <div class="flex gap-4">
                        <div class="text-4xl">{{ $notification->icon }}</div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-900 text-lg">{{ $notification->title }}</h3>
                                <span class="text-sm text-gray-500">{{ $notification->time_ago }}</span>
                            </div>
                            <p class="text-gray-700 mb-3">{{ $notification->message }}</p>
                            <div class="flex gap-2">
                                @if(!$notification->is_read)
                                    <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                @endif
                                @if($notification->link)
                                    <a href="{{ $notification->link }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                                        Lihat Detail →
                                    </a>
                                @endif
                                <form action="{{ route('admin.notifications.delete', $notification->id) }}" method="POST" class="inline ml-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-semibold" onclick="return confirm('Hapus notifikasi ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-lg font-semibold">Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
