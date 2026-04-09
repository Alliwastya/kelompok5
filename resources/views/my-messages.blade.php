<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Saya - Dapoer Budess</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .chat-bubble {
            max-width: 80%;
            word-wrap: break-word;
        }
        .user-message {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
        }
        .admin-message {
            background: #f3e5f5;
            border-left: 4px solid #9c27b0;
        }
        .message-time {
            font-size: 0.75rem;
            color: #999;
        }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-amber-600 text-white py-4 px-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-2xl font-bold">🍞 Dapoer Budess</a>
            <a href="/" class="bg-amber-700 hover:bg-amber-800 px-4 py-2 rounded">Kembali Ke Toko</a>
        </div>
    </nav>

    <div class="container mx-auto max-w-2xl py-8 px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">💬 Pesan Saya</h1>
            <p class="text-gray-600 mb-6">Lihat percakapan Anda dengan admin</p>

            {{-- Pencarian berdasarkan nomor telepon --}}
            @if(!request('phone'))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Masukkan Nomor Telepon</h2>
                <form method="GET" class="flex gap-2">
                    <input 
                        type="tel" 
                        name="phone" 
                        placeholder="Contoh: 628123456789" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        required
                    >
                    <button 
                        type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold"
                    >
                        Cari
                    </button>
                </form>
            </div>
            @endif

            {{-- Chat History --}}
            @if(request('phone'))
            <div class="mb-4 pb-4 border-b border-gray-200">
                <p class="text-sm text-gray-600">
                    Menampilkan pesan untuk: <span class="font-bold text-gray-800">{{ request('phone') }}</span>
                    <a href="/my-messages" class="ml-2 text-blue-500 hover:text-blue-700">Ubah</a>
                </p>
            </div>

            @if($messages->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">📭 Belum ada pesan</p>
                <p class="text-gray-400 text-sm mt-2">Kirim pesan ke admin dari halaman utama</p>
            </div>
            @else
            <div class="space-y-4 mb-6 max-h-[600px] overflow-y-auto">
                @foreach($messages->sortBy('created_at') as $message)
                    {{-- User Message --}}
                    <div class="flex justify-end">
                        <div class="chat-bubble user-message p-4 rounded-lg">
                            <p class="font-semibold text-sm text-blue-900">{{ $message->name }}</p>
                            <p class="text-gray-800 mt-2">{{ $message->user_message }}</p>
                            <p class="message-time mt-2">{{ $message->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    {{-- Admin Reply (if exists) --}}
                    @if($message->admin_message)
                    <div class="flex justify-start mt-3">
                        <div class="chat-bubble admin-message p-4 rounded-lg">
                            <p class="font-semibold text-sm text-purple-900">👨‍💼 Admin Dapoer Budess</p>
                            <p class="text-gray-800 mt-2">{{ $message->admin_message }}</p>
                            <p class="message-time mt-2">{{ $message->replied_at?->format('d M Y H:i') ?? 'Belum dibaca' }}</p>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            {{-- Send Reply Form --}}
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <p class="text-sm text-gray-600 mb-3">Gunakan tombol WhatsApp di bawah untuk menghubungi kami lebih cepat:</p>
                <a 
                    href="https://wa.me/62{{ substr(request('phone'), -10) }}?text=Halo%20Dapoer%20Budess" 
                    target="_blank" 
                    class="inline-block bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold"
                >
                    💬 Chat via WhatsApp
                </a>
            </div>
            @endif
            @endif
        </div>
    </div>
</body>
</html>
