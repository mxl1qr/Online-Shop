<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan — SIRKULA Admin Panel</title>
    
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

    <!-- Top Admin Bar -->
    <header class="bg-[#002547] text-white sticky top-0 z-50 border-b border-[#001830]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="w-8 h-8 bg-[#85f9bf] text-[#002547] flex items-center justify-center font-headline font-black text-sm">
                    S
                </span>
                <div class="flex flex-col">
                    <span class="font-headline font-bold text-sm tracking-wide text-white">SIRKULA MANAGEMENT</span>
                    <span class="font-label text-[9px] uppercase tracking-widest text-[#85f9bf] font-bold">Admin Backoffice</span>
                </div>
            </div>

            <div class="flex items-center space-x-6 font-label text-xs">
                <a href="{{ route('products.index') }}" target="_blank" class="text-white/80 hover:text-white transition flex items-center space-x-1">
                    <span>Lihat Toko Publik</span>
                    <span>&rarr;</span>
                </a>
                <span class="bg-white/10 px-2.5 py-1 text-white font-semibold">
                    {{ Auth::user()->name }}
                </span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        
        <!-- Notifikasi -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-[#85f9bf]/20 border border-[#85f9bf] text-[#006c47] font-label text-xs font-bold flex items-center justify-between">
                <span>✓ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-[#006c47] hover:opacity-70 font-bold">&times;</button>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="font-headline text-2xl font-extrabold text-[#002547] tracking-tight">
                    Kelola Pesanan Pakaian Masuk
                </h1>
                <p class="font-body text-xs text-[#73777f] mt-0.5">
                    Verifikasi bukti transfer bank pembeli, atur proses sanitasi SirkulaCare, dan perbarui status pengiriman.
                </p>
            </div>
        </div>

        <!-- Filter Tab Status -->
        <div class="flex flex-wrap gap-2 mb-6 font-label text-xs font-bold uppercase tracking-wider">
            <a href="{{ route('admin.orders.index') }}" 
               class="px-3.5 py-2 border transition {{ !request('status') ? 'bg-[#002547] text-white border-[#002547]' : 'bg-white text-[#73777f] border-[#e0e3e2] hover:border-[#002547]' }}">
                Semua ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
               class="px-3.5 py-2 border transition {{ request('status') == 'pending' ? 'bg-[#002547] text-white border-[#002547]' : 'bg-white text-[#73777f] border-[#e0e3e2] hover:border-[#002547]' }}">
                Menunggu Bayar ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
               class="px-3.5 py-2 border transition {{ request('status') == 'processing' ? 'bg-[#002547] text-white border-[#002547]' : 'bg-white text-[#73777f] border-[#e0e3e2] hover:border-[#002547]' }}">
                Disanitasi &amp; Dikemas ({{ $counts['processing'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" 
               class="px-3.5 py-2 border transition {{ request('status') == 'completed' ? 'bg-[#002547] text-white border-[#002547]' : 'bg-white text-[#73777f] border-[#e0e3e2] hover:border-[#002547]' }}">
                Selesai ({{ $counts['completed'] }})
            </a>
        </div>

        <!-- Tabel Pesanan -->
        <div class="bg-white border border-[#e0e3e2] overflow-hidden">
            @if($orders->isEmpty())
                <div class="p-16 text-center text-[#73777f] font-body text-xs">
                    Tidak ada transaksi pada filter status ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs divide-y divide-[#e0e3e2]">
                        <thead class="bg-[#f1f4f3] text-[#002547] font-label font-bold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="p-4">No. Pesanan &amp; Waktu</th>
                                <th class="p-4">Penerima &amp; Alamat</th>
                                <th class="p-4">Item Pakaian</th>
                                <th class="p-4">Total &amp; Bank</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Ubah Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e3e2] font-body">
                            @foreach($orders as $order)
                                <tr class="hover:bg-[#f7faf9] transition">
                                    
                                    <!-- No. Pesanan & Waktu -->
                                    <td class="p-4 align-top">
                                        <strong class="font-label font-bold text-[#002547] block text-xs tracking-wider">{{ $order->order_number }}</strong>
                                        <span class="font-label text-[#73777f] text-[10px]">{{ $order->created_at->format('d/m/Y H:i') }} WIB</span>
                                    </td>

                                    <!-- Penerima -->
                                    <td class="p-4 align-top">
                                        <strong class="text-[#181c1c] block font-headline font-semibold text-xs">{{ $order->shipping_name }}</strong>
                                        <span class="text-[#73777f] font-label text-[11px] block">{{ $order->shipping_phone }}</span>
                                        <p class="text-[11px] text-[#43474e] mt-1 max-w-xs line-clamp-2" title="{{ $order->shipping_address }}">
                                            {{ $order->shipping_address }}
                                        </p>
                                    </td>

                                    <!-- Item Baju -->
                                    <td class="p-4 align-top">
                                        <ul class="space-y-1">
                                            @foreach($order->items as $item)
                                                <li>
                                                    <span class="font-semibold text-[#181c1c]">{{ $item->product_name }}</span>
                                                    <span class="text-[#73777f] font-label text-[10px]">({{ $item->variant_info }} &times; {{ $item->quantity }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>

                                    <!-- Total & Bank -->
                                    <td class="p-4 align-top">
                                        <strong class="font-label text-sm font-bold text-[#002547] block">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </strong>
                                        <span class="font-label uppercase text-[10px] bg-[#f1f4f3] text-[#002547] font-bold px-1.5 py-0.5 border border-[#e0e3e2]">
                                            Transfer {{ $order->payment_method }}
                                        </span>
                                    </td>

                                    <!-- Status Saat Ini -->
                                    <td class="p-4 align-top font-label">
                                        @if($order->status === 'pending')
                                            <span class="px-2 py-0.5 bg-yellow-50 text-yellow-800 border border-yellow-200 font-bold uppercase text-[9px] tracking-wider inline-block">
                                                Menunggu Bayar
                                            </span>
                                        @elseif($order->status === 'processing')
                                            <span class="px-2 py-0.5 bg-[#85f9bf]/40 text-[#006c47] border border-[#85f9bf] font-bold uppercase text-[9px] tracking-wider inline-block">
                                                Disanitasi &amp; Kemas
                                            </span>
                                        @elseif($order->status === 'completed')
                                            <span class="px-2 py-0.5 bg-[#006c47] text-white font-bold uppercase text-[9px] tracking-wider inline-block">
                                                Selesai ✓
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-red-50 text-red-700 border border-red-200 font-bold uppercase text-[9px] tracking-wider inline-block">
                                                Batal
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Form Update Status Cepat -->
                                    <td class="p-4 align-top text-right">
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline-flex items-center space-x-1 font-label">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="text-xs bg-[#f1f4f3] border border-[#e0e3e2] py-1 px-2 focus:ring-1 focus:ring-[#002547]">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Proses / Sanitasi</option>
                                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai Kirim</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Batalkan</option>
                                            </select>
                                            <button type="submit" class="bg-[#002547] hover:bg-[#000f23] text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1.5 transition">
                                                Update
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-[#e0e3e2]">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </main>

</body>
</html>
