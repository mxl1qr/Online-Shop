@component('layouts.admin', [
    'title' => 'Kelola Pesanan & Logistik Pengiriman',
    'pageTitle' => 'Pesanan & Logistik'
])

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #sirkula-print-modal, #sirkula-print-modal * { visibility: visible; }
        #sirkula-print-modal {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .no-print { display: none !important; }
    }
</style>
@endpush

<div x-data="{ printOrder: null }" class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-headline text-2xl font-extrabold text-navy tracking-tight">
                Kelola Pesanan &amp; Logistik Pengiriman
            </h1>
            <p class="font-body text-sm text-muted mt-0.5">
                Verifikasi mutasi transfer bank, pantau sanitasi ozon SirkulaCare, cetak label pengiriman, atau batalkan pesanan.
            </p>
        </div>

        <!-- Form Cari Pesanan -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative flex-1 sm:w-64">
                <input type="text" name="q_order" value="{{ request('q_order') }}" placeholder="Cari No. Order / Penerima..."
                       class="h-10 pl-9 pr-3.5 rounded-xl bg-white border border-line-strong text-sm font-label focus:border-navy focus:ring-2 focus:ring-emerald/20 w-full transition">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-muted">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
            </div>
            <button type="submit" class="btn btn-primary h-10 px-4 rounded-xl font-label text-xs font-bold cursor-pointer shrink-0">
                Cari
            </button>
        </form>
    </div>

    <!-- Filter Segmented Tabs with Counts -->
    <div class="flex flex-wrap gap-2 font-label text-xs font-bold">
        <a href="{{ route('admin.orders.index') }}"
           class="px-3.5 py-2 rounded-xl transition border {{ !request('status') ? 'bg-navy text-white border-navy shadow-sm' : 'bg-white text-muted border-line hover:border-navy' }}">
            Semua ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
           class="px-3.5 py-2 rounded-xl transition border {{ request('status') == 'pending' ? 'bg-navy text-white border-navy shadow-sm' : 'bg-white text-muted border-line hover:border-navy' }}">
            Menunggu Bayar ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}"
           class="px-3.5 py-2 rounded-xl transition border {{ request('status') == 'processing' ? 'bg-navy text-white border-navy shadow-sm' : 'bg-white text-muted border-line hover:border-navy' }}">
            Disanitasi &amp; Dikemas ({{ $counts['processing'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}"
           class="px-3.5 py-2 rounded-xl transition border {{ request('status') == 'completed' ? 'bg-navy text-white border-navy shadow-sm' : 'bg-white text-muted border-line hover:border-navy' }}">
            Selesai ({{ $counts['completed'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}"
           class="px-3.5 py-2 rounded-xl transition border {{ request('status') == 'cancelled' ? 'bg-navy text-white border-navy shadow-sm' : 'bg-white text-muted border-line hover:border-navy' }}">
            Dibatalkan ({{ $counts['cancelled'] }})
        </a>
    </div>

    <!-- Tabel Pesanan Enterprise -->
    <div class="card overflow-hidden">
        @if($orders->isEmpty())
            <div class="p-16 text-center text-muted font-body text-sm space-y-3">
                <div class="w-12 h-12 rounded-full bg-surface-low border border-line flex items-center justify-center mx-auto text-muted">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <p class="font-bold text-navy">Tidak ada transaksi pada filter ini.</p>
                <p class="text-xs text-muted max-w-sm mx-auto">Pesanan yang masuk akan otomatis tampil di sini dengan status real-time.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm divide-y divide-line">
                    <thead class="bg-surface-low text-navy font-label font-bold text-xs uppercase tracking-wider sticky top-0 z-10">
                        <tr>
                            <th class="p-4">No. Order &amp; Waktu</th>
                            <th class="p-4">Penerima &amp; Alamat</th>
                            <th class="p-4">Item Pakaian</th>
                            <th class="p-4">Total &amp; Metode</th>
                            <th class="p-4">Status Pesanan</th>
                            <th class="p-4 text-right">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line font-body text-sm">
                        @foreach($orders as $order)
                            <tr class="hover:bg-surface-low/50 transition">
                                <!-- Order ID & Timestamp -->
                                <td class="p-4 align-top whitespace-nowrap">
                                    <strong class="font-mono font-bold text-navy block text-xs tracking-wider">{{ $order->order_number }}</strong>
                                    <span class="font-label text-muted text-xs">{{ $order->created_at->format('d/m/Y H:i') }} WIB</span>
                                </td>

                                <!-- Recipient & Address -->
                                <td class="p-4 align-top">
                                    <div class="flex items-start gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-navy/10 text-navy font-bold text-xs flex items-center justify-center shrink-0 mt-0.5 font-label">
                                            {{ strtoupper(substr($order->shipping_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <strong class="text-ink block font-headline font-semibold text-sm">{{ $order->shipping_name }}</strong>
                                            <span class="text-muted font-label text-xs block">{{ $order->shipping_phone }}</span>
                                            <p class="text-xs text-ink/80 mt-1 max-w-xs line-clamp-2 leading-relaxed" title="{{ $order->shipping_address }}">
                                                {{ $order->shipping_address }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Ordered Items -->
                                <td class="p-4 align-top">
                                    <div class="space-y-1.5 max-w-xs font-label text-xs">
                                        @foreach($order->items as $item)
                                            <div class="flex items-baseline justify-between gap-2 border-b border-line/40 pb-1">
                                                <div class="truncate">
                                                    <span class="font-semibold text-ink">{{ $item->product_name }}</span>
                                                    <span class="text-muted block text-[11px]">{{ $item->variant_info }}</span>
                                                </div>
                                                <span class="text-navy font-bold whitespace-nowrap shrink-0">x{{ $item->quantity }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                <!-- Total & Payment -->
                                <td class="p-4 align-top whitespace-nowrap font-label">
                                    <span class="font-bold text-navy block text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    <span class="px-2 py-0.5 rounded bg-surface-low text-muted font-mono text-[11px] uppercase tracking-wider inline-block mt-0.5 border border-line">
                                        Transfer {{ strtoupper($order->payment_method) }}
                                    </span>
                                </td>

                                <!-- Status Badge & Updater Form -->
                                <td class="p-4 align-top whitespace-nowrap">
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline-flex items-center gap-1.5 font-label">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" 
                                                class="h-8 px-2.5 rounded-lg border text-xs font-bold transition focus:ring-1 cursor-pointer
                                                {{ $order->status === 'pending' ? 'bg-amber-50 text-amber-800 border-amber-200 focus:ring-amber-400' : '' }}
                                                {{ $order->status === 'processing' ? 'bg-teal-50 text-teal-800 border-teal-200 focus:ring-teal-400' : '' }}
                                                {{ $order->status === 'completed' ? 'bg-emerald-soft/30 text-emerald border-emerald/30 focus:ring-emerald' : '' }}
                                                {{ $order->status === 'cancelled' ? 'bg-red-50 text-red-700 border-red-200 focus:ring-red-400' : '' }}">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses (Sanitasi)</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                        <button type="submit" class="h-8 px-2.5 rounded-lg bg-navy hover:bg-navy-600 text-white font-bold text-xs transition cursor-pointer" title="Simpan Perubahan Status">
                                            Simpan
                                        </button>
                                    </form>
                                </td>

                                <!-- Admin Actions: Print Slip & Delete Order -->
                                <td class="p-4 align-top text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2 font-label text-xs">
                                        <!-- Tombol Slip Pengiriman SirkulaCare -->
                                        <button type="button" 
                                                @click="printOrder = {
                                                    number: @js($order->order_number),
                                                    date: @js($order->created_at->format('d/m/Y H:i')),
                                                    status: @js(strtoupper($order->status)),
                                                    recipient: @js($order->shipping_name),
                                                    phone: @js($order->shipping_phone),
                                                    address: @js($order->shipping_address),
                                                    payment: @js(strtoupper($order->payment_method)),
                                                    total: @js('Rp ' . number_format($order->total_amount, 0, ',', '.')),
                                                    items: @js($order->items->map(fn($i) => ['name' => $i->product_name, 'variant' => $i->variant_info, 'qty' => $i->quantity]))
                                                }"
                                                class="h-8 px-2.5 rounded-lg border border-line bg-surface-low hover:bg-white text-navy font-semibold transition cursor-pointer inline-flex items-center gap-1.5"
                                                title="Cetak Label Slip Pengiriman SirkulaCare">
                                            <svg class="w-3.5 h-3.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            <span>Slip</span>
                                        </button>

                                        <!-- Hapus Pesanan -->
                                        <button type="button" 
                                                @click="openConfirm('Hapus Pesanan #' + @js($order->order_number), 'Pesanan ini akan dihapus permanen dan seluruh kuota stok pakaian dikembalikan ke katalog.', '{{ route('admin.orders.destroy', $order->id) }}', 'DELETE')" 
                                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus Pesanan Permanen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            <div class="p-4 bg-surface-low border-t border-line flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-label">
                <span class="text-muted">
                    Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} dari total {{ $orders->total() }} pesanan
                </span>
                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Cetak Slip Pengiriman SirkulaCare -->
    @include('admin.partials.print-slip')
</div>

@endcomponent
