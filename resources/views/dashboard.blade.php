<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keanggotaan — SIRKULA Recommerce</title>

    @include('partials.head-fonts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
@php
    $memberIdNumeric = '7131479' . str_pad((string) Auth::id(), 6, '0', STR_PAD_LEFT);
    $storeConfig = \App\Http\Controllers\AdminOrderController::getStoreConfig();
    $coupons = $storeConfig['coupons'] ?? [];
    $latestOrderWithAddress = $allOrders->first();
@endphp
<body x-data="{
          page: '{{ session('status') === 'password-updated' ? 'password' : (session('status') === 'profile-updated' ? 'details' : request('section', 'hub')) }}',
          orderSubTab: 'online',
          couponSubTab: 'online',
          editingProfile: false,
          barcodeModal: false,
          copiedCode: '',
          surveySent: false,
          prefSaved: false,
          copyVoucher(code) {
              navigator.clipboard.writeText(code);
              this.copiedCode = code;
              setTimeout(() => { if (this.copiedCode === code) this.copiedCode = ''; }, 2500);
          }
      }"
      class="bg-white text-[#1b1b1b] antialiased font-label min-h-screen flex flex-col selection:bg-navy selection:text-white">

    <!-- 1. Simplified Member Header (Persis Uniqlo /id/id/member: 56px Putih, Border #dadada, Logo Kiri + Ikon Member Kanan) -->
    <header class="sticky top-0 z-40 bg-white border-b border-line h-14 sm:h-16">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 h-full flex items-center justify-between">
            <!-- Kiri: Logo Sirkula -->
            <a href="{{ route('products.index') }}" class="flex items-center shrink-0" title="Beranda Sirkula">
                @include('partials.brand-logo', ['size' => 'md'])
            </a>

            <!-- Kanan: Pintasan Katalog + Ikon Member -->
            <div class="flex items-center gap-4">
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full bg-navy text-white hover:bg-navy-600 transition">
                        Buka CMS Admin &rarr;
                    </a>
                @endif
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-ink/80 hover:text-black transition hidden sm:inline">
                    Toko Online
                </a>
                <button type="button"
                        @click="page = 'hub'; window.scrollTo({top: 0, behavior: 'smooth'})"
                        class="w-9 h-9 rounded-full flex items-center justify-center text-[#1b1b1b] hover:bg-[#f4f4f4] transition cursor-pointer"
                        title="Keanggotaan">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Notifikasi Flash Status -->
    @if(session('status') === 'profile-updated')
        <div class="bg-navy text-white text-sm py-2.5 px-4 text-center font-medium">
            ✓ Data profil keanggotaan Anda berhasil diperbarui.
        </div>
    @elseif(session('status') === 'password-updated')
        <div class="bg-navy text-white text-sm py-2.5 px-4 text-center font-medium">
            ✓ Kata sandi akun Sirkula Anda berhasil diperbarui.
        </div>
    @endif

    <!-- 2. Main Layout Container (12-Column ITO Grid max-w-[1200px]) -->
    <main class="flex-1 w-full max-w-[1200px] mx-auto px-4 sm:px-6 pt-6 pb-14">

        <!-- ========================================================================= -->
        <!-- VIEW A: HALAMAN UTAMA KEANGGOTAAN (/member Hub — Persis Uniqlo ITO)      -->
        <!-- ========================================================================= -->
        <div x-show="page === 'hub'" class="grid grid-cols-1 lg:grid-cols-12 gap-x-8">
            
            <!-- Page Title Row (span 12) -->
            <div class="lg:col-span-12">
                <h1 class="text-[28px] sm:text-[32px] font-normal leading-[1.2] text-black my-4">
                    Keanggotaan
                </h1>
            </div>

            <!-- Main Content Column (span 8 pada Desktop = 773px, rata kiri seperti Uniqlo) -->
            <div class="lg:col-span-8">
                
                <!-- A. Kartu Barcode Keanggotaan (Kotak Putih border #dadada padding responsif) -->
                <div class="bg-white border border-line rounded-xl p-4 sm:p-6 mb-6 sm:mb-8 flex flex-col items-center justify-center text-center">
                    <h2 class="text-[20px] sm:text-[22px] font-normal leading-[1.2] text-black mb-4">
                        Barcode keanggotaan
                    </h2>

                    <!-- Gambar Barcode Code-128 + 13 Digit Numeric ID -->
                    <div class="flex flex-col items-center justify-center py-1 w-full">
                        <svg class="w-full max-w-[196px] h-[50px] sm:h-[56px] text-black" viewBox="0 0 220 56" fill="currentColor" aria-label="Barcode keanggotaan">
                            <rect x="4" y="0" width="3" height="56"/><rect x="10" y="0" width="1.5" height="56"/><rect x="15" y="0" width="4" height="56"/><rect x="22" y="0" width="2" height="56"/><rect x="27" y="0" width="1.5" height="56"/><rect x="32" y="0" width="5" height="56"/><rect x="40" y="0" width="2" height="56"/><rect x="45" y="0" width="3" height="56"/><rect x="51" y="0" width="1.5" height="56"/><rect x="56" y="0" width="4" height="56"/><rect x="63" y="0" width="2" height="56"/><rect x="68" y="0" width="3.5" height="56"/><rect x="75" y="0" width="1.5" height="56"/><rect x="80" y="0" width="5" height="56"/><rect x="88" y="0" width="2" height="56"/><rect x="93" y="0" width="1.5" height="56"/><rect x="98" y="0" width="4" height="56"/><rect x="105" y="0" width="3" height="56"/><rect x="111" y="0" width="1.5" height="56"/><rect x="116" y="0" width="4.5" height="56"/><rect x="124" y="0" width="2" height="56"/><rect x="129" y="0" width="3" height="56"/><rect x="135" y="0" width="1.5" height="56"/><rect x="140" y="0" width="5" height="56"/><rect x="148" y="0" width="2" height="56"/><rect x="153" y="0" width="3" height="56"/><rect x="159" y="0" width="1.5" height="56"/><rect x="164" y="0" width="4" height="56"/><rect x="171" y="0" width="2" height="56"/><rect x="176" y="0" width="3.5" height="56"/><rect x="183" y="0" width="1.5" height="56"/><rect x="188" y="0" width="4" height="56"/><rect x="195" y="0" width="2" height="56"/><rect x="200" y="0" width="5" height="56"/><rect x="208" y="0" width="1.5" height="56"/><rect x="213" y="0" width="3" height="56"/>
                        </svg>
                        <span class="font-mono text-[13px] tracking-[0.18em] text-black mt-1.5">
                            {{ $memberIdNumeric }}
                        </span>
                    </div>

                    <p class="text-[13px] font-normal leading-[1.35] text-black my-4 max-w-md">
                        Silakan tunjukkan barcode keanggotaan Anda di kasir saat Anda berbelanja.
                    </p>

                    <button type="button"
                            @click="barcodeModal = true"
                            class="h-8 px-4 rounded-full bg-white border border-line hover:border-black text-[13px] font-normal uppercase text-black transition cursor-pointer">
                        CETAK BARCODE
                    </button>
                </div>

                <!-- B. Primary Member Menu List (5 Item Persis Uniqlo /id/id/member) -->
                <ul class="mb-8 list-none p-0">
                    <li class="border-t border-line">
                        <button type="button" @click="page = 'orders'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="min-h-[45px] py-1 w-full flex items-center justify-between text-left text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition cursor-pointer">
                            <span>Riwayat pembelian</span>
                            @if($allOrders->count() > 0)
                                <span class="text-sm text-[#7d7d7d]">{{ $allOrders->count() }} pesanan</span>
                            @endif
                        </button>
                    </li>
                    <li class="border-t border-line">
                        <button type="button" @click="page = 'coupons'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="min-h-[45px] py-1 w-full flex items-center justify-between text-left text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition cursor-pointer">
                            <span>Kupon</span>
                            <span class="text-sm text-[#7d7d7d]">{{ count($coupons) }} tersedia</span>
                        </button>
                    </li>
                    <li class="border-t border-line">
                        <a href="{{ route('wishlist.index') }}"
                           class="min-h-[45px] py-1 w-full flex items-center justify-between text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition">
                            <span>Wishlist</span>
                        </a>
                    </li>
                    <li class="border-t border-line">
                        <button type="button" @click="page = 'reviews'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="min-h-[45px] py-1 w-full flex items-center justify-between text-left text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition cursor-pointer">
                            <span>Ulasan yang diposting</span>
                        </button>
                    </li>
                    <li class="border-t border-b border-line">
                        <button type="button" @click="page = 'survey'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="min-h-[45px] py-1 w-full flex items-center justify-between text-left text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition cursor-pointer">
                            <span>Survei layanan belanja online</span>
                        </button>
                    </li>
                </ul>

                <!-- C. Profile Settings Section Heading -->
                <h2 class="text-[24px] sm:text-[26px] font-normal leading-[1.2] text-black mb-4">
                    Pengaturan profil
                </h2>

                <!-- D. Profile Settings Menu List (Persis Uniqlo /id/id/member) -->
                <ul class="mb-8 list-none p-0">
                    <li class="border-t border-line">
                        <button type="button" @click="page = 'details'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="min-h-[45px] py-1 w-full flex items-center text-left text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition cursor-pointer">
                            Profil
                        </button>
                    </li>
                    <li class="border-t border-line">
                        <button type="button" @click="page = 'addresses'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="min-h-[45px] py-1 w-full flex items-center text-left text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition cursor-pointer">
                            Buku alamat
                        </button>
                    </li>
                    <li class="border-t border-line">
                        <button type="button" @click="page = 'password'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="min-h-[45px] py-1 w-full flex items-center text-left text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition cursor-pointer">
                            Ubah kata sandi saya
                        </button>
                    </li>
                    <li class="border-t border-line">
                        <button type="button" @click="page = 'wallet'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="min-h-[45px] py-1 w-full flex items-center text-left text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition cursor-pointer">
                            Kartu &amp; rekening bank saya
                        </button>
                    </li>
                    <li class="border-t border-line">
                        <button type="button" @click="page = 'preferences'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="min-h-[45px] py-1 w-full flex items-center text-left text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition cursor-pointer">
                            Pengaturan Preferensi
                        </button>
                    </li>
                    <li class="border-t border-b border-line">
                        <button type="button" @click="page = 'withdrawal'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="min-h-[45px] py-1 w-full flex items-center text-left text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition cursor-pointer">
                            Hapus akun SIRKULA
                        </button>
                    </li>
                </ul>

                <!-- E. Separate Logout Row (Persis Uniqlo /id/id/member) -->
                <ul class="mb-12 list-none p-0">
                    <li class="border-t border-b border-line">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="min-h-[45px] py-1 w-full flex items-center text-left text-[15px] font-normal leading-[21px] text-black hover:opacity-65 transition cursor-pointer">
                                Keluar
                            </button>
                        </form>
                    </li>
                </ul>

            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- VIEW B: SUB-HALAMAN KEANGGOTAAN (Layout 2-Kolom: 3 Col Sidebar + 9 Col)  -->
        <!-- ========================================================================= -->
        <div x-show="page !== 'hub'" x-cloak class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-2">
            
            <!-- Mobile Return Action (Persis Uniqlo Mobile Sub-Page UX: Akses Langsung Tanpa Tertimbun Sidebar) -->
            <div class="lg:hidden col-span-12 -mt-2 mb-1">
                <button type="button" 
                        @click="page = 'hub'; window.scrollTo({top: 0, behavior: 'smooth'})"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-black hover:opacity-70 transition cursor-pointer py-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Kembali ke Keanggotaan</span>
                </button>
            </div>

            <!-- Left Sidebar Navigation (span 3 pada Desktop — Disembunyikan di Mobile ala Uniqlo) -->
            <nav class="hidden lg:block lg:col-span-3 space-y-8 border-b lg:border-b-0 border-line pb-6 lg:pb-0">
                <div>
                    <h2 class="text-[20px] font-normal text-black mb-3">Keanggotaan</h2>
                    <ul class="space-y-2.5 text-[14px]">
                        <li>
                            <button type="button" @click="page = 'orders'"
                                    :class="page === 'orders' ? 'font-bold text-black underline' : 'text-ink/80 hover:text-black'"
                                    class="text-left transition cursor-pointer">
                                Riwayat pembelian
                            </button>
                        </li>
                        <li>
                            <button type="button" @click="page = 'coupons'"
                                    :class="page === 'coupons' ? 'font-bold text-black underline' : 'text-ink/80 hover:text-black'"
                                    class="text-left transition cursor-pointer">
                                Kupon
                            </button>
                        </li>
                        <li>
                            <a href="{{ route('wishlist.index') }}" class="text-ink/80 hover:text-black transition">
                                Wishlist
                            </a>
                        </li>
                        <li>
                            <button type="button" @click="page = 'reviews'"
                                    :class="page === 'reviews' ? 'font-bold text-black underline' : 'text-ink/80 hover:text-black'"
                                    class="text-left transition cursor-pointer">
                                Ulasan yang diposting
                            </button>
                        </li>
                        <li>
                            <button type="button" @click="page = 'survey'"
                                    :class="page === 'survey' ? 'font-bold text-black underline' : 'text-ink/80 hover:text-black'"
                                    class="text-left transition cursor-pointer">
                                Survei layanan belanja online
                            </button>
                        </li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-[20px] font-normal text-black mb-3">Pengaturan profil</h2>
                    <ul class="space-y-2.5 text-[14px]">
                        <li>
                            <button type="button" @click="page = 'details'"
                                    :class="page === 'details' ? 'font-bold text-black underline' : 'text-ink/80 hover:text-black'"
                                    class="text-left transition cursor-pointer">
                                Profil
                            </button>
                        </li>
                        <li>
                            <button type="button" @click="page = 'addresses'"
                                    :class="page === 'addresses' ? 'font-bold text-black underline' : 'text-ink/80 hover:text-black'"
                                    class="text-left transition cursor-pointer">
                                Buku alamat
                            </button>
                        </li>
                        <li>
                            <button type="button" @click="page = 'password'"
                                    :class="page === 'password' ? 'font-bold text-black underline' : 'text-ink/80 hover:text-black'"
                                    class="text-left transition cursor-pointer">
                                Ubah kata sandi saya
                            </button>
                        </li>
                        <li>
                            <button type="button" @click="page = 'wallet'"
                                    :class="page === 'wallet' ? 'font-bold text-black underline' : 'text-ink/80 hover:text-black'"
                                    class="text-left transition cursor-pointer">
                                Kartu &amp; rekening bank saya
                            </button>
                        </li>
                        <li>
                            <button type="button" @click="page = 'preferences'"
                                    :class="page === 'preferences' ? 'font-bold text-black underline' : 'text-ink/80 hover:text-black'"
                                    class="text-left transition cursor-pointer">
                                Pengaturan Preferensi
                            </button>
                        </li>
                        <li>
                            <button type="button" @click="page = 'withdrawal'"
                                    :class="page === 'withdrawal' ? 'font-bold text-black underline' : 'text-ink/80 hover:text-black'"
                                    class="text-left transition cursor-pointer">
                                Hapus akun SIRKULA
                            </button>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Right Main Content (span 9 pada Desktop) -->
            <div class="lg:col-span-9">

                <!-- 1. SUB-PAGE: RIWAYAT PEMBELIAN (Persis /id/id/member/orders/online-store) -->
                <div x-show="page === 'orders'" class="space-y-6">
                    <h1 class="text-[28px] sm:text-[34px] font-normal leading-[1.2] text-black">
                        Riwayat pembelian
                    </h1>

                    <!-- Horizontal Underline Tabs -->
                    <div class="flex items-center gap-8 border-b border-line">
                        <button type="button" @click="orderSubTab = 'online'"
                                :class="orderSubTab === 'online' ? 'border-b-2 border-black font-bold text-black' : 'text-[#7d7d7d] hover:text-black'"
                                class="pb-3 text-[15px] transition cursor-pointer">
                            Toko Online ({{ $allOrders->count() }})
                        </button>
                        <button type="button" @click="orderSubTab = 'items'"
                                :class="orderSubTab === 'items' ? 'border-b-2 border-black font-bold text-black' : 'text-[#7d7d7d] hover:text-black'"
                                class="pb-3 text-[15px] transition cursor-pointer">
                            Produk yang dibeli
                        </button>
                    </div>

                    <div class="text-[13px] text-ink/80 space-y-1">
                        <p>Anda dapat melihat riwayat pesanan online serta memantau status sanitasi ozon SirkulaCare dan pengiriman kurir.</p>
                        <p class="text-[#005db5] underline cursor-pointer" @click="page = 'survey'">Tentang garansi penukaran ukuran 3 hari &nearr;</p>
                    </div>

                    <hr class="border-line">

                    <!-- Tab 1: Daftar Pesanan Toko Online -->
                    <div x-show="orderSubTab === 'online'">
                        @if($allOrders->isEmpty())
                            <p class="text-[15px] text-black py-4">
                                Kami tidak memiliki pesanan untuk akun ini.
                            </p>
                        @else
                            <div class="space-y-4">
                                @foreach($allOrders as $order)
                                    <div class="border border-line rounded-xl p-5 space-y-4">
                                        <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-line text-sm">
                                            <div>
                                                <span class="font-bold text-black text-sm">{{ $order->order_number }}</span>
                                                <span class="text-[#7d7d7d] ml-2">{{ $order->created_at->format('d/m/Y H:i') }} WIB</span>
                                            </div>
                                            <div>
                                                <x-status-badge :status="$order->status" mode="long" />
                                            </div>
                                        </div>

                                        <div class="divide-y divide-[#f0f0f0]">
                                            @foreach($order->items as $item)
                                                <div class="py-2.5 flex items-center justify-between gap-4 text-sm">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-12 h-16 bg-[#f4f4f4] border border-line rounded-lg overflow-hidden shrink-0">
                                                            @if($item->product && $item->product->image)
                                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <p class="font-bold text-black text-sm">{{ $item->product_name }}</p>
                                                            <p class="text-[#7d7d7d]">{{ $item->variant_info }} &bull; {{ $item->quantity }} pcs</p>
                                                        </div>
                                                    </div>
                                                    <span class="font-bold text-black">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="pt-3 border-t border-line flex flex-wrap items-center justify-between gap-3 text-sm">
                                            <span class="text-[#7d7d7d]">Tujuan: <strong class="text-black">{{ $order->shipping_name }}</strong> ({{ $order->shipping_address }})</span>
                                            <div class="flex items-center gap-4">
                                                <span class="text-sm font-bold text-black">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                                @if($order->status === 'pending')
                                                    <a href="{{ route('checkout.success', $order->order_number) }}" class="px-4 py-2 rounded-full bg-black text-white font-bold uppercase text-xs">
                                                        Instruksi Bayar
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Tab 2: Produk yang Dibeli -->
                    <div x-show="orderSubTab === 'items'" x-cloak>
                        @if($allOrders->isEmpty())
                            <p class="text-[15px] text-black py-4">Belum ada produk yang pernah dibeli pada akun ini.</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($allOrders->flatMap->items as $item)
                                    <div class="border border-line rounded-xl p-4 flex items-center gap-4">
                                        <div class="w-16 h-20 bg-[#f4f4f4] border border-line rounded-lg overflow-hidden shrink-0">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div class="text-sm">
                                            <p class="font-bold text-black text-sm">{{ $item->product_name }}</p>
                                            <p class="text-[#7d7d7d] mt-0.5">{{ $item->variant_info }} &bull; {{ $item->quantity }} pcs</p>
                                            <p class="font-bold text-black mt-1.5">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="pt-6">
                        <button type="button" @click="page = 'hub'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="w-full sm:w-80 h-12 rounded-full border border-black bg-white hover:bg-black hover:text-white text-black font-bold text-[14px] uppercase tracking-wide transition cursor-pointer">
                            KEMBALI KE KEANGGOTAAN
                        </button>
                    </div>
                </div>

                <!-- 2. SUB-PAGE: KUPON (Persis /id/id/member/coupon/wallet-online-store) -->
                <div x-show="page === 'coupons'" x-cloak class="space-y-6">
                    <h1 class="text-[28px] sm:text-[34px] font-normal leading-[1.2] text-black">
                        Kupon
                    </h1>

                    <div class="flex items-center gap-8 border-b border-line">
                        <button type="button" @click="couponSubTab = 'online'"
                                :class="couponSubTab === 'online' ? 'border-b-2 border-black font-bold text-black' : 'text-[#7d7d7d] hover:text-black'"
                                class="pb-3 text-[14px] transition cursor-pointer">
                            TOKO ONLINE
                        </button>
                        <button type="button" @click="couponSubTab = 'retail'"
                                :class="couponSubTab === 'retail' ? 'border-b-2 border-black font-bold text-black' : 'text-[#7d7d7d] hover:text-black'"
                                class="pb-3 text-[14px] transition cursor-pointer">
                            TOKO RETAIL / POP-UP
                        </button>
                    </div>

                    <p class="text-[14px] text-black">
                        Kupon yang tersedia : {{ count($coupons) }}
                    </p>

                    <div class="divide-y divide-line border-t border-b border-line">
                        @foreach($coupons as $cp)
                            <div class="py-5 flex items-start justify-between gap-4">
                                <div class="space-y-1.5">
                                    <h3 class="text-[17px] sm:text-[18px] font-bold text-black">
                                        {{ $cp['title'] }}
                                    </h3>
                                    <p class="text-[13px] text-ink/80">
                                        Kode Voucher: <strong class="font-mono text-black">{{ $cp['code'] }}</strong> &bull; Min. Pembelian Rp {{ number_format($cp['min_purchase'], 0, ',', '.') }}
                                    </p>
                                    <p class="text-[13px] text-[#7d7d7d]">
                                        Tanggal kedaluwarsa : {{ $cp['expires'] }} 11:59 PM WIB
                                    </p>
                                    <div class="pt-1 flex items-center gap-4">
                                        <button type="button"
                                                @click="copyVoucher('{{ $cp['code'] }}')"
                                                class="text-[13px] text-[#005db5] hover:underline font-medium cursor-pointer"
                                                x-text="copiedCode === '{{ $cp['code'] }}' ? '✓ Kode Disalin ke Clipboard!' : 'Salin Kode Voucher'">
                                        </button>
                                        <a href="{{ route('cart.index') }}" class="text-[13px] text-[#005db5] hover:underline">
                                            Pakai di Keranjang &rarr;
                                        </a>
                                    </div>
                                </div>

                                <!-- Thumbnail Kupon 80x80 di Kanan (Persis Uniqlo) -->
                                <div class="w-20 h-20 rounded-xl bg-navy text-white shrink-0 flex flex-col items-center justify-center p-2 text-center border border-line">
                                    <span class="text-xs uppercase tracking-widest text-emerald-soft font-bold">SIRKULA</span>
                                    <span class="text-sm font-extrabold leading-tight mt-0.5">Rp{{ number_format(($cp['discount'] ?? 25000) / 1000, 0) }}RB</span>
                                    <span class="text-sm text-white/70 mt-0.5">VOUCHER</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-6">
                        <button type="button" @click="page = 'hub'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="w-full sm:w-80 h-12 rounded-full border border-black bg-white hover:bg-black hover:text-white text-black font-bold text-[14px] uppercase tracking-wide transition cursor-pointer">
                            KEMBALI KE KEANGGOTAAN
                        </button>
                    </div>
                </div>

                <!-- 3. SUB-PAGE: PROFIL (Persis /id/id/member/details) -->
                <div x-show="page === 'details'" x-cloak class="space-y-8">
                    <div class="border-b border-line pb-4">
                        <h1 class="text-[28px] sm:text-[34px] font-normal leading-[1.2] text-black">
                            Profil
                        </h1>
                    </div>

                    <!-- Section 1: Alamat e-mail keanggotaan -->
                    <div class="space-y-4 border-b border-line pb-8">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h2 class="text-[22px] sm:text-[24px] font-normal text-black">
                                Alamat e-mail keanggotaan
                            </h2>
                            <button type="button"
                                    @click="editingProfile = !editingProfile"
                                    class="h-8 px-4 rounded-full border border-line hover:border-black text-[12px] uppercase text-black transition cursor-pointer">
                                GANTI ALAMAT E-MAIL
                            </button>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-black">Alamat email</p>
                            <p class="text-[15px] text-ink/80 mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <!-- Section 2: Profil -->
                    <div class="space-y-5 border-b border-line pb-8">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h2 class="text-[22px] sm:text-[24px] font-normal text-black">
                                Profil
                            </h2>
                            <button type="button"
                                    @click="editingProfile = !editingProfile"
                                    class="h-8 px-4 rounded-full border border-line hover:border-black text-[12px] uppercase text-black transition cursor-pointer"
                                    x-text="editingProfile ? 'BATAL SUNTING' : 'SUNTING PROFIL'">
                            </button>
                        </div>

                        <!-- Mode Tampil (Label/Value Pairs Persis Uniqlo /id/id/member/details) -->
                        <div x-show="!editingProfile" class="space-y-4 text-[14px]">
                            @if(Auth::user()->avatar || Auth::user()->firebase_uid)
                                <div class="flex items-center gap-3 p-3.5 bg-surface-low border border-line rounded-xl">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-navy text-white flex items-center justify-center font-bold text-sm shrink-0">G</div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-ink text-sm flex items-center gap-2">
                                            <span>Akun Google Terhubung</span>
                                            <span class="text-xs bg-emerald-soft/40 text-emerald font-bold px-2 py-0.5 rounded-md">Aktif</span>
                                        </p>
                                        <p class="text-xs text-muted mt-0.5">Metode login aman Google Firebase Auth</p>
                                    </div>
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-black">Nama</p>
                                <p class="text-ink/80 mt-0.5">{{ Auth::user()->name }}</p>
                            </div>
                            <div>
                                <p class="font-bold text-black">ID Barcode Member</p>
                                <p class="text-ink/80 font-mono mt-0.5">{{ $memberIdNumeric }}</p>
                            </div>
                            <div>
                                <p class="font-bold text-black">Alamat Pengiriman Terakhir</p>
                                <p class="text-ink/80 mt-0.5">{{ $latestOrderWithAddress->shipping_address ?? 'Belum diatur (akan tersimpan saat checkout)' }}</p>
                            </div>
                            <div>
                                <p class="font-bold text-black">Nomor Telepon</p>
                                <p class="text-ink/80 mt-0.5">{{ $latestOrderWithAddress->shipping_phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="font-bold text-black">Terdaftar Sejak</p>
                                <p class="text-ink/80 mt-0.5">{{ Auth::user()->created_at->format('d F Y') }}</p>
                            </div>
                        </div>

                        <!-- Mode Sunting Profil (Terhubung ke route profile.update) -->
                        <form x-show="editingProfile" x-cloak method="post" action="{{ route('profile.update') }}" class="space-y-4 max-w-lg pt-2">
                            @csrf
                            @method('patch')
                            <div>
                                <label for="name" class="block text-sm font-bold text-black mb-1.5">Nama Lengkap *</label>
                                <input id="name" name="name" type="text" value="{{ old('name', Auth::user()->name) }}" required
                                       class="w-full h-11 px-3.5 rounded-lg border border-line text-sm focus:border-black focus:ring-2 focus:ring-emerald/20">
                                @error('name') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-bold text-black mb-1.5">Alamat Email *</label>
                                <input id="email" name="email" type="email" value="{{ old('email', Auth::user()->email) }}" required
                                       class="w-full h-11 px-3.5 rounded-lg border border-line text-sm focus:border-black focus:ring-2 focus:ring-emerald/20">
                                @error('email') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="h-11 px-6 rounded-full bg-black text-white text-xs font-bold uppercase tracking-wider hover:opacity-85 transition cursor-pointer">
                                Simpan Perubahan Profil
                            </button>
                        </form>
                    </div>

                    <div class="pt-2">
                        <button type="button" @click="page = 'hub'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="w-full sm:w-80 h-12 rounded-full border border-black bg-white hover:bg-black hover:text-white text-black font-bold text-[14px] uppercase tracking-wide transition cursor-pointer">
                            KEMBALI KE KEANGGOTAAN
                        </button>
                    </div>
                </div>

                <!-- 4. SUB-PAGE: BUKU ALAMAT -->
                <div x-show="page === 'addresses'" x-cloak class="space-y-6">
                    <div class="border-b border-line pb-4 flex items-center justify-between">
                        <h1 class="text-[28px] sm:text-[34px] font-normal leading-[1.2] text-black">
                            Buku alamat
                        </h1>
                    </div>

                    @if($latestOrderWithAddress)
                        <div class="border border-line rounded-xl p-6 space-y-2 max-w-xl">
                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-[#f4f4f4] text-black text-xs font-bold uppercase">Alamat Utama</span>
                            <h3 class="text-base font-bold text-black pt-1">{{ $latestOrderWithAddress->shipping_name }}</h3>
                            <p class="text-sm text-ink/80">{{ $latestOrderWithAddress->shipping_phone }}</p>
                            <p class="text-sm text-ink/80 leading-relaxed">{{ $latestOrderWithAddress->shipping_address }}</p>
                        </div>
                    @else
                        <p class="text-[15px] text-black py-4">
                            Belum ada alamat pengiriman yang tersimpan. Alamat Anda akan otomatis disimpan saat Anda melakukan pemesanan.
                        </p>
                    @endif

                    <div class="pt-6">
                        <button type="button" @click="page = 'hub'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="w-full sm:w-80 h-12 rounded-full border border-black bg-white hover:bg-black hover:text-white text-black font-bold text-[14px] uppercase tracking-wide transition cursor-pointer">
                            KEMBALI KE KEANGGOTAAN
                        </button>
                    </div>
                </div>

                <!-- 5. SUB-PAGE: UBAH KATA SANDI SAYA -->
                <div x-show="page === 'password'" x-cloak class="space-y-6">
                    <div class="border-b border-line pb-4">
                        <h1 class="text-[28px] sm:text-[34px] font-normal leading-[1.2] text-black">
                            Ubah kata sandi saya
                        </h1>
                    </div>

                    @if(Auth::user()->firebase_uid)
                        <div class="p-4 bg-surface-low border border-line rounded-xl text-sm text-ink/80 max-w-lg">
                            <strong class="text-ink font-bold block mb-1">Catatan Akun Google:</strong>
                            Anda terhubung melalui Google Sign-In. Pengubahan kata sandi di sini opsional atau dapat diatur bila ingin masuk via email dan sandi langsung.
                        </div>
                    @endif

                    <form method="post" action="{{ route('password.update') }}" class="space-y-4 max-w-lg">
                        @csrf
                        @method('put')
                        <div>
                            <label for="current_password" class="block text-sm font-bold text-black mb-1.5">Kata Sandi Saat Ini *</label>
                            <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                                   class="w-full h-11 px-3.5 rounded-xl border border-line text-sm focus:border-black focus:ring-2 focus:ring-emerald/20">
                            @if($errors->updatePassword->has('current_password'))
                                <span class="text-sm text-red-600 mt-1 block">{{ $errors->updatePassword->first('current_password') }}</span>
                            @endif
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-bold text-black mb-1.5">Kata Sandi Baru *</label>
                            <input id="password" name="password" type="password" required autocomplete="new-password"
                                   class="w-full h-11 px-3.5 rounded-xl border border-line text-sm focus:border-black focus:ring-2 focus:ring-emerald/20">
                            @if($errors->updatePassword->has('password'))
                                <span class="text-sm text-red-600 mt-1 block">{{ $errors->updatePassword->first('password') }}</span>
                            @endif
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-black mb-1.5">Konfirmasi Kata Sandi Baru *</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                   class="w-full h-11 px-3.5 rounded-xl border border-line text-sm focus:border-black focus:ring-2 focus:ring-emerald/20">
                        </div>
                        <button type="submit" class="h-11 px-8 rounded-full bg-black text-white text-xs font-bold uppercase tracking-wider hover:opacity-85 transition cursor-pointer">
                            Perbarui Kata Sandi
                        </button>
                    </form>

                    <div class="pt-6">
                        <button type="button" @click="page = 'hub'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="w-full sm:w-80 h-12 rounded-full border border-black bg-white hover:bg-black hover:text-white text-black font-bold text-[14px] uppercase tracking-wide transition cursor-pointer">
                            KEMBALI KE KEANGGOTAAN
                        </button>
                    </div>
                </div>

                <!-- 6. SUB-PAGE: ULASAN, SURVEI, REKENING, PREFERENSI, HAPUS AKUN -->
                <div x-show="['reviews', 'survey', 'wallet', 'preferences', 'withdrawal'].includes(page)" x-cloak class="space-y-6">
                    <div class="border-b border-line pb-4">
                        <h1 class="text-[28px] sm:text-[34px] font-normal leading-[1.2] text-black"
                            x-text="{
                                reviews: 'Ulasan yang diposting',
                                survey: 'Survei layanan belanja online',
                                wallet: 'Kartu & rekening bank saya',
                                preferences: 'Pengaturan Preferensi',
                                withdrawal: 'Hapus akun SIRKULA'
                            }[page]">
                        </h1>
                    </div>

                    <div x-show="page === 'reviews'">
                        <p class="text-[15px] text-black py-2">Anda belum memposting ulasan produk. Ulasan dapat diberikan setelah pesanan Anda berstatus Selesai.</p>
                    </div>

                    <div x-show="page === 'survey'" class="max-w-lg space-y-4">
                        <p class="text-sm text-ink/80">Bantu kami meningkatkan standar kurasi &amp; sanitasi SirkulaCare dengan memberikan masukan singkat Anda:</p>
                        <textarea rows="3" placeholder="Tuliskan pengalaman belanja Anda di Sirkula..." class="w-full p-3.5 rounded-xl border border-line text-sm"></textarea>
                        <button type="button" @click="surveySent = true" class="h-10 px-6 rounded-full bg-black text-white text-xs font-bold uppercase cursor-pointer">Kirim Survei</button>
                        <p x-show="surveySent" class="text-sm text-emerald font-bold">✓ Terima kasih atas masukan Anda untuk Sirkula!</p>
                    </div>

                    <div x-show="page === 'wallet'" class="max-w-lg space-y-3 text-sm">
                        <p class="text-ink/80">Sirkula menerima pembayaran Transfer Bank terverifikasi melalui rekening resmi berikut saat Checkout:</p>
                        <div class="p-4 border border-line rounded-xl space-y-2 text-sm">
                            <p><strong>BCA:</strong> {{ $storeConfig['bank_bca'] }}</p>
                            <p><strong>Mandiri:</strong> {{ $storeConfig['bank_mandiri'] }}</p>
                            <p><strong>BRI:</strong> {{ $storeConfig['bank_bri'] }}</p>
                        </div>
                    </div>

                    <div x-show="page === 'preferences'" class="max-w-lg space-y-4 text-sm">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" checked class="rounded border-line text-black focus:ring-2 focus:ring-emerald/20">
                            <span>Kirim notifikasi e-mail saat ada Drop Koleksi Archive terbaru</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" checked class="rounded border-line text-black focus:ring-2 focus:ring-emerald/20">
                            <span>Kirim pengingat kupon &amp; promo bebas ongkir bulanan</span>
                        </label>
                        <button type="button" @click="prefSaved = true" class="h-10 px-6 rounded-full bg-black text-white text-xs font-bold uppercase cursor-pointer">Simpan Preferensi</button>
                        <p x-show="prefSaved" class="text-sm text-emerald font-bold">✓ Preferensi notifikasi berhasil disimpan.</p>
                    </div>

                    <div x-show="page === 'withdrawal'" class="max-w-lg space-y-4">
                        <p class="text-sm text-red-700 leading-relaxed">
                            Peringatan: Menghapus akun SIRKULA akan menghapus seluruh riwayat keanggotaan dan akses kupon Anda secara permanen.
                        </p>
                        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-3" onsubmit="return confirm('Yakin ingin menghapus akun Anda secara permanen?');">
                            @csrf
                            @method('delete')
                            <input name="password" type="password" required placeholder="Masukkan kata sandi Anda untuk konfirmasi"
                                   class="w-full h-11 px-3.5 rounded-xl border border-line text-sm focus:border-emerald focus:ring-2 focus:ring-emerald/20">
                            <button type="submit" class="h-11 px-6 rounded-full bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider cursor-pointer">
                                Hapus Akun Secara Permanen
                            </button>
                        </form>
                    </div>

                    <div class="pt-6">
                        <button type="button" @click="page = 'hub'; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="w-full sm:w-80 h-12 rounded-full border border-black bg-white hover:bg-black hover:text-white text-black font-bold text-[14px] uppercase tracking-wide transition cursor-pointer">
                            KEMBALI KE KEANGGOTAAN
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Breadcrumbs Row (Persis Uniqlo: UNIQLO / Keanggotaan di bagian bawah) -->
        <nav class="mt-8 pt-4 text-[12px] text-[#7d7d7d] flex items-center gap-1.5">
            <a href="{{ route('products.index') }}" class="text-[#005db5] hover:underline">SIRKULA</a>
            <span>/</span>
            <button type="button" @click="page = 'hub'" class="text-black hover:underline cursor-pointer">Keanggotaan</button>
        </nav>

    </main>

    <!-- 3. Modal Cetak / Perbesar Barcode Member -->
    <div x-show="barcodeModal"
         x-cloak
         @keydown.escape.window="barcodeModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60">
        <div @click.outside="barcodeModal = false"
             class="bg-white border border-line rounded-2xl max-w-sm w-full p-6 text-center space-y-4 shadow-2xl">
            <h3 class="text-lg font-normal text-black">Barcode Keanggotaan SIRKULA</h3>
            <p class="text-sm text-[#7d7d7d]">{{ Auth::user()->name }} &bull; {{ Auth::user()->email }}</p>
            <div class="py-4 px-3 border border-line rounded-xl flex flex-col items-center">
                <svg class="w-56 h-20 text-black" viewBox="0 0 220 56" fill="currentColor">
                    <rect x="4" y="0" width="3" height="56"/><rect x="10" y="0" width="1.5" height="56"/><rect x="15" y="0" width="4" height="56"/><rect x="22" y="0" width="2" height="56"/><rect x="27" y="0" width="1.5" height="56"/><rect x="32" y="0" width="5" height="56"/><rect x="40" y="0" width="2" height="56"/><rect x="45" y="0" width="3" height="56"/><rect x="51" y="0" width="1.5" height="56"/><rect x="56" y="0" width="4" height="56"/><rect x="63" y="0" width="2" height="56"/><rect x="68" y="0" width="3.5" height="56"/><rect x="75" y="0" width="1.5" height="56"/><rect x="80" y="0" width="5" height="56"/><rect x="88" y="0" width="2" height="56"/><rect x="93" y="0" width="1.5" height="56"/><rect x="98" y="0" width="4" height="56"/><rect x="105" y="0" width="3" height="56"/><rect x="111" y="0" width="1.5" height="56"/><rect x="116" y="0" width="4.5" height="56"/><rect x="124" y="0" width="2" height="56"/><rect x="129" y="0" width="3" height="56"/><rect x="135" y="0" width="1.5" height="56"/><rect x="140" y="0" width="5" height="56"/><rect x="148" y="0" width="2" height="56"/><rect x="153" y="0" width="3" height="56"/><rect x="159" y="0" width="1.5" height="56"/><rect x="164" y="0" width="4" height="56"/><rect x="171" y="0" width="2" height="56"/><rect x="176" y="0" width="3.5" height="56"/><rect x="183" y="0" width="1.5" height="56"/><rect x="188" y="0" width="4" height="56"/><rect x="195" y="0" width="2" height="56"/><rect x="200" y="0" width="5" height="56"/><rect x="208" y="0" width="1.5" height="56"/><rect x="213" y="0" width="3" height="56"/>
                </svg>
                <span class="font-mono text-sm tracking-[0.2em] text-black mt-2">{{ $memberIdNumeric }}</span>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="window.print()" class="flex-1 h-10 rounded-full border border-black text-xs font-bold uppercase hover:bg-black hover:text-white transition cursor-pointer">
                    Cetak
                </button>
                <button type="button" @click="barcodeModal = false" class="flex-1 h-10 rounded-full bg-black text-white text-xs font-bold uppercase cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- 4. Minimal Utility Member Footer (Persis Uniqlo #f4f4f4 Footer di /id/id/member) -->
    <footer class="bg-[#f4f4f4] border-t border-line mt-auto">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-4 flex items-center justify-between text-[13px] text-[#1b1b1b]">
            <a href="{{ route('products.index') }}" class="hover:underline">Kembali ke Katalog Toko</a>
            <span class="text-[#7d7d7d] text-sm">Layanan SirkulaVerify &amp; SirkulaCare</span>
        </div>
        <hr class="border-line">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-4 text-[12px] text-[#7d7d7d]">
            Hak Cipta &copy; {{ date('Y') }} PT SIRKULASI INDONESIA LESTARI. Semua hak dilindungi undang-undang.
        </div>
    </footer>

</body>
</html>
