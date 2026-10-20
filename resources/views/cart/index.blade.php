<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja — NUEVA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FA] text-gray-900 antialiased font-sans">

    <!-- Header Sederhana -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('products.index') }}" class="flex items-center space-x-2">
                    <span class="bg-red-600 text-white font-black text-lg px-2.5 py-1 tracking-wider uppercase rounded-xs">NUEVA</span>
                    <span class="font-bold text-gray-700 tracking-tight text-sm">LIFEWEAR</span>
                </a>

                <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                    &larr; Lanjut Belanja
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        
        <!-- Notifikasi Pesan Sukses / Error -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xs flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-900 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xs flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900 font-bold">&times;</button>
            </div>
        @endif

        <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-8">
            Keranjang Belanja 
            @if(count($cart) > 0)
                <span class="text-base font-normal text-gray-500">({{ count($cart) }} Item)</span>
            @endif
        </h1>

        @if(empty($cart))
            <!-- Kondisi Keranjang Kosong -->
            <div class="bg-white border border-gray-200 rounded-xs p-12 text-center max-w-lg mx-auto">
                <span class="text-5xl block mb-4">🛒</span>
                <h2 class="text-lg font-bold text-gray-900 mb-2">Keranjang Belanja Anda Masih Kosong</h2>
                <p class="text-sm text-gray-500 mb-6">Pilih pakaian esensial favorit Anda dari katalog untuk mulai berbelanja.</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-gray-900 hover:bg-black text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded-xs transition">
                    Mulai Belanja Pakaian
                </a>
            </div>
        @else
            <!-- Grid Keranjang Belanja -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Kolom Kiri: Daftar Barang (8 Col) -->
                <div class="lg:col-span-8 space-y-4">
                    @foreach($cart as $variantId => $item)
                        <div class="bg-white border border-gray-200 rounded-xs p-4 sm:p-5 flex flex-col sm:flex-row gap-4 sm:gap-6 items-start sm:items-center justify-between">
                            
                            <!-- Foto & Rincian Produk -->
                            <div class="flex items-center space-x-4">
                                <div class="w-20 h-24 bg-gray-100 rounded-xs overflow-hidden shrink-0 flex items-center justify-center border border-gray-200">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-2xl">👕</span>
                                    @endif
                                </div>

                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">{{ $item['category'] }}</span>
                                    <h3 class="text-sm font-bold text-gray-900 leading-snug">
                                        <a href="{{ route('products.show', $item['slug']) }}" class="hover:underline">
                                            {{ $item['product_name'] }}
                                        </a>
                                    </h3>
                                    
                                    <!-- Info Varian Ukuran & Warna -->
                                    <div class="mt-1 flex items-center space-x-2 text-xs text-gray-600">
                                        <span>Warna: <strong class="text-gray-900">{{ $item['color'] }}</strong></span>
                                        <span>&bull;</span>
                                        <span>Ukuran: <strong class="text-gray-900">{{ $item['size'] }}</strong></span>
                                    </div>

                                    <!-- Harga Satuan -->
                                    <p class="text-sm font-semibold text-gray-900 mt-2">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Kontrol Jumlah & Tombol Hapus -->
                            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                                
                                <!-- Form Update Qty (+ / -) -->
                                <form action="{{ route('cart.update', $variantId) }}" method="POST" class="flex items-center border border-gray-300 rounded-xs bg-white">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" 
                                            class="px-2.5 py-1 text-gray-600 hover:bg-gray-100 font-bold text-sm"
                                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                                    
                                    <span class="w-10 text-center text-xs font-bold text-gray-900">{{ $item['quantity'] }}</span>

                                    <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" 
                                            class="px-2.5 py-1 text-gray-600 hover:bg-gray-100 font-bold text-sm"
                                            {{ $item['quantity'] >= $item['max_stock'] ? 'disabled' : '' }}>+</button>
                                </form>

                                <!-- Subtotal per Item -->
                                <div class="text-right min-w-[100px]">
                                    <span class="text-sm font-black text-gray-900">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </span>
                                </div>

                                <!-- Tombol Hapus Item -->
                                <form action="{{ route('cart.remove', $variantId) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus dari keranjang" class="text-gray-400 hover:text-red-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>

                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Kolom Kanan: Ringkasan Pesanan (4 Col) -->
                <div class="lg:col-span-4">
                    <div class="bg-white border border-gray-200 rounded-xs p-6 sticky top-24">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-4 border-b border-gray-200">
                            Ringkasan Belanja
                        </h2>

                        <div class="py-4 space-y-3 text-sm text-gray-600 border-b border-gray-100">
                            <div class="flex justify-between">
                                <span>Total Harga Barang</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Estimasi Ongkir</span>
                                <span class="text-xs text-green-700 font-medium bg-green-50 px-2 py-0.5 rounded-xs">Dihitung saat checkout</span>
                            </div>
                        </div>

                        <div class="py-4 flex justify-between items-baseline">
                            <span class="text-base font-bold text-gray-900">Total Tagihan</span>
                            <span class="text-xl font-black text-gray-900">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Tombol Menuju Checkout -->
                        <a href="{{ route('checkout.index') }}" 
                           class="w-full block text-center bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider py-4 rounded-xs transition shadow-xs mt-2">
                            Lanjut ke Checkout &rarr;
                        </a>

                        <div class="mt-4 text-[11px] text-gray-400 text-center space-y-1">
                            <p>🔒 Transaksi aman & terenkripsi</p>
                            <p>Stok produk akan dikunci setelah checkout selesai.</p>
                        </div>
                    </div>
                </div>

            </div>
        @endif

    </main>

</body>
</html>