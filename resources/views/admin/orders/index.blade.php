<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan — Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 antialiased font-sans">

    <!-- Top Admin Bar -->
    <header class="bg-gray-900 text-white sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="bg-red-600 text-white text-xs font-black px-2 py-1 uppercase rounded-xs">ADMIN</span>
                <span class="font-bold text-sm tracking-wide">NUEVA STORE MANAGEMENT</span>
            </div>

            <div class="flex items-center space-x-6 text-xs font-medium">
                <a href="{{ route('products.index') }}" target="_blank" class="text-gray-300 hover:text-white">
                    Lihat Toko Publik &rarr;
                </a>
                <span class="text-gray-400">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        
        <!-- Notifikasi -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 text-xs font-semibold rounded-xs">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Kelola Pesanan Masuk</h1>
                <p class="text-xs text-gray-500 mt-1">Verifikasi pembayaran transfer bank dan ubah status pengiriman pakaian.</p>
            </div>
        </div>

        <!-- Filter Tab Status -->
        <div class="flex flex-wrap gap-2 mb-6 text-xs font-semibold">
            <a href="{{ route('admin.orders.index') }}" 
               class="px-4 py-2 rounded-xs border transition {{ !request('status') ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                Semua ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-xs border transition {{ request('status') == 'pending' ? 'bg-yellow-500 text-white border-yellow-500' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                Menunggu Pembayaran ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
               class="px-4 py-2 rounded-xs border transition {{ request('status') == 'processing' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                Sedang Dikemas ({{ $counts['processing'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" 
               class="px-4 py-2 rounded-xs border transition {{ request('status') == 'completed' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                Selesai ({{ $counts['completed'] }})
            </a>
        </div>

        <!-- Tabel Pesanan -->
        <div class="bg-white border border-gray-200 rounded-xs overflow-hidden shadow-xs">
            @if($orders->isEmpty())
                <div class="p-12 text-center text-gray-500 text-sm">
                    Tidak ada pesanan pada status ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="p-4">No. Pesanan & Waktu</th>
                                <th class="p-4">Pembeli & Penerima</th>
                                <th class="p-4">Item Pakaian</th>
                                <th class="p-4">Total & Bank</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Ubah Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <!-- No. Pesanan -->
                                    <td class="p-4 align-top">
                                        <strong class="font-mono text-gray-900 block">{{ $order->order_number }}</strong>
                                        <span class="text-gray-400 text-[11px]">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                    </td>

                                    <!-- Pembeli -->
                                    <td class="p-4 align-top">
                                        <strong class="text-gray-900 block">{{ $order->shipping_name }}</strong>
                                        <span class="text-gray-500 block">{{ $order->shipping_phone }}</span>
                                        <p class="text-[11px] text-gray-400 mt-1 max-w-xs line-clamp-2" title="{{ $order->shipping_address }}">
                                            {{ $order->shipping_address }}
                                        </p>
                                    </td>

                                    <!-- Item Baju -->
                                    <td class="p-4 align-top">
                                        <ul class="space-y-1">
                                            @foreach($order->items as $item)
                                                <li>
                                                    <span class="font-medium text-gray-900">{{ $item->product_name }}</span>
                                                    <span class="text-gray-400 text-[11px]">({{ $item->variant_info }} &times; {{ $item->quantity }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>

                                    <!-- Total & Bank -->
                                    <td class="p-4 align-top">
                                        <strong class="text-gray-900 text-sm block">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                        <span class="uppercase text-[10px] font-mono bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-xs">
                                            {{ $order->payment_method }}
                                        </span>
                                    </td>

                                    <!-- Status Saat Ini -->
                                    <td class="p-4 align-top">
                                        @if($order->status === 'pending')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 font-bold uppercase text-[10px] tracking-wider rounded-xs inline-block">
                                                Menunggu Bayar
                                            </span>
                                        @elseif($order->status === 'processing')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 font-bold uppercase text-[10px] tracking-wider rounded-xs inline-block">
                                                Dikemas
                                            </span>
                                        @elseif($order->status === 'completed')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 font-bold uppercase text-[10px] tracking-wider rounded-xs inline-block">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 font-bold uppercase text-[10px] tracking-wider rounded-xs inline-block">
                                                Batal
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Form Update Status Cepat -->
                                    <td class="p-4 align-top text-right">
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline-flex items-center space-x-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="text-xs border-gray-300 rounded-xs py-1 px-2 focus:ring-gray-900 focus:border-gray-900">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Proses / Kemas</option>
                                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Batalkan</option>
                                            </select>
                                            <button type="submit" class="bg-gray-900 hover:bg-black text-white text-[11px] font-bold px-2.5 py-1.5 rounded-xs transition">
                                                Ubah
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </main>

</body>
</html>