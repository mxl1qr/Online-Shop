{{-- Official Sirkula Brand Logo Partial (from Sirkula Material/Sirkula Logo.svg) --}}
@php
    $size = $size ?? 'md'; // 'sm' | 'md' | 'lg'
    $theme = $theme ?? 'light'; // 'light' (navy on white) | 'dark' (white/mint on navy)
    $iconClass = match($size) {
        'sm' => 'w-6 h-7',
        'lg' => 'w-9 h-11',
        default => 'w-7 h-9',
    };
    $titleClass = match($size) {
        'sm' => 'text-base',
        'lg' => 'text-xl',
        default => 'text-lg',
    };
    $fillColor = $theme === 'dark' ? '#85f9bf' : '#062a4b';
    $textColor = $theme === 'dark' ? 'text-white' : 'text-navy';
    $subColor  = $theme === 'dark' ? 'text-emerald-soft' : 'text-emerald';
@endphp
<div class="inline-flex items-center gap-2.5 select-none">
    <svg class="{{ $iconClass }} shrink-0 transition-transform duration-200 group-hover:scale-105" viewBox="165 55 170 240" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M254.066,215.446c-11.245,48.58 -82.189,40.561 -83.394,-8.95c-0.943,-38.702 -0.135,-85.558 -0.007,-92.996c1.068,-62.001 79.917,-53.553 83.458,-6.969c0.42,5.525 1.553,6.964 -3.719,8.706c-4.021,1.329 -34.766,11.489 -29.001,44.281c2.508,14.263 18.969,28.203 19.909,29.16c12.406,12.625 12.855,14.143 12.754,26.768Z" fill="{{ $fillColor }}"/>
        <path d="M292.461,113.955c42.641,5.923 36.884,40.038 36.917,77.545c0.06,67.233 2.524,76.769 -14.184,90.62c-27.118,22.48 -71.96,-0.02 -69.334,-32.593c0.289,-3.58 21.051,-4.35 30.243,-23.234c16.747,-34.406 -28.618,-54.427 -30.022,-72.746c-1.241,-16.199 8.878,-26.215 10.574,-27.894c14.58,-14.431 35.126,-11.701 35.806,-11.698Z" fill="{{ $fillColor }}"/>
    </svg>
    <div class="flex flex-col text-left">
        <span class="font-headline font-extrabold {{ $titleClass }} tracking-tight {{ $textColor }} leading-none">
            SIRKULA
        </span>
        <span class="font-label text-xs uppercase tracking-wider {{ $subColor }} font-bold mt-0.5 leading-none">
            Recommerce
        </span>
    </div>
</div>
