<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Saya — NUEVA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FA] text-gray-900 antialiased font-sans">

    <!-- Header / Navbar Sederhana -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('products.index') }}" class="flex items-center space-x-2">
                    <span class="bg-red-600 text-white font-black text-lg px-2.5 py-1 tracking-wider uppercase rounded-xs">NUEVA</span>
                    <span class="font-bold text-gray-700 tracking-tight text-sm">LIFEWEAR</span>
                </a>

                <div class="flex items-center space-x-6 text-sm">
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                        Katalog
                    </a>
                    <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                        Keranjang ({{ count(session('cart', [])) }})
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        
        <div class="flex items-baseline justify-between border-b border-gray-200 pb-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight">Pesanan Saya</h1>
                <p class="text-xs text-gray-500 mt-1">Pantau status transaksi dan pengiriman pakaian Anda.</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-bold text-red-600 hover:underline uppercase tracking-wider">
                + Belanja Lagi
            </a>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white border border-gray-200 rounded-xs p-12 text-center">
                <span class="text-5xl block mb-3">📦</span>
                <h2 class="text-base font-bold text-gray-900 mb-1">Belum Ada Riwayat Pesanan</h2>
                <p class="text-xs text-gray-500 mb-6">Anda belum pernah melakukan pemesanan pakaian di toko kami.</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-gray-900 hover:bg-black text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded-xs transition">
                    Mulai Belanja Sekarang
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white border border-gray-200 rounded-xs overflow-hidden">
                        
                        <!-- Header Kartu Pesanan -->
                        <div class="bg-gray-50 px-4 sm:px-6 py-3 border-b border-gray-200 flex flex-wrap items-center justify-between gap-2 text-xs">
                            <div class="flex items-center space-x-4">
                                <span class="font-bold text-gray-900 font-mono">{{ $order->order_number }}</span>
                                <span class="text-gray-400">&bull;</span>
                                <span class="text-gray-500">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>

                            <!-- Badge Status Pesanan -->
                            <div>
                                @if($order->status === 'pending')
                                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 font-bold uppercase text-[10px] tracking-wider rounded-xs">
                                        Menunggu Pembayaran
                                    </span>
                                @elseif($order->status === 'processing')
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-800 font-bold uppercase text-[10px] tracking-wider rounded-xs">
                                        Sedang Dikemas
                                    </span>
                                @elseif($order->status === 'completed')
                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 font-bold uppercase text-[10px] tracking-wider rounded-xs">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 font-bold uppercase text-[10px] tracking-wider rounded-xs">
                                        Dibatalkan
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Rincian Produk di Pesanan -->
                        <div class="p-4 sm:p-6 divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                <div class="py-3 first:pt-0 last:pb-0 flex justify-between items-center text-xs sm:text-sm">
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $item->product_name }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->variant_info }} &times; {{ $item->quantity }} pcs</p>
                                    </div>
                                    <span class="font-semibold text-gray-900">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Footer Kartu: Total & Tombol Invoice -->
                        <div class="bg-gray-50/70 px-4 sm:px-6 py-3.5 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-gray-500">Total Pembayaran:</span>
                                <strong class="text-sm sm:text-base font-black text-gray-900 ml-1">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </strong>
                            </div>

                            <a href="{{ route('orders.show', $order->order_number) }}" 
                               class="text-xs font-bold text-gray-900 bg-white border border-gray-300 hover:border-gray-900 px-4 py-2 rounded-xs transition">
                                Lihat Invoice &rarr;
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif

    </main>

</body>
</html>