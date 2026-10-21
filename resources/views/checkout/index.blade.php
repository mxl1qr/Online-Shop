<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout & Informasi Pengiriman — SIRKULA Recommerce</title>
    
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
        <span>🌿 Transaksi Aman & Terenkripsi</span>
        <span class="text-white/40">&bull;</span>
        <span>Stok Pakaian Terkunci Otomatis (Lock For Update)</span>
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

                <!-- PILAR 2: TENGAH (Sumbu Simetri - Status Step) -->
                <nav class="hidden md:flex items-center justify-center space-x-6 font-label text-xs uppercase tracking-wider font-semibold">
                    <span class="text-[#73777f]">1. Keranjang</span>
                    <span class="text-[#73777f]">&rarr;</span>
                    <span class="text-[#002547] font-bold border-b-2 border-[#002547] pb-0.5">2. Pengiriman & Bayar</span>
                    <span class="text-[#73777f]">&rarr;</span>
                    <span class="text-[#73777f]">3. Selesai</span>
                </nav>

                <!-- PILAR 3: KANAN (Kembali ke Keranjang) -->
                <div class="flex items-center justify-end font-label text-xs">
                    <a href="{{ route('cart.index') }}" class="text-[#73777f] hover:text-[#002547] transition">
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
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 font-label text-xs font-bold">
                ⚠ {{ session('error') }}
            </div>
        @endif

        <div class="border-b border-[#e0e3e2] pb-4 mb-8">
            <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-[#181c1c] tracking-tight">
                Alamat Pengiriman & Pembayaran
            </h1>
            <p class="font-body text-xs text-[#73777f] mt-0.5">Harap lengkapi alamat penerima dengan teliti agar kurir dapat mengantar tepat waktu.</p>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- KOLOM KIRI: Form Input Pengiriman & Bank (8 Col) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- 1. Data Penerima -->
                    <div class="bg-white border border-[#e0e3e2] p-6 space-y-4">
                        <div class="flex items-center space-x-2 pb-3 border-b border-[#e0e3e2]">
                            <span class="w-5 h-5 bg-[#002547] text-white flex items-center justify-center font-label font-bold text-xs">1</span>
                            <h2 class="font-headline text-xs font-bold uppercase tracking-wider text-[#002547]">
                                Data Penerima Paket
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-label text-xs font-bold uppercase tracking-wider text-[#181c1c] mb-1.5">
                                    Nama Lengkap Penerima *
                                </label>
                                <input type="text" 
                                       name="shipping_name" 
                                       value="{{ old('shipping_name', Auth::user()->name) }}" 
                                       required
                                       placeholder="Contoh: Budi Santoso"
                                       class="w-full bg-[#f1f4f3] border-none text-xs font-body py-2.5 px-3 focus:ring-1 focus:ring-[#002547]">
                                @error('shipping_name') <span class="font-label text-red-600 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-label text-xs font-bold uppercase tracking-wider text-[#181c1c] mb-1.5">
                                    Nomor Telepon / WhatsApp *
                                </label>
                                <input type="text" 
                                       name="shipping_phone" 
                                       value="{{ old('shipping_phone') }}" 
                                       required
                                       placeholder="081234567890"
                                       class="w-full bg-[#f1f4f3] border-none text-xs font-body py-2.5 px-3 focus:ring-1 focus:ring-[#002547]">
                                @error('shipping_phone') <span class="font-label text-red-600 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 2. Alamat Tujuan -->
                    <div class="bg-white border border-[#e0e3e2] p-6 space-y-4">
                        <div class="flex items-center space-x-2 pb-3 border-b border-[#e0e3e2]">
                            <span class="w-5 h-5 bg-[#002547] text-white flex items-center justify-center font-label font-bold text-xs">2</span>
                            <h2 class="font-headline text-xs font-bold uppercase tracking-wider text-[#002547]">
                                Alamat Lengkap Rumah / Kantor
                            </h2>
                        </div>

                        <div>
                            <label class="block font-label text-xs font-bold uppercase tracking-wider text-[#181c1c] mb-1.5">
                                Alamat Lengkap & Kota / Kode Pos *
                            </label>
                            <textarea name="shipping_address" 
                                      rows="3" 
                                      required
                                      placeholder="Nama Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos"
                                      class="w-full bg-[#f1f4f3] border-none text-xs font-body py-2.5 px-3 focus:ring-1 focus:ring-[#002547]">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address') <span class="font-label text-red-600 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-label text-xs font-bold uppercase tracking-wider text-[#73777f] mb-1.5">
                                Catatan Kurir (Opsional)
                            </label>
                            <input type="text" 
                                   name="shipping_notes" 
                                   value="{{ old('shipping_notes') }}"
                                   placeholder="Contoh: Titipkan di satpam, pagar warna hitam"
                                   class="w-full bg-[#f1f4f3] border-none text-xs font-body py-2.5 px-3 focus:ring-1 focus:ring-[#002547]">
                        </div>
                    </div>

                    <!-- 3. Metode Pembayaran Transfer Bank -->
                    <div class="bg-white border border-[#e0e3e2] p-6 space-y-4">
                        <div class="flex items-center space-x-2 pb-3 border-b border-[#e0e3e2]">
                            <span class="w-5 h-5 bg-[#002547] text-white flex items-center justify-center font-label font-bold text-xs">3</span>
                            <h2 class="font-headline text-xs font-bold uppercase tracking-wider text-[#002547]">
                                Metode Pembayaran Transfer Bank
                            </h2>
                        </div>

                        <p class="font-body text-xs text-[#73777f]">
                            Pilih rekening bank tujuan transfer. Instruksi nomor rekening resmi PT Sirkula Indonesia Lestari akan ditampilkan setelah pesanan dibuat:
                        </p>

                        <div class="space-y-2.5 font-label text-xs">
                            <label class="flex items-center justify-between p-3.5 border border-[#e0e3e2] cursor-pointer hover:border-[#002547] transition">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="bca" checked class="text-[#002547] focus:ring-[#002547]">
                                    <span class="font-bold text-[#002547]">Bank Central Asia (BCA)</span>
                                </div>
                                <span class="bg-[#f1f4f3] text-[#002547] font-bold text-[10px] px-2 py-0.5">BCA Otomatis</span>
                            </label>

                            <label class="flex items-center justify-between p-3.5 border border-[#e0e3e2] cursor-pointer hover:border-[#002547] transition">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="mandiri" class="text-[#002547] focus:ring-[#002547]">
                                    <span class="font-bold text-[#002547]">Bank Mandiri</span>
                                </div>
                                <span class="bg-[#f1f4f3] text-[#002547] font-bold text-[10px] px-2 py-0.5">Mandiri Livin</span>
                            </label>

                            <label class="flex items-center justify-between p-3.5 border border-[#e0e3e2] cursor-pointer hover:border-[#002547] transition">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="bri" class="text-[#002547] focus:ring-[#002547]">
                                    <span class="font-bold text-[#002547]">Bank Rakyat Indonesia (BRI)</span>
                                </div>
                                <span class="bg-[#f1f4f3] text-[#002547] font-bold text-[10px] px-2 py-0.5">BRImo</span>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN: Ringkasan Pesanan (Sticky) -->
                <div class="lg:col-span-4 sticky top-24 space-y-4">
                    <div class="bg-white border border-[#e0e3e2] p-6 space-y-5">
                        <h2 class="font-headline text-xs font-bold uppercase tracking-wider text-[#002547] pb-3 border-b border-[#e0e3e2]">
                            Ringkasan Pesanan ({{ count($cart) }} Item)
                        </h2>

                        <!-- Mini List Barang -->
                        <div class="max-h-60 overflow-y-auto divide-y divide-[#e0e3e2] pr-1">
                            @foreach($cart as $item)
                                <div class="py-3 flex items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-14 bg-[#f4f6f5] shrink-0 border border-[#e0e3e2] flex items-center justify-center overflow-hidden">
                                            @if($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-5 h-5 text-[#c3c6cf]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-headline font-bold text-[#181c1c] text-xs line-clamp-1">{{ $item['product_name'] }}</h4>
                                            <p class="font-label text-[10px] text-[#73777f]">{{ $item['color'] }} &bull; {{ $item['size'] }} &bull; {{ $item['quantity'] }} pcs</p>
                                        </div>
                                    </div>
                                    <span class="font-label font-bold text-[#002547] whitespace-nowrap">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Baris Rincian Biaya -->
                        @php
                            $shippingCost = $total >= 300000 ? 0 : 20000;
                            $grandTotal = $total + $shippingCost;
                        @endphp
                        <div class="space-y-2.5 pt-3 border-t border-[#e0e3e2] font-label text-xs">
                            <div class="flex justify-between text-[#73777f]">
                                <span>Subtotal Pakaian:</span>
                                <span class="font-semibold text-[#181c1c]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-[#73777f]">
                                <span>Ongkos Kirim:</span>
                                <span class="font-semibold {{ $shippingCost == 0 ? 'text-[#006c47]' : 'text-[#181c1c]' }}">
                                    {{ $shippingCost == 0 ? 'GRATIS (Promo)' : 'Rp ' . number_format($shippingCost, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-[#73777f]">
                                <span>Sanitasi SirkulaCare:</span>
                                <span class="text-[#006c47] font-semibold">GRATIS</span>
                            </div>
                            <div class="pt-3 border-t border-[#e0e3e2] flex justify-between items-baseline">
                                <span class="font-headline font-bold text-sm text-[#002547]">Total Bayar:</span>
                                <span class="font-label text-xl font-bold text-[#002547]">
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Tombol Submit Checkout -->
                        <button type="submit" 
                                class="w-full h-[52px] bg-[#002547] hover:bg-[#000f23] text-white flex items-center justify-center font-label text-xs uppercase tracking-widest font-bold transition shadow-xs cursor-pointer">
                            Konfirmasi &amp; Buat Pesanan &rarr;
                        </button>

                        <div class="text-center font-body text-[10px] text-[#73777f] space-y-1">
                            <p>🔒 Stok langsung dikunci dengan aman saat Anda menekan tombol di atas</p>
                        </div>
                    </div>
                </div>

            </div>
        </form>

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
