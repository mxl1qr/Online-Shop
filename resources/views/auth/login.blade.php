<x-guest-layout>
    <!-- Grid 2 Kolom Sejajar ala Uniqlo Login (Tidak Memanjang ke Atas/Bawah) -->
    <div class="bg-white border border-line rounded-2xl shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-12">

        <!-- KOLOM KIRI (7 Kolom): Form Masuk Akun Sirkula -->
        <div class="md:col-span-7 p-6 sm:p-9">
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="mb-6">
                <span class="font-label text-xs uppercase tracking-widest text-emerald font-bold block mb-1">
                    Member Sirkula
                </span>
                <h1 class="font-headline text-xl sm:text-2xl font-extrabold text-navy tracking-tight">
                    Masuk ke Akun Anda
                </h1>
                <p class="font-body text-sm text-muted mt-1">
                    Masuk dengan akun Google atau alamat email yang terdaftar.
                </p>
            </div>

            @include('auth.partials.firebase-google-button', ['label' => 'Lanjutkan dengan Google'])

            <form method="POST" action="{{ route('login') }}" x-data="{ showPass: false }" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" value="Alamat Email *" class="font-label text-sm font-bold text-ink" />
                    <x-text-input id="email"
                                  class="block mt-1.5 w-full rounded-lg bg-surface-low border border-line-strong focus:border-navy focus:bg-white text-sm py-2.5 px-3.5 focus:ring-2 focus:ring-emerald/20"
                                  type="email"
                                  name="email"
                                  :value="old('email')"
                                  placeholder="nama@email.com"
                                  required
                                  autofocus
                                  autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5 font-label text-sm text-red-600" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between">
                        <x-input-label for="password" value="Kata Sandi *" class="font-label text-sm font-bold text-ink" />
                        <button type="button" @click="showPass = !showPass" class="font-label text-sm font-semibold text-muted hover:text-navy">
                            <span x-text="showPass ? 'Sembunyikan' : 'Tampilkan'">Tampilkan</span>
                        </button>
                    </div>

                    <input id="password"
                           :type="showPass ? 'text' : 'password'"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="Masukkan kata sandi"
                           class="block mt-1.5 w-full rounded-xl bg-surface-low border border-line-strong focus:border-navy focus:bg-white text-sm py-2.5 px-3.5 focus:ring-2 focus:ring-emerald/20" />

                    <x-input-error :messages="$errors->get('password')" class="mt-1.5 font-  text-sm text-red-600" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between pt-1 font-label text-sm">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded border-line-strong text-navy focus:ring-navy" name="remember">
                        <span class="ms-2 text-ink/80">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-muted hover:text-navy font-medium transition" href="{{ route('password.request') }}">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                <div class="pt-2">
                    <x-primary-button class="w-full h-12 rounded-lg justify-center">
                        Masuk ke Akun
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- KOLOM KANAN (5 Kolom): Buat Akun Baru & Keuntungan Member (Ala Uniqlo) -->
        <div class="md:col-span-5 bg-surface border-t md:border-t-0 md:border-l border-line p-6 sm:p-9 flex flex-col justify-between">
            <div class="space-y-4">
                <div>
                    <span class="font-label text-xs uppercase tracking-widest text-emerald font-bold block mb-1">
                        Pelanggan Baru
                    </span>
                    <h2 class="font-headline text-lg font-extrabold text-navy tracking-tight">
                        Buat Akun Member
                    </h2>
                    <p class="font-body text-sm text-ink/80 mt-1 leading-relaxed">
                        Daftar gratis untuk mendapatkan Kartu Barcode Member, kupon eksklusif, dan checkout lebih cepat.
                    </p>
                </div>

                <ul class="space-y-2.5 font-body text-sm text-ink/80 pt-2">
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-emerald-soft/40 text-emerald flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span><strong>Gratis Ongkir</strong> ke seluruh Indonesia min. belanja Rp 250.000</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-emerald-soft/40 text-emerald flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span><strong>Garansi Tukar Ukuran 3 Hari</strong> &amp; jaminan higienis uap ozon</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-emerald-soft/40 text-emerald flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span><strong>Kartu Member Digital &amp; Kupon</strong> hemat hingga 55% dari retail</span>
                    </li>
                </ul>
            </div>

            <div class="pt-6 mt-6 border-t border-line">
                <a href="{{ route('register') }}"
                   class="w-full h-12 rounded-lg border-2 border-navy bg-white hover:bg-navy hover:text-white text-navy font-label text-xs uppercase tracking-widest font-bold flex items-center justify-center transition">
                    Buat Akun Baru
                </a>
            </div>
        </div>

    </div>
</x-guest-layout>
