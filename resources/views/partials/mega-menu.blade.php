{{-- Compact Uniqlo x H&M Desktop Hover Mega Menu (Single-Row, No Redundant Search Bar, Soft Rounded Corners) --}}
@php
    $megaCatalog = [
        'all' => [
            'title' => 'Semua Koleksi Pakaian',
            'subtitle' => 'Archive Recommerce Drop #01 — Kurasi Tangan Pertama',
            'url' => route('products.index'),
            'featured_image' => asset('storage/banners/hero-drop01.jpg'),
            'featured_label' => 'DROP #01: ESSENTIAL ARCHIVE',
            'featured_sub' => 'Sanitasi Medis Uap 100°C & Ozon',
            'highlights' => [
                ['label' => 'BARANG BARU (DROP #01)', 'url' => route('products.index', ['sort' => 'newest']), 'accent' => false],
                ['label' => 'THE OUTERWEAR EDIT', 'url' => route('products.index', ['category' => 'outerwear-jaket']), 'accent' => false],
                ['label' => 'ESSENTIAL WORKWEAR', 'url' => route('products.index', ['category' => 'kemeja']), 'accent' => false],
                ['label' => 'DAILY BOXY TEES', 'url' => route('products.index', ['category' => 'kaos-t-shirt']), 'accent' => false],
                ['label' => 'SPECIAL PRICE -55%', 'url' => route('products.index', ['sort' => 'price_asc']), 'accent' => true],
            ],
            'items' => [
                ['name' => 'Kaos & T-Shirt', 'sub' => 'Mulai Rp 119.000', 'img' => asset('storage/products/tshirt-boxy-1.jpg'), 'url' => route('products.index', ['category' => 'kaos-t-shirt'])],
                ['name' => 'Kemeja Casual', 'sub' => 'Mulai Rp 179.000', 'img' => asset('storage/products/linen-shirt-1.jpg'), 'url' => route('products.index', ['category' => 'kemeja'])],
                ['name' => 'Outerwear & Jaket', 'sub' => 'Mulai Rp 229.000', 'img' => asset('storage/products/worker-jacket-1.jpg'), 'url' => route('products.index', ['category' => 'outerwear-jaket'])],
                ['name' => 'Celana & Bawahan', 'sub' => 'Mulai Rp 199.000', 'img' => asset('storage/products/chino-pants-1.jpg'), 'url' => route('products.index', ['category' => 'celana-bawahan'])],
            ],
        ],
        'kaos-t-shirt' => [
            'title' => 'Kaos & T-Shirt',
            'subtitle' => 'Siluet Boxy & Oversized Katun Combed Berat',
            'url' => route('products.index', ['category' => 'kaos-t-shirt']),
            'featured_image' => asset('storage/banners/cat-kaos.jpg'),
            'featured_label' => 'THE HEAVYWEIGHT TEE EDIT',
            'featured_sub' => 'Katun 240 GSM • Grade A+ Like New',
            'highlights' => [
                ['label' => 'SEMUA KAOS & T-SHIRT', 'url' => route('products.index', ['category' => 'kaos-t-shirt']), 'accent' => false],
                ['label' => 'HEAVYWEIGHT BOXY FIT', 'url' => route('products.show', 'heavyweight-boxy-t-shirt'), 'accent' => false],
                ['label' => 'VINTAGE WASHED TEE', 'url' => route('products.show', 'vintage-washed-oversized-tee'), 'accent' => false],
                ['label' => 'MULAI RP 119.000 (-55%)', 'url' => route('products.index', ['category' => 'kaos-t-shirt', 'sort' => 'price_asc']), 'accent' => true],
            ],
            'items' => [
                ['name' => 'Heavyweight Boxy Tee', 'sub' => 'Rp 129.000', 'img' => asset('storage/products/tshirt-boxy-1.jpg'), 'url' => route('products.show', 'heavyweight-boxy-t-shirt')],
                ['name' => 'Detail Serat Boxy', 'sub' => '240 GSM Combed', 'img' => asset('storage/products/tshirt-boxy-2.jpg'), 'url' => route('products.show', 'heavyweight-boxy-t-shirt')],
                ['name' => 'Vintage Washed Tee', 'sub' => 'Rp 119.000', 'img' => asset('storage/products/crewneck-1.jpg'), 'url' => route('products.show', 'vintage-washed-oversized-tee')],
                ['name' => 'Detail Washed Finish', 'sub' => 'Drop Shoulder', 'img' => asset('storage/products/crewneck-2.jpg'), 'url' => route('products.show', 'vintage-washed-oversized-tee')],
            ],
        ],
        'kemeja' => [
            'title' => 'Kemeja Casual',
            'subtitle' => 'Kemeja Kerah Terbuka Linen & Flannel Lengan Panjang',
            'url' => route('products.index', ['category' => 'kemeja']),
            'featured_image' => asset('storage/banners/cat-kemeja.jpg'),
            'featured_label' => 'LINEN & FLANNEL ARCHIVE',
            'featured_sub' => 'Adem, Rapi & Siap Langsung Dipakai',
            'highlights' => [
                ['label' => 'SEMUA KEMEJA CASUAL', 'url' => route('products.index', ['category' => 'kemeja']), 'accent' => false],
                ['label' => 'LINEN CAMP COLLAR', 'url' => route('products.show', 'linen-blend-camp-collar-shirt'), 'accent' => false],
                ['label' => 'FLANNEL LONG SLEEVE', 'url' => route('products.show', 'flannel-casual-long-sleeve-shirt'), 'accent' => false],
                ['label' => 'HEMAT 55% DARI RETAIL', 'url' => route('products.index', ['category' => 'kemeja', 'sort' => 'price_asc']), 'accent' => true],
            ],
            'items' => [
                ['name' => 'Linen Camp Collar', 'sub' => 'Rp 179.000', 'img' => asset('storage/products/linen-shirt-1.jpg'), 'url' => route('products.show', 'linen-blend-camp-collar-shirt')],
                ['name' => 'Tekstur Katun Linen', 'sub' => 'Relaxed Resort Fit', 'img' => asset('storage/products/linen-shirt-2.jpg'), 'url' => route('products.show', 'linen-blend-camp-collar-shirt')],
                ['name' => 'Flannel Long Sleeve', 'sub' => 'Rp 189.000', 'img' => asset('storage/products/flannel-1.jpg'), 'url' => route('products.show', 'flannel-casual-long-sleeve-shirt')],
                ['name' => 'Motif Kotak Tartan', 'sub' => 'Brushed Twill', 'img' => asset('storage/products/flannel-2.jpg'), 'url' => route('products.show', 'flannel-casual-long-sleeve-shirt')],
            ],
        ],
        'outerwear-jaket' => [
            'title' => 'Outerwear & Jaket',
            'subtitle' => 'Chore Coat Kanvas Tebal & Hoodie Fleece Minimalis',
            'url' => route('products.index', ['category' => 'outerwear-jaket']),
            'featured_image' => asset('storage/banners/cat-outerwear.jpg'),
            'featured_label' => 'THE OUTERWEAR EDIT',
            'featured_sub' => 'Konstruksi Tangguh • Sanitasi Uap Medis',
            'highlights' => [
                ['label' => 'SEMUA OUTERWEAR', 'url' => route('products.index', ['category' => 'outerwear-jaket']), 'accent' => false],
                ['label' => 'CANVAS WORKWEAR JACKET', 'url' => route('products.show', 'heavy-cotton-canvas-workwear-jacket'), 'accent' => false],
                ['label' => 'ZIP-UP FLEECE HOODIE', 'url' => route('products.show', 'minimalist-zip-up-fleece-hoodie'), 'accent' => false],
                ['label' => 'KOLEKSI TERBATAS (1-OF-1)', 'url' => route('products.index', ['category' => 'outerwear-jaket']), 'accent' => true],
            ],
            'items' => [
                ['name' => 'Canvas Workwear Jacket', 'sub' => 'Rp 279.000', 'img' => asset('storage/products/worker-jacket-1.jpg'), 'url' => route('products.show', 'heavy-cotton-canvas-workwear-jacket')],
                ['name' => 'Detail Kanvas 12oz', 'sub' => 'Jahitan Triple-Stitch', 'img' => asset('storage/products/worker-jacket-2.jpg'), 'url' => route('products.show', 'heavy-cotton-canvas-workwear-jacket')],
                ['name' => 'Zip-Up Fleece Hoodie', 'sub' => 'Rp 229.000', 'img' => asset('storage/products/denim-jacket-1.jpg'), 'url' => route('products.show', 'minimalist-zip-up-fleece-hoodie')],
                ['name' => 'Interior Brushed Fleece', 'sub' => '320 GSM Heavy', 'img' => asset('storage/products/denim-jacket-2.jpg'), 'url' => route('products.show', 'minimalist-zip-up-fleece-hoodie')],
            ],
        ],
        'celana-bawahan' => [
            'title' => 'Celana & Bawahan',
            'subtitle' => 'Celana Panjang Chino Lipit Lebar & Raw Denim 13.5oz',
            'url' => route('products.index', ['category' => 'celana-bawahan']),
            'featured_image' => asset('storage/banners/cat-celana.jpg'),
            'featured_label' => 'WIDE CHINO & SELVEDGE',
            'featured_sub' => 'Potongan Siluet Modern & Nyaman',
            'highlights' => [
                ['label' => 'SEMUA CELANA & BAWAHAN', 'url' => route('products.index', ['category' => 'celana-bawahan']), 'accent' => false],
                ['label' => 'PLEATED WIDE TROUSERS', 'url' => route('products.show', 'pleated-wide-leg-trousers'), 'accent' => false],
                ['label' => 'RELAXED RAW DENIM', 'url' => route('products.show', 'relaxed-fit-raw-denim-jeans'), 'accent' => false],
                ['label' => 'GARANSI TUKAR UKURAN', 'url' => route('products.index', ['category' => 'celana-bawahan']), 'accent' => true],
            ],
            'items' => [
                ['name' => 'Pleated Wide Trousers', 'sub' => 'Rp 199.000', 'img' => asset('storage/products/chino-pants-1.jpg'), 'url' => route('products.show', 'pleated-wide-leg-trousers')],
                ['name' => 'Detail Lipit Pinggang', 'sub' => 'Cotton Twill Halus', 'img' => asset('storage/products/chino-pants-2.jpg'), 'url' => route('products.show', 'pleated-wide-leg-trousers')],
                ['name' => 'Relaxed Raw Denim', 'sub' => 'Rp 249.000', 'img' => asset('storage/products/ankle-pants-1.jpg'), 'url' => route('products.show', 'relaxed-fit-raw-denim-jeans')],
                ['name' => 'Tekstur Denim 13.5oz', 'sub' => 'Straight Leg Cut', 'img' => asset('storage/products/ankle-pants-2.jpg'), 'url' => route('products.show', 'relaxed-fit-raw-denim-jeans')],
            ],
        ],
    ];
@endphp

<!-- Backdrop Area Kosong di Bawah Mega Menu (Gerakkan Kursor ke Bawah Langsung Menutup Menu) -->
<div x-show="activeMegaMenu !== null"
     x-cloak
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @mouseenter="closeMega()"
     @click="activeMegaMenu = null"
     class="hidden lg:block fixed inset-x-0 top-24 bottom-0 z-30 bg-black/25">
</div>

<!-- Compact Hover Mega Menu Panel (Tinggi Ringkas ~270px, Menyisakan >60% Ruang Layar Bawah) -->
<div x-show="activeMegaMenu !== null"
     x-cloak
     @mouseenter="clearTimeout(megaMenuTimer)"
     @mouseleave="closeMega()"
     @keydown.escape.window="activeMegaMenu = null"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 -translate-y-1"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-1"
     class="hidden lg:block absolute left-0 right-0 top-full z-50 bg-white border-t border-b border-line rounded-b-2xl shadow-xl">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        @foreach($megaCatalog as $slug => $panel)
            <div x-show="activeMegaMenu === '{{ $slug }}'" class="grid grid-cols-12 gap-6 items-start">

                <!-- KOLOM KIRI (3 Kolom): Link Editorial Bold + Filter Ukuran Cepat (Ala H&M) -->
                <div class="col-span-3 border-r border-line pr-5 space-y-4">
                    <ul class="space-y-2">
                        @foreach($panel['highlights'] as $hl)
                            <li>
                                <a href="{{ $hl['url'] }}"
                                   class="block font-headline text-xs font-extrabold uppercase tracking-wider transition hover:translate-x-0.5 {{ $hl['accent'] ? 'text-emerald' : 'text-ink hover:text-navy' }}">
                                    {{ $hl['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Filter Ukuran Cepat -->
                    <div class="pt-3 border-t border-line">
                        <span class="font-label text-sm font-bold text-muted block mb-2">
                            Filter Ukuran Cepat
                        </span>
                        <div class="grid grid-cols-4 gap-1.5 font-label text-sm font-bold">
                            @foreach(['S', 'M', 'L', 'XL'] as $sz)
                                <a href="{{ route('products.index', array_filter(['category' => $slug !== 'all' ? $slug : null, 'size' => $sz])) }}"
                                   class="h-8 rounded-lg border border-line-strong hover:border-navy hover:bg-navy hover:text-white text-ink flex items-center justify-center transition">
                                    {{ $sz }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- KOLOM TENGAH (6 Kolom): 1 Baris Grid Visual Thumbnail 4 Kartu (Ala Uniqlo) -->
                <div class="col-span-6 px-1">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="font-headline text-sm font-extrabold text-navy">
                                {{ $panel['title'] }}
                            </h3>
                            <p class="font-body text-sm text-muted">{{ $panel['subtitle'] }}</p>
                        </div>
                        <a href="{{ $panel['url'] }}" class="font-label text-sm font-bold text-navy hover:underline whitespace-nowrap">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-4 gap-3.5">
                        @foreach($panel['items'] as $item)
                            <a href="{{ $item['url'] }}" class="group flex flex-col items-center text-center">
                                <div class="w-full h-36 bg-canvas rounded-xl overflow-hidden mb-2 border border-line/70">
                                    <img src="{{ $item['img'] }}"
                                         alt="{{ $item['name'] }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <span class="font-headline text-sm font-bold text-ink group-hover:text-navy leading-snug line-clamp-1">
                                    {{ $item['name'] }}
                                </span>
                                <span class="font-label text-sm text-muted mt-0.5">
                                    {{ $item['sub'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- KOLOM KANAN (3 Kolom): Featured Editorial Lookbook Ringkas (Ala H&M) -->
                <div class="col-span-3 pl-2">
                    <a href="{{ $panel['url'] }}" class="group block relative rounded-xl overflow-hidden bg-navy h-48 border border-line">
                        <img src="{{ $panel['featured_image'] }}"
                             alt="{{ $panel['featured_label'] }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>
                        <span class="absolute top-2.5 left-2.5 bg-navy/90 text-white font-label text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md">
                            Kurasi Eksklusif
                        </span>
                        <div class="absolute bottom-3 left-3 right-3 text-white">
                            <span class="font-headline text-sm font-extrabold block leading-snug">
                                {{ $panel['featured_label'] }}
                            </span>
                            <span class="font-body text-sm text-white/85 block mt-0.5">
                                {{ $panel['featured_sub'] }}
                            </span>
                        </div>
                    </a>
                </div>

            </div>
        @endforeach
    </div>
</div>
