<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="font-headline text-lg font-bold text-[#002547]">Masuk ke Akun Sirkula</h2>
        <p class="font-body text-xs text-[#73777f] mt-1">Lacak pesanan dan kelola informasi akun belanja Anda.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" class="font-label text-xs uppercase tracking-wider font-bold text-[#181c1c]" />
            <x-text-input id="email" class="block mt-1 w-full bg-[#f1f4f3] border-none text-xs rounded-none py-2.5 px-3 focus:ring-1 focus:ring-[#002547]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 font-label text-xs text-red-600" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" class="font-label text-xs uppercase tracking-wider font-bold text-[#181c1c]" />

            <x-text-input id="password" class="block mt-1 w-full bg-[#f1f4f3] border-none text-xs rounded-none py-2.5 px-3 focus:ring-1 focus:ring-[#002547]"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 font-label text-xs text-red-600" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4 font-label text-xs">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="border-[#e0e3e2] text-[#002547] focus:ring-[#002547]" name="remember">
                <span class="ms-2 text-[#73777f]">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[#73777f] hover:text-[#002547] transition" href="{{ route('password.request') }}">
                    Lupa sandi?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full">
                Masuk Sekarang
            </x-primary-button>
        </div>

        <div class="mt-6 pt-4 border-t border-[#e0e3e2] text-center font-label text-xs text-[#73777f]">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="font-bold text-[#002547] hover:underline">
                Daftar Akun Baru &rarr;
            </a>
        </div>
    </form>
</x-guest-layout>
