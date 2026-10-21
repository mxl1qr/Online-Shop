<x-guest-layout>
    <div class="mb-6">
        <h2 class="font-headline text-lg font-bold text-[#002547]">Buat Akun Sirkula Baru</h2>
        <p class="font-body text-xs text-[#73777f] mt-1">Dapatkan kemudahan checkout cepat dan pelacakan pesanan pakaian.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" class="font-label text-xs uppercase tracking-wider font-bold text-[#181c1c]" />
            <x-text-input id="name" class="block mt-1 w-full bg-[#f1f4f3] border-none text-xs rounded-none py-2.5 px-3 focus:ring-1 focus:ring-[#002547]" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 font-label text-xs text-red-600" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="Alamat Email" class="font-label text-xs uppercase tracking-wider font-bold text-[#181c1c]" />
            <x-text-input id="email" class="block mt-1 w-full bg-[#f1f4f3] border-none text-xs rounded-none py-2.5 px-3 focus:ring-1 focus:ring-[#002547]" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 font-label text-xs text-red-600" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" class="font-label text-xs uppercase tracking-wider font-bold text-[#181c1c]" />

            <x-text-input id="password" class="block mt-1 w-full bg-[#f1f4f3] border-none text-xs rounded-none py-2.5 px-3 focus:ring-1 focus:ring-[#002547]"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 font-label text-xs text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" class="font-label text-xs uppercase tracking-wider font-bold text-[#181c1c]" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-[#f1f4f3] border-none text-xs rounded-none py-2.5 px-3 focus:ring-1 focus:ring-[#002547]"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 font-label text-xs text-red-600" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full">
                Daftar Sekarang
            </x-primary-button>
        </div>

        <div class="mt-6 pt-4 border-t border-[#e0e3e2] text-center font-label text-xs text-[#73777f]">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="font-bold text-[#002547] hover:underline">
                Masuk ke Akun &rarr;
            </a>
        </div>
    </form>
</x-guest-layout>
