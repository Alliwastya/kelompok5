<header class="fixed top-0 left-0 right-0 w-full bg-gray-900/98 backdrop-blur-lg px-[5%] py-3 shadow-md z-[10000] flex justify-between items-center border-b border-orange-400/10 transition-all duration-300">
    <!-- Logo -->
    <div class="flex items-center gap-3 flex-1">
        <div class="w-12 h-12 bg-cream rounded-full flex items-center justify-center text-2xl shadow-lg">
            🍞
        </div>
        <span class="font-playfair text-2xl font-bold text-cream tracking-wide">
            Dapoer Budess
        </span>
    </div>

    <!-- Actions -->
    <div class="flex gap-5 items-center relative z-[10001]">
        <!-- Cart Button -->
        <button onclick="toggleCart()" class="relative flex items-center gap-2 bg-transparent border-none px-3 py-2 rounded-lg cursor-pointer font-medium transition-all duration-300 text-cream hover:text-orange-400 hover:-translate-y-0.5 hover:bg-white/5">
            <span class="text-xl">🛒</span>
            <span class="font-outfit">Keranjang</span>
            <span id="cartCount" class="cart-count absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold hidden">
                0
            </span>
        </button>

        <!-- Message Button -->
        <button onclick="openMessageModal()" class="relative flex items-center gap-2 bg-transparent border-none px-3 py-2 rounded-lg cursor-pointer font-medium transition-all duration-300 text-cream hover:text-orange-400 hover:-translate-y-0.5 hover:bg-white/5">
            <span class="text-xl">💬</span>
            <span class="font-outfit">Pesan</span>
            <span id="msgBadge" class="cart-count absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold hidden">
                0
            </span>
        </button>

        @auth
            @if(auth()->user()->is_admin)
                <!-- Admin Button -->
                <a href="/admin/dashboard" class="flex items-center gap-2 bg-transparent border-none px-3 py-2 rounded-lg cursor-pointer font-medium transition-all duration-300 text-cream hover:text-orange-400 hover:-translate-y-0.5 hover:bg-white/5">
                    <span class="text-xl">⚙️</span>
                    <span class="font-outfit">Admin</span>
                </a>
            @endif
        @endauth
    </div>
</header>

<style>
header.scrolled {
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    background: rgba(44, 24, 16, 1);
}
</style>
