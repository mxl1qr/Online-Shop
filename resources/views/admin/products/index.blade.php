@component('layouts.admin', [
    'title' => 'Manajemen Katalog & Inventaris SKU',
    'pageTitle' => 'Katalog & Inventori'
])

<div x-data="{
         viewMode: localStorage.getItem('admin_product_view') || 'table',
         showAddProduct: false,
         editingProduct: null,
         addingVariantProduct: null,
         editingVariant: null,
         newProductImages: [],
         activeEditTab: 'details',
         activeAddTab: 'details',

         moveImage(fromIdx, toIdx) {
             if (!this.editingProduct || !this.editingProduct.images) return;
             if (toIdx < 0 || toIdx >= this.editingProduct.images.length) return;
             const item = this.editingProduct.images.splice(fromIdx, 1)[0];
             this.editingProduct.images.splice(toIdx, 0, item);
         },
         removeExistingImage(idx) {
             if (!this.editingProduct || !this.editingProduct.images) return;
             this.editingProduct.images.splice(idx, 1);
         }
     }"
     class="space-y-6">

    <!-- Top Section: Header & Action & View Switcher -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="font-headline text-2xl font-extrabold text-navy tracking-tight">
                Manajemen Katalog &amp; Inventaris SKU
            </h1>
            <p class="text-sm text-muted mt-0.5">Kelola koleksi pakaian, kurasi galeri multi-foto lookbook, dan pantau stok fisik per varian SKU.</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Hybrid View Switcher: List vs Grid/Tile -->
            <div class="inline-flex p-1 bg-surface-low border border-line rounded-xl font-label text-xs">
                <button type="button" @click="viewMode = 'table'; localStorage.setItem('admin_product_view', 'table')"
                        :class="viewMode === 'table' ? 'bg-white text-navy font-bold shadow-xs border border-line/60' : 'text-muted hover:text-navy'"
                        class="h-8 px-3 rounded-lg flex items-center gap-1.5 transition cursor-pointer"
                        title="Tampilan Tabel Data Terperinci">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>Daftar</span>
                </button>
                <button type="button" @click="viewMode = 'grid'; localStorage.setItem('admin_product_view', 'grid')"
                        :class="viewMode === 'grid' ? 'bg-white text-navy font-bold shadow-xs border border-line/60' : 'text-muted hover:text-navy'"
                        class="h-8 px-3 rounded-lg flex items-center gap-1.5 transition cursor-pointer"
                        title="Tampilan Kartu Galeri / Tile">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Tile</span>
                </button>
            </div>

            <button type="button" @click="showAddProduct = true; newProductImages = []; activeAddTab = 'details'"
                    class="btn btn-primary h-10 px-4 rounded-xl font-label text-xs font-bold transition cursor-pointer flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Tambah Produk</span>
            </button>
        </div>
    </div>

    <!-- Filter Bar: Search, Category, Stock Status -->
    <div class="card p-4 space-y-3 font-label text-xs">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            
            <!-- Pencarian Teks (5 Kolom) -->
            <div class="lg:col-span-5 relative">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama produk, SKU (SRK-...), atau warna..."
                       class="h-10 pl-9 pr-3.5 rounded-xl bg-surface-low border border-line focus:border-navy focus:bg-white text-xs w-full transition">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-muted">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
            </div>

            <!-- Filter Kategori (3 Kolom) -->
            <div class="lg:col-span-3">
                <select name="category_id" onchange="this.form.submit()"
                        class="h-10 px-3 rounded-xl bg-surface-low border border-line focus:border-navy focus:bg-white text-xs w-full font-medium">
                    <option value="">Semua Kategori Koleksi</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status Stok (4 Kolom) -->
            <div class="lg:col-span-4 flex items-center gap-1.5 flex-wrap sm:flex-nowrap justify-start lg:justify-end">
                <a href="{{ route('admin.products.index', array_merge(request()->except(['stock_status', 'page']))) }}"
                   class="h-9 px-3 rounded-xl border flex items-center transition {{ !request('stock_status') ? 'bg-navy text-white border-navy font-bold shadow-xs' : 'bg-surface-low text-muted border-line hover:border-navy' }}">
                    Semua ({{ $stockStats['total'] }})
                </a>
                <a href="{{ route('admin.products.index', array_merge(request()->except(['page']), ['stock_status' => 'low'])) }}"
                   class="h-9 px-3 rounded-xl border flex items-center gap-1.5 transition {{ request('stock_status') === 'low' ? 'bg-amber-500 text-navy border-amber-500 font-bold shadow-xs' : 'bg-surface-low text-amber-800 border-line hover:border-amber-500' }}">
                    <span>Kritis</span>
                    <span class="px-1.5 py-0.2 rounded-md bg-white/40 text-[10px] font-extrabold">{{ $stockStats['low'] }}</span>
                </a>
                <a href="{{ route('admin.products.index', array_merge(request()->except(['page']), ['stock_status' => 'out'])) }}"
                   class="h-9 px-3 rounded-xl border flex items-center gap-1.5 transition {{ request('stock_status') === 'out' ? 'bg-red-600 text-white border-red-600 font-bold shadow-xs' : 'bg-surface-low text-red-700 border-line hover:border-red-600' }}">
                    <span>Habis</span>
                    <span class="px-1.5 py-0.2 rounded-md bg-white/40 text-[10px] font-extrabold">{{ $stockStats['out'] }}</span>
                </a>
            </div>

        </form>
    </div>

    <!-- TAMPILAN HYBRID: 1. LIST/TABLE VIEW -->
    <div x-show="viewMode === 'table'" x-cloak class="card overflow-hidden">
        @if($products->isEmpty())
            <div class="p-16 text-center text-muted font-body text-sm space-y-3">
                <div class="w-12 h-12 rounded-full bg-surface-low border border-line flex items-center justify-center mx-auto text-muted">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <p class="font-bold text-navy">Tidak ada produk yang cocok dengan kriteria filter.</p>
                <p class="text-xs text-muted max-w-sm mx-auto">Coba ubah kata kunci pencarian atau reset filter kategori.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm divide-y divide-line">
                    <thead class="bg-surface-low text-navy font-label font-bold text-xs uppercase tracking-wider sticky top-0 z-10">
                        <tr>
                            <th class="p-4">Foto &amp; Nama Produk</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Harga Dasar</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Varian SKU &amp; Stok Fisik</th>
                            <th class="p-4 text-right">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line font-body text-sm">
                        @foreach($products as $product)
                            <tr class="hover:bg-surface-low/50 transition">
                                <!-- Thumbnail & Info -->
                                <td class="p-4 align-top">
                                    <div class="flex items-start gap-3">
                                        <div class="w-12 h-16 rounded-xl bg-surface-low border border-line overflow-hidden shrink-0 shadow-xs relative">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-[10px] text-muted font-label">No Pic</div>
                                            @endif
                                            @if(count($product->gallery_images) > 1)
                                                <span class="absolute bottom-1 right-1 px-1 py-0.2 rounded bg-navy/80 text-white font-mono text-[9px] font-bold">
                                                    {{ count($product->gallery_images) }}P
                                                </span>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('products.show', $product->slug) }}" target="_blank" 
                                               class="font-headline font-bold text-navy hover:text-emerald text-sm transition line-clamp-1 flex items-center gap-1.5"
                                               title="Buka Halaman Produk Publik">
                                                <span>{{ $product->name }}</span>
                                                <svg class="w-3.5 h-3.5 opacity-40 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                            <span class="font-mono text-xs text-muted block mt-0.5">Slug: {{ $product->slug }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="p-4 align-top whitespace-nowrap font-label text-xs">
                                    <span class="px-2.5 py-1 rounded-full bg-surface-low border border-line font-medium text-navy">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>

                                <!-- Harga Dasar -->
                                <td class="p-4 align-top whitespace-nowrap font-headline font-bold text-navy text-sm">
                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                </td>

                                <!-- Status Aktif -->
                                <td class="p-4 align-top whitespace-nowrap font-label text-xs">
                                    <span class="px-2.5 py-1 rounded-full font-bold inline-flex items-center gap-1.5 {{ $product->is_active ? 'bg-emerald-soft/30 text-emerald' : 'bg-surface text-muted border border-line' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $product->is_active ? 'bg-emerald' : 'bg-muted' }}"></span>
                                        <span>{{ $product->is_active ? 'Aktif' : 'Draft / Arsip' }}</span>
                                    </span>
                                </td>

                                <!-- Matrix Varian SKU & Inline Quick Restock -->
                                <td class="p-4 align-top">
                                    <div class="space-y-1.5 font-label text-xs">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            @forelse($product->variants as $v)
                                                <button type="button" 
                                                        @click="editingVariant = { id: {{ $v->id }}, sku: @js($v->sku), size: @js($v->size), color: @js($v->color), stock: {{ $v->stock }}, additional_price: {{ $v->additional_price }} }"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border text-xs cursor-pointer hover:border-navy transition
                                                    {{ $v->stock === 0 ? 'bg-red-50 border-red-200 text-red-700' : ($v->stock <= 1 ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-white border-line text-ink') }}"
                                                        title="Klik untuk ubah stok SKU ini">
                                                    <span class="font-bold">{{ $v->size }}</span>
                                                    <span class="text-muted">({{ $v->color }})</span>
                                                    <span class="font-mono font-extrabold ml-1 {{ $v->stock <= 1 ? 'text-red-600' : 'text-emerald' }}">{{ $v->stock }} pcs</span>
                                                </button>
                                            @empty
                                                <span class="text-red-600 italic">Belum ada varian ukuran</span>
                                            @endforelse
                                        </div>
                                        <div class="text-[11px] text-muted pt-0.5">
                                            Total: <strong class="text-navy font-bold">{{ $product->variants->sum('stock') }} pcs</strong> ({{ $product->variants->count() }} SKU)
                                        </div>
                                    </div>
                                </td>

                                <!-- Tindakan Admin -->
                                <td class="p-4 align-top text-right whitespace-nowrap font-label text-xs">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Edit Produk Button -->
                                        <button type="button"
                                                @click="editingProduct = {
                                                    id: {{ $product->id }},
                                                    name: @js($product->name),
                                                    category_id: String({{ $product->category_id }}),
                                                    base_price: {{ $product->base_price }},
                                                    description: @js($product->description),
                                                    is_active: {{ $product->is_active ? 'true' : 'false' }},
                                                    images: @js($product->gallery_images),
                                                    variants: @js($product->variants)
                                                }; activeEditTab = 'details'"
                                                class="h-8 px-2.5 rounded-lg border border-navy text-navy hover:bg-navy hover:text-white font-bold transition cursor-pointer"
                                                title="Edit Detail, Lookbook & Varian">
                                            Edit
                                        </button>

                                        <!-- Tambah Varian Button -->
                                        <button type="button"
                                                @click="addingVariantProduct = { id: {{ $product->id }}, name: @js($product->name) }"
                                                class="h-8 px-2.5 rounded-lg bg-surface-low hover:bg-line text-navy font-semibold transition cursor-pointer"
                                                title="Tambah Varian Ukuran / Warna Baru">
                                            + Varian
                                        </button>

                                        <!-- Hapus Produk Button -->
                                        <button type="button" 
                                                @click="openConfirm('Hapus Produk ' + @js($product->name), 'Produk beserta seluruh varian ukurannya akan dihapus permanen dari toko.', '{{ route('admin.products.destroy', $product->id) }}', 'DELETE')" 
                                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus Produk">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
                    Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari total {{ $products->total() }} produk
                </span>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- TAMPILAN HYBRID: 2. GRID / TILE CARD VIEW -->
    <div x-show="viewMode === 'grid'" x-cloak class="space-y-6">
        @if($products->isEmpty())
            <div class="card p-16 text-center text-muted font-body text-sm space-y-3">
                <p class="font-bold text-navy">Tidak ada produk ditemukan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($products as $product)
                    @php
                        $totStock = $product->variants->sum('stock');
                    @endphp
                    <div class="card overflow-hidden hover:shadow-float transition duration-200 flex flex-col group">
                        
                        <!-- Product Visual Lookbook Cover -->
                        <div class="aspect-[3/4] bg-surface-low relative overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-102 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-muted text-xs">Belum Ada Foto</div>
                            @endif

                            <!-- Floating Badges: Category & Stock -->
                            <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1.5 items-center">
                                <span class="px-2 py-0.5 rounded-md bg-white/95 backdrop-blur-xs text-navy font-bold text-[10px] uppercase tracking-wider shadow-xs">
                                    {{ $product->category->name ?? 'Kategori' }}
                                </span>
                            </div>

                            <div class="absolute top-2.5 right-2.5 flex flex-col gap-1 items-end">
                                <span class="px-2 py-0.5 rounded-md font-bold text-[11px] shadow-xs {{ $totStock === 0 ? 'bg-red-600 text-white' : ($totStock <= 2 ? 'bg-amber-500 text-navy' : 'bg-emerald text-white') }}">
                                    {{ $totStock === 0 ? 'Habis' : $totStock . ' pcs' }}
                                </span>
                                @if(count($product->gallery_images) > 1)
                                    <span class="px-1.5 py-0.5 rounded-md bg-navy/80 text-white font-mono text-[10px] font-bold">
                                        📷 {{ count($product->gallery_images) }} Foto
                                    </span>
                                @endif
                            </div>

                            <!-- Bottom Status Pill -->
                            <div class="absolute bottom-2 left-2.5">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $product->is_active ? 'bg-emerald-soft text-navy' : 'bg-surface-low text-muted border border-line' }}">
                                    {{ $product->is_active ? '● Aktif di Toko' : '○ Draft' }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-3 font-label">
                            <div>
                                <h3 class="font-headline font-bold text-navy text-sm line-clamp-1" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h3>
                                <p class="font-extrabold text-navy text-base mt-1">
                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                </p>

                                <!-- Mini Varian Chips -->
                                <div class="flex items-center gap-1 flex-wrap mt-2.5">
                                    @forelse($product->variants as $v)
                                        <button type="button" 
                                                @click="editingVariant = { id: {{ $v->id }}, sku: @js($v->sku), size: @js($v->size), color: @js($v->color), stock: {{ $v->stock }}, additional_price: {{ $v->additional_price }} }"
                                                class="px-1.5 py-0.5 rounded border text-[11px] cursor-pointer hover:border-navy transition
                                                {{ $v->stock === 0 ? 'bg-red-50 text-red-700 border-red-200' : 'bg-surface-low text-ink border-line' }}">
                                            <strong>{{ $v->size }}</strong> ({{ $v->stock }})
                                        </button>
                                    @empty
                                        <span class="text-[11px] text-red-600">0 varian</span>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Card Action Toolbar -->
                            <div class="pt-3 border-t border-line flex items-center justify-between gap-2 text-xs font-label">
                                <button type="button"
                                        @click="editingProduct = {
                                            id: {{ $product->id }},
                                            name: @js($product->name),
                                            category_id: String({{ $product->category_id }}),
                                            base_price: {{ $product->base_price }},
                                            description: @js($product->description),
                                            is_active: {{ $product->is_active ? 'true' : 'false' }},
                                            images: @js($product->gallery_images),
                                            variants: @js($product->variants)
                                        }; activeEditTab = 'details'"
                                        class="flex-1 h-8 rounded-lg bg-navy text-white hover:bg-navy-600 font-bold transition cursor-pointer text-center flex items-center justify-center">
                                    Edit Produk
                                </button>
                                <button type="button"
                                        @click="addingVariantProduct = { id: {{ $product->id }}, name: @js($product->name) }"
                                        class="h-8 px-2.5 rounded-lg border border-line hover:border-navy text-navy font-semibold transition cursor-pointer"
                                        title="+ Varian Baru">
                                    + Varian
                                </button>
                                <button type="button"
                                        @click="openConfirm('Hapus Produk ' + @js($product->name), 'Produk beserta seluruh varian ukurannya akan dihapus permanen dari toko.', '{{ route('admin.products.destroy', $product->id) }}', 'DELETE')"
                                        class="h-8 w-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition cursor-pointer"
                                        title="Hapus Produk">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination Bar for Grid View -->
            <div class="card p-4 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-label">
                <span class="text-muted">
                    Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari total {{ $products->total() }} produk
                </span>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- MODAL 1: Tambah Produk Baru (Clean Multi-Tab / Spacious) -->
    <div x-show="showAddProduct" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy/60 backdrop-blur-sm"
         @keydown.escape.window="showAddProduct = false">
        <div @click.outside="showAddProduct = false"
             class="bg-white rounded-3xl border border-line shadow-2xl max-w-3xl w-full p-6 space-y-4 max-h-[92vh] overflow-y-auto font-label">
            
            <div class="flex items-center justify-between pb-3 border-b border-line">
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full bg-emerald"></span>
                    <h2 class="font-headline font-bold text-lg text-navy">Tambah Koleksi Pakaian Baru</h2>
                </div>
                <button type="button" @click="showAddProduct = false" class="text-muted hover:text-navy text-2xl leading-none">&times;</button>
            </div>

            <!-- Tab Nav inside Modal -->
            <div class="flex border-b border-line gap-2 text-xs">
                <button type="button" @click="activeAddTab = 'details'"
                        :class="activeAddTab === 'details' ? 'border-b-2 border-navy text-navy font-bold' : 'text-muted hover:text-navy'"
                        class="pb-2.5 px-3 transition cursor-pointer">
                    1. Informasi Produk
                </button>
                <button type="button" @click="activeAddTab = 'lookbook'"
                        :class="activeAddTab === 'lookbook' ? 'border-b-2 border-navy text-navy font-bold' : 'text-muted hover:text-navy'"
                        class="pb-2.5 px-3 transition cursor-pointer">
                    2. Galeri Multi-Foto Lookbook
                </button>
                <button type="button" @click="activeAddTab = 'variant'"
                        :class="activeAddTab === 'variant' ? 'border-b-2 border-navy text-navy font-bold' : 'text-muted hover:text-navy'"
                        class="pb-2.5 px-3 transition cursor-pointer">
                    3. Varian Awal SKU
                </button>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                
                <!-- TAB 1: Detail Produk -->
                <div x-show="activeAddTab === 'details'" class="space-y-3.5">
                    <div>
                        <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Nama Koleksi Pakaian *</label>
                        <input type="text" name="name" required placeholder="Contoh: Heavyweight Boxy T-Shirt Olive"
                               class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:bg-white text-xs font-semibold text-navy">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Kategori Koleksi *</label>
                            <select name="category_id" required class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line focus:bg-white text-xs font-medium">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Harga Dasar (Rp) *</label>
                            <input type="number" name="base_price" required min="0" placeholder="185000"
                                   class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:bg-white text-xs font-bold text-navy">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Deskripsi Kurasi &amp; Dimensi Fisik *</label>
                        <textarea name="description" rows="3" required placeholder="Kondisi 9.5/10, bahan katun combed 240 GSM, sanitasi ozon 100°C..."
                                  class="w-full p-3 rounded-xl bg-surface-low border border-line focus:bg-white text-xs"></textarea>
                    </div>

                    <label class="inline-flex items-center gap-2 cursor-pointer pt-1">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-line-strong text-navy focus:ring-navy">
                        <span class="font-bold text-navy">Langsung Tampilkan Aktif di Website Toko</span>
                    </label>
                </div>

                <!-- TAB 2: Multi-Foto Lookbook -->
                <div x-show="activeAddTab === 'lookbook'" class="space-y-3.5">
                    <div>
                        <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Unggah Beberapa Foto Lookbook (Bisa > 1 Foto)</label>
                        <p class="text-[11px] text-muted mb-2">Pilih beberapa foto sekaligus (Tampak depan, detail kain, tag kurasi, dll). Format: JPG, PNG, WEBP.</p>
                        
                        <input type="file" name="image_files[]" multiple accept="image/*"
                               @change="
                                   newProductImages = [];
                                   const files = Array.from($event.target.files);
                                   files.forEach(file => {
                                       const reader = new FileReader();
                                       reader.onload = e => newProductImages.push(e.target.result);
                                       reader.readAsDataURL(file);
                                   });
                               "
                               class="w-full text-xs file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-navy file:text-white file:cursor-pointer hover:file:bg-navy-600 bg-surface-low p-2 rounded-2xl border border-line">
                    </div>

                    <!-- Previews of selected images -->
                    <template x-if="newProductImages.length > 0">
                        <div class="space-y-2 pt-2">
                            <span class="font-bold text-navy uppercase tracking-wider text-[11px] block">Preview Urutan Slide Foto (<span x-text="newProductImages.length"></span> Foto):</span>
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                                <template x-for="(imgSrc, idx) in newProductImages" :key="idx">
                                    <div class="aspect-[3/4] rounded-xl overflow-hidden border border-line relative group shadow-xs">
                                        <img :src="imgSrc" alt="Lookbook Preview" class="w-full h-full object-cover">
                                        <span class="absolute top-1 left-1 px-1.5 py-0.5 rounded text-[9px] font-bold"
                                              :class="idx === 0 ? 'bg-emerald text-white' : 'bg-navy/80 text-white'"
                                              x-text="idx === 0 ? 'Utama' : 'Slide ' + (idx + 1)"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- TAB 3: Varian Awal SKU -->
                <div x-show="activeAddTab === 'variant'" class="space-y-3.5">
                    <p class="text-xs text-muted">Tiap pakaian membutuhkan minimal 1 varian ukuran dan warna awal.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Ukuran (Size) *</label>
                            <input type="text" name="initial_size" value="L" required placeholder="L, XL, 32" class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line font-bold text-navy">
                        </div>
                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Warna Kain *</label>
                            <input type="text" name="initial_color" value="Olive" required placeholder="Washed Olive" class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line font-medium">
                        </div>
                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Stok Unit Fisik *</label>
                            <input type="number" name="initial_stock" value="1" min="0" required class="w-full h-10 px-3 text-center rounded-xl bg-surface-low border border-line font-bold text-navy">
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-between pt-3 border-t border-line">
                    <div class="flex items-center gap-2">
                        <template x-if="activeAddTab !== 'details'">
                            <button type="button" @click="activeAddTab = activeAddTab === 'variant' ? 'lookbook' : 'details'" class="text-xs text-muted hover:text-navy underline">
                                &larr; Tab Sebelumnya
                            </button>
                        </template>
                        <template x-if="activeAddTab !== 'variant'">
                            <button type="button" @click="activeAddTab = activeAddTab === 'details' ? 'lookbook' : 'variant'" class="text-xs font-bold text-navy hover:underline">
                                Lanjut ke Tab Berikutnya &rarr;
                            </button>
                        </template>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="showAddProduct = false" class="h-10 px-4 rounded-xl border border-line text-ink font-semibold">Batal</button>
                        <button type="submit" class="btn btn-primary h-10 px-6 rounded-xl font-bold cursor-pointer">Simpan &amp; Terbitkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: Edit Produk & Multi-Photo Lookbook Arranger (Spacious / Tabbed) -->
    <div x-show="editingProduct !== null" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy/60 backdrop-blur-sm"
         @keydown.escape.window="editingProduct = null">
        <div @click.outside="editingProduct = null"
             class="bg-white rounded-3xl border border-line shadow-2xl max-w-3xl w-full p-6 space-y-4 max-h-[92vh] overflow-y-auto font-label">
            
            <div class="flex items-center justify-between pb-3 border-b border-line">
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full bg-navy"></span>
                    <div>
                        <h2 class="font-headline font-bold text-lg text-navy">Edit Koleksi &amp; Galeri Lookbook</h2>
                        <span class="text-xs text-muted" x-text="editingProduct?.name"></span>
                    </div>
                </div>
                <button type="button" @click="editingProduct = null" class="text-muted hover:text-navy text-2xl leading-none">&times;</button>
            </div>

            <!-- Tab Selector inside Edit Modal -->
            <div class="flex border-b border-line gap-2 text-xs">
                <button type="button" @click="activeEditTab = 'details'"
                        :class="activeEditTab === 'details' ? 'border-b-2 border-navy text-navy font-bold' : 'text-muted hover:text-navy'"
                        class="pb-2.5 px-3 transition cursor-pointer">
                    1. Detail Koleksi
                </button>
                <button type="button" @click="activeEditTab = 'lookbook'"
                        :class="activeEditTab === 'lookbook' ? 'border-b-2 border-navy text-navy font-bold' : 'text-muted hover:text-navy'"
                        class="pb-2.5 px-3 transition cursor-pointer flex items-center gap-1.5">
                    <span>2. Galeri Lookbook &amp; Urutan Slide</span>
                    <span class="px-1.5 py-0.2 rounded-full bg-navy/10 text-navy font-bold text-[10px]" x-text="editingProduct?.images?.length || 0"></span>
                </button>
                <button type="button" @click="activeEditTab = 'variants'"
                        :class="activeEditTab === 'variants' ? 'border-b-2 border-navy text-navy font-bold' : 'text-muted hover:text-navy'"
                        class="pb-2.5 px-3 transition cursor-pointer flex items-center gap-1.5">
                    <span>3. Varian SKU &amp; Stok</span>
                    <span class="px-1.5 py-0.2 rounded-full bg-surface-low text-muted font-bold text-[10px]" x-text="editingProduct?.variants?.length || 0"></span>
                </button>
            </div>

            <template x-if="editingProduct">
                <form :action="'{{ url('/admin/products') }}/' + editingProduct.id" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <!-- Hidden input to store JSON array of reordered existing images -->
                    <input type="hidden" name="existing_images" :value="JSON.stringify(editingProduct.images || [])">
                    
                    <!-- TAB 1: Detail Koleksi -->
                    <div x-show="activeEditTab === 'details'" class="space-y-3.5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div class="sm:col-span-2">
                                <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Nama Produk Pakaian *</label>
                                <input type="text" name="name" x-model="editingProduct.name" required class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:bg-white text-xs font-semibold">
                            </div>
                            <div>
                                <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Kategori Koleksi *</label>
                                <select name="category_id" x-model="editingProduct.category_id" class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line focus:bg-white text-xs font-medium">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Harga Dasar (Rp) *</label>
                                <input type="number" name="base_price" x-model.number="editingProduct.base_price" required min="0" class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:bg-white text-xs font-bold text-navy">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Deskripsi Pakaian &amp; Dimensi</label>
                            <textarea name="description" x-model="editingProduct.description" rows="4" class="w-full p-3 rounded-xl bg-surface-low border border-line focus:bg-white text-xs"></textarea>
                        </div>

                        <label class="inline-flex items-center gap-2 cursor-pointer pt-1">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" x-model="editingProduct.is_active" class="rounded border-line-strong text-navy focus:ring-navy">
                            <span class="font-bold text-navy">Status Aktif (Tampil di Toko Publik)</span>
                        </label>
                    </div>

                    <!-- TAB 2: Lookbook Gallery & Slide Arranger -->
                    <div x-show="activeEditTab === 'lookbook'" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-bold text-navy uppercase tracking-wider text-[11px] block">Atur Urutan Slide Lookbook:</span>
                                <p class="text-muted text-[11px]">Slide #1 otomatis menjadi Foto Sampul Utama di katalog. Gunakan tombol panah untuk mengubah posisi urutan.</p>
                            </div>
                        </div>

                        <!-- Gallery Reordering Strip -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 max-h-72 overflow-y-auto p-1">
                            <template x-for="(imgPath, idx) in (editingProduct.images || [])" :key="imgPath">
                                <div class="rounded-2xl border border-line p-2 bg-surface-low flex flex-col justify-between space-y-2 relative group hover:border-navy transition">
                                    <div class="aspect-[3/4] rounded-xl overflow-hidden bg-white border border-line relative">
                                        <img :src="'{{ asset('storage') }}/' + imgPath" alt="Lookbook slide" class="w-full h-full object-cover">
                                        <span class="absolute top-1.5 left-1.5 px-2 py-0.5 rounded-md font-mono text-[10px] font-extrabold shadow-xs"
                                              :class="idx === 0 ? 'bg-emerald text-white' : 'bg-navy/80 text-white'"
                                              x-text="idx === 0 ? 'Slide 1 (Utama)' : 'Slide ' + (idx + 1)"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-1 pt-1">
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click="moveImage(idx, idx - 1)" :disabled="idx === 0"
                                                    class="w-7 h-7 rounded-lg border border-line bg-white hover:bg-navy hover:text-white disabled:opacity-30 disabled:pointer-events-none text-xs flex items-center justify-center font-bold"
                                                    title="Geser ke Kiri (Sebelumnya)">&larr;</button>
                                            <button type="button" @click="moveImage(idx, idx + 1)" :disabled="idx === editingProduct.images.length - 1"
                                                    class="w-7 h-7 rounded-lg border border-line bg-white hover:bg-navy hover:text-white disabled:opacity-30 disabled:pointer-events-none text-xs flex items-center justify-center font-bold"
                                                    title="Geser ke Kanan (Berikutnya)">&rarr;</button>
                                        </div>
                                        <button type="button" @click="removeExistingImage(idx)"
                                                class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition"
                                                title="Hapus Foto ini dari Lookbook">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Add New Images to Gallery -->
                        <div class="p-3.5 rounded-2xl bg-surface border border-line space-y-2">
                            <label class="block font-bold text-navy uppercase tracking-wider text-[11px]">+ Tambah Foto Baru ke Galeri</label>
                            <input type="file" name="new_image_files[]" multiple accept="image/*"
                                   class="w-full text-xs file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-navy file:text-white file:cursor-pointer hover:file:bg-navy-600">
                            <p class="text-[10px] text-muted">Foto baru yang diunggah akan otomatis ditambahkan ke urutan slide berikutnya.</p>
                        </div>
                    </div>

                    <!-- TAB 3: Varian SKU & Manajemen Stok -->
                    <div x-show="activeEditTab === 'variants'" class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-navy uppercase tracking-wider text-[11px]">Daftar Varian SKU Produk:</span>
                            <button type="button" 
                                    @click="addingVariantProduct = { id: editingProduct.id, name: editingProduct.name }"
                                    class="text-navy hover:underline font-bold text-xs">
                                + Tambah Varian Baru
                            </button>
                        </div>

                        <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                            <template x-for="v in (editingProduct.variants || [])" :key="v.id">
                                <div class="p-3 rounded-2xl bg-surface-low border border-line flex items-center justify-between text-xs hover:border-line-strong transition">
                                    <div>
                                        <span class="font-mono text-[10px] text-muted block" x-text="v.sku"></span>
                                        <span class="font-bold text-navy text-sm" x-text="v.size + ' &bull; ' + v.color"></span>
                                        <span class="text-[11px] text-muted block" x-show="v.additional_price > 0" x-text="'+Rp ' + Number(v.additional_price).toLocaleString('id-ID')"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 rounded-lg font-mono font-bold text-xs"
                                              :class="v.stock <= 1 ? 'bg-red-100 text-red-700' : 'bg-emerald-soft/30 text-emerald'"
                                              x-text="v.stock + ' pcs'"></span>
                                        <button type="button" 
                                                @click="editingVariant = { id: v.id, sku: v.sku, size: v.size, color: v.color, stock: v.stock, additional_price: v.additional_price || 0 }"
                                                class="h-8 px-2.5 rounded-lg border border-line bg-white hover:border-navy text-navy font-bold text-xs transition">
                                            Edit Stok
                                        </button>
                                        <button type="button" 
                                                @click="openConfirm('Hapus Varian ' + v.sku, 'Varian ini akan dihapus dari produk.', '{{ url('/admin/variants') }}/' + v.id, 'DELETE')" 
                                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition"
                                                title="Hapus Varian">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-line">
                        <button type="button" @click="editingProduct = null" class="h-10 px-4 rounded-xl border border-line text-ink font-semibold">Batal</button>
                        <button type="submit" class="btn btn-primary h-10 px-6 rounded-xl font-bold cursor-pointer">Simpan Perubahan</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    <!-- MODAL 3: Tambah Varian Baru ke Produk -->
    <div x-show="addingVariantProduct !== null" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy/60 backdrop-blur-sm"
         @keydown.escape.window="addingVariantProduct = null">
        <div @click.outside="addingVariantProduct = null"
             class="bg-white rounded-3xl border border-line shadow-2xl max-w-md w-full p-6 space-y-4 font-label">
            
            <div class="flex items-center justify-between pb-3 border-b border-line">
                <div>
                    <h2 class="font-headline font-bold text-base text-navy">+ Tambah Varian Baru</h2>
                    <p class="text-xs text-muted truncate max-w-xs" x-text="addingVariantProduct?.name"></p>
                </div>
                <button type="button" @click="addingVariantProduct = null" class="text-muted hover:text-navy text-xl leading-none">&times;</button>
            </div>

            <template x-if="addingVariantProduct">
                <form :action="'{{ url('/admin/products') }}/' + addingVariantProduct.id + '/variants'" method="POST" class="space-y-3.5 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Ukuran (Size) *</label>
                        <input type="text" name="size" required placeholder="Cth: S, M, L, XL, 32, 34" class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line font-bold text-navy">
                    </div>
                    <div>
                        <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Warna Garmen *</label>
                        <input type="text" name="color" required placeholder="Cth: Washed Olive / Vintage Black" class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line font-medium">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Stok Fisik Unit *</label>
                            <input type="number" name="stock" required min="0" value="1" class="w-full h-10 px-3 text-center rounded-xl bg-surface-low border border-line font-bold text-navy">
                        </div>
                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Tambahan Harga (Rp)</label>
                            <input type="number" name="additional_price" value="0" min="0" class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line font-bold">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-line">
                        <button type="button" @click="addingVariantProduct = null" class="h-10 px-4 rounded-xl border border-line font-semibold text-ink">Batal</button>
                        <button type="submit" class="btn btn-primary h-10 px-5 rounded-xl font-bold cursor-pointer">+ Simpan Varian</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    <!-- MODAL 4: Quick Edit Varian SKU (Stock, Additional Price, Size, Color) -->
    <div x-show="editingVariant !== null" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy/60 backdrop-blur-sm"
         @keydown.escape.window="editingVariant = null">
        <div @click.outside="editingVariant = null"
             class="bg-white rounded-3xl border border-line shadow-2xl max-w-md w-full p-6 space-y-4 font-label">
            
            <div class="flex items-center justify-between pb-3 border-b border-line">
                <div>
                    <h2 class="font-headline font-bold text-base text-navy">Update Stok &amp; Varian SKU</h2>
                    <p class="text-xs font-mono text-muted" x-text="editingVariant?.sku"></p>
                </div>
                <button type="button" @click="editingVariant = null" class="text-muted hover:text-navy text-xl leading-none">&times;</button>
            </div>

            <template x-if="editingVariant">
                <form :action="'{{ url('/admin/variants') }}/' + editingVariant.id" method="POST" class="space-y-3.5 text-xs">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Ukuran (Size)</label>
                            <input type="text" name="size" x-model="editingVariant.size" required class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line font-bold text-navy">
                        </div>
                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Warna</label>
                            <input type="text" name="color" x-model="editingVariant.color" required class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Jumlah Stok (pcs) *</label>
                            <input type="number" name="stock" x-model.number="editingVariant.stock" required min="0" class="w-full h-10 px-3 text-center rounded-xl bg-surface-low border border-line font-bold text-navy text-sm">
                        </div>
                        <div>
                            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Tambahan Harga (Rp)</label>
                            <input type="number" name="additional_price" x-model.number="editingVariant.additional_price" min="0" class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line font-bold">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-line">
                        <button type="button" @click="editingVariant = null" class="h-10 px-4 rounded-xl border border-line font-semibold text-ink">Batal</button>
                        <button type="submit" class="btn btn-primary h-10 px-5 rounded-xl font-bold cursor-pointer">Simpan Stok</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

</div>

@endcomponent
