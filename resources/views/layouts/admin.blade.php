<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sirkula Admin' }} — Control Panel</title>

    @include('partials.head-fonts')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body x-data="{
          confirmModal: {
              open: false,
              title: '',
              message: '',
              formAction: '',
              formMethod: 'POST',
              confirmText: 'Ya, Hapus'
          },
          openConfirm(title, message, action, method = 'POST', confirmText = 'Ya, Hapus') {
              this.confirmModal.title = title;
              this.confirmModal.message = message;
              this.confirmModal.formAction = action;
              this.confirmModal.formMethod = method;
              this.confirmModal.confirmText = confirmText;
              this.confirmModal.open = true;
          }
      }"
      class="bg-canvas text-ink antialiased font-body min-h-screen flex flex-col selection:bg-navy selection:text-white">

    <!-- MASTER NAVBAR: Clean, Eye-Friendly Enterprise Design -->
    <header class="bg-white sticky top-0 z-40 border-b border-line shadow-xs">
        
        <!-- TIER 1: Clean Minimalist Top Utility Bar -->
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between gap-4">
            
            <!-- Brand Logo & Context Label -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 shrink-0" title="Sirkula Admin Panel">
                    @include('partials.brand-logo', ['size' => 'sm', 'theme' => 'light'])
                </a>
                <span class="h-4 w-px bg-line hidden sm:inline-block"></span>
                <span class="hidden sm:inline-block text-xs font-semibold text-muted font-label uppercase tracking-wider">
                    Enterprise CMS
                </span>
            </div>

            <!-- Central Operational Status Indicator (Shopify-style System Monitor) -->
            <div class="hidden md:flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-soft/20 border border-emerald/20 text-emerald text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald animate-pulse"></span>
                <span>Toko Aktif</span>
                <span class="text-emerald/40">&bull;</span>
                <span class="text-ink/70 font-medium">Sistem Normal v2.2</span>
            </div>

            <!-- Utility Controls: Simple, Uncluttered, No Embel-Embel -->
            <div class="flex items-center gap-3 font-label text-xs">
                <!-- Link Toko Publik -->
                <a href="{{ route('products.index') }}" target="_blank" 
                   class="h-8 px-2.5 rounded-lg text-muted hover:text-navy hover:bg-surface-low transition flex items-center gap-1.5 font-semibold"
                   title="Buka Website Toko Publik di Tab Baru">
                    <span>Lihat Toko</span>
                    <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <span class="h-4 w-px bg-line"></span>

                <!-- Admin Profile Menu -->
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-navy/10 text-navy font-extrabold text-[11px] flex items-center justify-center shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="font-semibold text-navy max-w-[140px] truncate hidden sm:inline">{{ Auth::user()->name }}</span>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                        @csrf
                        <button type="submit" class="text-[11px] font-semibold text-muted hover:text-danger hover:underline cursor-pointer transition px-1 py-0.5" title="Keluar">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TIER 2: Eye-Friendly Symmetrical Segmented Navigation Tabs Bar (Centered) -->
        <div class="bg-[#fafbfa] border-t border-line/60 px-4 sm:px-6 lg:px-8 py-2">
            <div class="max-w-[1440px] mx-auto flex justify-center overflow-x-auto scrollbar-none">
                
                <nav class="inline-flex items-center p-1 bg-surface-low/90 rounded-xl border border-line/70 gap-1 font-label text-xs">
                    <!-- Tab: Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                       class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-2 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-navy font-bold shadow-xs border border-line/70' : 'text-muted hover:text-navy hover:bg-white/60 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Dashboard</span>
                        @if(isset($lowStockCount) && $lowStockCount > 0)
                            <span class="px-1.5 py-0.2 rounded-md bg-red-100 text-red-700 font-extrabold text-[10px]" title="{{ $lowStockCount }} varian stok menipis">{{ $lowStockCount }}</span>
                        @endif
                    </a>

                    <!-- Tab: Pesanan -->
                    <a href="{{ route('admin.orders.index') }}"
                       class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-2 shrink-0 {{ request()->routeIs('admin.orders.*') ? 'bg-white text-navy font-bold shadow-xs border border-line/70' : 'text-muted hover:text-navy hover:bg-white/60 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Pesanan &amp; Logistik</span>
                        @if(isset($sidebarCounts['pending']) && $sidebarCounts['pending'] > 0)
                            <span class="px-2 py-0.5 rounded-full font-bold bg-amber-100 text-amber-800 text-[10px]">
                                {{ $sidebarCounts['pending'] }}
                            </span>
                        @endif
                    </a>

                    <!-- Tab: Katalog & Inventori -->
                    <a href="{{ route('admin.products.index') }}"
                       class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-2 shrink-0 {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.variants.*') ? 'bg-white text-navy font-bold shadow-xs border border-line/70' : 'text-muted hover:text-navy hover:bg-white/60 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Katalog &amp; Stok</span>
                        @if(isset($sidebarCounts['products']))
                            <span class="px-1.5 py-0.5 rounded-md bg-white border border-line/60 text-muted text-[10px] font-semibold">{{ $sidebarCounts['products'] }}</span>
                        @endif
                    </a>

                    <!-- Tab: Kategori Koleksi -->
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-2 shrink-0 {{ request()->routeIs('admin.categories.*') ? 'bg-white text-navy font-bold shadow-xs border border-line/70' : 'text-muted hover:text-navy hover:bg-white/60 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>Kategori</span>
                    </a>

                    <!-- Tab: Pengguna & Hak Akses -->
                    <a href="{{ route('admin.users.index') }}"
                       class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-2 shrink-0 {{ request()->routeIs('admin.users.*') ? 'bg-white text-navy font-bold shadow-xs border border-line/70' : 'text-muted hover:text-navy hover:bg-white/60 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Pengguna &amp; Admin</span>
                    </a>

                    <!-- Tab: Pengaturan Toko -->
                    <a href="{{ route('admin.settings.index') }}"
                       class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-2 shrink-0 {{ request()->routeIs('admin.settings.*') ? 'bg-white text-navy font-bold shadow-xs border border-line/70' : 'text-muted hover:text-navy hover:bg-white/60 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Pengaturan Toko</span>
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- MAIN BODY CONTENT -->
    <main class="flex-1 max-w-[1440px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <!-- Flash Alert Notifikasi Toast / Card -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-5 p-4 rounded-2xl bg-emerald-soft/30 border border-emerald/20 text-emerald text-sm flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium font-body">{{ session('success') }}</span>
                </div>
                <button type="button" @click="show = false" class="text-emerald hover:text-navy cursor-pointer">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-5 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium font-body">{{ session('error') }}</span>
                </div>
                <button type="button" @click="show = false" class="text-red-700 hover:text-navy cursor-pointer">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" class="mb-5 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <strong class="font-bold font-headline flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Terdapat Kesalahan Input Data</span>
                    </strong>
                    <button type="button" @click="show = false" class="text-red-700 hover:text-navy cursor-pointer">&times;</button>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 font-label">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Global Accessible Confirmation Modal (Vanilla Alpine) -->
    <div x-show="confirmModal.open" 
         x-cloak 
         class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-navy/60 backdrop-blur-xs"
         @keydown.escape.window="confirmModal.open = false">
        <div class="card p-6 max-w-md w-full space-y-4 shadow-float animate-in fade-in zoom-in-95 duration-150"
             @click.away="confirmModal.open = false">
            <h3 class="font-headline font-extrabold text-lg text-navy" x-text="confirmModal.title"></h3>
            <p class="font-body text-sm text-muted leading-relaxed" x-text="confirmModal.message"></p>
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-line">
                <button type="button" 
                        @click="confirmModal.open = false" 
                        class="btn btn-outline h-10 px-4 rounded-xl text-xs font-bold cursor-pointer">
                    Batal
                </button>
                <form :action="confirmModal.formAction" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="_method" :value="confirmModal.formMethod">
                    <button type="submit" 
                            class="btn btn-primary h-10 px-4 rounded-xl text-xs font-bold cursor-pointer bg-red-600 hover:bg-red-700 border-red-600 text-white"
                            x-text="confirmModal.confirmText">
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Minimalist Footer -->
    <footer class="border-t border-line bg-white py-4 mt-auto">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-muted font-label">
            <span>&copy; {{ date('Y') }} PT Sirkulasi Indonesia Lestari &bull; Sirkula CMS Enterprise</span>
            <span>Versi Produksi 2.2.0</span>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
