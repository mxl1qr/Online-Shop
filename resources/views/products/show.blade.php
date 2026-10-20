<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — NUEVA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FA] text-gray-900 antialiased font-sans">

    <!-- Navbar Sederhana -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('products.index') }}" class="flex items-center space-x-2">
                    <span class="bg-red-600 text-white font-black text-lg px-2.5 py-1 tracking-wider uppercase rounded-sm">NUEVA</span>
                    <span class="font-bold text-gray-700 tracking-tight text-sm">LIFEWEAR</span>
                </a>

                <div class="flex items-center space-x-6 text-sm">
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                        &larr; Kembali ke Katalog
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
          x-data="{
              variants: {{ json_encode($product->variants) }},
              selectedColor: '{{ $product->variants->first()?->color ?? '' }}',
              selectedSize: '{{ $product->variants->first()?->size ?? '' }}',
              quantity: 1,

              // Mencari objek varian yang cocok dengan kombinasi warna & ukuran yang diklik
              get currentVariant() {
                  return this.variants.find(v => v.color === this.selectedColor && v.size === this.selectedSize);
              },
              get currentStock() {
                  return this.currentVariant ? this.currentVariant.stock : 0;
              },
              get currentPrice() {
                  const base = {{ $product->base_price }};
                  const add = this.currentVariant ? (this.currentVariant.additional_price || 0) : 0;
                  return base + add;
              },
              get colors() {
                  return [...new Set(this.variants.map(v => v.color))];
              },
              get sizes() {
                  return [...new Set(this.variants.filter(v => v.color === this.selectedColor).map(v => v.size))];
              }
          }">

        <!-- Breadcrumb -->
        <nav class="text-xs text-gray-500 mb-6 flex items-center space-x-2">
            <a href="{{ route('products.index') }}" class="hover:underline">Beranda</a>
            <span>/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:underline">{{ $product->category->name }}</a>
            <span>/</span>
            <span class="text-gray-900 font-medium truncate">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            
            <!-- Kolom Kiri: Foto Baju (Gaya Lookbook Vertikal 3:4) -->
            <div class="lg:col-span-7">
                <div class="w-full aspect-[3/4] bg-white border border-gray-200 rounded-sm overflow-hidden flex items-center justify-center sticky top-24">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-center p-8 text-gray-400">
                            <span class="text-6xl block mb-2">👕</span>
                            <span class="text-xs uppercase tracking-wider font-mono">Tampak Depan Pakaian</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kolom Kanan: Detail & Form Pembelian -->
            <div class="lg:col-span-5 flex flex-col justify-between">
                <div>
                    <!-- Kategori & Judul Baju -->
                    <span class="text-xs font-bold uppercase tracking-wider text-red-600 block mb-1">
                        {{ $product->category->name }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight leading-snug">
                        {{ $product->name }}
                    </h1>

                    <!-- Harga Dinamis -->
                    <div class="mt-4 pb-6 border-b border-gray-200">
                        <span class="text-2xl sm:text-3xl font-black text-gray-900" 
                              x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(currentPrice)">
                            Rp {{ number_format($product->base_price, 0, ',', '.') }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">Sudah termasuk pajak. Belum termasuk ongkos kirim.</p>
                    </div>

                    <!-- Pilihan Interaktif: Warna (Ala Uniqlo) -->
                    <div class="mt-6">
                        <div class="flex justify-between items-center text-sm font-semibold text-gray-900 mb-3">
                            <span>Warna: <span class="font-normal text-gray-600" x-text="selectedColor"></span></span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <template x-for="color in colors" :key="color">
                                <button type="button" 
                                        @click="selectedColor = color"
                                        :class="selectedColor === color ? 'ring-2 ring-gray-900 ring-offset-2' : 'hover:opacity-80'"
                                        class="px-4 py-2 text-xs font-semibold rounded-xs border border-gray-300 transition uppercase bg-white">
                                    <span x-text="color"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Pilihan Interaktif: Ukuran (Size) -->
                    <div class="mt-6">
                        <div class="flex justify-between items-center text-sm font-semibold text-gray-900 mb-3">
                            <span>Ukuran: <span class="font-normal text-gray-600" x-text="selectedSize"></span></span>
                            <span class="text-xs font-medium text-gray-500">
                                Sisa Stok: <strong class="text-gray-900" x-text="currentStock"></strong> pcs
                            </span>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="size in sizes" :key="size">
                                <button type="button" 
                                        @click="selectedSize = size"
                                        :class="selectedSize === size ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-800 border-gray-300 hover:border-gray-900'"
                                        class="py-3 text-xs font-bold rounded-xs border transition text-center uppercase">
                                    <span x-text="size"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Jumlah Pesanan (Qty) & Form Submit ke Keranjang -->
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-8">
                        @csrf
                        <!-- Input hidden membawa ID varian yang terpilih -->
                        <input type="hidden" name="variant_id" :value="currentVariant ? currentVariant.id : ''">

                        <div class="flex items-center space-x-3 mb-4">
                            <label class="text-sm font-medium text-gray-700">Jumlah:</label>
                            <div class="flex items-center border border-gray-300 rounded-xs bg-white">
                                <button type="button" 
                                        @click="if(quantity > 1) quantity--" 
                                        class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 font-bold">-</button>
                                <input type="number" 
                                       name="quantity" 
                                       x-model="quantity" 
                                       min="1" 
                                       :max="currentStock" 
                                       class="w-12 text-center border-none text-sm font-semibold focus:ring-0 p-0" readonly>
                                <button type="button" 
                                        @click="if(quantity < currentStock) quantity++" 
                                        class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 font-bold">+</button>
                            </div>
                        </div>

                        <!-- Tombol Tambah ke Keranjang -->
                        <button type="submit" 
                                :disabled="currentStock <= 0"
                                :class="currentStock > 0 ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                class="w-full py-4 text-sm font-bold uppercase tracking-wider rounded-xs transition shadow-sm">
                            <span x-show="currentStock > 0">+ Tambah ke Keranjang</span>
                            <span x-show="currentStock <= 0">Stok Habis</span>
                        </button>
                    </form>

                    <!-- Deskripsi & Info Produk -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Deskripsi Produk</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            {{ $product->description ?? 'Pakaian berkualitas dengan material pilihan, nyaman dipakai untuk aktivitas kasual harian.' }}
                        </p>
                        
                        <div class="mt-4 bg-gray-100 p-4 rounded-xs text-xs text-gray-600 space-y-1">
                            <p>&bull; 100% Katun Combed Premium</p>
                            <p>&bull; Jahitan rapi dan tahan lama</p>
                            <p>&bull; Petunjuk cuci: Cuci dengan air dingin, hindari pemutih</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

</body>
</html>