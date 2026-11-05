<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — SIRKULA Recommerce</title>
    
    <!-- Google Fonts Trio dari DESIGN.md -->
    @include('partials.head-fonts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-surface text-ink antialiased font-body selection:bg-navy selection:text-white pb-20 sm:pb-0"
      @keydown.escape.window="activeMegaMenu = null; sizeGuideOpen = false; zoomOpen = false; addedModalOpen = false"
      x-data="{
          variants: {{ json_encode($product->variants) }},
          selectedColor: '{{ $product->variants->first()?->color ?? '' }}',
          selectedSize: '{{ $product->variants->first()?->size ?? '' }}',
          quantity: 1,
          sizeGuideOpen: false,
          zoomOpen: false,
          addedModalOpen: {{ session()->has('added_to_cart') ? 'true' : 'false' }},
          shareToast: false,
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
          isWishlisted(id) {
              return this.wishlist.includes(id);
          },
          activeImage: '{{ asset('storage/' . ($product->image ?? 'products/tshirt-boxy-1.jpg')) }}',
          secondaryImage: '{{ asset('storage/' . ($product->secondary_image ?? $product->image ?? 'products/tshirt-boxy-2.jpg')) }}',
          accordion: null,

          get currentVariant() {
              return this.variants.find(v => v.color === this.selectedColor && v.size === this.selectedSize) 
                  || this.variants.find(v => v.color === this.selectedColor) 
                  || this.variants[0];
          },
          get currentStock() {
              return this.currentVariant ? this.currentVariant.stock : 0;
          },
          get currentPrice() {
              const base = {{ $product->base_price }};
              const add = this.currentVariant ? (this.currentVariant.additional_price || 0) : 0;
              return base + add;
          },
          get colors() {
              return [...new Set(this.variants.map(v => v.color))];
          },
          get sizes() {
              return [...new Set(this.variants.filter(v => v.color === this.selectedColor).map(v => v.size))];
          },
          selectColor(color) {
              this.selectedColor = color;
              const availableSizes = this.variants.filter(v => v.color === color).map(v => v.size);
              if (!availableSizes.includes(this.selectedSize)) {
                  this.selectedSize = availableSizes[0] || '';
              }
              this.quantity = 1;
          },
          isSizeInStock(size) {
              const v = this.variants.find(item => item.color === this.selectedColor && item.size === size);
              return v ? v.stock > 0 : false;
          }
      }">

    <!-- 1. Top Announcement Bar -->
    <div class="bg-navy text-white text-xs py-2 px-4 text-center font-label font-medium tracking-wide flex items-center justify-center space-x-3">
        <span class="inline-flex items-center space-x-1.5">
            <svg class="w-3.5 h-3.5 text-emerald-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>100% Terkurasi &amp; Higienis Sanitasi Uap Ozon</span>
        </span>
        <span class="text-white/40 hidden sm:inline">&bull;</span>
        <span class="hidden sm:inline">Garansi Keaslian SirkulaVerify</span>
        <span class="text-white/40 hidden md:inline">&bull;</span>
        <span class="hidden md:inline">Gratis Ongkir ke Seluruh Indonesia min. Rp250.000</span>
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

                <!-- PILAR 2: TENGAH (Sumbu Simetri - Kategori Navigasi 1 Baris Utuh + Hover Mega Menu) -->
                <nav class="hidden lg:flex items-center justify-center gap-5 xl:gap-7 whitespace-nowrap font-label text-sm font-semibold"
                     @mouseleave="closeMega()">
                    <a href="{{ route('products.index') }}" 
                       @mouseenter="openMega('all')"
                       :class="activeMegaMenu ? (activeMegaMenu === 'all' ? 'border-navy text-navy' : 'border-transparent text-ink/80') : 'border-transparent text-ink/80 hover:text-navy'"
                       class="h-16 inline-flex items-center whitespace-nowrap transition border-b-2">
                        Semua Koleksi
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                           @mouseenter="openMega('{{ $cat->slug }}')"
                           :class="activeMegaMenu ? (activeMegaMenu === '{{ $cat->slug }}' ? 'border-navy text-navy' : 'border-transparent text-ink/80') : '{{ $product->category_id == $cat->id ? 'border-navy text-navy' : 'border-transparent text-ink/80 hover:text-navy' }}'"
                           class="h-16 inline-flex items-center whitespace-nowrap transition border-b-2">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </nav>

                <!-- PILAR 3: KANAN (Aksi, Search & Transaksi) -->
                <div class="flex items-center justify-end gap-1 sm:gap-2 shrink-0 font-label text-sm">
                    
                    <!-- Search Input Tunggal -->
                    <div class="relative hidden lg:block w-44 xl:w-52">
                        <form action="{{ route('products.index') }}" method="GET">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari pakaian..." 
                                   class="w-full bg-surface-low border border-transparent focus:border-navy focus:bg-white text-sm rounded-full py-2 pl-8 pr-3 font-body focus:ring-2 focus:ring-emerald/20 placeholder-ink/80">
                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-ink/80">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                        </form>
                    </div>

                    <!-- Wishlist Icon Counter -->
                    <a href="{{ route('wishlist.index') }}" 
                       class="w-10 h-10 rounded-lg relative flex items-center justify-center text-ink hover:text-navy transition" 
                       title="Favorit Saya">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
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

                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="w-10 h-10 rounded-lg relative flex items-center justify-center text-navy hover:bg-surface-low transition" title="Keranjang">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(count(session('cart', [])) > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-navy text-white text-xs font-bold rounded-full flex items-center justify-center leading-none">
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

    <!-- Main Container Product Detail (PDP) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

        <!-- Breadcrumb Navigasi -->
        <nav class="font-label text-sm text-ink/80 mb-5 flex items-center gap-2">
            <a href="{{ route('products.index') }}" class="hover:text-navy transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-navy transition">{{ $product->category->name }}</a>
            <span>/</span>
            <span class="text-navy font-semibold truncate">{{ $product->name }}</span>
        </nav>

        <!-- Product Grid: 2 Kolom Sejak Tablet (768px) agar Judul & CTA Tampil di Layar Pertama -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 lg:gap-12 items-start">
            
            <!-- KOLOM KIRI: Galeri Foto Lookbook dengan Vertical Thumbnail Rail (Ala Uniqlo PDP) -->
            <div class="md:col-span-6 lg:col-span-7">
                <div class="md:sticky md:top-24 space-y-4">
                    
                    <div class="flex flex-col-reverse sm:flex-row gap-3 items-start">
                        <!-- Vertical Thumbnail Selector Rail di Kiri (Desktop/Tablet) & Horizontal di Mobile -->
                        <div class="flex sm:flex-col gap-2.5 shrink-0 overflow-x-auto sm:overflow-y-auto max-h-[520px] scrollbar-none">
                            @foreach($product->gallery_images as $idx => $gImg)
                                @php $imgUrl = asset('storage/' . $gImg); @endphp
                                <button type="button" 
                                        @click="activeImage = '{{ $imgUrl }}'" 
                                        :class="activeImage === '{{ $imgUrl }}' ? 'ring-2 ring-navy' : 'opacity-70 hover:opacity-100'"
                                        class="w-16 h-20 sm:w-[72px] sm:h-24 rounded-lg bg-canvas overflow-hidden transition shrink-0"
                                        title="Slide {{ $idx + 1 }}">
                                    <img src="{{ $imgUrl }}" alt="Slide {{ $idx + 1 }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>

                        <!-- Foto Utama Pakaian (Proporsional & Tidak Memakan Seluruh Layar Mobile/Tablet) -->
                        <div @click="zoomOpen = true" 
                             class="flex-1 w-full aspect-[4/5] sm:aspect-[3/4] max-h-[340px] sm:max-h-[520px] lg:max-h-[600px] rounded-2xl bg-canvas relative overflow-hidden flex items-center justify-center cursor-zoom-in group">
                            <img :src="activeImage" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-[1.03]">

                            <!-- Tombol Zoom Indikator -->
                            <div class="absolute top-3 right-3 rounded-lg bg-white/90 p-2 text-navy opacity-85 group-hover:opacity-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                            </div>

                            <!-- Kondisi Badge -->
                            <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                                <span class="rounded-md bg-navy text-white font-label font-bold text-xs px-3 py-1">
                                    Grade A+ &bull; Like New
                                </span>
                                <span class="rounded-md bg-white/95 text-emerald font-label font-bold text-xs px-2.5 py-0.5">
                                    Sanitasi Ozon 100%
                                </span>
                            </div>

                            <!-- Eco Impact Pill Tanpa Emoji -->
                            <div class="absolute bottom-3 right-3 rounded-lg bg-white/95 px-3 py-1.5 text-xs font-label font-bold text-emerald inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                                <span>Menghemat ~2.500L Air</span>
                            </div>
                        </div>
                    </div>

                    <!-- 3 Indikator Kondisi QC Transparan -->
                    <div class="grid grid-cols-3 gap-2.5 text-center font-label text-sm text-ink/80">
                        <div class="bg-white rounded-xl p-3 border border-line/60">
                            <span class="block font-bold text-navy">Warna Pekat</span>
                            <span class="text-sm text-ink/80">98% Kondisi Asli</span>
                        </div>
                        <div class="bg-white rounded-xl p-3 border border-line/60">
                            <span class="block font-bold text-navy">Jahitan &amp; Kancing</span>
                            <span class="text-sm text-ink/80">100% Utuh &amp; Rapi</span>
                        </div>
                        <div class="bg-white rounded-xl p-3 border border-line/60">
                            <span class="block font-bold text-navy">Aroma Pakaian</span>
                            <span class="text-sm text-ink/80">Steril &amp; Higienis</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- KOLOM KANAN: Informasi Pakaian & Formulir Transaksi -->
            <div class="md:col-span-6 lg:col-span-5 flex flex-col justify-between lg:sticky lg:top-24">
                <div>
                    <!-- Kategori & Tag Recommerce -->
                    <p class="font-label text-sm font-semibold text-emerald mb-1.5">
                        {{ $product->category->name }} &bull; Terkurasi Tangan Pertama
                    </p>

                    <!-- Judul Produk -->
                    <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-ink tracking-tight leading-snug">
                        {{ $product->name }}
                    </h1>

                    <!-- Dopamine Anchor Pricing (Harga Sirkula vs Retail Toko) -->
                    <div class="mt-4 pb-5">
                        <div class="flex flex-wrap items-baseline gap-3">
                            <span class="font-label text-2xl sm:text-3xl font-bold text-navy whitespace-nowrap" 
                                  x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(currentPrice)">
                                Rp {{ number_format($product->base_price, 0, ',', '.') }}
                            </span>
                            <span class="font-label text-sm text-ink/80 line-through whitespace-nowrap">
                                Rp {{ number_format($product->base_price * 2.2, 0, ',', '.') }}
                            </span>
                            <span class="font-label text-xs font-bold text-emerald bg-emerald-soft/40 px-2.5 py-0.5 whitespace-nowrap">
                                Hemat 55%
                            </span>
                        </div>
                        <p class="font-body text-sm sm:text-sm text-ink/80 mt-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Termasuk sanitasi uap medis &bull; Garansi tukar 3 hari</span>
                        </p>
                    </div>

                    <!-- Pilihan Interaktif: Warna (Color Swatch Dots ala Uniqlo) -->
                    <div class="mt-6">
                        <div class="flex justify-between items-center font-label text-sm font-bold text-ink mb-3">
                            <span>Warna: <span class="font-normal normal-case text-ink/80" x-text="selectedColor"></span></span>
                        </div>
                        <div class="flex flex-wrap gap-2.5 items-center">
                            <template x-for="color in colors" :key="color">
                                <button type="button" 
                                        @click="selectColor(color)"
                                        :class="selectedColor === color ? 'ring-2 ring-navy ring-offset-2' : 'border border-[#dcdcdc] hover:opacity-80'"
                                        class="w-7 h-7 rounded-full transition shadow-xs"
                                        :style="'background-color: ' + (color.toLowerCase().includes('putih') || color.toLowerCase().includes('white') ? '#ffffff' : (color.toLowerCase().includes('hitam') || color.toLowerCase().includes('black') ? '#181c1c' : (color.toLowerCase().includes('navy') || color.toLowerCase().includes('indigo') ? '#002547' : (color.toLowerCase().includes('green') || color.toLowerCase().includes('sage') || color.toLowerCase().includes('olive') ? '#006c47' : (color.toLowerCase().includes('terracotta') || color.toLowerCase().includes('brown') ? '#a0522d' : '#888888')))))"
                                        :title="color">
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Pilihan Interaktif: Ukuran (Size) & Panduan Ukuran Modal Link -->
                    <div class="mt-6">
                        <div class="flex justify-between items-center font-label text-sm font-bold text-ink mb-3">
                            <span>Pilih Ukuran: <span class="font-normal normal-case text-ink/80" x-text="selectedSize"></span></span>
                            <button type="button" 
                                    @click="sizeGuideOpen = true" 
                                    class="text-navy hover:underline normal-case font-semibold flex items-center space-x-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Panduan Ukuran Fisik</span>
                            </button>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="size in sizes" :key="size">
                                <button type="button" 
                                        @click="selectedSize = size; quantity = 1"
                                        :class="{
                                            'bg-navy text-white border-navy': selectedSize === size,
                                            'bg-white text-ink border-line hover:border-navy': selectedSize !== size && isSizeInStock(size),
                                            'bg-surface-low text-muted/60 border-dashed border-line line-through opacity-70': !isSizeInStock(size)
                                        }"
                                        class="min-h-[44px] py-2.5 rounded-lg font-label text-sm font-bold border transition text-center flex items-center justify-center relative">
                                    <span x-text="size"></span>
                                </button>
                            </template>
                        </div>
                        <p class="font-label text-sm text-muted mt-2">
                            Ketersediaan Stok: <strong class="text-navy" x-text="currentStock"></strong> pcs siap kirim
                        </p>
                    </div>

                    <!-- Form Tambah ke Keranjang -->
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-8">
                        @csrf
                        <input type="hidden" name="variant_id" :value="currentVariant ? currentVariant.id : ''">

                        <div class="flex items-center space-x-4 mb-4">
                            <label class="font-label text-sm font-bold text-ink">Jumlah:</label>
                            <div class="flex items-center rounded-lg border border-line bg-white overflow-hidden">
                                <button type="button" 
                                        @click="if(quantity > 1) quantity--" 
                                        class="w-10 h-10 flex items-center justify-center text-ink hover:bg-surface-low font-bold transition">-</button>
                                <input type="number" 
                                       name="quantity" 
                                       x-model="quantity" 
                                       min="1" 
                                       :max="currentStock" 
                                       class="w-12 text-center border-none font-label text-sm font-bold focus:ring-2 focus:ring-emerald/20 p-0 text-navy" readonly>
                                <button type="button" 
                                        @click="if(quantity < currentStock) quantity++" 
                                        class="w-10 h-10 flex items-center justify-center text-ink hover:bg-surface-low font-bold transition">+</button>
                            </div>
                        </div>

                        <!-- Baris Tombol CTA Utama (Solid Navy + Wishlist 52x52 + Share 52x52 ala H&M & Uniqlo PDP) -->
                        <div class="flex items-center gap-2.5">
                            <button type="submit" 
                                    :disabled="currentStock <= 0"
                                    :class="currentStock > 0 ? 'bg-navy hover:bg-navy-600 text-white cursor-pointer' : 'bg-line text-muted cursor-not-allowed'"
                                    class="flex-1 h-12 rounded-xl flex items-center justify-center font-label text-sm font-semibold transition">
                                <span x-show="currentStock > 0">+ Tambah ke Keranjang</span>
                                <span x-show="currentStock <= 0">Varian Ini Sedang Habis</span>
                            </button>

                            <!-- Tombol Wishlist PDP (52x52) -->
                            <button type="button"
                                    @click="toggleWishlist({{ $product->id }})"
                                    :title="isWishlisted({{ $product->id }}) ? 'Hapus dari Favorit' : 'Simpan ke Favorit'"
                                    class="w-[52px] h-[52px] rounded-xl border border-line-strong hover:border-navy bg-white flex items-center justify-center transition shrink-0">
                                <svg class="w-5 h-5 transition"
                                     :class="isWishlisted({{ $product->id }}) ? 'text-red-600 fill-current' : 'text-ink fill-none'"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>

                            <!-- Tombol Bagikan / Share (52x52 ala H&M PDP) -->
                            <button type="button"
                                    @click="navigator.clipboard.writeText(window.location.href); shareToast = true; setTimeout(() => shareToast = false, 2500)"
                                    title="Bagikan Produk Ini"
                                    class="w-[52px] h-[52px] rounded-xl border border-line-strong hover:border-navy bg-white flex items-center justify-center text-ink hover:text-navy transition shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            </button>
                        </div>

                        <!-- Toast Notifikasi Link Disalin -->
                        <div x-show="shareToast" x-cloak x-transition class="mt-2 bg-surface-low border border-emerald text-emerald px-3 py-2 text-sm font-label font-semibold">
                            Tautan produk berhasil disalin ke clipboard!
                        </div>
                    </form>

                    <!-- Accordion Section (Ala Uniqlo & H&M: Detail Bahan, Sanitasi, Perawatan, Pengiriman) -->
                    <div class="mt-8 border-t border-line divide-y divide-line font-body text-sm">
                        
                        <!-- Accordion 1: Detail & Spesifikasi Kurasi -->
                        <div>
                            <button type="button" 
                                    @click="accordion = (accordion === 1 ? null : 1)" 
                                    class="w-full py-4 flex items-center justify-between font-headline font-bold text-sm text-navy">
                                <span>Detail &amp; Deskripsi Pakaian</span>
                                <span x-text="accordion === 1 ? '−' : '+'" class="text-base font-bold"></span>
                            </button>
                            <div x-show="accordion === 1" x-cloak class="pb-4 text-ink/80 leading-relaxed max-w-prose">
                                <p class="whitespace-pre-line">{{ $product->description }}</p>
                            </div>
                        </div>

                        <!-- Accordion 2: Standar Sanitasi SirkulaCare -->
                        <div>
                            <button type="button" 
                                    @click="accordion = (accordion === 2 ? null : 2)" 
                                    class="w-full py-4 flex items-center justify-between font-headline font-bold text-sm text-navy">
                                <span>Standar Sanitasi SirkulaCare</span>
                                <span x-text="accordion === 2 ? '−' : '+'" class="text-base font-bold"></span>
                            </button>
                            <div x-show="accordion === 2" x-cloak class="pb-4 text-ink/80 leading-relaxed space-y-1.5">
                                <p>&bull; <strong>Suhu Uap 100°C:</strong> Membunuh bakteri dan meluruskan serat benang garmen.</p>
                                <p>&bull; <strong>Gas Ozon Medical-Grade:</strong> Mengeliminasi bau apek dan jamur tanpa bahan kimia abrasif.</p>
                                <p>&bull; <strong>Kemasan Segel Higienis:</strong> Pakaian siap langsung dipakai tanpa perlu dicuci ulang.</p>
                            </div>
                        </div>

                        <!-- Accordion 3: Instruksi Perawatan -->
                        <div>
                            <button type="button" 
                                    @click="accordion = (accordion === 3 ? null : 3)" 
                                    class="w-full py-4 flex items-center justify-between font-headline font-bold text-sm text-navy">
                                <span>Petunjuk Cuci &amp; Perawatan</span>
                                <span x-text="accordion === 3 ? '−' : '+'" class="text-base font-bold"></span>
                            </button>
                            <div x-show="accordion === 3" x-cloak class="pb-4 text-ink/80 leading-relaxed space-y-1">
                                <p>&bull; Cuci menggunakan air dingin untuk menjaga serat katun/linen tetap kuat.</p>
                                <p>&bull; Jangan menggunakan pemutih berbahan klorin.</p>
                                <p>&bull; Setrika dengan panas sedang (maks. 150°C).</p>
                            </div>
                        </div>

                        <!-- Accordion 4: Garansi & Kebijakan Tukar Ukuran -->
                        <div>
                            <button type="button" 
                                    @click="accordion = (accordion === 4 ? null : 4)" 
                                    class="w-full py-4 flex items-center justify-between font-headline font-bold text-sm text-navy">
                                <span>Garansi Tukar Ukuran 3 Hari</span>
                                <span x-text="accordion === 4 ? '−' : '+'" class="text-base font-bold"></span>
                            </button>
                            <div x-show="accordion === 4" x-cloak class="pb-4 text-ink/80 leading-relaxed">
                                <p>Bila ukuran pakaian tidak pas setelah dicoba di rumah, Anda dapat mengajukan penukaran ukuran atau pengembalian dalam 3 hari kalender setelah paket diterima.</p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        <!-- Section: Koleksi Serupa & Rekomendasi (4 Kartu Utuh ala Uniqlo & H&M) -->
        @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="mt-20 pt-12">
            <div class="flex items-baseline justify-between mb-8">
                <div>
                    <h2 class="font-headline text-lg sm:text-xl font-bold text-navy">
                        Pilihan Serupa &amp; Rekomendasi Kurator
                    </h2>
                    <p class="font-body text-sm sm:text-sm text-ink/80 mt-0.5">Pakaian terkurasi lainnya yang mungkin Anda sukai.</p>
                </div>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" 
                   class="font-label text-sm font-bold text-navy hover:underline whitespace-nowrap">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 sm:gap-x-6 gap-y-10">
                @foreach ($relatedProducts as $rel)
                    <article class="group flex flex-col cursor-pointer">
                        <a href="{{ route('products.show', $rel->slug) }}" class="relative w-full aspect-[3/4] rounded-xl bg-canvas overflow-hidden block">
                            @if($rel->image)
                                <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-102 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-ink/80">
                                    <span class="font-label text-sm">{{ $rel->category->name }}</span>
                                </div>
                            @endif
                            <div class="absolute top-2 left-2">
                                <span class="rounded-md bg-white/95 text-emerald font-label font-bold text-xs sm:text-xs px-2 py-0.5">
                                    Grade A+
                                </span>
                            </div>
                        </a>
                        <div class="pt-3">
                            <p class="font-label text-sm text-ink/80 mb-0.5 truncate">{{ $rel->category->name }}</p>
                            <h4 class="font-headline text-sm font-semibold text-ink truncate group-hover:text-navy transition">
                                <a href="{{ route('products.show', $rel->slug) }}">{{ $rel->name }}</a>
                            </h4>
                            <div class="mt-1 flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
                                <span class="font-label text-sm font-bold text-navy whitespace-nowrap">
                                    Rp {{ number_format($rel->base_price, 0, ',', '.') }}
                                </span>
                                <span class="font-label text-sm text-ink/80 line-through whitespace-nowrap">
                                    Rp {{ number_format($rel->base_price * 2.2, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
        @endif

    </main>

    <!-- Modal Panduan Ukuran Fisik (Size Guide Modal) -->
    <div x-show="sizeGuideOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click.self="sizeGuideOpen = false">
        <div class="bg-white max-w-lg w-full p-6 border border-line shadow-xl relative space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-line">
                <h3 class="font-headline font-bold text-sm text-navy">
                    Panduan Pengukuran Fisik (Flat-lay cm)
                </h3>
                <button type="button" @click="sizeGuideOpen = false" class="text-xl font-bold text-ink/80 hover:text-black">&times;</button>
            </div>
            <p class="font-body text-sm text-muted">
                Diukur mendatar oleh kurator Sirkula untuk akurasi tinggi pada postur tubuh Anda:
            </p>
            <div class="overflow-x-auto border border-line">
                <table class="w-full text-left font-label text-sm">
                    <thead class="bg-surface-low text-navy font-bold border-b border-line">
                        <tr>
                            <th class="py-2.5 px-3">Ukuran</th>
                            <th class="py-2.5 px-3">Lebar Dada (LD)</th>
                            <th class="py-2.5 px-3">Panjang Baju (PB)</th>
                            <th class="py-2.5 px-3">Panjang Lengan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line text-ink">
                        <tr><td class="py-2 px-3 font-bold text-navy">S</td><td class="py-2 px-3">50 cm</td><td class="py-2 px-3">68 cm</td><td class="py-2 px-3">21 cm</td></tr>
                        <tr><td class="py-2 px-3 font-bold text-navy">M</td><td class="py-2 px-3">53 cm</td><td class="py-2 px-3">71 cm</td><td class="py-2 px-3">22 cm</td></tr>
                        <tr><td class="py-2 px-3 font-bold text-navy">L</td><td class="py-2 px-3">56 cm</td><td class="py-2 px-3">74 cm</td><td class="py-2 px-3">23 cm</td></tr>
                        <tr><td class="py-2 px-3 font-bold text-navy">XL</td><td class="py-2 px-3">60 cm</td><td class="py-2 px-3">77 cm</td><td class="py-2 px-3">24 cm</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="text-right pt-2">
                <button type="button" @click="sizeGuideOpen = false" class="bg-navy text-white px-4 py-2 font-label text-sm font-bold">
                    Tutup Panduan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Zoom Foto Resolusi Tinggi (Ala H&M 1336x1919 Full Zoom) -->
    <div x-show="zoomOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 p-4" @click.self="zoomOpen = false">
        <div class="relative max-w-3xl w-full max-h-[92vh] bg-white overflow-hidden border border-line flex flex-col">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-line bg-white">
                <span class="font-headline font-bold text-sm text-navy">{{ $product->name }} — Inspeksi Visual Detail Serat</span>
                <button type="button" @click="zoomOpen = false" class="w-9 h-9 flex items-center justify-center text-xl font-bold text-ink hover:bg-surface-low">&times;</button>
            </div>
            <div class="overflow-y-auto flex-1 bg-canvas flex items-center justify-center p-4">
                <img :src="activeImage" alt="{{ $product->name }}" class="max-h-[78vh] w-auto object-contain">
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi "Produk Ditambahkan ke Keranjang Anda" (Ala Uniqlo 776x478 Modal) -->
    @if(session('added_to_cart'))
        @php
            $added = session('added_to_cart');
            $freeShippingMin = 250000;
            $remainingFreeShip = max(0, $freeShippingMin - $added['cart_total']);
        @endphp
        <div x-show="addedModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
             @click.self="addedModalOpen = false">
            <div class="bg-white w-full max-w-[776px] border border-line shadow-2xl overflow-hidden"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                
                <!-- Header 60px -->
                <div class="h-[60px] px-6 border-b border-line flex items-center justify-between bg-surface">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-6 h-6 rounded-full bg-emerald text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <h3 class="font-headline font-extrabold text-sm sm:text-base text-navy">
                            Produk ditambahkan ke keranjang Anda
                        </h3>
                    </div>
                    <button type="button" @click="addedModalOpen = false" class="w-10 h-10 flex items-center justify-center text-2xl text-muted hover:text-ink">&times;</button>
                </div>

                <!-- Isi Modal: Detail Barang + Total Pesanan + Progress Gratis Ongkir -->
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-center">
                        <!-- Kolom Kiri: Item yang Baru Ditambahkan -->
                        <div class="sm:col-span-7 flex items-center space-x-4 border-b sm:border-b-0 sm:border-r border-line pb-4 sm:pb-0 sm:pr-4">
                            <div class="w-20 h-24 bg-canvas border border-line shrink-0 overflow-hidden">
                                @if(!empty($added['image']))
                                    <img src="{{ asset('storage/' . $added['image']) }}" alt="{{ $added['product_name'] }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="space-y-1 font-label text-sm">
                                <span class="text-xs font-bold uppercase tracking-widest text-emerald bg-emerald-soft/30 px-2 py-0.5 inline-block">Grade A+ &bull; Sanitized</span>
                                <h4 class="font-headline font-bold text-sm text-ink">{{ $added['product_name'] }}</h4>
                                <p class="text-muted">Warna: <strong class="text-ink">{{ $added['color'] }}</strong> &bull; Ukuran: <strong class="text-ink">{{ $added['size'] }}</strong></p>
                                <p class="text-muted">Jumlah: {{ $added['quantity'] }} pcs &times; <strong class="text-navy">Rp {{ number_format($added['price'], 0, ',', '.') }}</strong></p>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Total Keranjang & Progress Gratis Ongkir (Ala Uniqlo Modal) -->
                        <div class="sm:col-span-5 space-y-3 font-label text-sm">
                            <div class="flex items-baseline justify-between">
                                <span class="text-muted">Total pesanan ({{ $added['cart_count'] }} produk):</span>
                                <strong class="text-lg font-extrabold text-navy">Rp {{ number_format($added['cart_total'], 0, ',', '.') }}</strong>
                            </div>

                            @if($remainingFreeShip > 0)
                                <div class="bg-surface-low p-3 border border-line space-y-2">
                                    <p class="text-ink leading-relaxed">
                                        Belanjakan <strong class="text-emerald">Rp {{ number_format($remainingFreeShip, 0, ',', '.') }}</strong> lagi untuk mendapatkan <strong>Pengiriman Gratis</strong>.
                                    </p>
                                    <div class="w-full h-1.5 bg-line overflow-hidden">
                                        <div class="h-full bg-emerald" style="width: {{ min(100, round(($added['cart_total'] / $freeShippingMin) * 100)) }}%"></div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-emerald-soft/25 p-3 border border-emerald/30 text-emerald font-semibold flex items-center space-x-2">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Selamat! Pesanan Anda berhak mendapatkan Pengiriman GRATIS.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer 84px: 2 Tombol Berdampingan ("LIHAT KERANJANG" & "LANJUT BELANJA" ala Uniqlo) -->
                <div class="h-[84px] px-6 bg-surface border-t border-line flex items-center justify-end gap-3">
                    <button type="button" 
                            @click="addedModalOpen = false" 
                            class="flex-1 sm:flex-initial sm:w-52 h-12 border-2 border-navy bg-white hover:bg-surface-low text-navy font-label text-xs uppercase tracking-widest font-bold transition">
                        Lanjut Belanja
                    </button>
                    <a href="{{ route('cart.index') }}" 
                       class="flex-1 sm:flex-initial sm:w-60 h-12 bg-navy hover:bg-navy-600 text-white font-label text-xs uppercase tracking-widest font-bold flex items-center justify-center transition">
                        Lihat Keranjang ({{ $added['cart_count'] }})
                    </a>
                </div>

            </div>
        </div>
    @endif

    <!-- Mobile Fixed Sticky Bottom CTA Bar (Ala Uniqlo saat Scroll) -->
    <div class="sm:hidden fixed bottom-0 inset-x-0 z-40 bg-white border-t border-line px-4 py-3 flex items-center justify-between gap-4 shadow-lg">
        <div>
            <span class="font-label text-sm text-ink/80 block">Harga Sirkula</span>
            <span class="font-label text-base font-bold text-navy whitespace-nowrap" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(currentPrice)">
                Rp {{ number_format($product->base_price, 0, ',', '.') }}
            </span>
        </div>
        <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
            @csrf
            <input type="hidden" name="variant_id" :value="currentVariant ? currentVariant.id : ''">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" 
                    :disabled="currentStock <= 0"
                    :class="currentStock > 0 ? 'bg-navy text-white' : 'bg-gray-300 text-gray-500'"
                    class="w-full h-11 flex items-center justify-center font-label text-sm font-bold">
                <span x-show="currentStock > 0">+ Keranjang</span>
                <span x-show="currentStock <= 0">Habis</span>
            </button>
        </form>
    </div>

    <!-- Comprehensive 4-Column Commercial Footer -->
    <footer class="bg-white border-t border-line mt-24 py-16 text-ink/80 font-body text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-line">
            <div class="space-y-3">
                @include('partials.brand-logo', ['size' => 'sm'])
                <p class="text-sm text-muted leading-relaxed">
                    Platform recommerce fashion sirkular yang mendigitalkan nilai kearifan lungsuran. Memperpanjang siklus pakai pakaian berkualitas untuk masa depan yang lebih lestari.
                </p>
            </div>
            <div>
                <h4 class="font-headline font-bold text-sm text-navy mb-3">Koleksi Pakaian</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('products.index', ['category' => 'kaos-t-shirt']) }}" class="hover:underline">Kaos &amp; T-Shirt Boxy</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'kemeja']) }}" class="hover:underline">Kemeja Casual &amp; Flannel</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'outerwear-jaket']) }}" class="hover:underline">Outerwear &amp; Jaket</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:underline">Rilisan Drop Terbaru</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-headline font-bold text-sm text-navy mb-3">Layanan &amp; Bantuan</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('products.index') }}#sirkulacare" class="hover:underline">Standar Sanitasi SirkulaCare</a></li>
                    <li><a href="javascript:void(0)" @click="sizeGuideOpen = true" class="hover:underline">Panduan Ukuran Fisik</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:underline">Lacak Status Pesanan</a></li>
                    <li><a href="{{ route('member') }}" class="hover:underline">Keanggotaan &amp; Garansi 3 Hari</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-headline font-bold text-sm text-navy mb-3">Program Lungsuran</h4>
                <p class="text-sm text-muted leading-relaxed mb-3">
                    Punya pakaian berkualitas di lemari yang jarang terpakai? Titip jual di Sirkula dan peroleh penghasilan.
                </p>
                <a href="{{ route('member') }}" class="inline-block font-label font-bold text-sm bg-navy text-white px-3 py-2">
                    Titip Jual Baju &rarr;
                </a>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 flex flex-col sm:flex-row justify-between items-center text-sm text-muted gap-3">
            <p>&copy; {{ date('Y') }} PT SIRKULASI INDONESIA LESTARI. Hak cipta dilindungi undang-undang.</p>
            <p class="font-label">Metode Pembayaran: Transfer BCA &bull; Mandiri &bull; BRI</p>
        </div>
    </footer>

</body>
</html>
