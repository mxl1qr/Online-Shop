<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — Informasi Pengiriman</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FA] text-gray-900 antialiased font-sans">

    <!-- Header Simpel -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('products.index') }}" class="flex items-center space-x-2">
                    <span class="bg-red-600 text-white font-black text-lg px-2.5 py-1 tracking-wider uppercase rounded-xs">NUEVA</span>
                    <span class="font-bold text-gray-700 tracking-tight text-sm">CHECKOUT</span>
                </a>

                <a href="{{ route('cart.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                    &larr; Kembali ke Keranjang
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        
        <!-- Pesan Error Validasi / Stok -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xs">
                {{ session('error') }}
            </div>
        @endif

        <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-8">
            Pengiriman & Pembayaran
        </h1>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Kolom Kiri: Formulir Alamat & Pembayaran (8 Kolom) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- 1. Informasi Penerima -->
                    <div class="bg-white border border-gray-200 rounded-xs p-6">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-200 mb-4">
                            1. Data Penerima Paket
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap Penerima *</label>
                                <input type="text" name="shipping_name" value="{{ old('shipping_name', Auth::user()->name) }}" required
                                       class="w-full text-sm border-gray-300 rounded-xs focus:ring-gray-900 focus:border-gray-900">
                                @error('shipping_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">No. Handphone / WhatsApp *</label>
                                <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" placeholder="081234567890" required
                                       class="w-full text-sm border-gray-300 rounded-xs focus:ring-gray-900 focus:border-gray-900">
                                @error('shipping_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Lengkap Pengiriman *</label>
                                <textarea name="shipping_address" rows="3" placeholder="Jl. Mawar No. 12, RT 01/RW 02, Kelurahan, Kecamatan, Kota, Kode Pos" required
                                          class="w-full text-sm border-gray-300 rounded-xs focus:ring-gray-900 focus:border-gray-900">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan untuk Kurir (Opsional)</label>
                                <input type="text" name="shipping_notes" value="{{ old('shipping_notes') }}" placeholder="Contoh: Titipkan di pos satpam atau pagar hitam"
                                       class="w-full text-sm border-gray-300 rounded-xs focus:ring-gray-900 focus:border-gray-900">
                            </div>
                        </div>
                    </div>

                    <!-- 2. Pilihan Bank Transfer -->
                    <div class="bg-white border border-gray-200 rounded-xs p-6">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-200 mb-4">
                            2. Metode Pembayaran Transfer Bank
                        </h2>

                        <div class="space-y-3">
                            <label class="flex items-center justify-between p-3.5 border border-gray-200 rounded-xs hover:border-gray-400 cursor-pointer transition">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="bca" checked class="text-gray-900 focus:ring-gray-900">
                                    <span class="text-sm font-semibold text-gray-900">Transfer Bank BCA</span>
                                </div>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-xs font-mono font-bold">Manual Cek</span>
                            </label>

                            <label class="flex items-center justify-between p-3.5 border border-gray-200 rounded-xs hover:border-gray-400 cursor-pointer transition">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="mandiri" class="text-gray-900 focus:ring-gray-900">
                                    <span class="text-sm font-semibold text-gray-900">Transfer Bank Mandiri</span>
                                </div>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-xs font-mono font-bold">Manual Cek</span>
                            </label>

                            <label class="flex items-center justify-between p-3.5 border border-gray-200 rounded-xs hover:border-gray-400 cursor-pointer transition">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="bri" class="text-gray-900 focus:ring-gray-900">
                                    <span class="text-sm font-semibold text-gray-900">Transfer Bank BRI</span>
                                </div>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-xs font-mono font-bold">Manual Cek</span>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Kolom Kanan: Ringkasan Pesanan (4 Kolom) -->
                <div class="lg:col-span-4">
                    <div class="bg-white border border-gray-200 rounded-xs p-6 sticky top-24">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-4 border-b border-gray-200 mb-4">
                            Ringkasan Pesanan
                        </h2>

                        <div class="space-y-3 max-h-60 overflow-y-auto pr-1 pb-4 border-b border-gray-100">
                            @foreach($cart as $item)
                                <div class="flex justify-between items-center text-xs">
                                    <div class="truncate pr-2">
                                        <p class="font-bold text-gray-900 truncate">{{ $item['product_name'] }}</p>
                                        <p class="text-gray-500">{{ $item['color'] }} - {{ $item['size'] }} &times; {{ $item['quantity'] }}</p>
                                    </div>
                                    <span class="font-semibold text-gray-900 shrink-0">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="py-4 space-y-2 text-sm text-gray-600 border-b border-gray-100">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-green-700 font-medium">
                                <span>Biaya Pengiriman</span>
                                <span>GRATIS</span>
                            </div>
                        </div>

                        <div class="py-4 flex justify-between items-baseline">
                            <span class="text-sm font-bold text-gray-900">Total Pembayaran</span>
                            <span class="text-xl font-black text-gray-900">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        <button type="submit" 
                                class="w-full text-center bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider py-4 rounded-xs transition shadow-xs mt-2">
                            Pesan Sekarang &rarr;
                        </button>

                        <p class="text-[11px] text-gray-400 text-center mt-3">
                            Dengan mengklik pesan, Anda menyetujui pemotongan stok otomatis.
                        </p>
                    </div>
                </div>

            </div>
        </form>

    </main>

</body>
</html>