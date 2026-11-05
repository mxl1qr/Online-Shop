<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Favorit Saya (Wishlist) — SIRKULA Recommerce</title>
    
    <!-- Google Fonts Trio dari DESIGN.md -->
    @include('partials.head-fonts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body @keydown.escape.window="activeMegaMenu = null"
      x-data="{
          mobileMenuOpen: false,
          activeMegaMenu: null,
          megaMenuTimer: null,
          openMega(slug) {
              clearTimeout(this.megaMenuTimer);
              this.activeMegaMenu = slug;
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
          clearWishlist() {
              this.wishlist = [];
              localStorage.setItem('sirkula_wishlist_{{ auth()->id() ?? 'guest' }}', JSON.stringify([]));
          },
          isWishlisted(id) {
              return this.wishlist.includes(id);
          }
      }"
      class="bg-surface text-ink antialiased font-body selection:bg-navy selection:text-white pb-16 md:pb-0">

    <!-- 1. Top Announcement Bar -->
    <div class="bg-navy text-white text-xs py-2 px-4 text-center font-label font-medium tracking-wide">
        <div class="max-w-7xl mx-auto flex items-center justify-center space-x-3">
            <span class="inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                100% Terkurasi &amp; Higienis (Sanitized)
            </span>
            <span class="hidden sm:inline text-white/30">|</span>
            <span class="hidden sm:inline">Gratis Ongkir ke Seluruh Indonesia Min. Belanja Rp 250.000</span>
        </div>
    </div>

    <!-- 2. Main Navigation Bar (3 Pilar Presisi 64px + Hover Mega Menu) -->
    <header class="sticky top-0 z-50 bg-white border-b border-line relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-3 lg:gap-6">
                
                <!-- PILAR 1: KIRI (Hamburger Mobile + Logo Asli Sirkula) -->
                <div class="flex items-center gap-2.5 shrink-0" @mouseenter="activeMegaMenu = null">
                    <button type="button"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="lg:hidden w-10 h-10 -ml-1 rounded-lg flex items-center justify-center text-navy hover:bg-surface-low transition"
                            aria-label="Buka Menu Navigasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <a href="{{ route('products.index') }}" class="group shrink-0">
                        @include('partials.brand-logo')
                    </a>
                </div>

                <!-- PILAR 2: TENGAH (Kategori Navigasi 1 Baris Utuh + Hover Mega Menu) -->
                <nav class="hidden lg:flex items-center justify-center gap-5 xl:gap-7 whitespace-nowrap font-label text-sm font-semibold"
                     @mouseleave="closeMega()">
                    <a href="{{ route('products.index') }}"
                       @mouseenter="openMega('all')"
                       :class="activeMegaMenu === 'all' ? 'border-navy text-navy' : 'border-transparent text-ink/80 hover:text-navy'"
                       class="h-16 inline-flex items-center whitespace-nowrap transition border-b-2">
                        Semua Koleksi
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                           @mouseenter="openMega('{{ $cat->slug }}')"
                           :class="activeMegaMenu === '{{ $cat->slug }}' ? 'border-navy text-navy' : 'border-transparent text-ink/80 hover:text-navy'"
                           class="h-16 inline-flex items-center whitespace-nowrap transition border-b-2">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </nav>

                <!-- PILAR 3: KANAN (Wishlist, Akun & Cart) -->
                <div class="flex items-center justify-end gap-1 sm:gap-2 shrink-0 font-label text-sm">
                    <a href="{{ route('wishlist.index') }}" 
                       class="w-10 h-10 rounded-lg relative flex items-center justify-center text-navy bg-surface-low transition" 
                       title="Favorit Saya">
                        <svg class="w-5 h-5 text-red-600 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span x-show="wishlist.length > 0" 
                              x-text="wishlist.length" 
                              class="absolute top-1 right-1 w-4 h-4 bg-emerald text-white text-xs font-bold rounded-full flex items-center justify-center leading-none">
                        </span>
                    </a>

                    <!-- Menu Akun / Member Hub -->
                    @auth
                        <div x-data="{ accountOpen: false }" class="relative">
                            <button type="button" 
                                    @click="accountOpen = !accountOpen" 
                                    @click.outside="accountOpen = false"
                                    class="h-10 px-2.5 rounded-lg inline-flex items-center gap-1.5 text-ink hover:text-navy hover:bg-surface-low transition cursor-pointer" 
                                    aria-label="Menu Akun">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-6 h-6 rounded-full object-cover shrink-0">
                                @else
                                    <svg class="w-5 h-5 shrink-0 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                                <span class="hidden xl:inline font-semibold text-sm max-w-[96px] truncate">{{ \Illuminate\Support\Str::words(Auth::user()->name, 1, '') }}</span>
                                <svg class="w-3.5 h-3.5 text-muted transition-transform" :class="accountOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
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
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex h-9 px-3.5 rounded-lg items-center text-sm font-semibold text-ink hover:text-navy hover:bg-surface-low whitespace-nowrap transition">
                            Masuk
                        </a>
                    @endauth

                    <a href="{{ route('cart.index') }}" class="relative w-10 h-10 rounded-lg flex items-center justify-center text-ink hover:text-navy hover:bg-surface-low transition" title="Keranjang Belanja">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        @if(count(session('cart', [])) > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-navy text-white text-xs font-bold rounded-full flex items-center justify-center">
                                {{ count(session('cart', [])) }}
                            </span>
                        @endif
                    </a>
                </div>

            </div>
        </div>

        <!-- Hover Mega Menu (Uniqlo x H&M Hybrid) -->
        @include('partials.mega-menu')
    </header>

    <!-- Mobile Drawer Outside Header -->
    @include('partials.mobile-drawer')

    <!-- 3. MAIN WISHLIST CONTENT (Ala H&M /wishlist/ & Uniqlo) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">

        <!-- Header Favorit -->
        <div class="border-b border-line pb-6 mb-10 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <nav class="font-label text-sm text-muted mb-2 flex items-center space-x-2">
                    <a href="{{ route('products.index') }}" class="hover:text-navy">Beranda</a>
                    <span>/</span>
                    <span class="text-ink font-semibold">Favorit</span>
                </nav>
                <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-navy">
                    Favorit Saya
                </h1>
                <p class="font-body text-sm text-muted mt-1">
                    Pakaian kurasi tunggal (1-of-1) yang Anda simpan. Segera amankan sebelum terjual.
                </p>
            </div>
            <div class="flex items-center space-x-4 font-label text-sm" x-show="wishlist.length > 0" x-cloak>
                <span class="font-bold text-emerald bg-emerald-soft/30 px-3 py-1.5">
                    <span x-text="wishlist.length"></span> Item Tersimpan
                </span>
                <button type="button" 
                        @click="clearWishlist()" 
                        class="text-muted hover:text-red-600 underline underline-offset-4 font-semibold transition">
                    Hapus Semua
                </button>
            </div>
        </div>

        <!-- EMPTY STATE (Ala H&M Favorit: 335x56 CTA + Copy) -->
        <div x-show="wishlist.length === 0" x-cloak class="card py-20 px-6 text-center max-w-2xl mx-auto my-6">
            <div class="w-14 h-14 rounded-2xl mx-auto mb-4 bg-canvas flex items-center justify-center text-navy">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <h2 class="font-headline text-lg sm:text-xl font-extrabold text-navy mb-2">
                Simpan Item Favorit Anda
            </h2>
            <p class="font-body text-sm sm:text-sm text-ink/80 max-w-md mx-auto leading-relaxed mb-8">
                Ingin menyimpan item pakaian yang Anda sukai? Klik lambang hati di sudut kartu produk dan item tersebut akan tersimpan di halaman ini.
            </p>
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center justify-center w-full sm:w-[335px] h-14 rounded-xl border-2 border-navy bg-navy hover:bg-navy-600 text-white font-label text-xs uppercase tracking-widest font-bold transition">
                Cari Koleksi Sekarang
            </a>
        </div>

        <!-- POPULATED WISHLIST GRID (4 Kolom 3:4) -->
        <div x-show="wishlist.length > 0" x-cloak class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 sm:gap-x-6 gap-y-10">
            @foreach ($products as $product)
                @php
                    $totalStock = $product->variants->sum('stock');
                    $availableSizes = $product->variants->where('stock', '>', 0)->pluck('size')->unique()->implode(', ');
                    $retailPrice = round($product->base_price * 2.2, -3);
                    $savingPercent = round((($retailPrice - $product->base_price) / $retailPrice) * 100);
                @endphp

                <div x-show="isWishlisted({{ $product->id }})" 
                     x-transition
                     class="card group flex flex-col p-3">
                    
                    <!-- Area Foto 3:4 -->
                    <div class="relative aspect-[3/4] w-full rounded-xl bg-canvas overflow-hidden mb-3">
                        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover transition-opacity duration-300 {{ $product->secondary_image ? 'group-hover:opacity-0' : '' }}">
                                @if($product->secondary_image)
                                    <img src="{{ asset('storage/' . $product->secondary_image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                @endif
                            @endif
                        </a>

                        <!-- Badge Kondisi -->
                        <div class="absolute top-2.5 left-2.5 pointer-events-none">
                            <span class="rounded-md bg-white/95 text-emerald font-label text-xs font-bold uppercase tracking-wider px-2 py-0.5 border border-line">
                                Grade A+
                            </span>
                        </div>

                        <!-- Tombol Hapus dari Wishlist (44x44 Tap Target) -->
                        <button type="button"
                                @click="toggleWishlist({{ $product->id }})"
                                class="absolute top-2 right-2 w-10 h-10 rounded-full bg-white/90 hover:bg-white flex items-center justify-center transition shadow-sm"
                                title="Hapus dari Favorit">
                            <svg class="w-5 h-5 text-red-600 fill-current" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Detail Produk & Tombol Aksi Langsung -->
                    <div class="flex flex-col flex-1 justify-between">
                        <div>
                            <div class="font-label text-sm text-muted flex items-center justify-between mb-1">
                                <span class="font-medium truncate">{{ $product->category->name }}</span>
                                <span class="font-bold text-ink">Size: {{ $availableSizes ?: 'Habis' }}</span>
                            </div>
                            <h3 class="font-headline font-semibold text-sm text-ink group-hover:text-navy line-clamp-1 mb-2">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                        </div>

                        <div class="pt-2 border-t border-line space-y-3">
                            <div class="flex items-baseline space-x-2 font-label">
                                <span class="font-bold text-base text-navy">
                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                </span>
                                <span class="text-sm text-muted line-through">
                                    Rp {{ number_format($retailPrice, 0, ',', '.') }}
                                </span>
                                <span class="text-sm font-bold text-emerald">-{{ $savingPercent }}%</span>
                            </div>

                            <a href="{{ route('products.show', $product->slug) }}" 
                               class="w-full h-11 rounded-lg bg-navy hover:bg-navy-600 text-white font-label text-sm font-bold flex items-center justify-center transition">
                                Pilih Ukuran &amp; Beli
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- RAIL REKOMENDASI BAWAH ("BARU SAJA DIKURASI / ANDA MUNGKIN MENYUKAINYA" ala H&M Wishlist) -->
        <section class="mt-20 pt-12 border-t border-line">
            <div class="flex items-baseline justify-between mb-8">
                <div>
                    <span class="font-label text-xs uppercase tracking-widest text-emerald font-bold block mb-1">Kurasi Pilihan</span>
                    <h2 class="font-headline text-xl font-extrabold text-navy">
                        Anda Mungkin Juga Menyukainya
                    </h2>
                </div>
                <a href="{{ route('products.index') }}" class="font-label text-sm font-bold text-navy hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($products->take(4) as $rec)
                    <div class="group flex flex-col">
                        <div class="relative aspect-[3/4] w-full rounded-xl bg-canvas overflow-hidden mb-3">
                            <a href="{{ route('products.show', $rec->slug) }}" class="block w-full h-full">
                                @if($rec->image)
                                    <img src="{{ asset('storage/' . $rec->image) }}" alt="{{ $rec->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @endif
                            </a>
                            <button type="button"
                                    @click="toggleWishlist({{ $rec->id }})"
                                    class="absolute top-2 right-2 w-9 h-9 rounded-full bg-white/85 hover:bg-white flex items-center justify-center transition">
                                <svg class="w-4 h-4 transition" 
                                     :class="isWishlisted({{ $rec->id }}) ? 'text-red-600 fill-current' : 'text-ink fill-none'" 
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>
                        <h3 class="font-headline font-semibold text-sm text-ink group-hover:text-navy line-clamp-1">
                            <a href="{{ route('products.show', $rec->slug) }}">{{ $rec->name }}</a>
                        </h3>
                        <span class="font-label font-bold text-sm text-navy mt-1">
                            Rp {{ number_format($rec->base_price, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>

    </main>

    <!-- MOBILE FIXED BOTTOM NAVIGATION BAR (56px) -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white border-t border-line h-14 flex items-center justify-around px-2 font-label text-sm font-semibold text-muted">
        <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center flex-1 h-full">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Beranda</span>
        </a>
        <a href="{{ route('products.index') }}#katalog" class="flex flex-col items-center justify-center flex-1 h-full">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            <span>Koleksi</span>
        </a>
        <a href="{{ route('wishlist.index') }}" class="flex flex-col items-center justify-center flex-1 h-full relative text-navy font-bold">
            <svg class="w-5 h-5 mb-0.5 text-red-600 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <span>Wishlist</span>
            <span x-show="wishlist.length > 0" x-text="wishlist.length" class="absolute top-1 right-5 w-3.5 h-3.5 bg-emerald text-white text-xs font-bold rounded-full flex items-center justify-center"></span>
        </a>
        <a href="{{ route('cart.index') }}" class="flex flex-col items-center justify-center flex-1 h-full relative">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span>Keranjang</span>
        </a>
        <a href="{{ route('member') }}" class="flex flex-col items-center justify-center flex-1 h-full">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Member</span>
        </a>
    </nav>

</body>
</html>
