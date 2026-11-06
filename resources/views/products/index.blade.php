<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRKULA — Circular & Essential Fashion Recommerce</title>
    
    <!-- Google Fonts Duo: Plus Jakarta Sans + Inter -->
    @include('partials.head-fonts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
@php
    $storeConfig = \App\Http\Controllers\AdminOrderController::getStoreConfig();
    $activeCoupons = $storeConfig['coupons'] ?? [];
    $firstCoupon = !empty($activeCoupons) ? $activeCoupons[0] : null;
    $freeShippingThreshold = $storeConfig['free_shipping_threshold'] ?? 250000;
@endphp
<body class="bg-surface text-ink antialiased font-body selection:bg-navy selection:text-white"
      @keydown.escape.window="activeMegaMenu = null; searchOpen = false; mobileMenuOpen = false"
      @scroll.window.passive="handleScroll()"
      x-data="{
          mobileMenuOpen: false,
          searchOpen: false,
          activeMegaMenu: null,
          megaMenuTimer: null,
          showFloatingFilter: false,
          scrollingDown: false,
          lastScrollY: 0,
          handleScroll() {
              const currentY = window.scrollY;
              this.scrollingDown = currentY > this.lastScrollY && currentY > 350;
              this.lastScrollY = currentY;
              const catalogEl = document.getElementById('katalog');
              const threshold = catalogEl ? catalogEl.offsetTop + 180 : 350;
              this.showFloatingFilter = currentY > threshold;
          },
          openMega(slug) {
              clearTimeout(this.megaMenuTimer);
              this.activeMegaMenu = slug;
              this.searchOpen = false;
          },
          closeMega() {
              this.megaMenuTimer = setTimeout(() => {
                  this.activeMegaMenu = null;
              }, 160);
          },
          wishlist: JSON.parse(localStorage.getItem('sirkula_wishlist_{{ auth()->id() ?? 'guest' }}') || '[]'),
          toggleWishlist(id) {
              if (this.wishlist.includes(id)) {
                  this.wishlist = this.wishlist.filter(item => item !== id);
              } else {
                  this.wishlist.push(id);
              }
              localStorage.setItem('sirkula_wishlist_{{ auth()->id() ?? 'guest' }}', JSON.stringify(this.wishlist));
          },
          isWishlisted(id) {
              return this.wishlist.includes(id);
          }
      }">

    <!-- 1. Top Announcement Bar (Dynamic from Store Config + Core SirkulaVerify Promise) -->
    <div class="bg-navy text-white text-[11px] sm:text-xs py-2 px-4 text-center font-label font-medium tracking-wide flex items-center justify-center gap-2 sm:gap-3">
        <span class="inline-flex items-center space-x-1.5">
            <svg class="w-3.5 h-3.5 text-emerald-soft shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>100% Terkurasi Sanitasi Uap Ozon 100°C</span>
        </span>
        <span class="text-white/40 hidden sm:inline">&bull;</span>
        <span class="hidden sm:inline font-semibold text-emerald-soft">Garansi Keaslian SirkulaVerify</span>
        <span class="text-white/40 hidden md:inline">&bull;</span>
        <span class="hidden md:inline">
            {{ !empty($storeConfig['announcement_banner']) ? $storeConfig['announcement_banner'] : ('Gratis Ongkir ke Seluruh Indonesia min. Rp' . number_format($freeShippingThreshold, 0, ',', '.')) }}
        </span>
    </div>

    <!-- 2. Main Navigation Bar (3 Pilar Presisi Symmetrical 56px + Hover Mega Menu ala Uniqlo) -->
    <header class="sticky top-0 z-50 bg-white border-b border-line relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16 gap-2 sm:gap-6">
                
                <!-- PILAR 1: KIRI (Logo Brand Asli Sirkula & Hamburger Mobile/Tablet) -->
                <div class="flex items-center gap-2 sm:gap-3 shrink-0" @mouseenter="activeMegaMenu = null">
                    <button type="button" 
                            @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="lg:hidden w-10 h-10 rounded-lg flex items-center justify-center text-navy hover:bg-surface-low transition cursor-pointer"
                            aria-label="Buka Menu Navigasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <a href="{{ route('products.index') }}" class="group shrink-0 flex items-center">
                        @include('partials.brand-logo', ['size' => 'sm'])
                    </a>
                </div>

                <!-- PILAR 2: TENGAH (Sumbu Simetri - Kategori Navigasi 1 Baris Utuh + Hover Trigger Mega Menu) -->
                <nav class="hidden lg:flex items-center justify-center gap-5 xl:gap-7 whitespace-nowrap font-label text-sm font-semibold"
                     @mouseleave="closeMega()">
                    <a href="{{ route('products.index') }}" 
                       @mouseenter="openMega('all')"
                       :class="activeMegaMenu ? (activeMegaMenu === 'all' ? 'border-navy text-navy' : 'border-transparent text-ink/80') : '{{ !request('category') ? 'border-navy text-navy' : 'border-transparent text-ink/80 hover:text-navy' }}'"
                       class="h-14 sm:h-16 inline-flex items-center whitespace-nowrap transition border-b-2">
                        Semua Koleksi
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                           @mouseenter="openMega('{{ $cat->slug }}')"
                           :class="activeMegaMenu ? (activeMegaMenu === '{{ $cat->slug }}' ? 'border-navy text-navy' : 'border-transparent text-ink/80') : '{{ request('category') == $cat->slug ? 'border-navy text-navy' : 'border-transparent text-ink/80 hover:text-navy' }}'"
                           class="h-14 sm:h-16 inline-flex items-center whitespace-nowrap transition border-b-2">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </nav>

                <!-- PILAR 3: KANAN (Search, Wishlist, Akun & Cart - Symmetrical 40px Touch Targets) -->
                <div class="flex items-center justify-end gap-1 sm:gap-2 shrink-0 font-label text-sm">
                    
                    <!-- Search Input Tunggal di Desktop Header -->
                    <div class="relative hidden lg:block w-44 xl:w-56">
                        <form action="{{ route('products.index') }}" method="GET">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Apa yang Anda cari?" 
                                   class="w-full bg-surface-low border border-transparent focus:border-navy focus:bg-white text-sm rounded-full py-2 pl-8 pr-3 font-body focus:ring-2 focus:ring-emerald/20 placeholder-ink/80 transition">
                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-ink/80">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                        </form>
                    </div>

                    <!-- Search Button Mobile & Tablet -->
                    <button type="button" 
                            @click="searchOpen = !searchOpen" 
                            aria-label="Cari Pakaian"
                            class="lg:hidden w-10 h-10 rounded-lg flex items-center justify-center text-ink hover:text-navy hover:bg-surface-low transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>

                    <!-- Wishlist Icon Counter (Link ke Halaman /wishlist) -->
                    <a href="{{ route('wishlist.index') }}" 
                       aria-label="Daftar Favorit"
                       class="w-10 h-10 rounded-lg relative flex items-center justify-center text-ink hover:text-navy hover:bg-surface-low transition" 
                       title="Wishlist Disimpan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span x-show="wishlist.length > 0" 
                              x-text="wishlist.length" 
                              class="absolute top-1 right-1 min-w-4 h-4 px-1 bg-emerald text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
                        </span>
                    </a>

                    <!-- Menu Akun / Member Hub -->
                    @auth
                        <div x-data="{ accountOpen: false }" class="relative">
                            <button type="button" 
                                    @click="accountOpen = !accountOpen" 
                                    @click.outside="accountOpen = false"
                                    class="h-10 px-2 sm:px-2.5 rounded-lg inline-flex items-center gap-1.5 text-ink hover:text-navy hover:bg-surface-low transition cursor-pointer" 
                                    aria-label="Menu Akun">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-6 h-6 rounded-full object-cover shrink-0">
                                @else
                                    <svg class="w-5 h-5 shrink-0 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                                <span class="hidden xl:inline font-semibold text-sm max-w-[96px] truncate">{{ \Illuminate\Support\Str::words(Auth::user()->name, 1, '') }}</span>
                                <svg class="w-3.5 h-3.5 text-muted transition-transform hidden sm:inline" :class="accountOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="accountOpen" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 mt-2 w-52 bg-white border border-line rounded-2xl shadow-card py-2 z-50 text-sm">
                                <div class="px-3.5 py-2 border-b border-line">
                                    <p class="font-bold text-ink truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-muted truncate">{{ Auth::user()->email }}</p>
                                </div>
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 px-3.5 py-2 text-emerald font-semibold hover:bg-surface-low transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                                        <span>Panel Admin CMS</span>
                                    </a>
                                @endif
                                <a href="{{ route('member') }}" class="flex items-center gap-2 px-3.5 py-2 text-ink hover:text-navy hover:bg-surface-low transition">
                                    <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>Area Member</span>
                                </a>
                                <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-3.5 py-2 text-ink hover:text-navy hover:bg-surface-low transition">
                                    <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span>Riwayat Pesanan</span>
                                </a>
                                <div class="border-t border-line my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2 px-3.5 py-2 text-red-600 hover:bg-red-50 transition cursor-pointer font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex h-10 px-3.5 rounded-lg items-center text-sm font-semibold text-ink hover:text-navy hover:bg-surface-low whitespace-nowrap transition">
                            Masuk
                        </a>
                    @endauth

                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="w-10 h-10 rounded-lg relative flex items-center justify-center text-navy hover:bg-surface-low transition" title="Keranjang Belanja">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(count(session('cart', [])) > 0)
                            <span class="absolute top-1 right-1 min-w-4 h-4 px-1 bg-navy text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
                                {{ count(session('cart', [])) }}
                            </span>
                        @endif
                    </a>

                </div>

            </div>
        </div>

        <!-- Mobile Quick Gender/Category Nav Tabs (Ala Uniqlo Mobile Tab Bar) -->
        <div class="lg:hidden border-t border-line bg-white overflow-x-auto scrollbar-none px-4 flex items-center gap-5 whitespace-nowrap text-xs font-label font-bold">
            <a href="{{ route('products.index') }}" 
               class="py-2.5 border-b-2 transition {{ !request('category') ? 'border-navy text-navy' : 'border-transparent text-ink/70 hover:text-navy' }}">
                Semua Koleksi
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                   class="py-2.5 border-b-2 transition {{ request('category') == $cat->slug ? 'border-navy text-navy' : 'border-transparent text-ink/70 hover:text-navy' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Hover Mega Menu (Uniqlo x H&M Hybrid Ringkas) -->
        @include('partials.mega-menu')

        @if(session('success'))
            <div class="bg-emerald text-white text-sm font-label font-semibold py-2.5 px-4 text-center flex items-center justify-center gap-3">
                <span>✓ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-white/80 hover:text-white font-bold cursor-pointer">&times;</button>
            </div>
        @endif

        <!-- Search Flyout Panel Mobile/Tablet -->
        <div x-show="searchOpen" 
             x-cloak 
             @click.outside="searchOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white border-t border-b border-line rounded-b-2xl shadow-lg py-5 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto space-y-4">
                <div class="flex items-center justify-between gap-2">
                    <form action="{{ route('products.index') }}" method="GET" class="flex-1 flex items-center gap-2">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <div class="relative flex-1">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Apa yang Anda cari? (Misal: Flannel, Linen, Jaket Denim, Chino...)" 
                                   class="w-full rounded-xl bg-surface-low border border-line-strong focus:border-navy focus:bg-white text-sm py-2.5 pl-10 pr-4 font-body focus:ring-2 focus:ring-emerald/20">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-muted">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                        </div>
                        <button type="submit" class="rounded-xl bg-navy text-white font-label text-sm font-bold px-5 py-3 hover:bg-navy-600 transition">
                            Cari
                        </button>
                    </form>
                    <button type="button" @click="searchOpen = false" class="w-10 h-10 rounded-lg flex items-center justify-center text-muted hover:text-ink font-bold text-lg" title="Tutup Pencarian">&times;</button>
                </div>

                <div>
                    <span class="font-label text-xs uppercase tracking-widest text-muted font-bold block mb-2">
                        Pencarian Populer (Trending)
                    </span>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Flannel', 'Linen', 'Boxy T-Shirt', 'Denim', 'Worker Jacket', 'Chino', 'Crewneck', 'Ankle Pants'] as $trend)
                            <a href="{{ route('products.index', ['search' => $trend]) }}" 
                               class="h-8 px-3.5 rounded-full border border-line-strong bg-white hover:border-navy hover:bg-surface-low text-ink font-label text-sm inline-flex items-center transition">
                                {{ $trend }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile & Tablet Slide-Over Drawer (Berada di Luar Header Agar Tidak Terpotong Backdrop-Filter) -->
    @include('partials.mobile-drawer')

    <!-- 3. HERO EDITORIAL FULL-BLEED (Ala Uniqlo x H&M) -->
    @if(!request('category') && !request('search') && !request('size'))
    <section class="relative w-full min-h-[360px] sm:min-h-[460px] lg:h-[560px] bg-navy overflow-hidden flex items-center py-6 sm:py-14">
        <!-- Background Editorial Fashion Photo -->
        <img src="{{ asset('storage/banners/hero-drop01.jpg') }}" 
             alt="Sirkula Drop #01 Editorial Lookbook" 
             class="absolute inset-0 w-full h-full object-cover object-center opacity-60">
        
        <!-- Neutral Scrim Overlay untuk Keterbacaan Teks -->
        <div class="absolute inset-0 bg-black/45"></div>

        <!-- Konten Editorial Teks -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-12 w-full">
            <div class="max-w-3xl space-y-3 sm:space-y-4">
                <p class="inline-flex items-center rounded-md bg-navy/90 border border-emerald-soft/60 text-emerald-soft font-label font-semibold text-xs px-3 py-1">
                    Archive Recommerce Drop #01
                </p>

                <h1 class="font-headline text-2xl sm:text-4xl lg:text-[48px] font-extrabold text-white tracking-tight leading-tight">
                    Gaya Esensial<br>Sirkular Tanpa Kompromi
                </h1>

                <p class="font-body text-xs sm:text-base text-white/90 max-w-xl leading-relaxed">
                    Pakaian terkurasi tangan pertama dari surplus ekspor dan arsip pilihan. Melewati sanitasi medis uap &amp; ozon 100°C, higienis siap pakai, hemat hingga 70% dari harga retail toko.
                </p>

                <div class="pt-2 sm:pt-4 flex flex-col sm:flex-row gap-2 sm:gap-3 font-label text-xs sm:text-sm font-bold w-full sm:w-auto">
                    <a href="#katalog" class="rounded-lg bg-white hover:bg-surface-low text-navy px-5 sm:px-7 py-2.5 sm:py-3.5 transition text-center shadow-xs">
                        Eksplorasi Koleksi &darr;
                    </a>
                    <a href="#sirkulacare" class="rounded-lg bg-navy/80 hover:bg-navy text-white border border-white/50 px-4 sm:px-6 py-2.5 sm:py-3.5 transition text-center">
                        Standar SirkulaCare
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. CARI BERDASARKAN KATEGORI (Category Visual Tiles 4:5 Portrait ala H&M & Uniqlo) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-10 pb-4 sm:pb-6">
        <div class="flex items-baseline justify-between mb-3.5 sm:mb-6">
            <h2 class="font-headline text-base sm:text-xl font-bold text-navy tracking-tight">
                Cari Berdasarkan Kategori
            </h2>
            <span class="font-label text-xs sm:text-sm font-medium text-ink/70">Kurasi Terverifikasi</span>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <a href="{{ route('products.index', ['category' => 'kaos-t-shirt']) }}" 
               class="group relative aspect-[4/5] rounded-xl bg-canvas overflow-hidden flex items-end p-3 sm:p-4">
                <img src="{{ asset('storage/banners/cat-kaos.jpg') }}" alt="Kaos & T-Shirt" class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                <div class="relative z-10 text-white w-full">
                    <span class="font-headline font-bold text-sm sm:text-base block leading-snug drop-shadow-xs">Kaos &amp; T-Shirt</span>
                    <span class="font-label text-xs sm:text-sm text-white/80 block mt-0.5 truncate">Heavyweight Boxy</span>
                </div>
            </a>

            <a href="{{ route('products.index', ['category' => 'kemeja']) }}" 
               class="group relative aspect-[4/5] rounded-xl bg-canvas overflow-hidden flex items-end p-3 sm:p-4">
                <img src="{{ asset('storage/banners/cat-kemeja.jpg') }}" alt="Kemeja Casual" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                <div class="relative z-10 text-white w-full">
                    <span class="font-headline font-bold text-sm sm:text-base block leading-snug drop-shadow-xs">Kemeja Casual</span>
                    <span class="font-label text-xs sm:text-sm text-white/80 block mt-0.5 truncate">Linen &amp; Flannel</span>
                </div>
            </a>

            <a href="{{ route('products.index', ['category' => 'outerwear-jaket']) }}" 
               class="group relative aspect-[4/5] rounded-xl bg-canvas overflow-hidden flex items-end p-3 sm:p-4">
                <img src="{{ asset('storage/banners/cat-outerwear.jpg') }}" alt="Outerwear & Jaket" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                <div class="relative z-10 text-white w-full">
                    <span class="font-headline font-bold text-sm sm:text-base block leading-snug drop-shadow-xs">Outerwear &amp; Jaket</span>
                    <span class="font-label text-xs sm:text-sm text-white/80 block mt-0.5 truncate">Chore &amp; Selvedge</span>
                </div>
            </a>

            <a href="{{ route('products.index', ['category' => 'celana-bawahan']) }}" 
               class="group relative aspect-[4/5] rounded-xl bg-canvas overflow-hidden flex items-end p-3 sm:p-4">
                <img src="{{ asset('storage/banners/cat-celana.jpg') }}" alt="Celana & Bawahan" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                <div class="relative z-10 text-white w-full">
                    <span class="font-headline font-bold text-sm sm:text-base block leading-snug drop-shadow-xs">Celana &amp; Bawahan</span>
                    <span class="font-label text-xs sm:text-sm text-white/80 block mt-0.5 truncate">Chino &amp; Denim</span>
                </div>
            </a>
        </div>
    </section>
    @endif

    <!-- 5. MAIN PRODUCT LISTING (PLP) & FILTER TOOLBAR -->
    <main id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-16 sm:py-14">
        
        <!-- Voucher Claim Banner Strip (Guest & Member 1-Click Copy ala Retail Ticket) -->
        @if($firstCoupon)
            <div x-data="{ 
                     copiedVoucher: false,
                     copyVoucherCode(code) {
                         if (navigator.clipboard && window.isSecureContext) {
                             navigator.clipboard.writeText(code).then(() => {
                                 this.copiedVoucher = true;
                                 setTimeout(() => this.copiedVoucher = false, 2500);
                             }).catch(() => this.fallbackCopy(code));
                         } else {
                             this.fallbackCopy(code);
                         }
                     },
                     fallbackCopy(code) {
                         const ta = document.createElement('textarea');
                         ta.value = code;
                         ta.style.position = 'fixed';
                         ta.style.left = '-9999px';
                         document.body.appendChild(ta);
                         ta.select();
                         try {
                             document.execCommand('copy');
                             this.copiedVoucher = true;
                             setTimeout(() => this.copiedVoucher = false, 2500);
                         } catch (_) {}
                         document.body.removeChild(ta);
                     }
                 }" 
                 class="mb-8 p-3.5 sm:p-4 rounded-2xl bg-gradient-to-r from-navy via-navy-600 to-navy text-white flex flex-col sm:flex-row items-center justify-between gap-3 shadow-card border border-navy/20">
                <div class="flex items-center gap-3.5 text-center sm:text-left">
                    <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center shrink-0 text-emerald-soft">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 justify-center sm:justify-start">
                            <span class="text-xs font-bold uppercase tracking-widest text-emerald-soft font-label">Kupon Belanja Aktif</span>
                            <span class="text-[11px] bg-white/20 text-white px-2 py-0.5 rounded-full font-mono font-bold">{{ $firstCoupon['code'] }}</span>
                        </div>
                        <p class="text-xs sm:text-sm text-white/90 font-body mt-0.5">
                            {{ $firstCoupon['title'] }} — Hemat <strong class="text-white font-bold">Rp {{ number_format($firstCoupon['discount'] ?? 25000, 0, ',', '.') }}</strong> min. belanja Rp {{ number_format($firstCoupon['min_purchase'] ?? 100000, 0, ',', '.') }}.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" 
                            @click="copyVoucherCode('{{ $firstCoupon['code'] }}')"
                            class="h-9 px-4 rounded-xl bg-white text-navy hover:bg-emerald-soft hover:text-navy transition font-label text-xs font-bold cursor-pointer flex items-center gap-1.5 shadow-2xs active:scale-95">
                        <span x-text="copiedVoucher ? '✓ Kode Disalin!' : 'Salin Kode Voucher'"></span>
                    </button>
                </div>
            </div>
        @endif

        <!-- Header Judul Koleksi & Filter Toolbar -->
        <div class="pb-5 mb-8 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2">
                <div>
                    <h2 class="font-headline text-xl sm:text-2xl font-extrabold text-navy tracking-tight">
                        {{ request('category') ? $categories->where('slug', request('category'))->first()?->name : 'Koleksi Drop Pilihan' }}
                    </h2>
                    <p class="font-body text-sm sm:text-sm text-ink/80 mt-0.5">Pakaian kurasi tangan pertama dengan penilaian kondisi transparan.</p>
                </div>
                <span class="font-label text-xs font-semibold text-emerald bg-emerald-soft/30 px-3 py-1 rounded-full self-start sm:self-auto">
                    {{ $products->total() }} Produk Siap Dikirim
                </span>
            </div>

            <!-- Toolbar Filter Chips & Sort (Sesuai Benchmark PLP Uniqlo & H&M - Mobile Horizontal Scroll) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
                
                <!-- Filter Ukuran Pakaian: Horizontally scrollable on mobile without wrapping -->
                <div class="flex items-center gap-1.5 font-label text-xs sm:text-sm overflow-x-auto scrollbar-none pb-1 sm:pb-0 -mx-4 px-4 sm:mx-0 sm:px-0">
                    <span class="text-ink/80 font-semibold shrink-0 mr-1">Ukuran:</span>
                    <a href="{{ route('products.index', request()->except(['size', 'page'])) }}" 
                       class="px-3 py-1.5 rounded-lg border shrink-0 transition font-medium {{ !request('size') ? 'bg-navy text-white border-navy font-bold' : 'bg-white text-ink border-line-strong hover:border-navy' }}">
                        Semua
                    </a>
                    @foreach (['S', 'M', 'L', 'XL'] as $size)
                        <a href="{{ route('products.index', array_merge(request()->except('page'), ['size' => $size])) }}" 
                           class="px-3 py-1.5 rounded-lg border shrink-0 transition font-medium {{ request('size') == $size ? 'bg-navy text-white border-navy font-bold' : 'bg-white text-ink border-line-strong hover:border-navy' }}">
                            {{ $size }}
                        </a>
                    @endforeach
                </div>

                <!-- Sort Dropdown -->
                <form action="{{ route('products.index') }}" method="GET" class="flex items-center justify-between sm:justify-end gap-2 font-label text-xs sm:text-sm">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('size'))
                        <input type="hidden" name="size" value="{{ request('size') }}">
                    @endif
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <label for="sort" class="text-ink/80 font-semibold shrink-0">Urutkan:</label>
                    <select name="sort" 
                            id="sort" 
                            onchange="this.form.submit()" 
                            class="rounded-xl bg-white border border-line-strong text-xs py-1.5 pl-2.5 pr-8 font-body focus:ring-1 focus:ring-navy cursor-pointer">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Rilisan Terbaru</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </form>

            </div>

            <!-- Blok Filter Aktif + Hapus Semua (Ala Uniqlo PLP Filter Drawer) -->
            @if(request('category') || request('size') || request('search') || (request('sort') && request('sort') !== 'newest'))
                <div class="flex flex-wrap items-center gap-2 pt-3 font-label text-sm">
                    <span class="text-ink/80 font-semibold mr-1">Filter Aktif:</span>

                    @if(request('category'))
                        <a href="{{ route('products.index', request()->except(['category', 'page'])) }}" 
                           class="inline-flex items-center gap-1.5 rounded-lg bg-surface-low hover:bg-line text-navy font-semibold px-3 py-1 border border-line-strong transition">
                            <span>Kategori: {{ $categories->where('slug', request('category'))->first()?->name ?? request('category') }}</span>
                            <span class="font-bold">&times;</span>
                        </a>
                    @endif

                    @if(request('size'))
                        <a href="{{ route('products.index', request()->except(['size', 'page'])) }}" 
                           class="inline-flex items-center gap-1.5 rounded-lg bg-surface-low hover:bg-line text-navy font-semibold px-3 py-1 border border-line-strong transition">
                            <span>Ukuran: {{ request('size') }}</span>
                            <span class="font-bold">&times;</span>
                        </a>
                    @endif

                    @if(request('search'))
                        <a href="{{ route('products.index', request()->except(['search', 'page'])) }}" 
                           class="inline-flex items-center gap-1.5 rounded-lg bg-surface-low hover:bg-line text-navy font-semibold px-3 py-1 border border-line-strong transition">
                            <span>Pencarian: "{{ request('search') }}"</span>
                            <span class="font-bold">&times;</span>
                        </a>
                    @endif

                    <a href="{{ route('products.index') }}" 
                       class="text-emerald hover:underline font-bold ml-2">
                        Hapus semua
                    </a>
                </div>
            @endif
        </div>

        @if($products->isEmpty())
            <div class="text-center py-20 bg-white rounded-2xl p-8">
                <svg class="w-12 h-12 text-ink/80 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p class="font-headline font-bold text-sm text-navy">Belum ada pakaian di kategori atau ukuran ini.</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-4 rounded-lg font-label text-sm font-bold bg-navy text-white px-5 py-2.5">
                    Reset Filter
                </a>
            </div>
        @else
            <!-- Grid dengan Vertical Breathing Room & Symmetric Cards (Proporsi Uniqlo 3:4 Aspect Ratio) -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-3 sm:gap-x-6 gap-y-7 sm:gap-y-12">
                @foreach ($products as $product)
                    <article class="group flex flex-col cursor-pointer relative">
                        
                        <!-- 1. Media Container: Symmetrical 3:4 Aspect Ratio (Uniqlo Matched) -->
                        <div class="relative w-full aspect-[3/4] rounded-lg sm:rounded-xl bg-[#f4f4f4] overflow-hidden">
                            <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
                                @if($product->image)
                                    <!-- Foto Utama (Lookbook Depan) -->
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         loading="{{ $loop->index < 4 ? 'eager' : 'lazy' }}"
                                         fetchpriority="{{ $loop->index < 4 ? 'high' : 'auto' }}"
                                         class="w-full h-full object-cover object-center transition duration-500 group-hover:scale-102">

                                    <!-- Foto Kedua Saat Hover (Lookbook Detail/Angle 2) -->
                                    @if($product->secondary_image && $product->secondary_image !== $product->image)
                                        <img src="{{ asset('storage/' . $product->secondary_image) }}" 
                                             alt="{{ $product->name }} Detail" 
                                             loading="lazy"
                                             class="absolute inset-0 w-full h-full object-cover object-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    @endif
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-ink/70">
                                        <svg class="w-10 h-10 text-line-strong" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        <span class="font-label text-xs sm:text-sm text-ink/70 mt-1">{{ $product->category->name }}</span>
                                    </div>
                                @endif
                            </a>

                            <!-- Kondisi Grade Pill / Sold Out Badge / Service Utama SirkulaVerify -->
                            <div class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 pointer-events-none flex flex-col gap-1 items-start z-10">
                                @php $totalStock = $product->variants->sum('stock'); @endphp
                                @if($totalStock <= 0)
                                    <span class="rounded-md bg-navy text-white font-label font-bold text-[10px] sm:text-xs px-2 py-0.5 shadow-xs uppercase tracking-wider whitespace-nowrap">
                                        Habis
                                    </span>
                                @else
                                    <!-- Service Utama: Grade A+ · SirkulaVerify -->
                                    <span class="rounded-md bg-white/95 text-emerald font-label font-bold text-[11px] sm:text-xs px-2 py-0.5 shadow-2xs flex items-center gap-1 backdrop-blur-xs whitespace-nowrap border border-emerald/20">
                                        <svg class="w-3 h-3 text-emerald shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        <span>Grade A+</span>
                                        <span class="text-emerald/40">&bull;</span>
                                        <span class="text-navy font-extrabold tracking-tight">Sirkula</span>
                                    </span>

                                    <!-- Scarcity Badge Recommerce (1-of-1 atau Sisa 1-2 pcs) -->
                                    @if($totalStock <= 2)
                                        <span class="rounded-md bg-amber-500 text-white font-label font-extrabold text-[10px] px-1.5 py-0.5 shadow-2xs uppercase tracking-wider flex items-center gap-1 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse shrink-0"></span>
                                            <span>{{ $totalStock === 1 ? '1-of-1' : 'Sisa 2' }}</span>
                                        </span>
                                    @endif
                                @endif
                            </div>

                            <!-- Tombol Wishlist Heart dengan Micro-Interaction Tactile -->
                            <button type="button" 
                                    @click.stop="toggleWishlist({{ $product->id }})" 
                                    class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 w-8 h-8 sm:w-9 sm:h-9 bg-white/90 hover:bg-white text-ink rounded-full flex items-center justify-center transition active:scale-125 duration-150 shadow-xs cursor-pointer z-10"
                                    :title="isWishlisted({{ $product->id }}) ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist'">
                                <svg class="w-4 h-4 transition duration-200" 
                                     :class="isWishlisted({{ $product->id }}) ? 'text-red-600 fill-current scale-110' : 'text-ink/70'"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- 2. Typography & Metadata (Symmetric Baseline Ala Uniqlo PLP) -->
                        <div class="pt-2 sm:pt-2.5 flex flex-col flex-1 justify-between">
                            <div>
                                <!-- Color Swatches (Clean Dots) -->
                                <div class="flex items-center gap-1.5 mb-1">
                                    @foreach($product->variants->pluck('color')->unique() as $color)
                                        <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full border border-line-strong inline-block shrink-0"
                                              style="background-color: {{ strtolower($color) == 'putih' || str_contains(strtolower($color), 'white') ? '#ffffff' : (strtolower($color) == 'hitam' || str_contains(strtolower($color), 'black') ? '#181c1c' : (str_contains(strtolower($color), 'navy') || str_contains(strtolower($color), 'indigo') ? '#002547' : (str_contains(strtolower($color), 'green') || str_contains(strtolower($color), 'sage') || str_contains(strtolower($color), 'olive') ? '#006c47' : (str_contains(strtolower($color), 'terracotta') || str_contains(strtolower($color), 'brown') ? '#a0522d' : '#888888')))) }}"
                                              title="{{ $color }}">
                                        </span>
                                    @endforeach
                                </div>

                                <!-- Category & Available Sizes (Uniqlo 12px Baseline) -->
                                <p class="font-label text-xs text-ink/70 mb-0.5 truncate">
                                    {{ $product->category->name }} &bull; {{ $product->variants->pluck('size')->unique()->implode(', ') }}
                                </p>

                                <!-- Product Title (Uniqlo 13px Baseline & Symmetrical 2-Line Height) -->
                                <h3 class="font-headline text-[13px] sm:text-sm font-medium text-ink leading-snug line-clamp-2 min-h-[36px] sm:min-h-[40px] group-hover:text-navy transition">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                            </div>

                            <!-- Price Row (Uniqlo 15-16px Baseline) -->
                            <div class="mt-1.5 flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
                                <span class="font-label text-[15px] sm:text-base font-bold text-navy whitespace-nowrap">
                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                </span>
                                <span class="font-label text-xs sm:text-sm text-ink/50 line-through whitespace-nowrap">
                                    Rp {{ number_format($product->base_price * 2.2, 0, ',', '.') }}
                                </span>
                                <span class="font-label text-xs font-bold text-emerald whitespace-nowrap">
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
    <section id="sirkulacare" class="bg-white my-10 sm:my-16 py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-12 items-center">
                
                <div class="lg:col-span-6 space-y-3.5 sm:space-y-4">
                    <p class="font-label text-xs sm:text-sm font-bold text-emerald">
                        Standar Sanitasi Medis SirkulaCare
                    </p>

                    <h2 class="font-headline text-xl sm:text-2xl lg:text-[28px] font-extrabold text-navy tracking-tight leading-snug">
                        Pakaian Bersih Tanpa Kompromi,<br>Terkurasi Seperti Baru
                    </h2>

                    <p class="font-body text-xs sm:text-sm text-ink/80 leading-relaxed max-w-xl">
                        Kami tidak sekadar menjual pakaian preloved; setiap potong pakaian di Sirkula melewati proses sanitasi 3 tahap: pencucian uap suhu 100°C, sterilisasi gas ozon pembasmi 99.9% bakteri, dan inspeksi fisik 12 titik oleh kurator profesional.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 pt-2 sm:pt-3 font-body text-xs sm:text-sm">
                        <div class="p-3.5 sm:p-4 rounded-xl bg-surface">
                            <span class="font-headline font-bold text-sm text-navy flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-emerald shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>Bebas Alergen</span>
                            </span>
                            <span class="text-ink/80 text-xs sm:text-sm leading-relaxed block">Aroma segar dan steril, langsung dapat dipakai tanpa dicuci ulang.</span>
                        </div>
                        <div class="p-4 rounded-xl bg-surface">
                            <span class="font-headline font-bold text-sm text-navy flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-emerald shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>SirkulaVerify QC</span>
                            </span>
                            <span class="text-ink/80 text-xs sm:text-sm leading-relaxed block">Pemeriksaan jahitan kerah, kancing orisinal, dan keaslian garmen.</span>
                        </div>
                        <div class="p-4 rounded-xl bg-surface">
                            <span class="font-headline font-bold text-sm text-navy flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-emerald shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                                <span>Hemat 2.500L Air</span>
                            </span>
                            <span class="text-ink/80 text-xs sm:text-sm leading-relaxed block">Memperpanjang siklus hidup pakaian mengurangi jejak emisi tekstil.</span>
                        </div>
                        <div class="p-4 rounded-xl bg-surface">
                            <span class="font-headline font-bold text-sm text-navy flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-emerald shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Garansi 3 Hari</span>
                            </span>
                            <span class="text-ink/80 text-xs sm:text-sm leading-relaxed block">Ukuran kurang pas? Layanan tukar pakaian mudah dan ramah.</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="w-full aspect-[4/3] rounded-xl bg-canvas overflow-hidden relative">
                        <img src="{{ asset('storage/banners/sirkulacare-story.jpg') }}" alt="SirkulaCare Sanitasi & Kurasi Workshop" class="w-full h-full object-cover">
                        <div class="absolute bottom-3 left-3 rounded-md bg-white/95 px-2.5 py-1 font-label text-xs font-bold text-navy shadow-xs">
                            Studio Sanitasi &bull; QC Jakarta Barat
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 7. Comprehensive 4-Column Commercial Footer (Ala Uniqlo & H&M) -->
    <footer class="bg-white border-t border-line mt-12 sm:mt-20 py-12 sm:py-16 text-ink/80 font-body text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-line">
            
            <div class="space-y-3">
                @include('partials.brand-logo', ['size' => 'sm'])
                <p class="text-xs sm:text-sm text-muted leading-relaxed">
                    Platform recommerce fashion sirkular yang mendigitalkan nilai kearifan lungsuran. Memperpanjang siklus pakai pakaian berkualitas untuk masa depan yang lebih lestari.
                </p>
            </div>

            <div>
                <h4 class="font-headline font-bold text-sm text-navy mb-3">Koleksi Pakaian</h4>
                <ul class="space-y-2 text-xs sm:text-sm">
                    <li><a href="{{ route('products.index', ['category' => 'kaos-t-shirt']) }}" class="hover:underline">Kaos &amp; T-Shirt Boxy</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'kemeja']) }}" class="hover:underline">Kemeja Casual &amp; Flannel</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'outerwear-jaket']) }}" class="hover:underline">Outerwear &amp; Jaket</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'celana-bawahan']) }}" class="hover:underline">Celana Chino &amp; Denim</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-headline font-bold text-sm text-navy mb-3">Layanan &amp; Bantuan</h4>
                <ul class="space-y-2 text-xs sm:text-sm">
                    <li><a href="#sirkulacare" class="hover:underline">Standar Sanitasi SirkulaCare</a></li>
                    <li><a href="{{ route('member') }}" class="hover:underline">Keanggotaan Member Sirkula</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:underline">Lacak Status Pesanan</a></li>
                    <li><a href="{{ route('member') }}" class="hover:underline">Garansi Retur Ukuran 3 Hari</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-headline font-bold text-sm text-navy mb-3">Program Lungsuran</h4>
                <p class="text-xs sm:text-sm text-muted leading-relaxed mb-3">
                    Punya pakaian berkualitas di lemari yang jarang terpakai? Titip jual di Sirkula dan peroleh penghasilan.
                </p>
                <a href="{{ route('member') }}" class="inline-block rounded-lg font-label font-bold text-xs sm:text-sm bg-navy text-white px-3.5 py-2">
                    Titip Jual Baju &rarr;
                </a>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs sm:text-sm text-muted gap-3">
            <p>&copy; {{ date('Y') }} PT SIRKULASI INDONESIA LESTARI. Hak cipta dilindungi undang-undang.</p>
            <p class="font-label">Metode Pembayaran: Transfer BCA &bull; Mandiri &bull; BRI</p>
        </div>
    </footer>

    <!-- 8. Floating Quick-Jump Filter & Sort Capsule (Mobile Viewport - Auto-Hide Saat Scroll Down) -->
    <div x-show="showFloatingFilter" 
         x-cloak
         :class="scrollingDown ? 'translate-y-24 opacity-0 pointer-events-none' : 'translate-y-0 opacity-100 pointer-events-auto'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-3"
         class="md:hidden fixed bottom-4 right-3.5 z-40 transition-all duration-300">
        <a href="#katalog" 
           @click.prevent="
               const el = document.getElementById('katalog');
               if (el) {
                   const offset = 70;
                   const bodyRect = document.body.getBoundingClientRect().top;
                   const elementRect = el.getBoundingClientRect().top;
                   const offsetPosition = elementRect - bodyRect - offset;
                   window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
               }
           "
           class="h-9 px-3.5 rounded-full bg-navy/95 text-white shadow-xl border border-white/20 font-label text-xs font-bold flex items-center gap-1.5 backdrop-blur-md active:scale-95 transition cursor-pointer select-none">
            <svg class="w-3.5 h-3.5 text-emerald-soft shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            <span>Filter &amp; Sort ({{ $products->total() }})</span>
            <span class="text-white/60">&uarr;</span>
        </a>
    </div>

</body>
</html>
