<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRKULA — Circular & Essential Fashion</title>
    
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
<body class="bg-[#f7faf9] text-[#181c1c] antialiased font-body selection:bg-[#002547] selection:text-white">

    <!-- 1. Top Announcement Bar (Trust & Urgency) -->
    <div class="bg-[#002547] text-white text-[11px] py-2 px-4 text-center font-label font-medium tracking-wide flex items-center justify-center space-x-3">
        <span>🌿 100% Terkurasi & Higienis (Sanitized)</span>
        <span class="text-white/40">&bull;</span>
        <span>Garansi Keaslian SirkulaVerify</span>
        <span class="text-white/40 hidden sm:inline">&bull;</span>
        <span class="hidden sm:inline">Gratis Ongkir ke Seluruh Indonesia min. Rp300.000</span>
    </div>

    <!-- 2. Main Navigation Bar (CSS Grid 3 Pilar Presisi 64px - Simetris 100%) -->
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

                <!-- PILAR 2: TENGAH (Sumbu Simetri Absolut - Kategori Navigasi) -->
                <nav class="hidden md:flex items-center justify-center space-x-8 font-label text-xs uppercase tracking-widest font-semibold">
                    <a href="{{ route('products.index') }}" 
                       class="h-16 inline-flex items-center transition border-b-2 {{ !request('category') ? 'border-[#002547] text-[#002547]' : 'border-transparent text-[#73777f] hover:text-[#002547]' }}">
                        Semua
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                           class="h-16 inline-flex items-center transition border-b-2 {{ request('category') == $cat->slug ? 'border-[#002547] text-[#002547]' : 'border-transparent text-[#73777f] hover:text-[#002547]' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </nav>

                <!-- PILAR 3: KANAN (Aksi, Search & Transaksi - Simetris terhadap Kiri) -->
                <div class="flex items-center justify-end space-x-1 sm:space-x-3 font-label text-xs">
                    
                    <!-- Search Input Ringkas -->
                    <div class="relative hidden sm:block w-36 lg:w-48">
                        <form action="{{ route('products.index') }}" method="GET">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari pakaian..." 
                                   class="w-full bg-[#f1f4f3] border-none text-xs rounded-none py-1.5 pl-7 pr-2 font-body focus:ring-1 focus:ring-[#002547] placeholder-[#73777f]">
                            <span class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-[#73777f]">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
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
                        <a href="{{ route('orders.index') }}" class="w-9 h-9 flex items-center justify-center text-[#181c1c] hover:text-[#002547] transition" title="Pesanan Saya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="h-9 px-2.5 flex items-center text-xs font-semibold uppercase tracking-wider text-[#181c1c] hover:text-[#002547] transition">
                            Masuk
                        </a>
                    @endauth

                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="w-9 h-9 relative flex items-center justify-center text-[#002547] hover:opacity-80 transition" title="Keranjang">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(count(session('cart', [])) > 0)
                            <span class="absolute top-1 right-1 w-3.5 h-3.5 bg-[#002547] text-white text-[8px] font-bold rounded-full flex items-center justify-center leading-none">
                                {{ count(session('cart', [])) }}
                            </span>
                        @endif
                    </a>

                </div>

            </div>
        </div>

        <!-- Mobile Categories Tab -->
        <div class="md:hidden flex overflow-x-auto px-4 py-2 border-t border-[#e0e3e2] space-x-6 font-label text-xs uppercase tracking-wider font-semibold scrollbar-none bg-white">
            <a href="{{ route('products.index') }}" class="whitespace-nowrap {{ !request('category') ? 'text-[#002547] font-bold border-b-2 border-[#002547] pb-1' : 'text-[#73777f]' }}">Semua</a>
            @foreach ($categories as $cat)
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                   class="whitespace-nowrap {{ request('category') == $cat->slug ? 'text-[#002547] font-bold border-b-2 border-[#002547] pb-1' : 'text-[#73777f]' }}">
                   {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </header>

    <!-- 3. Hero Editorial Section -->
    @if(!request('category') && !request('search'))
    <section class="bg-[#ebeeed] border-b border-[#e0e3e2] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <div class="lg:col-span-7 space-y-4">
                    <span class="inline-flex items-center space-x-2 bg-[#85f9bf]/40 border border-[#85f9bf] text-[#006c47] font-label font-bold text-[10px] uppercase tracking-widest px-2.5 py-1">
                        <span>🌿</span>
                        <span>RECOMMERCE CURATION DROP #01</span>
                    </span>

                    <h1 class="font-headline text-3xl sm:text-5xl font-extrabold text-[#000f23] tracking-tight leading-[1.15]">
                        Gaya Esensial.<br>Sirkular Tanpa Kompromi.
                    </h1>

                    <p class="font-body text-xs sm:text-sm text-[#43474e] max-w-xl leading-relaxed">
                        Pilihan pakaian berkualitas tinggi dari sisa ekspor garmen dan pakaian terkurasi. Disanitasi standar medis dengan uap & ozon, siap pakai, hemat hingga 70% dari harga retail toko.
                    </p>

                    <div class="pt-2 flex flex-wrap gap-3 font-label text-xs uppercase tracking-wider font-bold">
                        <a href="#katalog" class="bg-[#002547] hover:bg-[#000f23] text-white px-6 py-3 transition">
                            Jelajahi Koleksi &darr;
                        </a>
                        <a href="#keunggulan" class="bg-white hover:bg-[#f1f4f3] text-[#002547] border border-[#c3c6cf] px-6 py-3 transition">
                            Standar SirkulaCare
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 flex justify-center">
                    <div class="w-full max-w-sm aspect-[4/5] bg-white border border-[#c3c6cf] p-3 relative">
                        <div class="w-full h-full bg-[#f1f4f3] flex flex-col items-center justify-center text-center p-6">
                            <span class="text-6xl mb-2">👕</span>
                            <span class="font-headline font-bold text-sm text-[#002547]">Lungsuran Berkualitas</span>
                            <span class="font-label text-[10px] text-[#73777f] uppercase tracking-wider mt-1">QC Passed &bull; Like-New Condition</span>
                        </div>
                        <div class="absolute -bottom-3 -left-3 bg-[#002547] text-white font-label text-[10px] uppercase tracking-widest font-bold px-3 py-1.5 shadow-sm">
                            Hemat ~2.500L Air / Baju
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @endif

    <!-- 4. Strip 4 Pilar SirkulaCare -->
    <section id="keunggulan" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-4">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="bg-white border border-[#e0e3e2] p-4">
                <span class="text-xl mb-1 block">🧼</span>
                <h4 class="font-headline font-bold text-xs text-[#002547]">Sanitasi Uap & Ozon</h4>
                <p class="font-body text-[11px] text-[#43474e] mt-0.5">Bebas kuman & wangi segar, siap langsung pakai.</p>
            </div>
            <div class="bg-white border border-[#e0e3e2] p-4">
                <span class="text-xl mb-1 block">🔍</span>
                <h4 class="font-headline font-bold text-xs text-[#002547]">SirkulaVerify 100%</h4>
                <p class="font-body text-[11px] text-[#43474e] mt-0.5">Jahitan, kancing, dan keaslian label terverifikasi.</p>
            </div>
            <div class="bg-white border border-[#e0e3e2] p-4">
                <span class="text-xl mb-1 block">🏷️</span>
                <h4 class="font-headline font-bold text-xs text-[#002547]">Diskon s/d 70%</h4>
                <p class="font-body text-[11px] text-[#43474e] mt-0.5">Kualitas brand ternama dengan fraksi harga retail.</p>
            </div>
            <div class="bg-white border border-[#e0e3e2] p-4">
                <span class="text-xl mb-1 block">🔄</span>
                <h4 class="font-headline font-bold text-xs text-[#002547]">Garansi Tukar Ukuran</h4>
                <p class="font-body text-[11px] text-[#43474e] mt-0.5">Kurang pas di badan? Bebas tukar dalam 3 hari.</p>
            </div>
        </div>
    </section>

    <!-- 5. Main Product Grid (BORDERLESS, NATURAL WHITESPACE ALA UNIQLO & H&M) -->
    <main id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        
        <div class="flex items-baseline justify-between border-b border-[#e0e3e2] pb-4 mb-8">
            <div>
                <h2 class="font-headline text-xl sm:text-2xl font-bold text-[#181c1c] tracking-tight">
                    {{ request('category') ? $categories->where('slug', request('category'))->first()?->name : 'Koleksi Drop Pilihan' }}
                </h2>
                <p class="font-body text-xs text-[#43474e] mt-0.5">Pakaian kurasi tangan pertama dengan penilaian kondisi transparan.</p>
            </div>
            <span class="font-label text-xs text-[#73777f]">{{ $products->total() }} Produk Siap Dikirim</span>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-20 bg-white border border-[#e0e3e2]">
                <p class="font-body text-sm text-[#73777f]">Belum ada pakaian di kategori ini.</p>
            </div>
        @else
            <!-- Grid dengan Vertical Breathing Room 56px (gap-y-14) & BORDERLESS CARDS -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-5 sm:gap-x-7 gap-y-10 sm:gap-y-14">
                @foreach ($products as $product)
                    <article class="group flex flex-col cursor-pointer">
                        
                        <!-- 1. Media Container: Borderless 3:4 Aspect Ratio (Edge-to-Edge) -->
                        <a href="{{ route('products.show', $product->slug) }}" 
                           class="relative w-full aspect-[3/4] bg-[#f4f6f5] overflow-hidden block">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover object-center group-hover:scale-103 transition duration-500 ease-out">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-[#73777f]">
                                    <span class="text-4xl mb-1 opacity-60">👕</span>
                                    <span class="font-label text-[10px] uppercase tracking-wider text-[#73777f]">{{ $product->category->name }}</span>
                                </div>
                            @endif

                            <!-- Kondisi Grade Pill (Minimalis ala Uniqlo) -->
                            <div class="absolute top-2.5 left-2.5">
                                <span class="bg-white/95 backdrop-blur-xs text-[#006c47] font-label font-bold text-[9px] uppercase tracking-widest px-2 py-0.5 shadow-2xs">
                                    Grade A+ &bull; Like New
                                </span>
                            </div>
                        </a>

                        <!-- 2. Typography & Metadata (Mengalir Bebas Tanpa Kotak Kaku) -->
                        <div class="pt-3 flex flex-col flex-1 justify-between">
                            <div>
                                <!-- Color Swatches (Clean Dots) -->
                                <div class="flex items-center space-x-1.5 mb-1.5">
                                    @foreach($product->variants->pluck('color')->unique() as $color)
                                        <span class="w-2.5 h-2.5 rounded-full border border-[#dcdcdc] inline-block"
                                              style="background-color: {{ strtolower($color) == 'putih' ? '#ffffff' : (strtolower($color) == 'hitam' ? '#181c1c' : (str_contains(strtolower($color), 'navy') ? '#002547' : '#73777f')) }}"
                                              title="{{ $color }}">
                                        </span>
                                    @endforeach
                                </div>

                                <!-- Category & Available Sizes -->
                                <p class="font-label text-[11px] text-[#73777f] uppercase tracking-wider mb-1">
                                    {{ $product->category->name }} &bull; {{ $product->variants->pluck('size')->unique()->implode(', ') }}
                                </p>

                                <!-- Product Title -->
                                <h3 class="font-headline text-xs sm:text-[13px] font-semibold text-[#181c1c] leading-snug line-clamp-2 group-hover:text-[#002547] transition">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                            </div>

                            <!-- Price Row (Dopamine Anchor: Coret Harga Mall vs Harga Sirkula) -->
                            <div class="mt-2.5 flex items-baseline space-x-2">
                                <span class="font-label text-xs sm:text-sm font-bold text-[#002547]">
                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                </span>
                                <span class="font-label text-[10px] text-[#73777f] line-through">
                                    Rp {{ number_format($product->base_price * 2.2, 0, ',', '.') }}
                                </span>
                                <span class="font-label text-[9px] font-bold text-[#006c47]">
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

    <!-- 6. Comprehensive 4-Column Commercial Footer (Ala Uniqlo & H&M) -->
    <footer class="bg-white border-t border-[#e0e3e2] mt-24 py-16 text-[#43474e] font-body text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-[#e0e3e2]">
            
            <div class="space-y-3">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-none bg-[#002547] text-white flex items-center justify-center font-headline font-black text-xs">
                        S
                    </span>
                    <span class="font-headline font-bold text-[#002547] text-base">SIRKULA</span>
                </div>
                <p class="text-[11px] text-[#73777f] leading-relaxed">
                    Platform recommerce fashion sirkular yang mendigitalkan nilai kearifan lungsuran. Memperpanjang siklus pakai pakaian berkualitas untuk masa depan yang lebih lestari.
                </p>
            </div>

            <div>
                <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-[#002547] mb-3">Koleksi Pakaian</h4>
                <ul class="space-y-2 text-[11px]">
                    <li><a href="#" class="hover:underline">Kaos &amp; T-Shirt Boxy</a></li>
                    <li><a href="#" class="hover:underline">Kemeja Casual &amp; Flannel</a></li>
                    <li><a href="#" class="hover:underline">Outerwear &amp; Jaket</a></li>
                    <li><a href="#" class="hover:underline">Rilisan Drop Terbaru</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-[#002547] mb-3">Layanan &amp; Bantuan</h4>
                <ul class="space-y-2 text-[11px]">
                    <li><a href="#" class="hover:underline">Standar Sanitasi SirkulaCare</a></li>
                    <li><a href="#" class="hover:underline">Panduan Ukuran Fisik</a></li>
                    <li><a href="#" class="hover:underline">Lacak Status Pesanan</a></li>
                    <li><a href="#" class="hover:underline">Kebijakan Pengembalian 3 Hari</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-[#002547] mb-3">Program Lungsuran</h4>
                <p class="text-[11px] text-[#73777f] leading-relaxed mb-3">
                    Punya pakaian berkualitas di lemari yang jarang terpakai? Titip jual di Sirkula dan peroleh penghasilan.
                </p>
                <a href="#" class="inline-block font-label font-bold text-[10px] uppercase tracking-wider bg-[#002547] text-white px-3 py-2">
                    Titip Jual Baju &rarr;
                </a>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 flex flex-col sm:flex-row justify-between items-center text-[11px] text-[#73777f] gap-3">
            <p>&copy; {{ date('Y') }} PT SIRKULA INDONESIA LESTARI. Hak cipta dilindungi undang-undang.</p>
            <p class="font-label">Metode Pembayaran: Transfer BCA &bull; Mandiri &bull; BRI</p>
        </div>
    </footer>

</body>
</html>