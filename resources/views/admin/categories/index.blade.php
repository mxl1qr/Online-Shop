@component('layouts.admin', [
    'title' => 'Manajemen Kategori Koleksi',
    'pageTitle' => 'Kategori Koleksi'
])

<div x-data="{ editingCategory: null }" class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-headline text-2xl font-extrabold text-navy tracking-tight">
                Manajemen Kategori Koleksi
            </h1>
            <p class="text-sm text-muted mt-0.5">Kelola kategori pakaian yang tampil di navigasi katalog dan Mega Menu storefront.</p>
        </div>
        <span class="px-3 py-1 rounded-full bg-surface-low text-muted font-label text-xs font-semibold">
            Total: {{ $categories->count() }} Kategori
        </span>
    </div>

    <!-- Form Tambah Kategori Baru -->
    <form action="{{ route('admin.categories.store') }}" method="POST" class="card p-5 flex flex-wrap items-end gap-3 font-label text-xs">
        @csrf
        <div class="flex-1 min-w-[200px]">
            <label class="block font-bold text-ink mb-1.5 uppercase tracking-wider text-muted">Nama Kategori Baru *</label>
            <input type="text" name="name" required placeholder="Contoh: Celana &amp; Denim"
                   class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:border-navy focus:bg-white focus:ring-2 focus:ring-emerald/20 transition font-medium">
        </div>
        <div class="w-full sm:w-64">
            <label class="block font-bold text-muted mb-1.5 uppercase tracking-wider">Slug URL (Opsional)</label>
            <input type="text" name="slug" placeholder="Otomatis digenerate"
                   class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:border-navy focus:bg-white focus:ring-2 focus:ring-emerald/20 transition font-mono">
        </div>
        <button type="submit" class="btn btn-primary h-10 px-5 rounded-xl font-bold cursor-pointer shrink-0">
            + Tambah Kategori
        </button>
    </form>

    <!-- Tabel Kategori Enterprise (Bersih & Rapi) -->
    <div class="card overflow-hidden">
        <table class="w-full text-left text-sm divide-y divide-line font-label">
            <thead class="bg-surface-low text-navy font-bold text-xs uppercase tracking-wider">
                <tr>
                    <th class="p-4">Nama &amp; Slug Kategori</th>
                    <th class="p-4">Jumlah Koleksi Terdaftar</th>
                    <th class="p-4 text-right">Tindakan Admin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line text-sm">
                @foreach($categories as $cat)
                    <tr class="hover:bg-surface-low/50 transition">
                        <td class="p-4 align-top">
                            <strong class="font-headline font-bold text-navy text-sm block">{{ $cat->name }}</strong>
                            <span class="font-mono text-xs text-muted block mt-0.5">Slug: {{ $cat->slug }}</span>
                        </td>
                        <td class="p-4 align-top">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-soft/30 text-emerald font-bold text-xs">
                                {{ $cat->products_count }} Produk
                            </span>
                        </td>
                        <td class="p-4 align-top text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" 
                                        @click="editingCategory = { id: {{ $cat->id }}, name: @js($cat->name), slug: @js($cat->slug) }"
                                        class="h-8 px-3 rounded-lg border border-navy text-navy hover:bg-navy hover:text-white font-bold text-xs transition cursor-pointer">
                                    Edit
                                </button>
                                <button type="button" 
                                        @click="openConfirm('Hapus Kategori ' + @js($cat->name), 'Kategori ini akan dihapus dari navigasi toko dan Mega Menu.', '{{ route('admin.categories.destroy', $cat->id) }}', 'DELETE')" 
                                        class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition cursor-pointer"
                                        title="Hapus Kategori">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Edit Kategori -->
    <div x-show="editingCategory !== null" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy/60 backdrop-blur-sm"
         @keydown.escape.window="editingCategory = null">
        <div @click.outside="editingCategory = null"
             class="bg-white rounded-3xl border border-line shadow-2xl max-w-md w-full p-6 space-y-4 font-label">
            
            <div class="flex items-center justify-between pb-3 border-b border-line">
                <h2 class="font-headline font-bold text-base text-navy">Edit Kategori Koleksi</h2>
                <button type="button" @click="editingCategory = null" class="text-muted hover:text-navy text-xl leading-none">&times;</button>
            </div>

            <template x-if="editingCategory">
                <form :action="'{{ url('/admin/categories') }}/' + editingCategory.id" method="POST" class="space-y-4 text-xs">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Nama Kategori *</label>
                        <input type="text" name="name" x-model="editingCategory.name" required 
                               class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:bg-white text-xs font-bold text-navy">
                    </div>
                    <div>
                        <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Slug URL (Opsional)</label>
                        <input type="text" name="slug" x-model="editingCategory.slug" placeholder="Otomatis digenerate jika kosong"
                               class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:bg-white text-xs font-mono">
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-line">
                        <button type="button" @click="editingCategory = null" class="h-10 px-4 rounded-xl border border-line font-semibold text-ink">Batal</button>
                        <button type="submit" class="btn btn-primary h-10 px-5 rounded-xl font-bold cursor-pointer">Simpan Perubahan</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>

@endcomponent
