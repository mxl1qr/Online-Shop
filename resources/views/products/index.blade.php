<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRKULA — Circular & Essential Fashion Recommerce</title>
    
    <!-- Google Fonts Trio dari DESIGN.md -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-headline { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-body     { font-family: 'Montserrat', sans-serif; }
        .font-label    { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f7faf9] text-[#181c1c] antialiased font-body selection:bg-[#002547] selection:text-white pb-14 md:pb-0"
      x-data="{
          mobileMenuOpen: false,
          searchOpen: false,
          wishlist: JSON.parse(localStorage.getItem('sirkula_wishlist') || '[]'),
          toggleWishlist(id) {
              if (this.wishlist.includes(id)) {
                  this.wishlist = this.wishlist.filter(item => item !== id);
              } else {
                  this.wishlist.push(id);
              }
              localStorage.setItem('sirkula_wishlist', JSON.stringify(this.wishlist));
          },
          isWishlisted(id) {
              return this.wishlist.includes(id);
          }
      }">

    <!-- 1. Top Announcement Bar (Trust & Urgency) -->
    <div class="bg-[#002547] text-white text-[11px] py-2 px-4 text-center font-label font-medium tracking-wide flex items-center justify-center space-x-3">
        <span class="inline-flex items-center space-x-1.5">
            <svg class="w-3.5 h-3.5 text-[#85f9bf]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>100% Terkurasi &amp; Higienis Sanitasi Uap Ozon</span>
        </span>
        <span class="text-white/40 hidden sm:inline">&bull;</span>
        <span class="hidden sm:inline">Garansi Keaslian SirkulaVerify</span>
        <span class="text-white/40 hidden md:inline">&bull;</span>
        <span class="hidden md:inline">Gratis Ongkir ke Seluruh Indonesia min. Rp300.000</span>
    </div>

    <!-- 2. Main Navigation Bar (CSS Grid 3 Pilar Presisi 64px) -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-[#e0e3e2]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-[auto_1fr_auto] md:grid-cols-[1fr_auto_1fr] items-center h-16 gap-4">
                
                <!-- PILAR 1: KIRI (Logo Brand & Hamburger Mobile) -->
                <div class="flex items-center space-x-3">
                    <button type="button" 
                            @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="md:hidden w-10 h-10 flex items-center justify-center text-[#002547] hover:bg-[#f1f4f3] transition"
                            aria-label="Buka Menu Navigasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <a href="{{ route('products.index') }}" class="flex items-center space-x-2.5 group">
                        <span class="w-8 h-8 rounded-none bg-[#002547] text-white flex items-center justify-center font-headline font-black text-base tracking-wider">
                            S
                        </span>
                        <div class="flex flex-col">
                            <span class="font-headline font-extrabold text-lg tracking-tight text-[#002547] leading-none">
                                SIRKULA
                            </span>
                            <span class="font-label text-[8px] uppercase tracking-widest text-[#006c47] font-bold mt-0.5">
                                Recommerce
                            </span>
                        </div>
                    </a>
                </div>

                <!-- PILAR 2: TENGAH (Sumbu Simetri Absolut - Kategori Navigasi) -->
                <nav class="hidden md:flex items-center justify-center space-x-8 font-label text-xs uppercase tracking-widest font-semibold">
                    <a href="{{ route('products.index') }}" 
                       class="h-16 inline-flex items-center transition border-b-2 {{ !request('category') ? 'border-[#002547] text-[#002547]' : 'border-transparent text-[#73777f] hover:text-[#002547]' }}">
                        Semua Koleksi
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                           class="h-16 inline-flex items-center transition border-b-2 {{ request('category') == $cat->slug ? 'border-[#002547] text-[#002547]' : 'border-transparent text-[#73777f] hover:text-[#002547]' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </nav>

                <!-- PILAR 3: KANAN (Search, Wishlist, Akun & Cart) -->
                <div class="flex items-center justify-end space-x-1 sm:space-x-2.5 font-label text-xs">
                    
                    <!-- Search Input Ringkas -->
                    <div class="relative hidden sm:block w-36 lg:w-48">
                        <form action="{{ route('products.index') }}" method="GET">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari pakaian..." 
                                   class="w-full bg-[#f1f4f3] border-none text-xs rounded-none py-1.5 pl-8 pr-2 font-body focus:ring-1 focus:ring-[#002547] placeholder-[#73777f]">
                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-[#73777f]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                        </form>
                    </div>

                    <!-- Search Button Mobile -->
                    <button type="button" 
                            @click="searchOpen = !searchOpen" 
                            class="sm:hidden w-10 h-10 flex items-center justify-center text-[#181c1c] hover:text-[#002547] transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>

                    <!-- Wishlist Icon Counter -->
                    <a href="#katalog" 
                       class="w-10 h-10 relative flex items-center justify-center text-[#181c1c] hover:text-[#002547] transition" 
                       title="Wishlist Disimpan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span x-show="wishlist.length > 0" 
                              x-text="wishlist.length" 
                              class="absolute top-1.5 right-1.5 w-3.5 h-3.5 bg-[#006c47] text-white text-[8px] font-bold rounded-full flex items-center justify-center leading-none">
                        </span>
                    </a>

                    <!-- Menu Akun / Admin -->
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.orders.index') }}" class="text-[#006c47] bg-[#85f9bf]/30 px-2 py-1 rounded-none hover:bg-[#85f9bf]/50 transition font-bold uppercase tracking-wider text-[10px]">
                                Admin
                            </a>
                        @endif
                        <a href="{{ route('orders.index') }}" class="w-10 h-10 flex items-center justify-center text-[#181c1c] hover:text-[#002547] transition" title="Pesanan Saya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="h-9 px-2.5 flex items-center text-xs font-semibold uppercase tracking-wider text-[#181c1c] hover:text-[#002547] transition">
                            Masuk
                        </a>
                    @endauth

                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="w-10 h-10 relative flex items-center justify-center text-[#002547] hover:opacity-80 transition" title="Keranjang Belanja">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(count(session('cart', [])) > 0)
                            <span class="absolute top-1.5 right-1.5 w-3.5 h-3.5 bg-[#002547] text-white text-[8px] font-bold rounded-full flex items-center justify-center leading-none">
                                {{ count(session('cart', [])) }}
                            </span>
                        @endif
                    </a>

                </div>

            </div>
        </div>

        <!-- Mobile Search Dropdown Bar -->
        <div x-show="searchOpen" x-cloak class="sm:hidden px-4 py-2 bg-white border-t border-[#e0e3e2]">
            <form action="{{ route('products.index') }}" method="GET">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari pakaian esensial..." 
                       class="w-full bg-[#f1f4f3] border-none text-xs rounded-none py-2 px-3 font-body focus:ring-1 focus:ring-[#002547]">
            </form>
        </div>

        <!-- Mobile Drawer Navigation (100% Fullscreen Overlay) -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden fixed inset-0 z-50 bg-black/50" @click="mobileMenuOpen = false"></div>
        <div x-show="mobileMenuOpen" 
             x-cloak 
             class="md:hidden fixed inset-y-0 left-0 z-50 w-72 bg-white p-6 shadow-xl flex flex-col justify-between overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-[#e0e3e2] mb-6">
                    <span class="font-headline font-extrabold text-base text-[#002547]">SIRKULA MENU</span>
                    <button type="button" @click="mobileMenuOpen = false" class="w-10 h-10 flex items-center justify-center text-[#181c1c] text-lg font-bold">&times;</button>
                </div>
                <nav class="space-y-4 font-label text-sm uppercase tracking-wider font-semibold">
                    <a href="{{ route('products.index') }}" class="block py-2 text-[#002547]">Semua Koleksi</a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                           class="block py-2 {{ request('category') == $cat->slug ? 'text-[#002547] font-bold' : 'text-[#73777f]' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                    <div class="pt-4 border-t border-[#e0e3e2] space-y-3 font-normal normal-case text-xs text-[#43474e]">
                        <a href="{{ route('orders.index') }}" class="block py-1">Lacak Pesanan Saya</a>
                        <a href="#sirkulacare" @click="mobileMenuOpen = false" class="block py-1">Standar Sanitasi SirkulaCare</a>
                    </div>
                </nav>
            </div>
            <div class="pt-6 border-t border-[#e0e3e2]">
                @auth
                    <p class="text-xs text-[#73777f] mb-2">Halo, <strong>{{ Auth::user()->name }}</strong></p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-label uppercase font-bold text-red-600">Keluar Akun</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-center py-2.5 bg-[#002547] text-white font-label text-xs uppercase font-bold">Masuk Akun</a>
                @endauth
            </div>
        </div>

    </header>

    <!-- 3. HERO EDITORIAL FULL-BLEED (Ala Uniqlo x H&M) -->
    @if(!request('category') && !request('search') && !request('size'))
    <section class="relative w-full h-[460px] sm:h-[540px] lg:h-[620px] bg-[#002547] overflow-hidden flex items-center">
        <!-- Background Editorial Fashion Photo -->
        <img src="{{ asset('storage/banners/hero-drop01.jpg') }}" 
             alt="Sirkula Drop #01 Editorial Lookbook" 
             class="absolute inset-0 w-full h-full object-cover object-center opacity-65">
        
        <!-- Gradient Overlay Tonal (#002547) -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#000f23]/90 via-[#002547]/60 to-transparent"></div>

        <!-- Konten Editorial Teks -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
            <div class="max-w-xl space-y-4">
                <span class="inline-flex items-center space-x-2 bg-[#85f9bf]/20 border border-[#85f9bf] text-[#85f9bf] font-label font-bold text-[10px] uppercase tracking-widest px-3 py-1">
                    <span>ARCHIVE RECOMMERCE DROP #01</span>
                </span>

                <h1 class="font-headline text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.1]">
                    Gaya Esensial.<br>Sirkular Tanpa Kompromi.
                </h1>

                <p class="font-body text-xs sm:text-sm text-white/80 max-w-lg leading-relaxed">
                    Pakaian terkurasi tangan pertama dari surplus ekspor dan arsip pilihan. Melewati sanitasi medis uap &amp; ozon 100°C, higienis siap pakai, hemat hingga 70% dari harga retail toko.
                </p>

                <div class="pt-4 flex flex-wrap gap-3 font-label text-xs uppercase tracking-wider font-bold">
                    <a href="#katalog" class="bg-white hover:bg-[#f1f4f3] text-[#002547] px-7 py-3.5 transition shadow-sm">
                        Eksplorasi Koleksi &darr;
                    </a>
                    <a href="#sirkulacare" class="bg-transparent hover:bg-white/10 text-white border border-white/50 px-6 py-3.5 transition">
                        Standar SirkulaCare
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. CARI BERDASARKAN KATEGORI (Category Visual Tiles ala Uniqlo) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6">
        <div class="flex items-baseline justify-between mb-6">
            <h2 class="font-headline text-lg sm:text-xl font-bold text-[#002547] tracking-tight">
                Cari Berdasarkan Kategori
            </h2>
            <span class="font-label text-xs text-[#73777f]">Kurasi Terverifikasi</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('products.index', ['category' => 'kaos-t-shirt']) }}" 
               class="group relative aspect-[4/3] bg-[#f4f6f5] overflow-hidden border border-[#e0e3e2] flex items-end p-4">
                <img src="{{ asset('storage/banners/cat-kaos.jpg') }}" alt="Kaos & T-Shirt" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="relative z-10 text-white">
                    <span class="font-headline font-bold text-sm block">Kaos &amp; T-Shirt</span>
                    <span class="font-label text-[10px] text-white/80 uppercase tracking-wider">Heavyweight &amp; Boxy Fit</span>
                </div>
            </a>

            <a href="{{ route('products.index', ['category' => 'kemeja']) }}" 
               class="group relative aspect-[4/3] bg-[#f4f6f5] overflow-hidden border border-[#e0e3e2] flex items-end p-4">
                <img src="{{ asset('storage/banners/cat-kemeja.jpg') }}" alt="Kemeja Casual" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="relative z-10 text-white">
                    <span class="font-headline font-bold text-sm block">Kemeja Casual</span>
                    <span class="font-label text-[10px] text-white/80 uppercase tracking-wider">Linen &amp; Flannel Tartan</span>
                </div>
            </a>

            <a href="{{ route('products.index', ['category' => 'outerwear-jaket']) }}" 
               class="group relative aspect-[4/3] bg-[#f4f6f5] overflow-hidden border border-[#e0e3e2] flex items-end p-4">
                <img src="{{ asset('storage/banners/cat-outerwear.jpg') }}" alt="Outerwear & Jaket" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="relative z-10 text-white">
                    <span class="font-headline font-bold text-sm block">Outerwear &amp; Jaket</span>
                    <span class="font-label text-[10px] text-white/80 uppercase tracking-wider">Chore Canvas &amp; Selvedge</span>
                </div>
            </a>

            <a href="{{ route('products.index', ['category' => 'celana-bawahan']) }}" 
               class="group relative aspect-[4/3] bg-[#f4f6f5] overflow-hidden border border-[#e0e3e2] flex items-end p-4">
                <img src="{{ asset('storage/banners/cat-celana.jpg') }}" alt="Celana & Bawahan" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="relative z-10 text-white">
                    <span class="font-headline font-bold text-sm block">Celana &amp; Bawahan</span>
                    <span class="font-label text-[10px] text-white/80 uppercase tracking-wider">Pleated Chino &amp; Denim</span>
                </div>
            </a>
        </div>
    </section>
    @endif

    <!-- 5. MAIN PRODUCT LISTING (PLP) & FILTER TOOLBAR -->
    <main id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        
        <!-- Header Judul Koleksi & Filter Toolbar -->
        <div class="border-b border-[#e0e3e2] pb-5 mb-8 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2">
                <div>
                    <h2 class="font-headline text-xl sm:text-2xl font-extrabold text-[#002547] tracking-tight">
                        {{ request('category') ? $categories->where('slug', request('category'))->first()?->name : 'Koleksi Drop Pilihan' }}
                    </h2>
                    <p class="font-body text-xs text-[#73777f] mt-0.5">Pakaian kurasi tangan pertama dengan penilaian kondisi transparan.</p>
                </div>
                <span class="font-label text-xs font-semibold text-[#006c47] bg-[#85f9bf]/30 px-3 py-1">
                    {{ $products->total() }} Produk Siap Dikirim
                </span>
            </div>

            <!-- Toolbar Filter Chips & Sort (Sesuai Benchmark PLP Uniqlo & H&M) -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                
                <!-- Filter Ukuran Pakaian -->
                <div class="flex items-center space-x-1.5 font-label text-xs">
                    <span class="text-[#73777f] uppercase tracking-wider font-semibold mr-1">Ukuran:</span>
                    <a href="{{ route('products.index', array_merge(request()->except('size'), [])) }}" 
                       class="px-2.5 py-1 border transition {{ !request('size') ? 'bg-[#002547] text-white border-[#002547]' : 'bg-white text-[#181c1c] border-[#c3c6cf] hover:border-[#002547]' }}">
                        Semua
                    </a>
                    @foreach (['S', 'M', 'L', 'XL'] as $size)
                        <a href="{{ route('products.index', array_merge(request()->all(), ['size' => $size])) }}" 
                           class="px-2.5 py-1 border transition {{ request('size') == $size ? 'bg-[#002547] text-white border-[#002547]' : 'bg-white text-[#181c1c] border-[#c3c6cf] hover:border-[#002547]' }}">
                            {{ $size }}
                        </a>
                    @endforeach
                </div>

                <!-- Sort Dropdown -->
                <form action="{{ route('products.index') }}" method="GET" class="flex items-center space-x-2 font-label text-xs">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('size'))
                        <input type="hidden" name="size" value="{{ request('size') }}">
                    @endif
                    <label for="sort" class="text-[#73777f] uppercase tracking-wider font-semibold">Urutkan:</label>
                    <select name="sort" 
                            id="sort" 
                            onchange="this.form.submit()" 
                            class="bg-white border border-[#c3c6cf] text-xs py-1.5 pl-2.5 pr-8 font-body focus:ring-1 focus:ring-[#002547]">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Rilisan Terbaru</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </form>

            </div>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-20 bg-white border border-[#e0e3e2] p-8">
                <svg class="w-12 h-12 text-[#73777f] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p class="font-headline font-bold text-sm text-[#002547]">Belum ada pakaian di kategori atau ukuran ini.</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-4 font-label text-xs uppercase tracking-wider font-bold bg-[#002547] text-white px-5 py-2.5">
                    Reset Filter
                </a>
            </div>
        @else
            <!-- Grid dengan Vertical Breathing Room 56px (gap-y-14) & BORDERLESS CARDS DENGAN FOTO ASLI -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-5 sm:gap-x-7 gap-y-10 sm:gap-y-14">
                @foreach ($products as $product)
                    <article class="group flex flex-col cursor-pointer relative">
                        
                        <!-- 1. Media Container: Borderless 3:4 Aspect Ratio dengan Hover Photo Transition -->
                        <div class="relative w-full aspect-[3/4] bg-[#f4f6f5] overflow-hidden">
                            <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
                                @if($product->image)
                                    <!-- Foto Utama (Lookbook Depan) -->
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         loading="lazy"
                                         class="w-full h-full object-cover object-center transition duration-500 group-hover:scale-102">

                                    <!-- Foto Kedua Saat Hover (Lookbook Detail/Angle 2) -->
                                    @if($product->secondary_image && $product->secondary_image !== $product->image)
                                        <img src="{{ asset('storage/' . $product->secondary_image) }}" 
                                             alt="{{ $product->name }} Detail" 
                                             loading="lazy"
                                             class="absolute inset-0 w-full h-full object-cover object-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    @endif
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-[#73777f]">
                                        <svg class="w-10 h-10 text-[#c3c6cf]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        <span class="font-label text-[11px] uppercase tracking-wider text-[#73777f] mt-1">{{ $product->category->name }}</span>
                                    </div>
                                @endif
                            </a>

                            <!-- Kondisi Grade Pill (Minimalis ala Uniqlo) -->
                            <div class="absolute top-2.5 left-2.5 pointer-events-none">
                                <span class="bg-white/95 backdrop-blur-xs text-[#006c47] font-label font-bold text-[10px] uppercase tracking-widest px-2.5 py-1 border border-[#85f9bf]/50 shadow-2xs">
                                    Grade A+ &bull; Like New
                                </span>
                            </div>

                            <!-- Tombol Wishlist Heart (44x44 Tap Target) -->
                            <button type="button" 
                                    @click="toggleWishlist({{ $product->id }})" 
                                    class="absolute top-2.5 right-2.5 w-9 h-9 bg-white/90 hover:bg-white text-[#181c1c] rounded-full flex items-center justify-center shadow-xs transition"
                                    :title="isWishlisted({{ $product->id }}) ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist'">
                                <svg class="w-4 h-4 transition" 
                                     :class="isWishlisted({{ $product->id }}) ? 'text-red-600 fill-current' : 'text-[#73777f]'"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- 2. Typography & Metadata (Mengalir Bebas Tanpa Kotak Kaku) -->
                        <div class="pt-3.5 flex flex-col flex-1 justify-between">
                            <div>
                                <!-- Color Swatches (Clean Dots) -->
                                <div class="flex items-center space-x-1.5 mb-2">
                                    @foreach($product->variants->pluck('color')->unique() as $color)
                                        <span class="w-3 h-3 rounded-full border border-[#dcdcdc] inline-block shadow-2xs"
                                              style="background-color: {{ strtolower($color) == 'putih' || str_contains(strtolower($color), 'white') ? '#ffffff' : (strtolower($color) == 'hitam' || str_contains(strtolower($color), 'black') ? '#181c1c' : (str_contains(strtolower($color), 'navy') || str_contains(strtolower($color), 'indigo') ? '#002547' : (str_contains(strtolower($color), 'green') || str_contains(strtolower($color), 'sage') || str_contains(strtolower($color), 'olive') ? '#006c47' : (str_contains(strtolower($color), 'terracotta') || str_contains(strtolower($color), 'brown') ? '#a0522d' : '#888888')))) }}"
                                              title="{{ $color }}">
                                        </span>
                                    @endforeach
                                </div>

                                <!-- Category & Available Sizes (Font >= 12px) -->
                                <p class="font-label text-xs text-[#73777f] uppercase tracking-wider mb-1">
                                    {{ $product->category->name }} &bull; {{ $product->variants->pluck('size')->unique()->implode(', ') }}
                                </p>

                                <!-- Product Title (14px/600 Plus Jakarta Sans) -->
                                <h3 class="font-headline text-sm font-semibold text-[#181c1c] leading-snug line-clamp-2 group-hover:text-[#002547] transition">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                            </div>

                            <!-- Price Row (Dopamine Anchor: Coret Harga Mall vs Harga Sirkula) -->
                            <div class="mt-3 flex items-baseline space-x-2.5">
                                <span class="font-label text-sm sm:text-base font-bold text-[#002547]">
                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                </span>
                                <span class="font-label text-xs text-[#73777f] line-through">
                                    Rp {{ number_format($product->base_price * 2.2, 0, ',', '.') }}
                                </span>
                                <span class="font-label text-xs font-bold text-[#006c47]">
                                    -55%
                                </span>
                            </div>
                        </div>

                    </article>
                @endforeach
            </div>

            <div class="mt-14">
                {{ $products->links() }}
            </div>
        @endif

    </main>

    <!-- 6. SIRKULACARE TRUST & SANITASI STORY BANNER (Ala H&M / Uniqlo Editorial) -->
    <section id="sirkulacare" class="bg-white border-y border-[#e0e3e2] my-16 py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <div class="lg:col-span-6 space-y-4">
                    <span class="font-label text-xs uppercase tracking-widest text-[#006c47] font-bold">
                        STANDAR SANITASI MEDIS SIRKULACARE
                    </span>

                    <h2 class="font-headline text-2xl sm:text-3xl font-extrabold text-[#002547] tracking-tight">
                        Pakaian Bersih Tanpa Kompromi, Terkurasi Seperti Baru.
                    </h2>

                    <p class="font-body text-xs sm:text-sm text-[#43474e] leading-relaxed">
                        Kami tidak sekadar menjual pakaian preloved; setiap potong pakaian di Sirkula melewati proses sanitasi 3 tahap: pencucian uap suhu 100°C, sterilisasi gas ozon pembasmi 99.9% bakteri, dan inspeksi fisik 12 titik oleh kurator profesional.
                    </p>

                    <div class="grid grid-cols-2 gap-4 pt-3 font-body text-xs">
                        <div class="p-3 bg-[#f7faf9] border border-[#e0e3e2]">
                            <span class="font-headline font-bold text-xs text-[#002547] block mb-1">🧼 Bebas Alergen</span>
                            <span class="text-[#73777f]">Aroma segar dan steril, langsung dapat dipakai tanpa dicuci ulang.</span>
                        </div>
                        <div class="p-3 bg-[#f7faf9] border border-[#e0e3e2]">
                            <span class="font-headline font-bold text-xs text-[#002547] block mb-1">🔍 SirkulaVerify QC</span>
                            <span class="text-[#73777f]">Pemeriksaan jahitan kerah, kancing orisinal, dan keaslian garmen.</span>
                        </div>
                        <div class="p-3 bg-[#f7faf9] border border-[#e0e3e2]">
                            <span class="font-headline font-bold text-xs text-[#002547] block mb-1">💧 Hemat 2.500L Air</span>
                            <span class="text-[#73777f]">Memperpanjang siklus hidup pakaian mengurangi jejak emisi tekstil.</span>
                        </div>
                        <div class="p-3 bg-[#f7faf9] border border-[#e0e3e2]">
                            <span class="font-headline font-bold text-xs text-[#002547] block mb-1">🔄 Garansi 3 Hari</span>
                            <span class="text-[#73777f]">Ukuran kurang pas? Layanan tukar pakaian mudah dan ramah.</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="w-full aspect-[4/3] bg-[#f4f6f5] border border-[#e0e3e2] overflow-hidden relative">
                        <img src="{{ asset('storage/banners/sirkulacare-story.jpg') }}" alt="SirkulaCare Sanitasi & Kurasi Workshop" class="w-full h-full object-cover">
                        <div class="absolute bottom-4 left-4 bg-white/95 px-3 py-1.5 font-label text-xs font-bold text-[#002547] border border-[#e0e3e2] shadow-xs">
                            Studio Sanitasi &bull; QC Jakarta Barat
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 7. MOBILE FIXED BOTTOM NAVIGATION BAR (56px ala Uniqlo & H&M Apps) -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white border-t border-[#e0e3e2] h-14 flex items-center justify-around px-2 font-label text-[10px] font-semibold text-[#73777f]">
        <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ !request('category') ? 'text-[#002547] font-bold' : '' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Beranda</span>
        </a>
        <a href="#katalog" class="flex flex-col items-center justify-center flex-1 h-full">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            <span>Koleksi</span>
        </a>
        <a href="#katalog" class="flex flex-col items-center justify-center flex-1 h-full relative">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <span>Wishlist</span>
            <span x-show="wishlist.length > 0" x-text="wishlist.length" class="absolute top-1 right-5 w-3 h-3 bg-[#006c47] text-white text-[7px] font-bold rounded-full flex items-center justify-center"></span>
        </a>
        <a href="{{ route('cart.index') }}" class="flex flex-col items-center justify-center flex-1 h-full relative">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span>Keranjang</span>
            @if(count(session('cart', [])) > 0)
                <span class="absolute top-1 right-5 w-3 h-3 bg-[#002547] text-white text-[7px] font-bold rounded-full flex items-center justify-center">
                    {{ count(session('cart', [])) }}
                </span>
            @endif
        </a>
        <a href="{{ route('orders.index') }}" class="flex flex-col items-center justify-center flex-1 h-full">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Akun</span>
        </a>
    </nav>

    <!-- 8. Comprehensive 4-Column Commercial Footer (Ala Uniqlo & H&M) -->
    <footer class="bg-white border-t border-[#e0e3e2] mt-20 py-16 text-[#43474e] font-body text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-[#e0e3e2]">
            
            <div class="space-y-3">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 bg-[#002547] text-white flex items-center justify-center font-headline font-black text-xs">
                        S
                    </span>
                    <span class="font-headline font-bold text-[#002547] text-base">SIRKULA</span>
                </div>
                <p class="text-xs text-[#73777f] leading-relaxed">
                    Platform recommerce fashion sirkular yang mendigitalkan nilai kearifan lungsuran. Memperpanjang siklus pakai pakaian berkualitas untuk masa depan yang lebih lestari.
                </p>
            </div>

            <div>
                <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-[#002547] mb-3">Koleksi Pakaian</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('products.index', ['category' => 'kaos-t-shirt']) }}" class="hover:underline">Kaos &amp; T-Shirt Boxy</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'kemeja']) }}" class="hover:underline">Kemeja Casual &amp; Flannel</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'outerwear-jaket']) }}" class="hover:underline">Outerwear &amp; Jaket</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'celana-bawahan']) }}" class="hover:underline">Celana Chino &amp; Denim</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-[#002547] mb-3">Layanan &amp; Bantuan</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#sirkulacare" class="hover:underline">Standar Sanitasi SirkulaCare</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:underline">Lacak Status Pesanan</a></li>
                    <li><a href="#" class="hover:underline">Kebijakan Pengembalian 3 Hari</a></li>
                    <li><a href="#" class="hover:underline">Panduan Ukuran Fisik Baju</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-[#002547] mb-3">Program Lungsuran</h4>
                <p class="text-xs text-[#73777f] leading-relaxed mb-3">
                    Punya pakaian berkualitas di lemari yang jarang terpakai? Titip jual di Sirkula dan peroleh penghasilan.
                </p>
                <a href="#" class="inline-block font-label font-bold text-[11px] uppercase tracking-wider bg-[#002547] text-white px-3.5 py-2">
                    Titip Jual Baju &rarr;
                </a>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-[#73777f] gap-3">
            <p>&copy; {{ date('Y') }} PT SIRKULA INDONESIA LESTARI. Hak cipta dilindungi undang-undang.</p>
            <p class="font-label">Metode Pembayaran: Transfer BCA &bull; Mandiri &bull; BRI</p>
        </div>
    </footer>

</body>
</html>
