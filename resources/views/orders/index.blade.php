<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Saya — SIRKULA Recommerce</title>
    
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
        <span>Layanan Pelanggan & Garansi Tukar Ukuran 3 Hari</span>
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

                <!-- PILAR 2: TENGAH (Sumbu Simetri - Link ke Katalog) -->
                <nav class="hidden md:flex items-center justify-center space-x-8 font-label text-xs uppercase tracking-widest font-semibold">
                    <a href="{{ route('products.index') }}" 
                       class="h-16 inline-flex items-center transition border-b-2 border-transparent text-[#73777f] hover:text-[#002547]">
                        &larr; Eksplorasi Katalog Pakaian
                    </a>
                </nav>

                <!-- PILAR 3: KANAN (Aksi & User Profile) -->
                <div class="flex items-center justify-end space-x-1 sm:space-x-3 font-label text-xs">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.orders.index') }}" class="text-[#006c47] bg-[#85f9bf]/30 px-2 py-1 rounded-none hover:bg-[#85f9bf]/50 transition font-bold uppercase tracking-wider text-[10px]">
                                Admin
                            </a>
                        @endif
                        <span class="font-semibold text-[#002547] px-2 py-1 bg-[#f1f4f3]">
                            {{ Auth::user()->name }}
                        </span>
                    @endauth

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
    </header>

    <!-- Main Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        
        <div class="flex items-baseline justify-between border-b border-[#e0e3e2] pb-4 mb-8">
            <div>
                <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-[#181c1c] tracking-tight">
                    Riwayat Pesanan Saya
                </h1>
                <p class="font-body text-xs text-[#73777f] mt-0.5">Pantau status transaksi, sanitasi pakaian, dan pengiriman kurir.</p>
            </div>
            <a href="{{ route('products.index') }}" class="font-label text-xs uppercase tracking-wider font-bold text-[#002547] hover:underline">
                + Belanja Lagi
            </a>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white border border-[#e0e3e2] p-16 text-center max-w-lg mx-auto">
                <span class="text-6xl block mb-4 opacity-60">📦</span>
                <h2 class="font-headline text-lg font-bold text-[#002547] mb-2">Belum Ada Riwayat Pesanan</h2>
                <p class="font-body text-xs text-[#73777f] mb-8 leading-relaxed">
                    Anda belum pernah melakukan pemesanan pakaian di Sirkula. Mulai eksplorasi koleksi kurasi vintage dan esensial kami.
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-[#002547] hover:bg-[#000f23] text-white font-label text-xs font-bold uppercase tracking-widest px-8 py-3.5 transition">
                    Mulai Belanja Pakaian &rarr;
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white border border-[#e0e3e2] overflow-hidden">
                        
                        <!-- Header Kartu Pesanan -->
                        <div class="bg-[#f1f4f3] px-4 sm:px-6 py-3.5 border-b border-[#e0e3e2] flex flex-wrap items-center justify-between gap-3 text-xs">
                            <div class="flex items-center space-x-3">
                                <span class="font-label font-bold text-[#002547] tracking-wider">{{ $order->order_number }}</span>
                                <span class="text-[#73777f]">&bull;</span>
                                <span class="font-body text-[#73777f]">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>

                            <!-- Badge Status Pesanan -->
                            <div>
                                @if($order->status === 'pending')
                                    <span class="px-2.5 py-1 bg-yellow-50 text-yellow-800 border border-yellow-200 font-label font-bold uppercase text-[10px] tracking-wider">
                                        Menunggu Transfer Bank
                                    </span>
                                @elseif($order->status === 'processing')
                                    <span class="px-2.5 py-1 bg-[#85f9bf]/40 text-[#006c47] border border-[#85f9bf] font-label font-bold uppercase text-[10px] tracking-wider">
                                        Sedang Disanitasi &amp; Dikemas
                                    </span>
                                @elseif($order->status === 'completed')
                                    <span class="px-2.5 py-1 bg-[#006c47] text-white font-label font-bold uppercase text-[10px] tracking-wider">
                                        Pesanan Selesai ✓
                                    </span>
                                @elseif($order->status === 'cancelled')
                                    <span class="px-2.5 py-1 bg-red-50 text-red-700 border border-red-200 font-label font-bold uppercase text-[10px] tracking-wider">
                                        Dibatalkan
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Daftar Item Pakaian -->
                        <div class="p-4 sm:p-6 divide-y divide-[#e0e3e2]">
                            @foreach($order->items as $item)
                                <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between text-xs">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-14 bg-[#f4f6f5] border border-[#e0e3e2] flex items-center justify-center shrink-0 text-[#73777f]">
                                            <svg class="w-6 h-6 text-[#c3c6cf]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-headline font-bold text-[#181c1c] text-xs">{{ $item->product_name }}</h4>
                                            <p class="font-label text-[11px] text-[#73777f] mt-0.5">
                                                {{ $item->variant_info }} &bull; {{ $item->quantity }} pcs
                                            </p>
                                        </div>
                                    </div>
                                    <span class="font-label font-bold text-[#002547]">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Footer Kartu Pesanan -->
                        <div class="bg-white px-4 sm:px-6 py-4 border-t border-[#e0e3e2] flex flex-wrap items-center justify-between gap-4 text-xs font-label">
                            <div>
                                <span class="text-[#73777f]">Penerima: </span>
                                <strong class="text-[#181c1c]">{{ $order->shipping_name }}</strong>
                                <span class="text-[#73777f]">({{ $order->shipping_address }})</span>
                            </div>

                            <div class="flex items-center space-x-6">
                                <div>
                                    <span class="text-[#73777f] block text-[10px] uppercase tracking-wider">Total Pembayaran (Transfer {{ strtoupper($order->payment_method) }})</span>
                                    <span class="font-headline text-base font-bold text-[#002547]">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </span>
                                </div>

                                @if($order->status === 'pending')
                                    <a href="{{ route('checkout.success', $order->order_number) }}" 
                                       class="bg-[#002547] hover:bg-[#000f23] text-white px-4 py-2 font-bold uppercase tracking-wider text-[10px] transition">
                                        Instruksi Rekening &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
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
