<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — SIRKULA Recommerce</title>
    
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
<body class="bg-[#f7faf9] text-[#181c1c] antialiased font-body selection:bg-[#002547] selection:text-white pb-20 sm:pb-0"
      x-data="{
          variants: {{ json_encode($product->variants) }},
          selectedColor: '{{ $product->variants->first()?->color ?? '' }}',
          selectedSize: '{{ $product->variants->first()?->size ?? '' }}',
          quantity: 1,
          sizeGuideOpen: false,
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
          }
      }">

    <!-- 1. Top Announcement Bar -->
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
                
                <!-- PILAR 1: KIRI (Logo Brand Sirkula) -->
                <div class="flex items-center justify-start">
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

                <!-- PILAR 2: TENGAH (Sumbu Simetri - Kategori Navigasi) -->
                <nav class="hidden md:flex items-center justify-center space-x-8 font-label text-xs uppercase tracking-widest font-semibold">
                    <a href="{{ route('products.index') }}" 
                       class="h-16 inline-flex items-center transition border-b-2 border-transparent text-[#73777f] hover:text-[#002547]">
                        Semua Koleksi
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                           class="h-16 inline-flex items-center transition border-b-2 {{ $product->category_id == $cat->id ? 'border-[#002547] text-[#002547]' : 'border-transparent text-[#73777f] hover:text-[#002547]' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </nav>

                <!-- PILAR 3: KANAN (Aksi, Search & Transaksi) -->
                <div class="flex items-center justify-end space-x-1 sm:space-x-3 font-label text-xs">
                    
                    <!-- Search Input Ringkas -->
                    <div class="relative hidden sm:block w-36 lg:w-48">
                        <form action="{{ route('products.index') }}" method="GET">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari pakaian..." 
                                   class="w-full bg-[#f1f4f3] border-none text-xs rounded-none py-1.5 pl-8 pr-2 font-body focus:ring-1 focus:ring-[#002547] placeholder-[#73777f]">
                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-[#73777f]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                        </form>
                    </div>

                    <!-- Menu Akun / Pesanan -->
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
                    <a href="{{ route('cart.index') }}" class="w-10 h-10 relative flex items-center justify-center text-[#002547] hover:opacity-80 transition" title="Keranjang">
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
    </header>

    <!-- Main Container Product Detail (PDP) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Breadcrumb Navigasi -->
        <nav class="font-label text-xs text-[#73777f] mb-8 flex items-center space-x-2">
            <a href="{{ route('products.index') }}" class="hover:text-[#002547] transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-[#002547] transition">{{ $product->category->name }}</a>
            <span>/</span>
            <span class="text-[#002547] font-semibold truncate">{{ $product->name }}</span>
        </nav>

        <!-- Product Grid: Kolom Galeri Foto vs Detail Beli -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-start">
            
            <!-- KOLOM KIRI: Galeri Foto Lookbook Multi-Angle (Rasio 3:4 Vertikal ala Uniqlo) -->
            <div class="lg:col-span-7">
                <div class="sticky top-24 space-y-4">
                    
                    <!-- Foto Utama Pakaian (Active Image Switcher) -->
                    <div class="w-full aspect-[3/4] bg-[#f4f6f5] border border-[#e0e3e2] relative overflow-hidden flex items-center justify-center">
                        <img :src="activeImage" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover object-center transition-all duration-300">

                        <!-- Kondisi Badge -->
                        <div class="absolute top-4 left-4 flex flex-col gap-1.5">
                            <span class="bg-[#002547] text-white font-label font-bold text-[10px] uppercase tracking-widest px-3 py-1 shadow-sm">
                                Grade A+ &bull; Like New
                            </span>
                            <span class="bg-white/95 text-[#006c47] font-label font-bold text-[10px] uppercase tracking-widest px-2.5 py-0.5 border border-[#85f9bf]">
                                Sanitasi Ozon 100%
                            </span>
                        </div>

                        <!-- Micro Eco Impact Pill -->
                        <div class="absolute bottom-4 right-4 bg-white/95 backdrop-blur-xs border border-[#e0e3e2] px-3 py-1 text-xs font-label font-bold text-[#006c47]">
                            💧 Menghemat ~2.500L Air
                        </div>
                    </div>

                    <!-- Thumbnail Selector Strip (Tampak Depan & Detail Fabric) -->
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                @click="activeImage = '{{ asset('storage/' . ($product->image ?? 'products/tshirt-boxy-1.jpg')) }}'" 
                                :class="activeImage === '{{ asset('storage/' . ($product->image ?? 'products/tshirt-boxy-1.jpg')) }}' ? 'ring-2 ring-[#002547] border-transparent' : 'border-[#e0e3e2] opacity-70 hover:opacity-100'"
                                class="w-16 h-20 bg-[#f4f6f5] border overflow-hidden transition">
                            <img src="{{ asset('storage/' . ($product->image ?? 'products/tshirt-boxy-1.jpg')) }}" alt="Tampak Depan" class="w-full h-full object-cover">
                        </button>

                        <button type="button" 
                                @click="activeImage = secondaryImage" 
                                :class="activeImage === secondaryImage ? 'ring-2 ring-[#002547] border-transparent' : 'border-[#e0e3e2] opacity-70 hover:opacity-100'"
                                class="w-16 h-20 bg-[#f4f6f5] border overflow-hidden transition">
                            <img :src="secondaryImage" alt="Detail &amp; Texture" class="w-full h-full object-cover">
                        </button>
                    </div>

                    <!-- 3 Indikator Kondisi QC Transparan -->
                    <div class="grid grid-cols-3 gap-2 text-center font-label text-xs text-[#43474e] pt-2">
                        <div class="bg-white border border-[#e0e3e2] p-2.5">
                            <span class="block font-bold text-[#002547]">Warna Pekat</span>
                            <span class="text-[11px] text-[#73777f]">98% Kondisi Asli</span>
                        </div>
                        <div class="bg-white border border-[#e0e3e2] p-2.5">
                            <span class="block font-bold text-[#002547]">Jahitan &amp; Kancing</span>
                            <span class="text-[11px] text-[#73777f]">100% Utuh &amp; Rapi</span>
                        </div>
                        <div class="bg-white border border-[#e0e3e2] p-2.5">
                            <span class="block font-bold text-[#002547]">Aroma Pakaian</span>
                            <span class="text-[11px] text-[#73777f]">Steril &amp; Higienis</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- KOLOM KANAN: Informasi Pakaian & Formulir Transaksi -->
            <div class="lg:col-span-5 flex flex-col justify-between">
                <div>
                    <!-- Kategori & Tag Recommerce -->
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="font-label text-xs uppercase tracking-widest text-[#006c47] font-bold">
                            {{ $product->category->name }}
                        </span>
                        <span class="text-[#73777f] text-xs">&bull;</span>
                        <span class="font-label text-xs uppercase tracking-widest text-[#73777f] font-semibold">
                            Terkurasi Tangan Pertama
                        </span>
                    </div>

                    <!-- Judul Produk (24px Plus Jakarta Sans yang Tenang & Berkelas) -->
                    <h1 class="font-headline text-2xl font-bold text-[#181c1c] tracking-tight leading-snug">
                        {{ $product->name }}
                    </h1>

                    <!-- Dopamine Anchor Pricing (Harga Sirkula vs Retail Toko) -->
                    <div class="mt-4 pb-6 border-b border-[#e0e3e2]">
                        <div class="flex items-baseline space-x-3">
                            <span class="font-label text-2xl sm:text-3xl font-bold text-[#002547]" 
                                  x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(currentPrice)">
                                Rp {{ number_format($product->base_price, 0, ',', '.') }}
                            </span>
                            <span class="font-label text-sm text-[#73777f] line-through">
                                Rp {{ number_format($product->base_price * 2.2, 0, ',', '.') }}
                            </span>
                            <span class="font-label text-xs font-bold text-[#006c47] bg-[#85f9bf]/40 border border-[#85f9bf] px-2 py-0.5">
                                Hemat 55%
                            </span>
                        </div>
                        <p class="font-body text-xs text-[#73777f] mt-1.5 flex items-center space-x-2">
                            <span>✓ Termasuk sanitasi uap medis</span>
                            <span>&bull;</span>
                            <span>Garansi tukar 3 hari</span>
                        </p>
                    </div>

                    <!-- Pilihan Interaktif: Warna (Color Swatch Dots ala Uniqlo) -->
                    <div class="mt-6">
                        <div class="flex justify-between items-center font-label text-xs font-bold uppercase tracking-wider text-[#181c1c] mb-3">
                            <span>Warna: <span class="font-normal normal-case text-[#43474e]" x-text="selectedColor"></span></span>
                        </div>
                        <div class="flex flex-wrap gap-2.5 items-center">
                            <template x-for="color in colors" :key="color">
                                <button type="button" 
                                        @click="selectColor(color)"
                                        :class="selectedColor === color ? 'ring-2 ring-[#002547] ring-offset-2' : 'border border-[#dcdcdc] hover:opacity-80'"
                                        class="w-7 h-7 rounded-full transition shadow-xs"
                                        :style="'background-color: ' + (color.toLowerCase().includes('putih') || color.toLowerCase().includes('white') ? '#ffffff' : (color.toLowerCase().includes('hitam') || color.toLowerCase().includes('black') ? '#181c1c' : (color.toLowerCase().includes('navy') || color.toLowerCase().includes('indigo') ? '#002547' : (color.toLowerCase().includes('green') || color.toLowerCase().includes('sage') || color.toLowerCase().includes('olive') ? '#006c47' : (color.toLowerCase().includes('terracotta') || color.toLowerCase().includes('brown') ? '#a0522d' : '#888888')))))"
                                        :title="color">
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Pilihan Interaktif: Ukuran (Size) & Panduan Ukuran Modal Link -->
                    <div class="mt-6">
                        <div class="flex justify-between items-center font-label text-xs font-bold uppercase tracking-wider text-[#181c1c] mb-3">
                            <span>Pilih Ukuran: <span class="font-normal normal-case text-[#43474e]" x-text="selectedSize"></span></span>
                            <button type="button" 
                                    @click="sizeGuideOpen = true" 
                                    class="text-[#002547] hover:underline normal-case font-semibold flex items-center space-x-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Panduan Ukuran Fisik</span>
                            </button>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="size in sizes" :key="size">
                                <button type="button" 
                                        @click="selectedSize = size; quantity = 1"
                                        :class="selectedSize === size ? 'bg-[#002547] text-white border-[#002547]' : 'bg-white text-[#181c1c] border-[#e0e3e2] hover:border-[#002547]'"
                                        class="py-3 font-label text-xs font-bold border transition text-center uppercase">
                                    <span x-text="size"></span>
                                </button>
                            </template>
                        </div>
                        <p class="font-label text-xs text-[#73777f] mt-2">
                            Ketersediaan Stok: <strong class="text-[#002547]" x-text="currentStock"></strong> pcs siap kirim
                        </p>
                    </div>

                    <!-- Form Tambah ke Keranjang -->
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-8">
                        @csrf
                        <input type="hidden" name="variant_id" :value="currentVariant ? currentVariant.id : ''">

                        <div class="flex items-center space-x-4 mb-4">
                            <label class="font-label text-xs uppercase tracking-wider font-bold text-[#181c1c]">Jumlah:</label>
                            <div class="flex items-center border border-[#e0e3e2] bg-white">
                                <button type="button" 
                                        @click="if(quantity > 1) quantity--" 
                                        class="w-10 h-10 flex items-center justify-center text-[#181c1c] hover:bg-[#f1f4f3] font-bold transition">-</button>
                                <input type="number" 
                                       name="quantity" 
                                       x-model="quantity" 
                                       min="1" 
                                       :max="currentStock" 
                                       class="w-12 text-center border-none font-label text-xs font-bold focus:ring-0 p-0 text-[#002547]" readonly>
                                <button type="button" 
                                        @click="if(quantity < currentStock) quantity++" 
                                        class="w-10 h-10 flex items-center justify-center text-[#181c1c] hover:bg-[#f1f4f3] font-bold transition">+</button>
                            </div>
                        </div>

                        <!-- Tombol CTA Utama (52px Solid #002547) -->
                        <button type="submit" 
                                :disabled="currentStock <= 0"
                                :class="currentStock > 0 ? 'bg-[#002547] hover:bg-[#000f23] text-white shadow-xs cursor-pointer' : 'bg-[#e0e3e2] text-[#73777f] cursor-not-allowed'"
                                class="w-full h-[52px] flex items-center justify-center font-label text-xs uppercase tracking-widest font-bold transition">
                            <span x-show="currentStock > 0">+ Tambah ke Keranjang</span>
                            <span x-show="currentStock <= 0">Varian Ini Sedang Habis</span>
                        </button>
                    </form>

                    <!-- Accordion Section (Ala Uniqlo & H&M: Detail Bahan, Sanitasi, Perawatan, Pengiriman) -->
                    <div class="mt-8 border-t border-[#e0e3e2] divide-y divide-[#e0e3e2] font-body text-xs">
                        
                        <!-- Accordion 1: Detail & Spesifikasi Kurasi -->
                        <div>
                            <button type="button" 
                                    @click="accordion = (accordion === 1 ? null : 1)" 
                                    class="w-full py-4 flex items-center justify-between font-headline font-bold text-xs uppercase tracking-wider text-[#002547]">
                                <span>Detail &amp; Deskripsi Pakaian</span>
                                <span x-text="accordion === 1 ? '−' : '+'" class="text-base font-bold"></span>
                            </button>
                            <div x-show="accordion === 1" x-cloak class="pb-4 text-[#43474e] leading-relaxed">
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>

                        <!-- Accordion 2: Standar Sanitasi SirkulaCare -->
                        <div>
                            <button type="button" 
                                    @click="accordion = (accordion === 2 ? null : 2)" 
                                    class="w-full py-4 flex items-center justify-between font-headline font-bold text-xs uppercase tracking-wider text-[#002547]">
                                <span>Standar Sanitasi SirkulaCare</span>
                                <span x-text="accordion === 2 ? '−' : '+'" class="text-base font-bold"></span>
                            </button>
                            <div x-show="accordion === 2" x-cloak class="pb-4 text-[#43474e] leading-relaxed space-y-1.5">
                                <p>&bull; <strong>Suhu Uap 100°C:</strong> Membunuh bakteri dan meluruskan serat benang garmen.</p>
                                <p>&bull; <strong>Gas Ozon Medical-Grade:</strong> Mengeliminasi bau apek dan jamur tanpa bahan kimia abrasif.</p>
                                <p>&bull; <strong>Kemasan Segel Higienis:</strong> Pakaian siap langsung dipakai tanpa perlu dicuci ulang.</p>
                            </div>
                        </div>

                        <!-- Accordion 3: Instruksi Perawatan -->
                        <div>
                            <button type="button" 
                                    @click="accordion = (accordion === 3 ? null : 3)" 
                                    class="w-full py-4 flex items-center justify-between font-headline font-bold text-xs uppercase tracking-wider text-[#002547]">
                                <span>Petunjuk Cuci &amp; Perawatan</span>
                                <span x-text="accordion === 3 ? '−' : '+'" class="text-base font-bold"></span>
                            </button>
                            <div x-show="accordion === 3" x-cloak class="pb-4 text-[#43474e] leading-relaxed space-y-1">
                                <p>&bull; Cuci menggunakan air dingin untuk menjaga serat katun/linen tetap kuat.</p>
                                <p>&bull; Jangan menggunakan pemutih berbahan klorin.</p>
                                <p>&bull; Setrika dengan panas sedang (maks. 150°C).</p>
                            </div>
                        </div>

                        <!-- Accordion 4: Garansi & Kebijakan Tukar Ukuran -->
                        <div>
                            <button type="button" 
                                    @click="accordion = (accordion === 4 ? null : 4)" 
                                    class="w-full py-4 flex items-center justify-between font-headline font-bold text-xs uppercase tracking-wider text-[#002547]">
                                <span>Garansi Tukar Ukuran 3 Hari</span>
                                <span x-text="accordion === 4 ? '−' : '+'" class="text-base font-bold"></span>
                            </button>
                            <div x-show="accordion === 4" x-cloak class="pb-4 text-[#43474e] leading-relaxed">
                                <p>Bila ukuran pakaian tidak pas setelah dicoba di rumah, Anda dapat mengajukan penukaran ukuran atau pengembalian dalam 3 hari kalender setelah paket diterima.</p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        <!-- Section: Koleksi Serupa (Related Products ala Uniqlo & H&M) -->
        @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="mt-20 pt-12 border-t border-[#e0e3e2]">
            <div class="flex items-baseline justify-between mb-8">
                <div>
                    <h2 class="font-headline text-lg sm:text-xl font-bold text-[#002547]">
                        Pilihan Serupa di Kategori {{ $product->category->name }}
                    </h2>
                    <p class="font-body text-xs text-[#73777f] mt-0.5">Pakaian terkurasi lainnya yang mungkin Anda sukai.</p>
                </div>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" 
                   class="font-label text-xs uppercase tracking-wider font-bold text-[#002547] hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-5 gap-y-10">
                @foreach ($relatedProducts as $rel)
                    <article class="group flex flex-col cursor-pointer">
                        <a href="{{ route('products.show', $rel->slug) }}" class="relative w-full aspect-[3/4] bg-[#f4f6f5] overflow-hidden block">
                            @if($rel->image)
                                <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-102 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#73777f]">
                                    <span class="font-label text-xs uppercase">{{ $rel->category->name }}</span>
                                </div>
                            @endif
                            <div class="absolute top-2 left-2">
                                <span class="bg-white/95 text-[#006c47] font-label font-bold text-[9px] uppercase tracking-widest px-2 py-0.5 border border-[#85f9bf]">
                                    Grade A+
                                </span>
                            </div>
                        </a>
                        <div class="pt-3">
                            <p class="font-label text-xs text-[#73777f] uppercase tracking-wider mb-0.5">{{ $rel->category->name }}</p>
                            <h4 class="font-headline text-xs font-semibold text-[#181c1c] truncate group-hover:text-[#002547] transition">
                                <a href="{{ route('products.show', $rel->slug) }}">{{ $rel->name }}</a>
                            </h4>
                            <div class="mt-1 flex items-baseline space-x-2">
                                <span class="font-label text-xs font-bold text-[#002547]">
                                    Rp {{ number_format($rel->base_price, 0, ',', '.') }}
                                </span>
                                <span class="font-label text-[11px] text-[#73777f] line-through">
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
        <div class="bg-white max-w-lg w-full p-6 border border-[#e0e3e2] shadow-xl relative space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#e0e3e2]">
                <h3 class="font-headline font-bold text-sm uppercase tracking-wider text-[#002547]">
                    📐 Panduan Pengukuran Fisik (Flat-lay cm)
                </h3>
                <button type="button" @click="sizeGuideOpen = false" class="text-xl font-bold text-[#73777f] hover:text-black">&times;</button>
            </div>
            <p class="font-body text-xs text-[#73777f]">
                Diukur mendatar oleh kurator Sirkula untuk akurasi tinggi pada postur tubuh Anda:
            </p>
            <div class="overflow-x-auto border border-[#e0e3e2]">
                <table class="w-full text-left font-label text-xs">
                    <thead class="bg-[#f1f4f3] text-[#002547] font-bold border-b border-[#e0e3e2]">
                        <tr>
                            <th class="py-2.5 px-3">Ukuran</th>
                            <th class="py-2.5 px-3">Lebar Dada (LD)</th>
                            <th class="py-2.5 px-3">Panjang Baju (PB)</th>
                            <th class="py-2.5 px-3">Panjang Lengan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e0e3e2] text-[#181c1c]">
                        <tr><td class="py-2 px-3 font-bold text-[#002547]">S</td><td class="py-2 px-3">50 cm</td><td class="py-2 px-3">68 cm</td><td class="py-2 px-3">21 cm</td></tr>
                        <tr><td class="py-2 px-3 font-bold text-[#002547]">M</td><td class="py-2 px-3">53 cm</td><td class="py-2 px-3">71 cm</td><td class="py-2 px-3">22 cm</td></tr>
                        <tr><td class="py-2 px-3 font-bold text-[#002547]">L</td><td class="py-2 px-3">56 cm</td><td class="py-2 px-3">74 cm</td><td class="py-2 px-3">23 cm</td></tr>
                        <tr><td class="py-2 px-3 font-bold text-[#002547]">XL</td><td class="py-2 px-3">60 cm</td><td class="py-2 px-3">77 cm</td><td class="py-2 px-3">24 cm</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="text-right pt-2">
                <button type="button" @click="sizeGuideOpen = false" class="bg-[#002547] text-white px-4 py-2 font-label text-xs uppercase font-bold">
                    Tutup Panduan
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Fixed Sticky Bottom CTA Bar (Ala Uniqlo saat Scroll) -->
    <div class="sm:hidden fixed bottom-0 inset-x-0 z-40 bg-white border-t border-[#e0e3e2] p-3 flex items-center justify-between gap-3 shadow-lg">
        <div>
            <span class="font-label text-[10px] text-[#73777f] block uppercase tracking-wider">Harga Sirkula</span>
            <span class="font-label text-base font-bold text-[#002547]" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(currentPrice)">
                Rp {{ number_format($product->base_price, 0, ',', '.') }}
            </span>
        </div>
        <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
            @csrf
            <input type="hidden" name="variant_id" :value="currentVariant ? currentVariant.id : ''">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" 
                    :disabled="currentStock <= 0"
                    :class="currentStock > 0 ? 'bg-[#002547] text-white' : 'bg-gray-300 text-gray-500'"
                    class="w-full h-11 flex items-center justify-center font-label text-xs uppercase font-bold tracking-wider">
                <span x-show="currentStock > 0">+ Keranjang</span>
                <span x-show="currentStock <= 0">Habis</span>
            </button>
        </form>
    </div>

    <!-- Comprehensive 4-Column Commercial Footer -->
    <footer class="bg-white border-t border-[#e0e3e2] mt-24 py-16 text-[#43474e] font-body text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-[#e0e3e2]">
            <div class="space-y-3">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 bg-[#002547] text-white flex items-center justify-center font-headline font-black text-xs">S</span>
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
                    <li><a href="{{ route('products.index') }}" class="hover:underline">Rilisan Drop Terbaru</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-[#002547] mb-3">Layanan &amp; Bantuan</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:underline">Standar Sanitasi SirkulaCare</a></li>
                    <li><a href="#" class="hover:underline">Panduan Ukuran Fisik</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:underline">Lacak Status Pesanan</a></li>
                    <li><a href="#" class="hover:underline">Kebijakan Pengembalian 3 Hari</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-[#002547] mb-3">Program Lungsuran</h4>
                <p class="text-xs text-[#73777f] leading-relaxed mb-3">
                    Punya pakaian berkualitas di lemari yang jarang terpakai? Titip jual di Sirkula dan peroleh penghasilan.
                </p>
                <a href="#" class="inline-block font-label font-bold text-[11px] uppercase tracking-wider bg-[#002547] text-white px-3 py-2">
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
