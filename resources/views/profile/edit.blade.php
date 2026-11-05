<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil Member — SIRKULA Recommerce</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/sirkula-icon.svg') }}">

    @include('partials.head-fonts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-surface text-ink antialiased font-body selection:bg-navy selection:text-white">

    <!-- Header Ringkas -->
    <header class="sticky top-0 z-40 bg-white border-b border-line">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('products.index') }}" class="group">
                @include('partials.brand-logo')
            </a>
            <div class="flex items-center gap-4 font-label text-sm">
                <a href="{{ route('member') }}" class="font-bold text-navy hover:underline">&larr; Kembali ke Keanggotaan</a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
        <div class="border-b border-line pb-4">
            <span class="font-label text-xs uppercase tracking-widest text-emerald font-bold block">Keanggotaan Sirkula</span>
            <h1 class="font-headline text-2xl font-extrabold text-navy tracking-tight">Pengaturan Profil &amp; Keamanan</h1>
        </div>

        <div class="card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </main>

</body>
</html>
