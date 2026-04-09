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
                <h1 class="text-3xl font-bold text-gray-900">Inbox - Pesan Kontak</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-8 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @forelse($messages as $message)
                <a href="{{ route('admin.contact.show', $message->id) }}" class="block p-6 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ !$message->is_read ? 'bg-blue-50' : '' }}">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($message->sender_name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $message->sender_name }}</h3>
                                <p class="text-sm text-gray-500">{{ $message->sender_email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm text-gray-500">{{ $message->time_ago }}</span>
                            @if(!$message->is_read)
                                <div class="mt-1">
                                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">{{ $message->subject }}</h4>
                    <p class="text-gray-600 line-clamp-2">{{ $message->message }}</p>
                    @if($message->reply)
                        <div class="mt-3 flex items-center gap-2 text-sm text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="font-semibold">Sudah dibalas</span>
                        </div>
                    @endif
                </a>
            @empty
                <div class="p-12 text-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <p class="text-lg font-semibold">Tidak ada pesan</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection
