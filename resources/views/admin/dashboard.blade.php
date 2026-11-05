@component('layouts.admin', [
    'title' => 'Ringkasan Eksekutif & Analitik',
    'pageTitle' => 'Ringkasan & Analitik'
])
<div class="space-y-6">
    <!-- Header Banner & Action Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-headline text-2xl font-extrabold text-navy tracking-tight">
                Ringkasan Eksekutif &amp; Operasional
            </h1>
            <p class="text-sm text-muted mt-0.5">Pantau omzet penjualan, antrean sanitasi SirkulaCare, dan ketersediaan stok kurasi secara real-time.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.products.index') }}"
               class="btn btn-primary h-10 px-4 rounded-xl font-label text-xs font-bold transition cursor-pointer flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Kelola Produk</span>
            </a>
            <a href="{{ route('admin.export', 'orders') }}" 
               class="btn btn-outline h-10 px-3.5 rounded-xl font-label text-xs font-bold transition cursor-pointer flex items-center gap-1.5"
               title="Unduh Laporan Transaksi CSV">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span class="hidden sm:inline">Ekspor CSV</span>
            </a>
        </div>
    </div>

    <!-- 4 Kartu KPI Utama (Enterprise Metrics with Deep Contrast) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        
        <!-- KPI 1: Pendapatan -->
        <div class="card p-5 space-y-2 hover:shadow-float transition">
            <div class="flex items-center justify-between">
                <span class="font-label text-xs font-bold uppercase tracking-widest text-emerald">Total Pendapatan</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-soft/30 text-emerald flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="font-headline text-2xl font-extrabold text-navy tracking-tight">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
            <div class="pt-1 border-t border-line flex items-center justify-between text-xs font-label">
                <span class="text-muted">Menunggu Bayar:</span>
                <span class="font-bold text-amber-700">Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- KPI 2: Antrean Pesanan -->
        <div class="card p-5 space-y-2 hover:shadow-float transition">
            <div class="flex items-center justify-between">
                <span class="font-label text-xs font-bold uppercase tracking-widest text-amber-700">Antrean Pesanan</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <p class="font-headline text-2xl font-extrabold text-navy tracking-tight">
                    {{ $counts['pending'] + $counts['processing'] }}
                </p>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">Perlu Diproses</span>
            </div>
            <div class="pt-1 border-t border-line flex items-center justify-between text-xs font-label">
                <span class="text-muted">{{ $counts['pending'] }} Pending</span>
                <span class="text-emerald font-semibold">{{ $counts['completed'] }} Terkirim ✓</span>
            </div>
        </div>

        <!-- KPI 3: Katalog & Inventaris -->
        <div class="card p-5 space-y-2 hover:shadow-float transition">
            <div class="flex items-center justify-between">
                <span class="font-label text-xs font-bold uppercase tracking-widest text-navy">Katalog &amp; Stok</span>
                <div class="w-8 h-8 rounded-xl bg-navy/10 text-navy flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <p class="font-headline text-2xl font-extrabold text-navy tracking-tight">
                    {{ $productsCount }}
                </p>
                <span class="text-xs text-muted font-medium">Model ({{ $totalStock }} pcs)</span>
            </div>
            <div class="pt-1 border-t border-line flex items-center justify-between text-xs font-label">
                <span class="text-muted">Aset Inventori:</span>
                <span class="font-bold text-navy">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- KPI 4: Pengguna & Komunitas -->
        <div class="card p-5 space-y-2 hover:shadow-float transition">
            <div class="flex items-center justify-between">
                <span class="font-label text-xs font-bold uppercase tracking-widest text-emerald">Pengguna Terdaftar</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-soft/30 text-emerald flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <p class="font-headline text-2xl font-extrabold text-navy tracking-tight">
                    {{ $usersCount }}
                </p>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-soft/30 text-emerald">Member Aktif</span>
            </div>
            <div class="pt-1 border-t border-line flex items-center justify-between text-xs font-label">
                <span class="text-muted">Akses:</span>
                <a href="{{ route('admin.users.index') }}" class="font-bold text-navy hover:underline">Kelola &rarr;</a>
            </div>
        </div>

    </div>

    <!-- 2 Kolom Layout: Peringatan Stok Kritis & Komposisi Metode Pembayaran -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Peringatan Stok Kritis (7 Kolom) -->
        <div class="card lg:col-span-7 p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-line">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-600 animate-pulse"></span>
                    <h2 class="font-headline text-base font-extrabold text-navy">
                        Peringatan Stok Kritis (&le; 1 pcs)
                    </h2>
                </div>
                <a href="{{ route('admin.products.index') }}" class="font-label text-xs font-bold text-navy hover:underline">
                    Buka Inventori &rarr;
                </a>
            </div>

            @if($lowStockVariants->isEmpty())
                <div class="p-8 text-center text-muted font-body text-sm space-y-2">
                    <div class="w-10 h-10 rounded-full bg-emerald-soft/30 text-emerald flex items-center justify-center mx-auto">✓</div>
                    <p class="font-bold text-navy">Stok Aman Terkendali</p>
                    <p class="text-xs text-muted">Seluruh varian produk memiliki stok di atas batas minimum.</p>
                </div>
            @else
                <div class="divide-y divide-line font-label text-xs">
                    @foreach($lowStockVariants as $lv)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <span class="font-mono text-muted text-[11px] block">{{ $lv->sku }}</span>
                                <strong class="text-navy text-sm font-semibold truncate block">{{ $lv->product->name ?? 'Produk Dihapus' }}</strong>
                                <span class="text-muted">Varian: {{ $lv->size }} &bull; Warna: {{ $lv->color }}</span>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="px-2.5 py-1 rounded-full font-bold text-xs {{ $lv->stock === 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $lv->stock === 0 ? 'Habis (0)' : 'Sisa 1 pcs' }}
                                </span>
                                <form action="{{ route('admin.variants.update', $lv->id) }}" method="POST" class="flex items-center gap-1.5 shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="stock" value="{{ $lv->stock + 3 }}" min="0" 
                                           class="w-16 h-8 px-2 text-center rounded-lg bg-surface-low border border-line text-xs font-bold">
                                    <button type="submit" class="h-8 px-2.5 rounded-lg bg-navy hover:bg-navy-600 text-white font-bold text-xs cursor-pointer transition" title="Tambah 3 pcs stok cepat">
                                        + Restock
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Kanal Pembayaran & Produk Terlaris (5 Kolom) -->
        <div class="space-y-6 lg:col-span-5">
            
            <!-- Breakdown Pembayaran -->
            <div class="card p-6 space-y-4">
                <h3 class="font-headline text-base font-extrabold text-navy pb-3 border-b border-line flex items-center justify-between">
                    <span>Metode Pembayaran</span>
                    <span class="text-xs font-label text-muted font-normal">Omzet Masuk</span>
                </h3>

                <div class="space-y-3 font-label text-xs">
                    @forelse($paymentBreakdown as $pay)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-navy uppercase">{{ $pay->payment_method }} ({{ $pay->total_orders }} order)</span>
                                <span class="font-extrabold text-ink">Rp {{ number_format($pay->revenue, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-surface-low rounded-full h-2 overflow-hidden">
                                @php
                                    $pct = $totalRevenue > 0 ? min(100, round(($pay->revenue / $totalRevenue) * 100)) : 0;
                                @endphp
                                <div class="bg-navy h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-xs py-2 text-center">Belum ada transaksi tervalidasi.</p>
                    @endforelse
                </div>
            </div>

            <!-- Top 5 Produk Terlaris -->
            <div class="card p-6 space-y-4">
                <h3 class="font-headline text-base font-extrabold text-navy pb-3 border-b border-line">
                    Top 5 Best Seller Pakaian
                </h3>

                <div class="divide-y divide-line font-label text-xs">
                    @forelse($topProducts as $idx => $tp)
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-5 h-5 rounded-full bg-surface-low text-navy font-extrabold flex items-center justify-center text-[10px] shrink-0 font-mono">
                                    {{ $idx + 1 }}
                                </span>
                                <strong class="text-navy truncate font-semibold">{{ $tp->product_name }}</strong>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-bold text-emerald">{{ $tp->total_qty }} pcs</span>
                                <span class="text-[11px] text-muted block">Rp {{ number_format($tp->total_sales, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-xs py-2 text-center">Belum ada data penjualan.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
@endcomponent
