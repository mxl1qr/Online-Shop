<x-guest-layout>
    <div class="bg-white border border-line rounded-2xl shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-12">

        <!-- KOLOM KIRI (7 Kolom): Form Pendaftaran Member -->
        <div class="md:col-span-7 p-6 sm:p-9">
            <div class="mb-6">
                <span class="font-label text-xs uppercase tracking-widest text-emerald font-bold block mb-1">
                    Pendaftaran Keanggotaan
                </span>
                <h1 class="font-headline text-xl sm:text-2xl font-extrabold text-navy tracking-tight">
                    Buat Akun Sirkula Baru
                </h1>
                <p class="font-body text-sm text-muted mt-1">
                    Daftar instan dengan Google atau lengkapi formulir di bawah ini.
                </p>
            </div>

            @include('auth.partials.firebase-google-button', ['label' => 'Daftar Cepat dengan Google'])

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" value="Nama Lengkap *" class="font-label text-sm font-bold text-ink" />
                    <x-text-input id="name" class="block mt-1.5 w-full rounded-lg bg-surface-low border border-line-strong focus:border-navy focus:bg-white text-sm py-2.5 px-3.5 focus:ring-2 focus:ring-emerald/20" type="text" name="name" :value="old('name')" placeholder="Nama lengkap Anda" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1.5 font-label text-sm text-red-600" />
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" value="Alamat Email *" class="font-label text-sm font-bold text-ink" />
                    <x-text-input id="email" class="block mt-1.5 w-full rounded-lg bg-surface-low border border-line-strong focus:border-navy focus:bg-white text-sm py-2.5 px-3.5 focus:ring-2 focus:ring-emerald/20" type="email" name="email" :value="old('email')" placeholder="nama@email.com" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5 font-label text-sm text-red-600" />
                </div>

                <!-- Password & Confirm Password Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="password" value="Kata Sandi *" class="font-label text-sm font-bold text-ink" />
                        <x-text-input id="password" class="block mt-1.5 w-full rounded-lg bg-surface-low border border-line-strong focus:border-navy focus:bg-white text-sm py-2.5 px-3.5 focus:ring-2 focus:ring-emerald/20"
                                      type="password"
                                      name="password"
                                      placeholder="Min. 8 karakter"
                                      required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 font-label text-sm text-red-600" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Ulangi Sandi *" class="font-label text-sm font-bold text-ink" />
                        <x-text-input id="password_confirmation" class="block mt-1.5 w-full rounded-lg bg-surface-low border border-line-strong focus:border-navy focus:bg-white text-sm py-2.5 px-3.5 focus:ring-2 focus:ring-emerald/20"
                                      type="password"
                                      name="password_confirmation"
                                      placeholder="Konfirmasi sandi"
                                      required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 font-label text-sm text-red-600" />
                    </div>
                </div>

                <div class="pt-2">
                    <x-primary-button class="w-full h-12 rounded-lg justify-center">
                        Daftar &amp; Aktifkan Member
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- KOLOM KANAN (5 Kolom): Sudah Punya Akun -->
        <div class="md:col-span-5 bg-surface border-t md:border-t-0 md:border-l border-line p-6 sm:p-9 flex flex-col justify-between">
            <div class="space-y-4">
                <span class="font-label text-xs uppercase tracking-widest text-emerald font-bold block">
                    Keuntungan Member Sirkula
                </span>
                <h2 class="font-headline text-lg font-extrabold text-navy">
                    Satu Akun untuk Seluruh Layanan Sirkular
                </h2>
                <p class="font-body text-sm text-ink/80 leading-relaxed">
                    Setiap akun member langsung mendapatkan 3 kupon aktif (Gratis Ongkir, Potongan Archive 55%, dan Garansi Tukar Ukuran 3 Hari) serta pelacakan jejak penghematan air bersih.
                </p>
            </div>

            <div class="pt-6 mt-6 border-t border-line space-y-3">
                <p class="font-label text-sm text-muted">Sudah memiliki akun Sirkula?</p>
                <a href="{{ route('login') }}"
                   class="w-full h-12 rounded-lg border-2 border-navy bg-white hover:bg-navy hover:text-white text-navy font-label text-xs uppercase tracking-widest font-bold flex items-center justify-center transition">
                    Masuk ke Akun
                </a>
            </div>
        </div>

    </div>
</x-guest-layout>
