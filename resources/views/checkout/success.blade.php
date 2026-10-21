<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Diterima — {{ $order->order_number }} — SIRKULA Recommerce</title>
    
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
        <span>🌿 Reservasi Stok Pakaian Anda Telah Aman</span>
        <span class="text-white/40">&bull;</span>
        <span>SirkulaCare Siap Mempersiapkan Pakaian Anda</span>
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
                    <span class="text-[#73777f]">2. Pengiriman & Bayar</span>
                    <span class="text-[#73777f]">&rarr;</span>
                    <span class="text-[#006c47] font-bold border-b-2 border-[#006c47] pb-0.5">3. Pesanan Diterima ✓</span>
                </nav>

                <!-- PILAR 3: KANAN (Kembali ke Beranda) -->
                <div class="flex items-center justify-end font-label text-xs">
                    <a href="{{ route('products.index') }}" class="text-[#002547] font-bold hover:underline">
                        Kembali ke Toko &rarr;
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
        
        <div class="bg-white border border-[#e0e3e2] p-6 sm:p-10 space-y-8">
            
            <!-- Icon & Status Berhasil -->
            <div class="text-center pb-8 border-b border-[#e0e3e2]">
                <div class="w-14 h-14 bg-[#85f9bf]/30 text-[#006c47] flex items-center justify-center mx-auto mb-3 font-headline text-2xl font-bold border border-[#85f9bf]">
                    ✓
                </div>
                <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-[#002547] tracking-tight">
                    Pesanan Anda Berhasil Dibuat!
                </h1>
                <p class="font-body text-xs text-[#73777f] mt-1">
                    Nomor Pesanan: <strong class="text-[#002547] font-label font-bold tracking-wider">{{ $order->order_number }}</strong>
                </p>
                <div class="mt-3">
                    <span class="inline-block px-3 py-1 bg-yellow-50 text-yellow-800 border border-yellow-200 font-label text-[10px] font-bold uppercase tracking-wider">
                        Status: Menunggu Pembayaran Transfer
                    </span>
                </div>
            </div>

            <!-- Petunjuk Rekening Transfer Bank -->
            <div class="p-6 bg-[#f1f4f3] border border-[#e0e3e2] space-y-4">
                <h2 class="font-headline text-xs font-bold uppercase tracking-wider text-[#002547] pb-2 border-b border-[#e0e3e2]">
                    Instruksi Pembayaran Transfer Bank
                </h2>
                
                <div class="space-y-3 font-label text-xs">
                    <div class="flex justify-between items-center pb-2.5 border-b border-[#e0e3e2]">
                        <span class="text-[#73777f]">Bank Tujuan:</span>
                        <strong class="text-[#002547] uppercase font-bold">{{ strtoupper($order->payment_method) }}</strong>
                    </div>

                    <div class="flex justify-between items-center pb-2.5 border-b border-[#e0e3e2]">
                        <span class="text-[#73777f]">Nomor Rekening Resmi:</span>
                        <div class="flex items-center space-x-2">
                            <span id="rekening" class="font-bold text-sm text-[#002547] bg-white px-3 py-1 border border-[#e0e3e2]">
                                123-456-7890
                            </span>
                            <button type="button" 
                                    onclick="navigator.clipboard.writeText('1234567890'); alert('Nomor rekening disalin!')"
                                    class="bg-[#002547] text-white text-[10px] uppercase tracking-wider font-bold px-2 py-1 hover:bg-[#000f23] transition">
                                Salin
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pb-2.5 border-b border-[#e0e3e2]">
                        <span class="text-[#73777f]">Atas Nama Rekening:</span>
                        <strong class="text-[#181c1c] font-bold">PT SIRKULA INDONESIA LESTARI</strong>
                    </div>

                    <div class="flex justify-between items-center pt-1">
                        <span class="font-headline font-bold text-xs text-[#002547]">Total yang Wajib Ditransfer:</span>
                        <strong class="font-label text-2xl text-[#002547] font-bold">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>

                <p class="font-body text-[11px] text-[#73777f] leading-relaxed pt-2">
                    *Harap lakukan transfer dengan jumlah tepat di atas dalam 1x24 jam. Setelah transfer terverifikasi oleh admin, pesanan akan langsung diproses ke tahap sanitasi dan pengemasan higienis SirkulaCare.
                </p>
            </div>

            <!-- Rincian Pakaian yang Dipesan -->
            <div class="space-y-4">
                <h2 class="font-headline text-xs font-bold uppercase tracking-wider text-[#002547]">
                    Rincian Pakaian yang Dipesan
                </h2>
                
                <div class="border border-[#e0e3e2] divide-y divide-[#e0e3e2]">
                    @foreach($order->items as $item)
                        <div class="p-4 flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-12 bg-[#f4f6f5] border border-[#e0e3e2] flex items-center justify-center shrink-0 text-[#73777f]">
                                    <svg class="w-5 h-5 text-[#c3c6cf]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-headline font-bold text-[#181c1c] text-xs">{{ $item->product_name }}</h4>
                                    <p class="font-label text-[10px] text-[#73777f]">{{ $item->variant_info }} &bull; {{ $item->quantity }} pcs</p>
                                </div>
                            </div>
                            <span class="font-label font-bold text-[#002547]">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Informasi Alamat Penerima -->
            <div class="p-4 bg-[#f1f4f3] border border-[#e0e3e2] font-body text-xs space-y-1">
                <h4 class="font-headline font-bold text-xs text-[#002547] uppercase tracking-wider mb-2">Tujuan Pengiriman</h4>
                <p><strong>Penerima:</strong> {{ $order->shipping_name }} ({{ $order->shipping_phone }})</p>
                <p><strong>Alamat:</strong> {{ $order->shipping_address }}</p>
                @if($order->shipping_notes)
                    <p class="text-[#73777f]"><strong>Catatan:</strong> {{ $order->shipping_notes }}</p>
                @endif
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 flex flex-col sm:flex-row gap-3 font-label text-xs uppercase tracking-wider font-bold">
                <a href="{{ route('orders.index') }}" 
                   class="flex-1 h-12 bg-[#002547] hover:bg-[#000f23] text-white flex items-center justify-center transition">
                    Lihat Riwayat Pesanan Saya &rarr;
                </a>
                <a href="{{ route('products.index') }}" 
                   class="flex-1 h-12 bg-white hover:bg-[#f1f4f3] text-[#002547] border border-[#002547] flex items-center justify-center transition">
                    Lanjut Belanja Pakaian
                </a>
            </div>

        </div>

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
