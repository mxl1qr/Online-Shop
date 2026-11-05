<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan {{ $order->order_number }} — SIRKULA Recommerce</title>
    
    <!-- Google Fonts Trio dari DESIGN.md -->
    @include('partials.head-fonts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
@php
    $storeConfig = \App\Http\Controllers\AdminOrderController::getStoreConfig();
    $method = strtolower($order->payment_method ?? 'bca');
    $bankConfigKey = 'bank_' . $method;
    $rawBank = $storeConfig[$bankConfigKey] ?? ($method === 'mandiri' ? ($storeConfig['bank_mandiri'] ?? '900-00-1234567-8') : ($method === 'bri' ? ($storeConfig['bank_bri'] ?? '0341-01-000789-30-5') : ($storeConfig['bank_bca'] ?? '123-456-7890')));
    $bankParts = explode(' a/n ', (string) $rawBank);
    $bankAccountNo = trim($bankParts[0]);
    $bankAccountHolder = isset($bankParts[1]) ? trim($bankParts[1]) : 'PT SIRKULASI INDONESIA LESTARI';
    $bankDigits = preg_replace('/[^0-9]/', '', $bankAccountNo);

    $itemsSubtotal = $order->items->sum('subtotal');
    $shippingFee = max(0, $order->total_amount - $itemsSubtotal);
@endphp
<body class="bg-surface text-ink antialiased font-body selection:bg-navy selection:text-white">

    <!-- 1. Top Announcement Bar -->
    <div class="bg-navy text-white text-xs py-2 px-4 text-center font-label font-medium tracking-wide flex items-center justify-center space-x-3">
        <span class="inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-emerald-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Reservasi Stok Pakaian Anda Telah Aman
        </span>
        <span class="text-white/40">&bull;</span>
        <span>SirkulaCare Siap Mempersiapkan Pakaian Anda</span>
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

                <!-- PILAR 2: TENGAH (Sumbu Simetri - Status Step) -->
                <nav class="hidden md:flex items-center justify-center space-x-6 font-label text-sm font-semibold">
                    <span class="text-muted">1. Keranjang</span>
                    <span class="text-muted">&rarr;</span>
                    <span class="text-muted">2. Pengiriman &amp; Bayar</span>
                    <span class="text-muted">&rarr;</span>
                    <span class="text-emerald font-bold border-b-2 border-emerald pb-0.5">3. Rincian Pesanan ✓</span>
                </nav>

                <!-- PILAR 3: KANAN (Kembali ke Beranda) -->
                <div class="flex items-center justify-end font-label text-sm">
                    <a href="{{ route('products.index') }}" class="text-navy font-bold hover:underline">
                        Kembali ke Toko &rarr;
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
        
        <div class="card p-6 sm:p-10 space-y-8">
            
            <!-- Icon & Status Berhasil Dinamis -->
            <div class="text-center pb-8 border-b border-line">
                @if($order->status === 'cancelled')
                    <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-3 font-headline text-2xl font-bold border border-red-200">
                        &times;
                    </div>
                    <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-navy tracking-tight">
                        Pesanan Ini Telah Dibatalkan
                    </h1>
                @elseif($order->status === 'completed')
                    <div class="w-14 h-14 bg-emerald-soft/30 text-emerald rounded-2xl flex items-center justify-center mx-auto mb-3 font-headline text-2xl font-bold border border-emerald-soft">
                        ✓
                    </div>
                    <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-navy tracking-tight">
                        Pesanan Selesai &bull; Terkirim
                    </h1>
                @elseif($order->status === 'processing')
                    <div class="w-14 h-14 bg-teal-50 text-emerald rounded-2xl flex items-center justify-center mx-auto mb-3 font-headline text-2xl font-bold border border-teal-200">
                        ✓
                    </div>
                    <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-navy tracking-tight">
                        Pesanan Sedang Diproses &bull; SirkulaCare
                    </h1>
                @else
                    <div class="w-14 h-14 bg-emerald-soft/30 text-emerald rounded-2xl flex items-center justify-center mx-auto mb-3 font-headline text-2xl font-bold border border-emerald-soft">
                        ✓
                    </div>
                    <h1 class="font-headline text-2xl sm:text-3xl font-extrabold text-navy tracking-tight">
                        Pesanan Anda Berhasil Dibuat!
                    </h1>
                @endif

                <p class="font-body text-sm text-muted mt-1">
                    Nomor Pesanan: <strong class="text-navy font-label font-bold tracking-wider">{{ $order->order_number }}</strong>
                </p>

                <!-- Badge Status Pesanan Aktual -->
                <div class="mt-3">
                    <x-status-badge :status="$order->status" mode="detail" />
                </div>
            </div>

            <!-- Petunjuk Rekening Transfer Bank (Hanya jika Status Pending) -->
            @if($order->status === 'pending')
                <div class="p-6 bg-surface-low border border-line rounded-xl space-y-4">
                    <h2 class="font-headline text-sm font-bold text-navy pb-2 border-b border-line">
                        Instruksi Pembayaran Transfer Bank
                    </h2>
                    
                    <div class="space-y-3 font-label text-sm">
                        <div class="flex justify-between items-center pb-2.5 border-b border-line">
                            <span class="text-muted">Bank Tujuan:</span>
                            <strong class="text-navy font-bold">{{ strtoupper($order->payment_method) }}</strong>
                        </div>

                        <div class="flex justify-between items-center pb-2.5 border-b border-line">
                            <span class="text-muted">Nomor Rekening Resmi:</span>
                            <div class="flex items-center space-x-2">
                                <span id="rekening" class="font-bold text-sm text-navy bg-white px-3 py-1 border border-line rounded-md font-mono">
                                    {{ $bankAccountNo }}
                                </span>
                                <button type="button" 
                                        onclick="navigator.clipboard.writeText('{{ $bankDigits }}'); alert('Nomor rekening disalin!')"
                                        class="bg-navy text-white text-xs uppercase tracking-wider font-bold px-2.5 py-1.5 rounded-md hover:bg-navy-600 transition cursor-pointer">
                                    Salin
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pb-2.5 border-b border-line">
                            <span class="text-muted">Atas Nama Rekening:</span>
                            <strong class="text-ink font-bold">{{ $bankAccountHolder }}</strong>
                        </div>

                        <div class="flex justify-between items-center pt-1">
                            <span class="font-headline font-bold text-sm text-navy">Total yang Wajib Ditransfer:</span>
                            <strong class="font-label text-2xl text-navy font-bold">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>

                    <p class="font-body text-sm text-muted leading-relaxed pt-2">
                        *Harap lakukan transfer tepat sesuai jumlah di atas dalam 1x24 jam. Setelah transfer terverifikasi oleh tim admin, pakaian Anda akan langsung disiapkan, disanitasi medis ozon, dan dikirimkan oleh kurir.
                    </p>
                </div>
            @elseif($order->status === 'processing' || $order->status === 'completed')
                <div class="p-5 bg-teal-50 border border-teal-200 rounded-xl flex items-center justify-between text-sm font-label">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-emerald text-white flex items-center justify-center font-bold text-sm">✓</span>
                        <div>
                            <strong class="text-emerald block font-bold">Pembayaran Telah Diverifikasi</strong>
                            <span class="text-ink/80 font-body text-sm">Metode: Transfer {{ strtoupper($order->payment_method) }} &bull; Total Terbayar: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <span class="text-xs uppercase tracking-wider font-bold text-emerald bg-emerald-soft/40 px-2.5 py-1 rounded-md">Lunas</span>
                </div>
            @else
                <div class="p-5 bg-red-50 border border-red-200 rounded-xl text-sm font-label text-red-800">
                    <strong class="block font-bold mb-1">Pesanan Dibatalkan</strong>
                    <span class="font-body text-sm">Pesanan ini telah dibatalkan dan kuota stok pakaian telah dikembalikan secara otomatis ke katalog Sirkula.</span>
                </div>
            @endif

            <!-- Rincian Pakaian yang Dipesan -->
            <div class="space-y-4">
                <h2 class="font-headline text-sm font-bold text-navy">
                    Rincian Pakaian yang Dipesan
                </h2>
                
                <div class="border border-line rounded-xl divide-y divide-line overflow-hidden">
                    @foreach($order->items as $item)
                        <div class="p-4 flex items-center justify-between text-sm">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-16 bg-canvas border border-line rounded-lg flex items-center justify-center shrink-0 text-muted overflow-hidden">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-line-strong" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-headline font-bold text-ink text-sm">{{ $item->product_name }}</h4>
                                    <p class="font-label text-sm text-muted">{{ $item->variant_info }} &bull; {{ $item->quantity }} pcs</p>
                                </div>
                            </div>
                            <span class="font-label font-bold text-navy">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Rincian Kalkulasi Harga (Subtotal + Ongkir + Total) -->
                <div class="p-4 bg-white border border-line rounded-xl space-y-2 font-label text-sm">
                    <div class="flex justify-between text-muted">
                        <span>Subtotal Pakaian:</span>
                        <span class="font-semibold text-ink">Rp {{ number_format($itemsSubtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-muted">
                        <span>Ongkos Kirim:</span>
                        <span class="font-semibold {{ $shippingFee == 0 ? 'text-emerald' : 'text-ink' }}">
                            {{ $shippingFee == 0 ? 'GRATIS (Promo Bebas Ongkir)' : 'Rp ' . number_format($shippingFee, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between text-muted">
                        <span>Sanitasi SirkulaCare:</span>
                        <span class="text-emerald font-semibold">GRATIS</span>
                    </div>
                    <div class="pt-2 border-t border-line flex justify-between items-baseline">
                        <span class="font-headline font-bold text-sm text-navy">Total Tagihan:</span>
                        <span class="font-label text-base font-extrabold text-navy">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Alamat Penerima -->
            <div class="p-4 bg-surface-low border border-line rounded-xl font-body text-sm space-y-1">
                <h4 class="font-headline font-bold text-sm text-navy mb-2">Tujuan Pengiriman</h4>
                <p><strong>Penerima:</strong> {{ $order->shipping_name }} ({{ $order->shipping_phone }})</p>
                <p><strong>Alamat:</strong> {{ $order->shipping_address }}</p>
                @if($order->shipping_notes)
                    <p class="text-muted"><strong>Catatan:</strong> {{ $order->shipping_notes }}</p>
                @endif
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 flex flex-col sm:flex-row gap-3 font-label text-sm font-bold">
                <a href="{{ route('orders.index') }}" 
                   class="flex-1 h-12 bg-navy hover:bg-navy-600 text-white rounded-lg flex items-center justify-center transition">
                    Lihat Riwayat Pesanan Saya &rarr;
                </a>
                <a href="{{ route('products.index') }}" 
                   class="flex-1 h-12 bg-white hover:bg-surface-low text-navy border border-navy rounded-lg flex items-center justify-center transition">
                    Lanjut Belanja Pakaian
                </a>
            </div>

        </div>

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
