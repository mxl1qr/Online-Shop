    <!-- Modal Cetak Slip Pengiriman / Invoice Resmi SirkulaCare -->
    <div id="sirkula-print-modal" x-show="printOrder" x-cloak role="dialog" aria-modal="true" aria-label="Slip Pengiriman SirkulaCare" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy/60 backdrop-blur-sm">
        <div @click.outside="printOrder = null" class="bg-white border border-line rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl font-label text-sm">
            
            <!-- Slip Header -->
            <div class="flex items-center justify-between border-b border-line pb-3">
                @include('partials.brand-logo', ['size' => 'sm'])
                <span class="font-headline font-bold text-xs uppercase tracking-wider text-emerald">SLIP PENGIRIMAN SIRKULACARE</span>
            </div>

            <!-- Barcode Logistik Resmi -->
            <div class="py-2.5 px-4 bg-surface-low rounded-2xl border border-line flex flex-col items-center justify-center">
                <svg class="h-9 w-52 text-navy" viewBox="0 0 200 40" fill="currentColor">
                    <rect x="5" y="4" width="3" height="32" />
                    <rect x="11" y="4" width="2" height="32" />
                    <rect x="16" y="4" width="4" height="32" />
                    <rect x="23" y="4" width="2" height="32" />
                    <rect x="28" y="4" width="5" height="32" />
                    <rect x="36" y="4" width="2" height="32" />
                    <rect x="42" y="4" width="4" height="32" />
                    <rect x="49" y="4" width="3" height="32" />
                    <rect x="55" y="4" width="6" height="32" />
                    <rect x="64" y="4" width="2" height="32" />
                    <rect x="69" y="4" width="3" height="32" />
                    <rect x="75" y="4" width="5" height="32" />
                    <rect x="83" y="4" width="2" height="32" />
                    <rect x="88" y="4" width="4" height="32" />
                    <rect x="95" y="4" width="2" height="32" />
                    <rect x="100" y="4" width="6" height="32" />
                    <rect x="109" y="4" width="3" height="32" />
                    <rect x="115" y="4" width="2" height="32" />
                    <rect x="120" y="4" width="5" height="32" />
                    <rect x="128" y="4" width="2" height="32" />
                    <rect x="133" y="4" width="4" height="32" />
                    <rect x="140" y="4" width="2" height="32" />
                    <rect x="145" y="4" width="6" height="32" />
                    <rect x="154" y="4" width="3" height="32" />
                    <rect x="160" y="4" width="2" height="32" />
                    <rect x="165" y="4" width="5" height="32" />
                    <rect x="173" y="4" width="3" height="32" />
                    <rect x="179" y="4" width="2" height="32" />
                    <rect x="184" y="4" width="5" height="32" />
                    <rect x="192" y="4" width="3" height="32" />
                </svg>
                <span class="font-mono text-xs font-bold text-navy tracking-widest mt-1" x-text="printOrder?.number"></span>
            </div>

            <!-- Detail Pesanan -->
            <div class="space-y-2 text-xs">
                <div class="flex justify-between items-center">
                    <p><strong>Tanggal Pesan:</strong> <span x-text="printOrder?.date" class="text-muted"></span></p>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-soft/30 text-emerald font-bold text-[10px] uppercase" x-text="printOrder?.status"></span>
                </div>
                <p><strong>Metode Bayar:</strong> <span x-text="printOrder?.payment" class="font-mono font-semibold"></span></p>
                
                <hr class="border-line">
                
                <div>
                    <span class="font-bold text-muted uppercase tracking-wider text-[10px] block">Tujuan Pengiriman:</span>
                    <strong class="text-sm font-headline text-navy block mt-0.5" x-text="printOrder?.recipient"></strong>
                    <span class="text-muted block" x-text="printOrder?.phone"></span>
                    <p class="leading-relaxed mt-1 text-ink/80 bg-surface-low p-2.5 rounded-xl border border-line" x-text="printOrder?.address"></p>
                </div>

                <hr class="border-line">
                
                <div>
                    <span class="font-bold text-muted uppercase tracking-wider text-[10px] block mb-1">Daftar Pakaian (Higienis Sanitasi Ozon 100°C):</span>
                    <ul class="space-y-1.5 divide-y divide-line/60">
                        <template x-for="(it, idx) in (printOrder?.items || [])" :key="idx">
                            <li class="pt-1 flex justify-between gap-2">
                                <div class="min-w-0">
                                    <strong class="text-navy truncate block" x-text="it.name"></strong>
                                    <span class="text-muted text-[11px]" x-text="it.variant"></span>
                                </div>
                                <span class="font-bold text-navy shrink-0" x-text="it.qty + ' pcs'"></span>
                            </li>
                        </template>
                    </ul>
                </div>

                <hr class="border-line">
                
                <div class="flex justify-between items-center text-sm font-bold text-navy pt-1">
                    <span>Total Pembayaran:</span>
                    <span x-text="printOrder?.total" class="font-extrabold text-base"></span>
                </div>
            </div>

            <div class="flex gap-2 pt-2 no-print">
                <button type="button" onclick="window.print()" class="btn btn-primary flex-1 h-10 rounded-xl font-bold cursor-pointer">
                    Cetak Label Pengiriman
                </button>
                <button type="button" @click="printOrder = null" class="btn btn-outline flex-1 h-10 rounded-xl font-bold cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
