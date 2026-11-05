<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Saya — SIRKULA Recommerce</title>
    
    <!-- Google Fonts Trio dari DESIGN.md -->
    @include('partials.head-fonts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-surface text-ink antialiased font-body selection:bg-navy selection:text-white">

    <!-- 1. Top Announcement Bar -->
    <div class="bg-navy text-white text-xs py-2 px-4 text-center font-label font-medium tracking-wide flex items-center justify-center space-x-3">
        <span class="inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-emerald-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            100% Terkurasi &amp; Higienis (Sanitized)
        </span>
        <span class="text-white/40">&bull;</span>
        <span>Layanan Pelanggan &amp; Garansi Tukar Ukuran 3 Hari</span>
    </div>

    <!-- 2. Main Navigation Bar (CSS Grid 3 Pilar Presisi 64px) -->
    <header class="sticky top-0 z-50 bg-white border-b border-line">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-[auto_1fr_auto] md:grid-cols-[1fr_auto_1fr] items-center h-16 gap-4">
                
                <!-- PILAR 1: KIRI (Logo Brand Sirkula) -->
                <div class="flex items-center justify-start">
                    <a href="{{ route('products.index') }}" class="flex items-center space-x-2.5 group">
                        @include('partials.brand-logo', ['size' => 'md'])
                    </a>
                </div>

                <!-- PILAR 2: TENGAH (Sumbu Simetri - Link ke Katalog) -->
                <nav class="hidden md:flex items-center justify-center space-x-8 font-label text-xs uppercase tracking-widest font-semibold">
                    <a href="{{ route('products.index') }}" 
                       class="h-16 inline-flex items-center transition border-b-2 border-transparent text-muted hover:text-navy">
                        &larr; Eksplorasi Katalog Pakaian
                    </a>
                </nav>

                <!-- PILAR 3: KANAN (Aksi & User Profile) -->
                <div class="flex items-center justify-end space-x-1 sm:space-x-3 font-label text-sm">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.orders.index') }}" class="text-emerald bg-emerald-soft/30 px-2.5 py-1 rounded-md hover:bg-emerald-soft/50 transition font-bold uppercase tracking-wider text-xs">
                                Admin
                            </a>
                        @endif
                        <a href="{{ route('member') }}" class="font-semibold text-navy px-2.5 py-1 bg-surface-low rounded-md hover:bg-[#e5eae8] transition" title="Keanggotaan Sirkula">
                            {{ Auth::user()->name }}
                        </a>
                    @endauth

                    <a href="{{ route('cart.index') }}" class="w-9 h-9 relative flex items-center justify-center text-navy hover:opacity-80 transition" title="Keranjang">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(count(session('cart', [])) > 0)
                            <span class="absolute top-1 right-1 w-3.5 h-3.5 bg-navy text-white text-xs font-bold rounded-full flex items-center justify-center leading-none">
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
        
        <div class="flex items-baseline justify-between border-b border-line pb-4 mb-8">
            <div>
                <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">
                    Riwayat Pesanan Saya
                </h1>
                <p class="font-body text-sm text-muted mt-0.5">Pantau status transaksi, sanitasi pakaian, dan pengiriman kurir.</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('member') }}" class="font-label text-sm font-bold text-muted hover:text-navy">
                    &larr; Hub Member
                </a>
                <a href="{{ route('products.index') }}" class="font-label text-sm font-bold text-navy hover:underline">
                    + Belanja Lagi
                </a>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="card p-12 sm:p-16 text-center max-w-lg mx-auto">
                <div class="w-16 h-16 bg-canvas border border-line rounded-full flex items-center justify-center mx-auto mb-4 text-navy">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h2 class="font-headline text-lg font-bold text-navy mb-2">Belum Ada Riwayat Pesanan</h2>
                <p class="font-body text-sm text-muted mb-8 leading-relaxed">
                    Anda belum pernah melakukan pemesanan pakaian di Sirkula. Mulai eksplorasi koleksi kurasi vintage dan esensial kami.
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-navy hover:bg-navy-600 text-white font-label text-xs font-bold uppercase tracking-widest px-8 py-3.5 rounded-lg transition">
                    Mulai Belanja Pakaian &rarr;
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="card overflow-hidden">
                        
                        <!-- Header Kartu Pesanan -->
                        <div class="bg-surface-low px-4 sm:px-6 py-3.5 border-b border-line flex flex-wrap items-center justify-between gap-3 text-sm">
                            <div class="flex items-center space-x-3">
                                <span class="font-label font-bold text-navy tracking-wider">{{ $order->order_number }}</span>
                                <span class="text-muted">&bull;</span>
                                <span class="font-body text-muted">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>

                            <!-- Badge Status Pesanan -->
                            <div>
                                <x-status-badge :status="$order->status" mode="long" />
                            </div>
                        </div>

                        <!-- Daftar Item Pakaian -->
                        <div class="p-4 sm:p-6 divide-y divide-line">
                            @foreach($order->items as $item)
                                <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-16 bg-canvas border border-line rounded-lg flex items-center justify-center shrink-0 text-muted overflow-hidden">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-6 h-6 text-line-strong" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-headline font-bold text-ink text-sm">{{ $item->product_name }}</h4>
                                            <p class="font-label text-sm text-muted mt-0.5">
                                                {{ $item->variant_info }} &bull; {{ $item->quantity }} pcs
                                            </p>
                                        </div>
                                    </div>
                                    <span class="font-label font-bold text-navy">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Footer Kartu Pesanan -->
                        <div class="bg-white px-4 sm:px-6 py-4 border-t border-line flex flex-wrap items-center justify-between gap-4 text-sm font-label">
                            <div>
                                <span class="text-muted">Penerima: </span>
                                <strong class="text-ink">{{ $order->shipping_name }}</strong>
                                <span class="text-muted">({{ $order->shipping_address }})</span>
                            </div>

                            <div class="flex items-center space-x-6">
                                <div>
                                    <span class="text-muted block text-sm">Total Pembayaran (Transfer {{ strtoupper($order->payment_method) }})</span>
                                    <span class="font-headline text-base font-bold text-navy">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </span>
                                </div>

                                @if($order->status === 'pending')
                                    <a href="{{ route('checkout.success', $order->order_number) }}" 
                                       class="bg-navy hover:bg-navy-600 text-white px-4 py-2 rounded-lg font-bold text-sm transition">
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
