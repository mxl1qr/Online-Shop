@component('layouts.admin', [
    'title' => 'Pengaturan Toko & Voucher Promo',
    'pageTitle' => 'Pengaturan Toko'
])

<div x-data="{
         announcementText: @js($storeConfig['announcement'] ?? ''),
         freeShippingMin: {{ (int) ($storeConfig['free_shipping_threshold'] ?? 250000) }},
         newCode: '',
         newDiscount: 25000,
         newTitle: '',
         newMin: 200000,
         copiedCode: null,
         copyToClipboard(text) {
             navigator.clipboard.writeText(text);
             this.copiedCode = text;
             setTimeout(() => this.copiedCode = null, 2000);
         }
     }"
     class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-headline text-2xl font-extrabold text-navy tracking-tight">
                Pengaturan Operasional &amp; Voucher Promo
            </h1>
            <p class="text-sm text-muted mt-0.5">Kelola banner pengumuman storefront, ambang gratis ongkir, rekening bank transfer resmi, dan kupon diskon member.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full bg-emerald-soft/30 text-emerald text-xs font-bold inline-flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald animate-pulse"></span>
                <span>Konfigurasi Real-Time Aktif</span>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI: Pengaturan Operasional Toko & Rekening Pembayaran (7 Kolom) -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="lg:col-span-7 space-y-6 font-label text-xs">
            @csrf

            <!-- Card 1: Branding & Banner Pengumuman -->
            <div class="card p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-line">
                    <h2 class="font-headline text-sm font-bold text-navy flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-navy"></span>
                        <span>Banner Pengumuman Toko (Top Announcement)</span>
                    </h2>
                    <span class="text-[11px] text-muted">Tampil di paling atas website publik</span>
                </div>

                <div>
                    <label class="block font-bold mb-1.5 uppercase tracking-wider text-muted">Teks Banner Berjalan / Pengumuman</label>
                    <input type="text" name="announcement" x-model="announcementText"
                           class="w-full h-11 px-4 rounded-xl bg-surface-low border border-line focus:border-navy focus:bg-white focus:ring-2 focus:ring-emerald/20 transition font-medium text-ink">
                    <p class="text-[11px] text-muted mt-1.5">Gunakan pemisah bullet (&bull;) untuk memisahkan pesan pengumuman kurasi, garansi, atau promo.</p>
                </div>

                <!-- Live Preview Banner Toko -->
                <div class="p-3.5 rounded-2xl bg-surface border border-line space-y-1.5">
                    <span class="text-[10px] uppercase tracking-wider font-bold text-muted block">Live Preview Storefront:</span>
                    <div class="bg-navy text-white text-center py-2 px-4 rounded-xl text-xs font-medium truncate shadow-xs">
                        <span x-text="announcementText || 'Teks pengumuman toko akan tampil di sini...'"></span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Ambang Bebas Ongkir & Layanan Pelanggan -->
            <div class="card p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-line">
                    <h2 class="font-headline text-sm font-bold text-navy flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald"></span>
                        <span>Kebijakan Logistik &amp; Layanan Pelanggan</span>
                    </h2>
                    <span class="text-[11px] text-muted">Otomasi kalkulasi checkout</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold mb-1.5 uppercase tracking-wider text-muted">Batas Gratis Ongkir (Rp)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none font-bold text-muted text-xs">Rp</span>
                            <input type="number" name="free_shipping_threshold" x-model.number="freeShippingMin"
                                   class="w-full h-11 pl-10 pr-4 rounded-xl bg-surface-low border border-line focus:border-navy focus:bg-white text-xs font-bold text-navy">
                        </div>
                        <p class="text-[11px] text-muted mt-1">Belanja di atas nominal ini otomatis bebas ongkir (Rp 0).</p>
                    </div>

                    <div>
                        <label class="block font-bold mb-1.5 uppercase tracking-wider text-muted">WhatsApp Layanan Pelanggan (CS)</label>
                        <div class="relative">
                            <input type="text" name="whatsapp_cs" value="{{ $storeConfig['whatsapp_cs'] ?? '6281234567890' }}" placeholder="6281234567890"
                                   class="w-full h-11 px-4 rounded-xl bg-surface-low border border-line focus:border-navy focus:bg-white text-xs font-medium">
                        </div>
                        <div class="flex items-center justify-between mt-1 text-[11px]">
                            <span class="text-muted">Format internasional (628...)</span>
                            <a :href="'https://wa.me/' + '{{ $storeConfig['whatsapp_cs'] ?? '6281234567890' }}'" target="_blank" class="text-emerald hover:underline font-bold inline-flex items-center gap-0.5">
                                <span>Uji Chat CS</span> &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Live Progress Bar Preview -->
                <div class="p-3.5 rounded-2xl bg-surface border border-line space-y-2">
                    <div class="flex justify-between items-center text-[11px]">
                        <span class="font-bold text-navy">Simulasi Banner Gratis Ongkir di Keranjang:</span>
                        <span class="font-bold text-emerald">Rp 175.000 / Rp <span x-text="Number(freeShippingMin).toLocaleString('id-ID')"></span></span>
                    </div>
                    <div class="w-full bg-surface-low rounded-full h-2.5 overflow-hidden">
                        <div class="bg-emerald h-2.5 rounded-full" style="width: 70%"></div>
                    </div>
                    <p class="text-[10px] text-muted">"Tambahkan Rp 75.000 lagi untuk mendapatkan Bebas Ongkir!"</p>
                </div>
            </div>

            <!-- Card 3: Rekening Resmi Pembayaran Transfer Bank -->
            <div class="card p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-line">
                    <h2 class="font-headline text-sm font-bold text-navy flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-navy"></span>
                        <span>Rekening Bank Resmi PT Sirkulasi Indonesia Lestari</span>
                    </h2>
                    <span class="text-[11px] text-muted">Instruksi invoice checkout</span>
                </div>

                <div class="space-y-3.5">
                    <!-- Bank BCA -->
                    <div class="p-3.5 rounded-2xl bg-surface-low border border-line space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-navy text-xs uppercase tracking-wider flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-800 font-mono text-[10px] font-bold">BCA</span>
                                <span>Bank Central Asia</span>
                            </span>
                            <span class="text-[10px] font-semibold text-muted">a/n PT SIRKULASI INDONESIA LESTARI</span>
                        </div>
                        <input type="text" name="bank_bca" value="{{ $storeConfig['bank_bca'] ?? '' }}"
                               class="w-full h-10 px-3.5 rounded-xl bg-white border border-line focus:border-navy text-xs font-mono font-bold text-navy">
                    </div>

                    <!-- Bank Mandiri -->
                    <div class="p-3.5 rounded-2xl bg-surface-low border border-line space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-navy text-xs uppercase tracking-wider flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 font-mono text-[10px] font-bold">MANDIRI</span>
                                <span>Bank Mandiri</span>
                            </span>
                            <span class="text-[10px] font-semibold text-muted">a/n PT SIRKULASI INDONESIA LESTARI</span>
                        </div>
                        <input type="text" name="bank_mandiri" value="{{ $storeConfig['bank_mandiri'] ?? '' }}"
                               class="w-full h-10 px-3.5 rounded-xl bg-white border border-line focus:border-navy text-xs font-mono font-bold text-navy">
                    </div>

                    <!-- Bank BRI -->
                    <div class="p-3.5 rounded-2xl bg-surface-low border border-line space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-navy text-xs uppercase tracking-wider flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-mono text-[10px] font-bold">BRI</span>
                                <span>Bank Rakyat Indonesia</span>
                            </span>
                            <span class="text-[10px] font-semibold text-muted">a/n PT SIRKULASI INDONESIA LESTARI</span>
                        </div>
                        <input type="text" name="bank_bri" value="{{ $storeConfig['bank_bri'] ?? '' }}"
                               class="w-full h-10 px-3.5 rounded-xl bg-white border border-line focus:border-navy text-xs font-mono font-bold text-navy">
                    </div>
                </div>

                <div class="pt-3 border-t border-line flex justify-end">
                    <button type="submit" class="btn btn-primary h-11 px-7 rounded-xl font-bold cursor-pointer text-xs shadow-xs">
                        Simpan Semua Pengaturan Operasional
                    </button>
                </div>
            </div>

        </form>

        <!-- KOLOM KANAN: Manajemen Voucher Kupon Member Enterprise (5 Kolom) -->
        <div class="lg:col-span-5 space-y-6 font-label text-xs">
            
            <div class="card p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-line">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gold"></span>
                        <h2 class="font-headline text-sm font-bold text-navy">
                            Voucher Promo Member ({{ count($storeConfig['coupons'] ?? []) }})
                        </h2>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 font-bold text-[10px]">
                        Tampil di /member
                    </span>
                </div>

                <!-- Daftar Tiket Voucher Cantik (Perforated Card Style) -->
                <div class="space-y-3 max-h-[380px] overflow-y-auto pr-1">
                    @forelse(($storeConfig['coupons'] ?? []) as $idx => $cp)
                        <div class="relative p-4 rounded-2xl bg-surface border border-line hover:border-navy transition duration-200 shadow-xs flex flex-col justify-between space-y-3">
                            
                            <!-- Header Voucher -->
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="px-2 py-0.5 rounded-md bg-navy text-white font-mono font-extrabold text-[11px] tracking-wider select-all">
                                        {{ $cp['code'] }}
                                    </span>
                                    <h4 class="font-headline font-bold text-navy text-sm mt-1.5 leading-snug">
                                        {{ $cp['title'] }}
                                    </h4>
                                </div>
                                <span class="px-2.5 py-1 rounded-xl bg-emerald-soft/30 text-emerald font-extrabold text-xs shrink-0">
                                    Rp {{ number_format($cp['discount'], 0, ',', '.') }} OFF
                                </span>
                            </div>

                            <!-- Footer Voucher: Syarat & Aksi -->
                            <div class="pt-2.5 border-t border-line/60 flex items-center justify-between text-[11px] text-muted">
                                <div>
                                    <span>Min. Belanja: <strong>Rp {{ number_format($cp['min_purchase'], 0, ',', '.') }}</strong></span>
                                    <span class="block text-[10px] mt-0.5 text-muted">Berlaku s/d: {{ $cp['expires'] ?? '-' }}</span>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button type="button" @click="copyToClipboard(@js($cp['code']))"
                                            class="h-7 px-2 rounded-lg bg-white border border-line hover:border-navy text-navy font-semibold text-[10px] transition cursor-pointer">
                                        <span x-text="copiedCode === @js($cp['code']) ? 'Tersalin ✓' : 'Salin'"></span>
                                    </button>

                                    <form action="{{ route('admin.settings.update') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="remove_coupon_index" value="{{ $idx }}">
                                        <button type="submit" 
                                                class="w-7 h-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition cursor-pointer" 
                                                title="Hapus Voucher ini">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-muted border-2 border-dashed border-line rounded-2xl">
                            <p class="font-bold text-navy">Belum Ada Voucher Promo</p>
                            <p class="text-[11px] text-muted mt-0.5">Buat voucher pertama di bawah untuk menarik minat pembeli.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Form Buat Voucher Promo Baru -->
                <form action="{{ route('admin.settings.update') }}" method="POST" class="pt-4 border-t border-line space-y-3.5">
                    @csrf
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-navy uppercase tracking-wider text-[11px]">+ Terbitkan Voucher Baru</span>
                        <span class="text-[10px] text-muted">Langsung aktif untuk pelanggan</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block font-bold mb-1 text-muted">Kode Voucher *</label>
                            <input type="text" name="new_coupon_code" x-model="newCode" required placeholder="SIRKULA50" 
                                   class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line focus:border-navy uppercase font-mono font-bold text-navy">
                        </div>
                        <div>
                            <label class="block font-bold mb-1 text-muted">Nilai Potongan (Rp) *</label>
                            <input type="number" name="new_coupon_discount" x-model.number="newDiscount" required placeholder="25000" 
                                   class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line focus:border-navy font-bold text-emerald">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold mb-1 text-muted">Judul Promosi Voucher *</label>
                        <input type="text" name="new_coupon_title" x-model="newTitle" required placeholder="Potongan Spesial Gajian Rp 25.000" 
                               class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:border-navy">
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block font-bold mb-1 text-muted">Min. Belanja (Rp)</label>
                            <input type="number" name="new_coupon_min" x-model.number="newMin" placeholder="200000" 
                                   class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line focus:border-navy">
                        </div>
                        <div>
                            <label class="block font-bold mb-1 text-muted">Kedaluwarsa</label>
                            <input type="date" name="new_coupon_expires" value="{{ date('Y-m-d', strtotime('+3 months')) }}" 
                                   class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line focus:border-navy text-xs">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full h-10 rounded-xl font-bold cursor-pointer text-xs shadow-xs">
                        + Terbitkan Voucher ke Member Hub
                    </button>
                </form>

            </div>

        </div>

    </div>
</div>

@endcomponent
