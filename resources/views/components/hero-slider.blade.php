<div class="relative w-full h-[95vh] min-h-[650px] overflow-hidden bg-gray-900 -mt-20 pt-20 z-2">
    <!-- Slide 1 -->
    <div class="slide active absolute inset-0 opacity-0 transition-opacity duration-1000 flex items-center justify-start px-[8%] bg-cover bg-center" 
         style="background-image: url('https://images.unsplash.com/photo-1509440159596-0249088772ff?w=1920');">
        <div class="slide-1 absolute inset-0"></div>
        <div class="slide-content relative z-10 max-w-[650px] text-white pl-8">
            <h1 class="font-playfair text-5xl font-bold leading-tight mb-6 drop-shadow-lg opacity-0 translate-y-8 transition-all duration-800 delay-300">
                Roti Rumahan Berkualitas Premium
            </h1>
            <p class="font-lora text-xl leading-relaxed mb-10 drop-shadow-md max-w-[550px] opacity-0 translate-y-8 transition-all duration-800 delay-500">
                Dibuat dengan bahan pilihan terbaik, tanpa pengawet, segar setiap hari untuk keluarga Indonesia
            </p>
            <a href="#products" class="hero-btn inline-flex items-center gap-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-10 py-4 rounded-full font-semibold uppercase tracking-wide shadow-xl hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition-all duration-300 opacity-0 translate-y-8">
                Pesan Sekarang
            </a>
        </div>
    </div>

    <!-- Slide 2 -->
    <div class="slide absolute inset-0 opacity-0 transition-opacity duration-1000 flex items-center justify-center px-[8%] bg-cover bg-center text-center" 
         style="background-image: url('https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=1920');">
        <div class="slide-2 absolute inset-0"></div>
        <div class="slide-content relative z-10 max-w-[800px] text-white">
            <h1 class="font-playfair text-5xl font-bold leading-tight mb-6 drop-shadow-lg opacity-0 translate-y-8 transition-all duration-800 delay-300">
                Promo Spesial Hari Ini!
            </h1>
            <p class="font-lora text-xl leading-relaxed mb-10 drop-shadow-md opacity-0 translate-y-8 transition-all duration-800 delay-500">
                Dapatkan diskon hingga 30% untuk pembelian paket bundling. Hemat lebih banyak!
            </p>
            <a href="#promo" class="hero-btn inline-flex items-center gap-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-10 py-4 rounded-full font-semibold uppercase tracking-wide shadow-xl hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition-all duration-300 opacity-0 translate-y-8">
                Lihat Promo
            </a>
        </div>
    </div>

    <!-- Slide 3 -->
    <div class="slide absolute inset-0 opacity-0 transition-opacity duration-1000 flex items-center justify-end px-[8%] bg-cover bg-center text-right" 
         style="background-image: url('https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=1920');">
        <div class="slide-3 absolute inset-0"></div>
        <div class="slide-content relative z-10 max-w-[650px] text-white pr-8">
            <h1 class="font-playfair text-5xl font-bold leading-tight mb-6 drop-shadow-lg opacity-0 translate-y-8 transition-all duration-800 delay-300">
                Testimoni Pelanggan Puas
            </h1>
            <p class="font-lora text-xl leading-relaxed mb-10 drop-shadow-md max-w-[550px] ml-auto opacity-0 translate-y-8 transition-all duration-800 delay-500">
                Ribuan pelanggan telah merasakan kelezatan roti kami. Bergabunglah dengan keluarga besar Dapoer Budess!
            </p>
            <a href="#reviews" class="hero-btn inline-flex items-center gap-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-10 py-4 rounded-full font-semibold uppercase tracking-wide shadow-xl hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition-all duration-300 opacity-0 translate-y-8">
                Baca Testimoni
            </a>
        </div>
    </div>

    <!-- Navigation -->
    <button onclick="prevSlide()" class="slider-nav prev-slide absolute left-10 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/15 backdrop-blur-sm border border-white/30 rounded-full flex items-center justify-center text-white text-2xl hover:bg-white/30 hover:scale-110 transition-all duration-300 z-20">
        ‹
    </button>
    <button onclick="nextSlide()" class="slider-nav next-slide absolute right-10 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/15 backdrop-blur-sm border border-white/30 rounded-full flex items-center justify-center text-white text-2xl hover:bg-white/30 hover:scale-110 transition-all duration-300 z-20">
        ›
    </button>

    <!-- Dots -->
    <div class="slider-dots absolute bottom-10 left-1/2 -translate-x-1/2 flex gap-3 z-20">
        <span class="dot w-3 h-3 bg-white/40 rounded-full cursor-pointer transition-all duration-300 hover:bg-white active" onclick="goToSlide(0)"></span>
        <span class="dot w-3 h-3 bg-white/40 rounded-full cursor-pointer transition-all duration-300 hover:bg-white" onclick="goToSlide(1)"></span>
        <span class="dot w-3 h-3 bg-white/40 rounded-full cursor-pointer transition-all duration-300 hover:bg-white" onclick="goToSlide(2)"></span>
    </div>
</div>

<style>
.slide.active { opacity: 1; z-index: 2; pointer-events: auto; }
.slide.active h1,
.slide.active p,
.slide.active .hero-btn { opacity: 1; transform: translateY(0); }
.dot.active { background: white; transform: scale(1.3); box-shadow: 0 0 12px rgba(255,255,255,0.6); }
</style>
