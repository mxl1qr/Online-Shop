<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout & Informasi Pengiriman — SIRKULA Recommerce</title>
    
    <!-- Google Fonts Trio dari DESIGN.md -->
    @include('partials.head-fonts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-surface text-ink antialiased font-body selection:bg-navy selection:text-white">

    <!-- 1. Top Announcement Bar -->
    <div class="bg-navy text-white text-xs py-2 px-4 text-center font-label font-medium tracking-wide flex items-center justify-center space-x-3">
        <span>Transaksi Aman &amp; Terenkripsi</span>
        <span class="text-white/40 hidden sm:inline">&bull;</span>
        <span class="hidden sm:inline">Stok Pakaian Terkunci Otomatis (Lock For Update)</span>
    </div>

    <!-- 2. Main Navigation Bar (3 Pilar Presisi 64px Tanpa Wrapping) -->
    <header class="sticky top-0 z-50 bg-white border-b border-line">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                
                <!-- PILAR 1: KIRI (Logo Brand Sirkula) -->
                <div class="flex items-center justify-start shrink-0">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-2.5 group">
                        @include('partials.brand-logo', ['size' => 'md'])
                    </a>
                </div>

                <!-- PILAR 2: TENGAH (Sumbu Simetri - Status Step 1 Baris Utuh) -->
                <nav class="hidden lg:flex items-center justify-center gap-6 whitespace-nowrap font-label text-sm font-semibold">
                    <span class="text-ink/80 whitespace-nowrap">1. Keranjang</span>
                    <span class="text-ink/80">&rarr;</span>
                    <span class="text-navy font-bold border-b-2 border-navy pb-0.5 whitespace-nowrap">2. Pengiriman &amp; Bayar</span>
                    <span class="text-ink/80">&rarr;</span>
                    <span class="text-ink/80 whitespace-nowrap">3. Selesai</span>
                </nav>

                <!-- PILAR 3: KANAN (Kembali ke Keranjang) -->
                <div class="flex items-center justify-end font-label text-sm shrink-0">
                    <a href="{{ route('cart.index') }}" class="text-ink/80 hover:text-navy whitespace-nowrap transition">
                        &larr; Ubah Keranjang
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        
        <!-- Notifikasi Error -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 font-label text-sm font-bold">
                ⚠ {{ session('error') }}
            </div>
        @endif

        <div class="border-b border-line pb-4 mb-8">
            <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">
                Alamat Pengiriman & Pembayaran
            </h1>
            <p class="font-body text-sm text-muted mt-0.5">Harap lengkapi alamat penerima dengan teliti agar kurir dapat mengantar tepat waktu.</p>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- KOLOM KIRI: Form Input Pengiriman & Bank (8 Col) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- 1. Data Penerima -->
                    <div class="card p-6 space-y-4">
                        <div class="flex items-center space-x-2 pb-3 border-b border-line">
                            <span class="w-5 h-5 bg-navy text-white rounded-md flex items-center justify-center font-label font-bold text-sm">1</span>
                            <h2 class="font-headline text-sm font-bold text-navy">
                                Data Penerima Paket
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-label text-sm font-bold text-ink mb-1.5">
                                    Nama Lengkap Penerima *
                                </label>
                                <input type="text" 
                                       name="shipping_name" 
                                       value="{{ old('shipping_name', Auth::user()->name) }}" 
                                       required
                                       placeholder="Contoh: Budi Santoso"
                                       class="w-full bg-surface-low border border-line-strong rounded-xl text-sm font-body py-2.5 px-3.5 focus:border-navy focus:bg-white focus:ring-2 focus:ring-emerald/20 transition">
                                @error('shipping_name') <span class="font-label text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-label text-sm font-bold text-ink mb-1.5">
                                    Nomor Telepon / WhatsApp *
                                </label>
                                <input type="text" 
                                       name="shipping_phone" 
                                       value="{{ old('shipping_phone') }}" 
                                       required
                                       placeholder="081234567890"
                                       class="w-full bg-surface-low border border-line-strong rounded-xl text-sm font-body py-2.5 px-3.5 focus:border-navy focus:bg-white focus:ring-2 focus:ring-emerald/20 transition">
                                @error('shipping_phone') <span class="font-label text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 2. Alamat Tujuan -->
                    <div class="card p-6 space-y-4">
                        <div class="flex items-center space-x-2 pb-3 border-b border-line">
                            <span class="w-5 h-5 bg-navy text-white rounded-md flex items-center justify-center font-label font-bold text-sm">2</span>
                            <h2 class="font-headline text-sm font-bold text-navy">
                                Alamat Lengkap Rumah / Kantor
                            </h2>
                        </div>

                        <div>
                            <label class="block font-label text-sm font-bold text-ink mb-1.5">
                                Alamat Lengkap & Kota / Kode Pos *
                            </label>
                            <textarea name="shipping_address" 
                                      rows="3" 
                                      required
                                      placeholder="Nama Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos"
                                      class="w-full bg-surface-low border border-line-strong rounded-xl text-sm font-body py-2.5 px-3.5 focus:border-navy focus:bg-white focus:ring-2 focus:ring-emerald/20 transition">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address') <span class="font-label text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-label text-sm font-bold text-muted mb-1.5">
                                Catatan Kurir (Opsional)
                            </label>
                            <input type="text" 
                                   name="shipping_notes" 
                                   value="{{ old('shipping_notes') }}"
                                   placeholder="Contoh: Titipkan di satpam, pagar warna hitam"
                                   class="w-full bg-surface-low border border-line-strong rounded-xl text-sm font-body py-2.5 px-3.5 focus:border-navy focus:bg-white focus:ring-2 focus:ring-emerald/20 transition">
                        </div>
                    </div>

                    <!-- 3. Metode Pembayaran Transfer Bank -->
                    <div class="card p-6 space-y-4">
                        <div class="flex items-center space-x-2 pb-3 border-b border-line">
                            <span class="w-5 h-5 bg-navy text-white rounded-md flex items-center justify-center font-label font-bold text-sm">3</span>
                            <h2 class="font-headline text-sm font-bold text-navy">
                                Metode Pembayaran Transfer Bank
                            </h2>
                        </div>

                        <p class="font-body text-sm text-muted">
                            Pilih rekening bank tujuan transfer. Instruksi nomor rekening resmi PT SIRKULASI Indonesia Lestari akan ditampilkan setelah pesanan dibuat:
                        </p>

                        <div class="space-y-2.5 font-label text-sm">
                            <label class="flex items-center justify-between p-3.5 border border-line rounded-xl cursor-pointer hover:border-navy transition">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="bca" checked class="text-navy focus:ring-navy">
                                    <span class="font-bold text-navy">Bank Central Asia (BCA)</span>
                                </div>
                                <span class="bg-surface-low text-navy font-bold text-xs px-2 py-0.5 rounded-md">BCA Otomatis</span>
                            </label>

                            <label class="flex items-center justify-between p-3.5 border border-line rounded-xl cursor-pointer hover:border-navy transition">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="mandiri" class="text-navy focus:ring-navy">
                                    <span class="font-bold text-navy">Bank Mandiri</span>
                                </div>
                                <span class="bg-surface-low text-navy font-bold text-xs px-2 py-0.5 rounded-md">Mandiri Livin</span>
                            </label>

                            <label class="flex items-center justify-between p-3.5 border border-line rounded-xl cursor-pointer hover:border-navy transition">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="bri" class="text-navy focus:ring-navy">
                                    <span class="font-bold text-navy">Bank Rakyat Indonesia (BRI)</span>
                                </div>
                                <span class="bg-surface-low text-navy font-bold text-xs px-2 py-0.5 rounded-md">BRImo</span>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN: Ringkasan Pesanan (Sticky) -->
                <div class="lg:col-span-4 sticky top-24 space-y-4">
                    <div class="card p-6 space-y-5">
                        <h2 class="font-headline text-sm font-bold text-navy pb-3 border-b border-line">
                            Ringkasan Pesanan ({{ count($cart) }} Item)
                        </h2>

                        <!-- Mini List Barang -->
                        <div class="max-h-60 overflow-y-auto divide-y divide-line pr-1">
                            @foreach($cart as $item)
                                <div class="py-3 flex items-center justify-between gap-3 text-sm">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-14 bg-canvas shrink-0 border border-line rounded-lg flex items-center justify-center overflow-hidden">
                                            @if($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-5 h-5 text-line-strong" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-headline font-bold text-ink text-sm line-clamp-1">{{ $item['product_name'] }}</h4>
                                            <p class="font-label text-sm text-muted">{{ $item['color'] }} &bull; {{ $item['size'] }} &bull; {{ $item['quantity'] }} pcs</p>
                                        </div>
                                    </div>
                                    <span class="font-label font-bold text-navy whitespace-nowrap">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Baris Rincian Biaya -->
                        @php
                            $storeConfig = \App\Http\Controllers\AdminOrderController::getStoreConfig();
                            $threshold = (int) ($storeConfig['free_shipping_threshold'] ?? 250000);
                            $shippingCost = $total >= $threshold ? 0 : 20000;
                            $grandTotal = $total + $shippingCost;
                        @endphp
                        <div class="space-y-2.5 pt-3 border-t border-line font-label text-sm">
                            <div class="flex justify-between text-muted">
                                <span>Subtotal Pakaian:</span>
                                <span class="font-semibold text-ink">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-muted">
                                <span>Ongkos Kirim:</span>
                                <span class="font-semibold {{ $shippingCost == 0 ? 'text-emerald' : 'text-ink' }}">
                                    {{ $shippingCost == 0 ? 'GRATIS (Promo)' : 'Rp ' . number_format($shippingCost, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-muted">
                                <span>Sanitasi SirkulaCare:</span>
                                <span class="text-emerald font-semibold">GRATIS</span>
                            </div>
                            <div class="pt-3 border-t border-line flex justify-between items-baseline">
                                <span class="font-headline font-bold text-sm text-navy">Total Bayar:</span>
                                <span class="font-label text-xl font-bold text-navy">
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Tombol Submit Checkout -->
                        <button type="submit" 
                                class="btn btn-primary w-full text-center cursor-pointer">
                            Konfirmasi &amp; Buat Pesanan &rarr;
                        </button>

                        <div class="text-center font-body text-sm text-muted space-y-1">
                            <p class="inline-flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5 text-emerald shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Stok langsung dikunci dengan aman saat Anda menekan tombol di atas</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </form>

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
