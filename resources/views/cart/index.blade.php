<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja — SIRKULA Recommerce</title>
    
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

    <!-- 1. Top Announcement Bar -->
    <div class="bg-[#002547] text-white text-[11px] py-2 px-4 text-center font-label font-medium tracking-wide flex items-center justify-center space-x-3">
        <span>🌿 100% Terkurasi & Higienis (Sanitized)</span>
        <span class="text-white/40">&bull;</span>
        <span>Garansi Keaslian SirkulaVerify</span>
        <span class="text-white/40 hidden sm:inline">&bull;</span>
        <span class="hidden sm:inline">Gratis Ongkir ke Seluruh Indonesia min. Rp300.000</span>
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

                <!-- PILAR 2: TENGAH (Sumbu Simetri - Step Indicator ala H&M) -->
                <nav class="hidden md:flex items-center justify-center space-x-6 font-label text-xs uppercase tracking-wider font-semibold">
                    <span class="text-[#002547] font-bold border-b-2 border-[#002547] pb-0.5">1. Keranjang Belanja</span>
                    <span class="text-[#73777f]">&rarr;</span>
                    <span class="text-[#73777f]">2. Pengiriman &amp; Bayar</span>
                    <span class="text-[#73777f]">&rarr;</span>
                    <span class="text-[#73777f]">3. Selesai</span>
                </nav>

                <!-- PILAR 3: KANAN (Aksi & User Profile) -->
                <div class="flex items-center justify-end space-x-1 sm:space-x-3 font-label text-xs">
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

                    <span class="w-9 h-9 relative flex items-center justify-center text-[#002547]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(count($cart) > 0)
                            <span class="absolute top-1 right-1 w-3.5 h-3.5 bg-[#002547] text-white text-[8px] font-bold rounded-full flex items-center justify-center leading-none">
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
            <div class="mb-6 p-4 bg-[#85f9bf]/20 border border-[#85f9bf] text-[#006c47] font-label text-xs font-bold flex items-center justify-between">
                <span>✓ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-[#006c47] hover:opacity-70 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 font-label text-xs font-bold flex items-center justify-between">
                <span>⚠ {{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:opacity-70 font-bold">&times;</button>
            </div>
        @endif

        <!-- Judul Halaman -->
        <div class="flex items-baseline justify-between border-b border-[#e0e3e2] pb-4 mb-8">
            <div>
                <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-[#181c1c] tracking-tight">
                    Keranjang Belanja
                </h1>
                <p class="font-body text-xs text-[#73777f] mt-0.5">Pakaian pilihan Anda sebelum diproses pengiriman higienis.</p>
            </div>
            @if(count($cart) > 0)
                <span class="font-label text-xs text-[#006c47] font-semibold bg-[#85f9bf]/30 px-2.5 py-1">
                    {{ count($cart) }} Item Pakaian
                </span>
            @endif
        </div>

        @if(empty($cart))
            <!-- Kondisi Keranjang Kosong -->
            <div class="bg-white border border-[#e0e3e2] p-16 text-center max-w-lg mx-auto">
                <div class="w-16 h-16 bg-[#f4f6f5] border border-[#e0e3e2] rounded-full flex items-center justify-center mx-auto mb-4 text-[#002547]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h2 class="font-headline text-lg font-bold text-[#002547] mb-2">Keranjang Belanja Anda Masih Kosong</h2>
                <p class="font-body text-xs text-[#73777f] mb-8 leading-relaxed">
                    Setiap potong pakaian di Sirkula adalah satu-satunya kurasi eksklusif. Temukan pakaian esensial favorit Anda sekarang.
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-[#002547] hover:bg-[#000f23] text-white font-label text-xs font-bold uppercase tracking-widest px-8 py-3.5 transition">
                    Jelajahi Koleksi Pakaian &rarr;
                </a>
            </div>
        @else
            <!-- Grid Keranjang: List Barang (8 Col) vs Ringkasan Pesanan (4 Col) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- KOLOM KIRI: Daftar Pakaian di Keranjang -->
                <div class="lg:col-span-8 space-y-4">
                    @foreach($cart as $variantId => $item)
                        <div class="bg-white border border-[#e0e3e2] p-4 sm:p-5 flex flex-col sm:flex-row gap-4 sm:gap-6 items-start sm:items-center justify-between">
                            
                            <!-- Foto & Informasi Barang -->
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('products.show', $item['slug']) }}" 
                                   class="w-20 h-24 bg-[#f4f6f5] shrink-0 border border-[#e0e3e2] overflow-hidden flex items-center justify-center">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-2xl opacity-60">👕</span>
                                    @endif
                                </a>

                                <div>
                                    <span class="font-label text-[10px] uppercase tracking-wider text-[#006c47] font-bold block mb-0.5">
                                        {{ $item['category'] }} &bull; Grade A+
                                    </span>
                                    <h3 class="font-headline text-sm font-bold text-[#181c1c] leading-snug">
                                        <a href="{{ route('products.show', $item['slug']) }}" class="hover:text-[#002547] transition">
                                            {{ $item['product_name'] }}
                                        </a>
                                    </h3>
                                    
                                    <!-- Rincian Varian -->
                                    <div class="mt-1 flex items-center space-x-2 font-label text-xs text-[#73777f]">
                                        <span>Warna: <strong class="text-[#002547]">{{ $item['color'] }}</strong></span>
                                        <span>&bull;</span>
                                        <span>Ukuran: <strong class="text-[#002547]">{{ $item['size'] }}</strong></span>
                                    </div>

                                    <!-- Harga Satuan -->
                                    <p class="font-label text-xs font-bold text-[#002547] mt-1.5">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }} / pcs
                                    </p>
                                </div>
                            </div>

                            <!-- Qty Controller & Total Per Item -->
                            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-[#e0e3e2]">
                                
                                <!-- Form Update Kuantitas -->
                                <form action="{{ route('cart.update', $variantId) }}" method="POST" class="flex items-center border border-[#e0e3e2] bg-white">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            name="quantity" 
                                            value="{{ max(1, $item['quantity'] - 1) }}"
                                            class="w-8 h-8 flex items-center justify-center text-[#73777f] hover:bg-[#f1f4f3] font-bold transition"
                                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                        -
                                    </button>
                                    <span class="w-10 text-center font-label text-xs font-bold text-[#002547]">
                                        {{ $item['quantity'] }}
                                    </span>
                                    <button type="submit" 
                                            name="quantity" 
                                            value="{{ min($item['max_stock'] ?? 99, $item['quantity'] + 1) }}"
                                            class="w-8 h-8 flex items-center justify-center text-[#73777f] hover:bg-[#f1f4f3] font-bold transition"
                                            {{ $item['quantity'] >= ($item['max_stock'] ?? 99) ? 'disabled' : '' }}>
                                        +
                                    </button>
                                </form>

                                <!-- Subtotal per Item -->
                                <div class="text-right">
                                    <span class="font-label text-sm font-bold text-[#002547] block">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </span>
                                    
                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('cart.remove', $variantId) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-label text-[10px] text-red-600 hover:underline uppercase tracking-wider font-semibold mt-1">
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
                    <div class="bg-white border border-[#e0e3e2] p-6 space-y-5">
                        <h2 class="font-headline text-xs font-bold uppercase tracking-wider text-[#002547] pb-3 border-b border-[#e0e3e2]">
                            Ringkasan Transaksi
                        </h2>

                        <!-- Progress Bar Gratis Ongkir -->
                        @php
                            $freeShippingThreshold = 300000;
                            $diff = $freeShippingThreshold - $total;
                            $percent = min(100, round(($total / $freeShippingThreshold) * 100));
                        @endphp
                        <div class="bg-[#f1f4f3] p-3 border border-[#e0e3e2]">
                            @if($diff > 0)
                                <p class="font-label text-[11px] text-[#43474e] mb-1.5">
                                    Tambah <strong>Rp {{ number_format($diff, 0, ',', '.') }}</strong> lagi untuk <strong>Gratis Ongkir</strong>!
                                </p>
                            @else
                                <p class="font-label text-[11px] text-[#006c47] font-bold mb-1.5 flex items-center space-x-1">
                                    <span>🎉</span>
                                    <span>Selamat! Anda Berhak Mendapatkan Gratis Ongkir!</span>
                                </p>
                            @endif
                            <div class="w-full bg-[#e0e3e2] h-1.5 rounded-full overflow-hidden">
                                <div class="bg-[#006c47] h-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>

                        <!-- Baris Rincian Harga -->
                        <div class="space-y-3 font-label text-xs">
                            <div class="flex justify-between text-[#73777f]">
                                <span>Subtotal Pakaian:</span>
                                <span class="font-semibold text-[#181c1c]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-[#73777f]">
                                <span>Biaya Sanitasi SirkulaCare:</span>
                                <span class="text-[#006c47] font-semibold">GRATIS (Rp 0)</span>
                            </div>
                            <div class="flex justify-between text-[#73777f]">
                                <span>Estimasi Ongkos Kirim:</span>
                                <span class="font-semibold text-[#181c1c]">
                                    {{ $total >= 300000 ? 'GRATIS' : 'Dihitung saat Checkout' }}
                                </span>
                            </div>
                            <div class="pt-3 border-t border-[#e0e3e2] flex justify-between items-baseline">
                                <span class="font-headline font-bold text-sm text-[#002547]">Total Belanja:</span>
                                <span class="font-label text-xl font-bold text-[#002547]">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Tombol CTA Checkout -->
                        <a href="{{ route('checkout.index') }}" 
                           class="w-full h-[52px] bg-[#002547] hover:bg-[#000f23] text-white flex items-center justify-center font-label text-xs uppercase tracking-widest font-bold transition shadow-xs">
                            Lanjut ke Checkout &rarr;
                        </a>

                        <!-- Micro Trust Point -->
                        <div class="pt-2 text-center font-body text-[10px] text-[#73777f] space-y-1">
                            <p>🔒 Transaksi aman & stok terlindungi saat checkout</p>
                            <p>🌿 Pakaian disanitasi ozon & uap sebelum dikemas</p>
                        </div>
                    </div>
                </div>

            </div>
        @endif

    </main>

    <!-- Comprehensive 4-Column Commercial Footer -->
    <footer class="bg-white border-t border-[#e0e3e2] mt-24 py-16 text-[#43474e] font-body text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-[#e0e3e2]">
            <div class="space-y-3">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 bg-[#002547] text-white flex items-center justify-center font-headline font-black text-xs">S</span>
                    <span class="font-headline font-bold text-[#002547] text-base">SIRKULA</span>
                </div>
                <p class="text-[11px] text-[#73777f] leading-relaxed">
                    Platform recommerce fashion sirkular yang mendigitalkan nilai kearifan lungsuran. Memperpanjang siklus pakai pakaian berkualitas untuk masa depan yang lebih lestari.
                </p>
            </div>
            <div>
                <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-[#002547] mb-3">Koleksi Pakaian</h4>
                <ul class="space-y-2 text-[11px]">
                    <li><a href="{{ route('products.index', ['category' => 'kaos']) }}" class="hover:underline">Kaos &amp; T-Shirt Boxy</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'kemeja']) }}" class="hover:underline">Kemeja Casual &amp; Flannel</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'outerwear']) }}" class="hover:underline">Outerwear &amp; Jaket</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:underline">Rilisan Drop Terbaru</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-[#002547] mb-3">Layanan &amp; Bantuan</h4>
                <ul class="space-y-2 text-[11px]">
                    <li><a href="#" class="hover:underline">Standar Sanitasi SirkulaCare</a></li>
                    <li><a href="#" class="hover:underline">Panduan Ukuran Fisik</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:underline">Lacak Status Pesanan</a></li>
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
