<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIRKULA') }} — Keanggotaan Member</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/sirkula-icon.svg') }}">

        <!-- Google Fonts Trio dari DESIGN.md -->
        @include('partials.head-fonts')

        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="min-h-screen flex flex-col bg-surface text-ink antialiased font-body selection:bg-navy selection:text-white">

        <!-- Header Minimalis Auth (Ala Uniqlo Auth Header) -->
        <header class="bg-white border-b border-line shrink-0">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('products.index') }}" class="group">
                    @include('partials.brand-logo')
                </a>
                <a href="{{ route('products.index') }}" class="font-label text-sm font-semibold text-ink/80 hover:text-navy transition">
                    &larr; Kembali ke Katalog
                </a>
            </div>
        </header>

        <!-- Konten Utama Terpusat Proporsional dengan Ruang Napas Atas-Bawah -->
        <main class="flex-1 flex items-center justify-center py-10 sm:py-14 px-4 sm:px-6">
            <div class="w-full max-w-4xl">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer Ringkas Auth -->
        <footer class="bg-white border-t border-line py-4 px-4 text-center font-label text-sm text-muted shrink-0">
            &copy; {{ date('Y') }} PT SIRKULASI INDONESIA LESTARI &bull; Standar Sanitasi Uap &amp; Ozon SirkulaCare
        </footer>

    </body>
</html>
