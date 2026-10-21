<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIRKULA') }} — Masuk Akun</title>

        <!-- Google Fonts Trio dari DESIGN.md -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-headline { font-family: 'Plus Jakarta Sans', sans-serif; }
            .font-body     { font-family: 'Montserrat', sans-serif; }
            .font-label    { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="bg-[#f7faf9] text-[#181c1c] antialiased font-body selection:bg-[#002547] selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 sm:pt-0 pb-12 px-4">
            <div class="mb-6">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <div class="w-full sm:max-w-md bg-white border border-[#e0e3e2] p-8">
                {{ $slot }}
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('products.index') }}" class="font-label text-xs text-[#73777f] hover:text-[#002547] transition">
                    &larr; Kembali ke Beranda Katalog
                </a>
            </div>
        </div>
    </body>
</html>
