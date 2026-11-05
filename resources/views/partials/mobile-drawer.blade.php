{{-- Mobile & Tablet Slide-Over Drawer (Placed Outside Header to Prevent Backdrop-Filter Clipping) --}}
<div x-show="mobileMenuOpen"
     x-cloak
     x-transition.opacity
     class="lg:hidden fixed inset-0 z-[60] bg-black/50"
     @click="mobileMenuOpen = false">
</div>

<aside x-show="mobileMenuOpen"
       x-cloak
       @keydown.escape.window="mobileMenuOpen = false"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       role="dialog"
       aria-modal="true"
       aria-label="Menu Navigasi Mobile"
       class="lg:hidden fixed inset-y-0 left-0 z-[70] w-[85vw] max-w-sm bg-white rounded-r-2xl shadow-2xl flex flex-col justify-between overflow-y-auto p-5">

    <div class="space-y-5">
        <!-- Drawer Header: Sirkula Official Logo + Close Button -->
        <div class="flex items-center justify-between pb-3.5 border-b border-line">
            <a href="{{ route('products.index') }}" class="group">
                @include('partials.brand-logo', ['size' => 'sm'])
            </a>
            <button type="button"
                    @click="mobileMenuOpen = false"
                    class="w-9 h-9 rounded-lg bg-surface-low hover:bg-line flex items-center justify-center text-ink transition"
                    aria-label="Tutup Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Pill Search Bar di Mobile Drawer -->
        <form action="{{ route('products.index') }}" method="GET">
            <div class="relative flex items-center">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari kemeja linen, flannel, boxy tee..."
                       class="w-full h-10 rounded-full bg-surface-low border border-line-strong focus:border-navy focus:bg-white pl-4 pr-10 text-sm font-body focus:ring-2 focus:ring-emerald/20">
                <button type="submit" class="absolute right-2.5 w-7 h-7 flex items-center justify-center text-ink/80">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </div>
        </form>

        <!-- Highlight Editorial ala H&M -->
        <div class="space-y-1.5 pb-4 border-b border-line">
            <a href="{{ route('products.index') }}" class="flex items-center justify-between py-1.5 px-2.5 rounded-lg hover:bg-surface-low font-headline font-extrabold text-xs uppercase tracking-wider text-navy">
                <span>Semua Koleksi Pakaian</span>
                <span>&rarr;</span>
            </a>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="flex items-center justify-between py-1.5 px-2.5 rounded-lg hover:bg-surface-low font-headline font-extrabold text-xs uppercase tracking-wider text-ink">
                <span>Barang Baru (Drop #01)</span>
                <span>&rarr;</span>
            </a>
            <a href="{{ route('products.index', ['sort' => 'price_asc']) }}" class="flex items-center justify-between py-1.5 px-2.5 rounded-lg bg-emerald-soft/25 font-headline font-extrabold text-xs uppercase tracking-wider text-emerald">
                <span>Special Price Hemat 55%</span>
                <span>&rarr;</span>
            </a>
        </div>

        <!-- Grid Kategori Visual 2 Kolom ala Uniqlo (Gambar Asli Terverifikasi) -->
        <div>
            <span class="font-label text-sm font-bold text-muted block mb-2.5">
                Kategori Pakaian
            </span>
            <div class="grid grid-cols-2 gap-2.5">
                <a href="{{ route('products.index', ['category' => 'kaos-t-shirt']) }}" class="flex items-center gap-2.5 p-2 rounded-xl bg-surface border border-line hover:border-navy transition">
                    <img src="{{ asset('storage/products/tshirt-boxy-1.jpg') }}" alt="Kaos & T-Shirt" class="w-11 h-14 rounded-lg object-cover bg-canvas shrink-0">
                    <span class="font-headline font-bold text-sm text-ink leading-snug">Kaos &amp; T-Shirt</span>
                </a>
                <a href="{{ route('products.index', ['category' => 'kemeja']) }}" class="flex items-center gap-2.5 p-2 rounded-xl bg-surface border border-line hover:border-navy transition">
                    <img src="{{ asset('storage/products/linen-shirt-1.jpg') }}" alt="Kemeja Casual" class="w-11 h-14 rounded-lg object-cover bg-canvas shrink-0">
                    <span class="font-headline font-bold text-sm text-ink leading-snug">Kemeja Casual</span>
                </a>
                <a href="{{ route('products.index', ['category' => 'outerwear-jaket']) }}" class="flex items-center gap-2.5 p-2 rounded-xl bg-surface border border-line hover:border-navy transition">
                    <img src="{{ asset('storage/products/worker-jacket-1.jpg') }}" alt="Outerwear & Jaket" class="w-11 h-14 rounded-lg object-cover bg-canvas shrink-0">
                    <span class="font-headline font-bold text-sm text-ink leading-snug">Outerwear &amp; Jaket</span>
                </a>
                <a href="{{ route('products.index', ['category' => 'celana-bawahan']) }}" class="flex items-center gap-2.5 p-2 rounded-xl bg-surface border border-line hover:border-navy transition">
                    <img src="{{ asset('storage/products/chino-pants-1.jpg') }}" alt="Celana & Bawahan" class="w-11 h-14 rounded-lg object-cover bg-canvas shrink-0">
                    <span class="font-headline font-bold text-sm text-ink leading-snug">Celana &amp; Bawahan</span>
                </a>
            </div>
        </div>

        <!-- Filter Ukuran Cepat -->
        <div>
            <span class="font-label text-sm font-bold text-muted block mb-2">
                Cari Berdasarkan Ukuran
            </span>
            <div class="grid grid-cols-4 gap-2 font-label text-sm font-bold">
                @foreach(['S', 'M', 'L', 'XL'] as $sz)
                    <a href="{{ route('products.index', ['size' => $sz]) }}"
                       class="h-9 rounded-lg border border-line-strong hover:border-navy hover:bg-navy hover:text-white text-ink flex items-center justify-center transition">
                        {{ $sz }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Pintasan Member & Favorit -->
        <div class="pt-3 border-t border-line space-y-1.5 text-sm font-label">
            <a href="{{ route('member') }}" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-surface-low font-semibold text-navy">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Keanggotaan Sirkula (Member ID)</span>
            </a>
            <a href="{{ route('wishlist.index') }}" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-surface-low font-medium text-ink/80">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Daftar Favorit Saya (Wishlist)</span>
            </a>
            <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 py-2 px-2.5 rounded-lg hover:bg-surface-low font-medium text-ink/80">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Riwayat Pesanan Saya</span>
            </a>
        </div>
    </div>

    <!-- Drawer Footer: Auth Action -->
    <div class="pt-4 border-t border-line mt-4">
        @auth
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-sm font-label text-muted">Member Aktif</span>
                    <strong class="text-sm font-headline text-navy">{{ Auth::user()->name }}</strong>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-label uppercase font-bold hover:bg-red-100 transition">
                        Keluar
                    </button>
                </form>
            </div>
        @else
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('login') }}" class="py-2.5 rounded-lg bg-navy text-white text-center font-label text-sm font-bold">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="py-2.5 rounded-lg border border-navy text-navy text-center font-label text-sm font-bold">
                    Daftar
                </a>
            </div>
        @endauth
    </div>
</aside>
