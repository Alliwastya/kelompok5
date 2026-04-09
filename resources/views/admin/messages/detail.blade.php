@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-8 py-6">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.contact.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pesan</h1>
            </div>
            <form action="{{ route('admin.contact.delete', $message->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                    Hapus Pesan
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-8 py-8">
        <!-- Message Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6">
            <!-- Sender Info -->
            <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-200">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                    {{ strtoupper(substr($message->sender_name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900">{{ $message->sender_name }}</h2>
                    <p class="text-gray-600">{{ $message->sender_email }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $message->created_at->format('d M Y, H:i') }} ({{ $message->time_ago }})</p>
                </div>
            </div>

            <!-- Subject -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Subjek</h3>
                <p class="text-xl font-bold text-gray-900">{{ $message->subject }}</p>
            </div>

            <!-- Message -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Pesan</h3>
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
                </div>
            </div>

            <!-- Previous Reply (if exists) -->
            @if($message->reply)
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Balasan Anda</h3>
                    <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                        <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $message->reply }}</p>
                        <p class="text-sm text-gray-500 mt-4">Dibalas pada: {{ $message->replied_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Reply Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">{{ $message->reply ? 'Kirim Balasan Baru' : 'Balas Pesan' }}</h3>
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.contact.reply', $message->id) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Balasan Anda</label>
                    <textarea name="reply" rows="8" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Tulis balasan Anda di sini..."></textarea>
                    @error('reply')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        Kirim Balasan
                    </button>
                    <a href="{{ route('admin.contact.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
