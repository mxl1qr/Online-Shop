<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Diterima — {{ $order->order_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FA] text-gray-900 antialiased font-sans">

    <!-- Header Simpel -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('products.index') }}" class="flex items-center space-x-2">
                <span class="bg-red-600 text-white font-black text-lg px-2.5 py-1 tracking-wider uppercase rounded-xs">NUEVA</span>
                <span class="font-bold text-gray-700 tracking-tight text-sm">LIFEWEAR</span>
            </a>
            <a href="{{ route('products.index') }}" class="text-xs font-bold uppercase tracking-wider text-gray-700 hover:text-black">
                Kembali ke Toko
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-10 sm:py-16">
        
        <div class="bg-white border border-gray-200 rounded-xs p-6 sm:p-10 shadow-xs">
            
            <!-- Icon & Status Berhasil -->
            <div class="text-center pb-8 border-b border-gray-100">
                <div class="w-14 h-14 bg-green-100 text-green-700 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl font-bold">
                    ✓
                </div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Pesanan Berhasil Dibuat!</h1>
                <p class="text-sm text-gray-500 mt-1">Nomor Pesanan: <strong class="text-gray-900 font-mono">{{ $order->order_number }}</strong></p>
                <span class="inline-block mt-3 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold uppercase tracking-wider rounded-full">
                    Status: Menunggu Pembayaran
                </span>
            </div>

            <!-- Petunjuk Rekening Transfer Bank -->
            <div class="my-8 p-6 bg-gray-50 border border-gray-200 rounded-xs">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4">
                    Instruksi Pembayaran Transfer Bank
                </h2>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Bank Tujuan:</span>
                        <strong class="text-gray-900 uppercase font-mono">{{ strtoupper($order->payment_method) }}</strong>
                    </div>

                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Nomor Rekening:</span>
                        <strong class="text-base text-gray-900 font-mono bg-white px-3 py-1 border border-gray-300 rounded-xs">
                            123-456-7890
                        </strong>
                    </div>

                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Atas Nama:</span>
                        <strong class="text-gray-900">PT NUEVA WEAR INDONESIA</strong>
                    </div>

                    <div class="flex justify-between items-center pt-1">
                        <span class="text-gray-900 font-bold">Total yang Harus Ditransfer:</span>
                        <strong class="text-xl text-red-600 font-black">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-4 leading-relaxed">
                    *Harap lakukan transfer sesuai nominal di atas dalam 1x24 jam agar pesanan tidak dibatalkan otomatis dan stok tidak kembali dilepas ke publik.
                </p>
            </div>

            <!-- Rincian Barang yang Dipesan -->
            <div class="mb-8">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-900 mb-4">Rincian Pakaian yang Dipesan</h2>
                <div class="border border-gray-200 rounded-xs divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="p-4 flex justify-between items-center text-sm">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $item->product_name }}</h3>
                                <p class="text-xs text-gray-500">{{ $item->variant_info }} &times; {{ $item->quantity }} pcs</p>
                            </div>
                            <span class="font-semibold text-gray-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Alamat Pengiriman -->
            <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded-xs text-xs text-gray-700">
                <h3 class="font-bold text-gray-900 mb-1">Tujuan Pengiriman:</h3>
                <p><strong>{{ $order->shipping_name }}</strong> ({{ $order->shipping_phone }})</p>
                <p class="mt-1 text-gray-600">{{ $order->shipping_address }}</p>
                @if($order->shipping_notes)
                    <p class="mt-1 text-gray-400 italic">Catatan: {{ $order->shipping_notes }}</p>
                @endif
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('products.index') }}" 
                   class="bg-gray-900 hover:bg-black text-white text-xs font-bold uppercase tracking-wider px-8 py-3.5 text-center rounded-xs transition">
                    Belanja Lagi
                </a>
            </div>

        </div>

    </main>

</body>
</html>