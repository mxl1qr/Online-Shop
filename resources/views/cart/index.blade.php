<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja — SIRKULA Recommerce</title>
    
    <!-- Google Fonts Trio dari DESIGN.md -->
    @include('partials.head-fonts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body x-data="{
          wishlist: JSON.parse(localStorage.getItem('sirkula_wishlist_{{ auth()->id() ?? 'guest' }}') || '[]'),
          couponOpen: false,
          couponCode: '',
          couponApplied: false,
          toggleWishlist(id) {
              if (!id) return;
              if (this.wishlist.includes(id)) {
                  this.wishlist = this.wishlist.filter(item => item !== id);
              } else {
                  this.wishlist.push(id);
              }
              localStorage.setItem('sirkula_wishlist_{{ auth()->id() ?? 'guest' }}', JSON.stringify(this.wishlist));
          },
          isWishlisted(id) {
              return id && this.wishlist.includes(id);
          }
      }"
      class="bg-surface text-ink antialiased font-body selection:bg-navy selection:text-white">

    <!-- 1. Top Announcement Bar -->
    <div class="bg-navy text-white text-xs py-2 px-4 text-center font-label font-medium tracking-wide flex items-center justify-center space-x-3">
        <span class="inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-emerald-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            100% Terkurasi &amp; Higienis (Sanitized)
        </span>
        <span class="text-white/40 hidden sm:inline">&bull;</span>
        <span class="hidden sm:inline">Garansi Keaslian SirkulaVerify</span>
        <span class="text-white/40 hidden md:inline">&bull;</span>
        <span class="hidden md:inline">Gratis Ongkir ke Seluruh Indonesia min. Rp250.000</span>
    </div>

    <!-- 2. Main Navigation Bar (3 Pilar Presisi 64px Tanpa Wrapping) -->
    <header class="sticky top-0 z-50 bg-white border-b border-line">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-3 lg:gap-6">
                
                <!-- PILAR 1: KIRI (Logo Brand Sirkula) -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <a href="{{ route('products.index') }}" class="lg:hidden w-10 h-10 -ml-1 flex items-center justify-center text-navy hover:bg-surface-low rounded-lg transition" title="Kembali ke Katalog">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-2.5 group shrink-0">
                        @include('partials.brand-logo', ['size' => 'md'])
                    </a>
                </div>

                <!-- PILAR 2: TENGAH (Sumbu Simetri - Step Indicator 1 Baris Utuh) -->
                <nav class="hidden lg:flex items-center justify-center gap-6 whitespace-nowrap font-label text-sm font-semibold">
                    <span class="text-navy font-bold border-b-2 border-navy pb-0.5 whitespace-nowrap">1. Keranjang Belanja</span>
                    <span class="text-ink/80">&rarr;</span>
                    <span class="text-ink/80 whitespace-nowrap">2. Pengiriman &amp; Bayar</span>
                    <span class="text-ink/80">&rarr;</span>
                    <span class="text-ink/80 whitespace-nowrap">3. Selesai</span>
                </nav>

                <!-- PILAR 3: KANAN (Aksi, Wishlist & User Profile) -->
                <div class="flex items-center justify-end gap-1 sm:gap-2 shrink-0 font-label text-sm">
                    <a href="{{ route('wishlist.index') }}" 
                       class="w-10 h-10 relative flex items-center justify-center text-ink hover:text-navy transition" 
                       title="Favorit Saya">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span x-show="wishlist.length > 0" 
                              x-text="wishlist.length" 
                              class="absolute top-1 right-1 w-4 h-4 bg-emerald text-white text-xs font-bold rounded-full flex items-center justify-center leading-none">
                        </span>
                    </a>
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.orders.index') }}" class="text-emerald bg-emerald-soft/30 px-2.5 py-1 rounded-md hover:bg-emerald-soft/50 transition font-bold uppercase tracking-wider text-xs">
                                Admin
                            </a>
                        @endif
                        <a href="{{ route('member') }}" class="w-10 h-10 flex items-center justify-center text-ink hover:text-navy transition" title="Keanggotaan Sirkula">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex h-9 px-2.5 items-center text-sm font-semibold text-ink hover:text-navy whitespace-nowrap transition">
                            Masuk
                        </a>
                    @endauth

                    <span class="w-10 h-10 relative flex items-center justify-center text-navy">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(count($cart) > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-navy text-white text-xs font-bold rounded-full flex items-center justify-center leading-none">
                                {{ count($cart) }}
                            </span>
                        @endif
                    </span>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        
        <!-- Notifikasi Flash Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-soft/20 border border-emerald-soft text-emerald font-label text-sm font-bold flex items-center justify-between">
                <span>✓ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald hover:opacity-70 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 font-label text-sm font-bold flex items-center justify-between">
                <span>⚠ {{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:opacity-70 font-bold">&times;</button>
            </div>
        @endif

        <!-- Judul Halaman -->
        <div class="flex items-baseline justify-between border-b border-line pb-4 mb-8">
            <div>
                <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">
                    Keranjang Belanja
                </h1>
                <p class="font-body text-sm text-muted mt-0.5">Pakaian pilihan Anda sebelum diproses pengiriman higienis.</p>
            </div>
            @if(count($cart) > 0)
                <span class="font-label text-xs text-emerald font-semibold bg-emerald-soft/30 px-2.5 py-1">
                    {{ count($cart) }} Item Pakaian
                </span>
            @endif
        </div>

        @if(empty($cart))
            <!-- Kondisi Keranjang Kosong -->
            <div class="card px-6 py-14 sm:p-16 text-center max-w-lg mx-auto">
                <div class="w-16 h-16 bg-canvas border border-line rounded-full flex items-center justify-center mx-auto mb-4 text-navy">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h2 class="font-headline text-lg font-bold text-navy mb-2">Keranjang Belanja Anda Masih Kosong</h2>
                <p class="font-body text-sm sm:text-sm text-ink/80 mb-8 leading-relaxed">
                    Setiap potong pakaian di Sirkula adalah satu-satunya kurasi eksklusif. Temukan pakaian esensial favorit Anda sekarang.
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center justify-center w-full sm:w-auto bg-navy hover:bg-navy-600 text-white font-label text-xs font-bold uppercase tracking-widest px-8 py-3.5 rounded-lg whitespace-nowrap transition">
                    Jelajahi Koleksi Pakaian &rarr;
                </a>
            </div>
        @else
            <!-- Grid Keranjang: List Barang (8 Col) vs Ringkasan Pesanan (4 Col) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- KOLOM KIRI: Daftar Pakaian di Keranjang -->
                <div class="lg:col-span-8 space-y-4">
                    @foreach($cart as $variantId => $item)
                        <div class="bg-white border border-line rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row gap-4 sm:gap-6 items-start sm:items-center justify-between">
                            
                            <!-- Foto & Informasi Barang (Thumbnail 3:4 ala Uniqlo 152x203) -->
                            <div class="flex items-start space-x-4">
                                <a href="{{ route('products.show', $item['slug']) }}" 
                                   class="w-24 h-32 bg-canvas shrink-0 border border-line rounded-lg overflow-hidden flex items-center justify-center">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8 text-line-strong" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    @endif
                                </a>

                                <div>
                                    <span class="font-label text-sm text-emerald font-bold block mb-0.5">
                                        {{ $item['category'] }} &bull; Grade A+ Sanitized
                                    </span>
                                    <h3 class="font-headline text-sm sm:text-base font-bold text-ink leading-snug">
                                        <a href="{{ route('products.show', $item['slug']) }}" class="hover:text-navy transition">
                                            {{ $item['product_name'] }}
                                        </a>
                                    </h3>
                                    
                                    <!-- Rincian Varian -->
                                    <div class="mt-1.5 flex items-center space-x-2 font-label text-sm text-muted">
                                        <span>Warna: <strong class="text-navy">{{ $item['color'] }}</strong></span>
                                        <span>&bull;</span>
                                        <span>Ukuran: <strong class="text-navy">{{ $item['size'] }}</strong></span>
                                    </div>

                                    <!-- Harga Satuan -->
                                    <p class="font-label text-sm font-bold text-navy mt-2">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }} / pcs
                                    </p>
                                </div>
                            </div>

                            <!-- Qty Controller (Stepper 41x44 ala Uniqlo), Wishlist 44x44 & Total Per Item -->
                            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-5 pt-3 sm:pt-0 border-t sm:border-t-0 border-line">
                                
                                <!-- Tombol Simpan ke Favorit di Baris Cart (44x44 ala Uniqlo /cart) -->
                                @if(!empty($item['product_id']))
                                    <button type="button"
                                            @click="toggleWishlist({{ $item['product_id'] }})"
                                            :title="isWishlisted({{ $item['product_id'] }}) ? 'Tersimpan di Favorit' : 'Simpan ke Favorit'"
                                            class="w-11 h-11 border border-line rounded-lg hover:border-navy flex items-center justify-center transition">
                                        <svg class="w-4 h-4 transition"
                                             :class="isWishlisted({{ $item['product_id'] }}) ? 'text-red-600 fill-current' : 'text-muted fill-none'"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                @endif

                                <!-- Form Update Kuantitas (41x44 Stepper) -->
                                <form action="{{ route('cart.update', $variantId) }}" method="POST" class="flex items-center border border-line-strong rounded-lg bg-white overflow-hidden">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            name="quantity" 
                                            value="{{ max(1, $item['quantity'] - 1) }}"
                                            class="w-10 h-11 flex items-center justify-center text-ink hover:bg-surface-low font-bold transition"
                                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                        &minus;
                                    </button>
                                    <span class="w-10 text-center font-label text-sm font-bold text-navy">
                                        {{ $item['quantity'] }}
                                    </span>
                                    <button type="submit" 
                                            name="quantity" 
                                            value="{{ min($item['max_stock'] ?? 99, $item['quantity'] + 1) }}"
                                            class="w-10 h-11 flex items-center justify-center text-ink hover:bg-surface-low font-bold transition"
                                            {{ $item['quantity'] >= ($item['max_stock'] ?? 99) ? 'disabled' : '' }}>
                                        +
                                    </button>
                                </form>

                                <!-- Subtotal per Item & Hapus -->
                                <div class="text-right min-w-[110px]">
                                    <span class="font-label text-sm font-bold text-navy block">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </span>
                                    
                                    <form action="{{ route('cart.remove', $variantId) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-label text-sm text-red-600 hover:underline font-semibold mt-1">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- KOLOM KANAN: Ringkasan Belanja (Sticky) -->
                <div class="lg:col-span-4 sticky top-24">
                    <div class="card p-6 space-y-5">
                        <h2 class="font-headline text-sm font-bold text-navy pb-3 border-b border-line">
                            Ringkasan Transaksi
                        </h2>

                        <!-- Progress Bar Gratis Ongkir -->
                        @php
                            $freeShippingThreshold = 250000;
                            $diff = max(0, $freeShippingThreshold - $total);
                            $percent = min(100, round(($total / $freeShippingThreshold) * 100));
                            $estimatedTax = round($total * 0.11);
                        @endphp
                        <div class="bg-surface-low rounded-xl p-3.5 border border-line">
                            @if($diff > 0)
                                <p class="font-label text-sm text-ink/80 mb-2">
                                    Belanjakan <strong class="text-emerald">Rp {{ number_format($diff, 0, ',', '.') }}</strong> lagi untuk <strong>Pengiriman Gratis</strong>.
                                </p>
                            @else
                                <p class="font-label text-sm text-emerald font-bold mb-2 flex items-center space-x-1.5">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Selamat! Anda mendapatkan ongkir GRATIS.</span>
                                </p>
                            @endif
                            <div class="w-full bg-line rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald h-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>

                        <!-- Opsi Kupon / Voucher Diskon (Ala Uniqlo 376x52 & H&M Voucher Toggle) -->
                        <div class="border border-line rounded-xl overflow-hidden">
                            <button type="button" 
                                    @click="couponOpen = !couponOpen"
                                    class="w-full h-12 px-4 flex items-center justify-between font-label text-sm font-bold text-ink hover:bg-surface-low transition">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                    Gunakan Kupon / E-Voucher
                                </span>
                                <span x-text="couponOpen ? '−' : '+'" class="text-base"></span>
                            </button>
                            <div x-show="couponOpen" x-cloak class="p-3 border-t border-line bg-surface space-y-2">
                                <div class="flex gap-2">
                                    <input type="text" 
                                           x-model="couponCode" 
                                           placeholder="Masukkan kode (cth: SIRKULAFREE)" 
                                           class="flex-1 bg-white border border-line-strong rounded-xl text-sm px-3 py-2 font-label focus:ring-2 focus:ring-emerald/20 focus:border-navy">
                                    <button type="button" 
                                            @click="if(couponCode.trim()) couponApplied = true"
                                            class="bg-navy text-white font-label text-sm font-bold px-4 py-2 rounded-lg">
                                        Pakai
                                    </button>
                                </div>
                                <p x-show="couponApplied" x-cloak class="text-sm font-label text-emerald font-semibold">
                                    Kupon SirkulaVerify aktif: Bebas biaya layanan sanitasi &amp; inspeksi.
                                </p>
                            </div>
                        </div>

                        <!-- Baris Rincian Harga -->
                        <div class="space-y-3 font-label text-sm">
                            <div class="flex justify-between text-muted">
                                <span>Subtotal ({{ count($cart) }} Item):</span>
                                <span class="font-semibold text-ink">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-muted">
                                <span>Biaya Sanitasi Ozon SirkulaCare:</span>
                                <span class="text-emerald font-semibold">GRATIS (Rp 0)</span>
                            </div>
                            <div class="flex justify-between text-muted">
                                <span>Estimasi Pengiriman:</span>
                                <span class="font-semibold text-ink">
                                    {{ $total >= 250000 ? 'GRATIS' : 'Dihitung saat Checkout' }}
                                </span>
                            </div>
                            <div class="pt-3 border-t border-line">
                                <div class="flex justify-between items-baseline">
                                    <span class="font-headline font-bold text-sm text-navy">Total Pesanan:</span>
                                    <span class="font-label text-xl font-extrabold text-navy">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>
                                <span class="text-sm text-muted block mt-0.5">
                                    Termasuk estimasi PPN Rp {{ number_format($estimatedTax, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Tombol CTA Checkout & Lanjutkan Belanja -->
                        <div class="space-y-2.5">
                            <a href="{{ route('checkout.index') }}" 
                               class="btn btn-primary w-full text-center">
                                Lanjutkan ke Pembayaran &rarr;
                            </a>
                            <a href="{{ route('products.index') }}" 
                               class="btn btn-outline w-full text-center">
                                Lanjutkan Belanja
                            </a>
                        </div>

                        <!-- Micro-copy Trust & Metode Pembayaran (Ala Uniqlo 30m Reservation & H&M Encrypted Copy) -->
                        <div class="pt-3 border-t border-line font-body text-sm text-muted space-y-2">
                            <p class="leading-relaxed">
                                Produk pilihan Anda akan dikunci stoknya selama proses checkout berlangsung. Semua data transaksi dienkripsi secara aman.
                            </p>
                            <p class="font-label font-semibold text-ink">
                                Garansi Penukaran Ukuran 3 Hari &bull; SirkulaVerify
                            </p>
                            <div class="flex flex-wrap gap-1.5 pt-1 font-label text-sm font-bold text-navy">
                                <span class="px-2 py-1 bg-surface-low border border-line rounded-md">BCA</span>
                                <span class="px-2 py-1 bg-surface-low border border-line rounded-md">MANDIRI</span>
                                <span class="px-2 py-1 bg-surface-low border border-line rounded-md">BRI</span>
                                <span class="px-2 py-1 bg-surface-low border border-line rounded-md">BNI</span>
                                <span class="px-2 py-1 bg-surface-low border border-line rounded-md">QRIS</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        <!-- Rail "ANDA MUNGKIN JUGA MENYUKAINYA" (Muncul di Bawah Cart Kosong maupun Terisi ala H&M & Uniqlo) -->
        @if(isset($recommendedProducts) && $recommendedProducts->isNotEmpty())
            <section class="mt-20 pt-12 border-t border-line">
                <div class="flex items-baseline justify-between mb-8">
                    <div>
                        <span class="font-label text-xs uppercase tracking-widest text-emerald font-bold block mb-1">Rekomendasi Kurator</span>
                        <h2 class="font-headline text-lg sm:text-xl font-extrabold text-navy">
                            Anda Mungkin Juga Menyukainya
                        </h2>
                    </div>
                    <a href="{{ route('products.index') }}" class="font-label text-sm font-bold text-navy hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($recommendedProducts as $rec)
                        <article class="group flex flex-col">
                            <div class="relative aspect-[3/4] w-full bg-canvas rounded-xl overflow-hidden mb-3">
                                <a href="{{ route('products.show', $rec->slug) }}" class="block w-full h-full">
                                    @if($rec->image)
                                        <img src="{{ asset('storage/' . $rec->image) }}" alt="{{ $rec->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @endif
                                </a>
                                <button type="button"
                                        @click="toggleWishlist({{ $rec->id }})"
                                        class="absolute top-2 right-2 w-10 h-10 bg-white/90 hover:bg-white rounded-lg flex items-center justify-center transition"
                                        title="Simpan ke Favorit">
                                    <svg class="w-4 h-4 transition"
                                         :class="isWishlisted({{ $rec->id }}) ? 'text-red-600 fill-current' : 'text-ink fill-none'"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="font-label text-sm text-muted">{{ $rec->category->name }}</span>
                            <h3 class="font-headline text-sm font-semibold text-ink group-hover:text-navy line-clamp-1 mt-0.5">
                                <a href="{{ route('products.show', $rec->slug) }}">{{ $rec->name }}</a>
                            </h3>
                            <span class="font-label text-sm font-bold text-navy mt-1">
                                Rp {{ number_format($rec->base_price, 0, ',', '.') }}
                            </span>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

    <!-- Comprehensive 4-Column Commercial Footer -->
    <footer class="bg-white border-t border-line mt-24 py-16 text-ink/80 font-body text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-line">
            <div class="space-y-3">
                <a href="{{ route('products.index') }}" class="inline-flex">
                    @include('partials.brand-logo', ['size' => 'sm'])
                </a>
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
                    <li><a href="{{ route('products.index') }}#katalog" class="hover:underline">Panduan Ukuran Fisik</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:underline">Lacak Status Pesanan</a></li>
                    <li><a href="{{ route('member') }}" class="hover:underline">Keanggotaan &amp; Garansi 3 Hari</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-headline font-bold text-sm text-navy mb-3">Program Lungsuran</h4>
                <p class="text-sm text-muted leading-relaxed mb-3">
                    Punya pakaian berkualitas di lemari yang jarang terpakai? Titip jual di Sirkula dan peroleh penghasilan.
                </p>
                <a href="{{ route('member') }}" class="inline-block font-label font-bold text-sm bg-navy text-white px-3.5 py-2 rounded-lg">
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
