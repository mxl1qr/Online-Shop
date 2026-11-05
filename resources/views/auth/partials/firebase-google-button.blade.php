@php
    $firebaseWeb = app(\App\Services\FirebaseService::class)->getWebConfig();
@endphp

<div x-data="{ loadingGoogle: false, errorMsg: '' }" class="mb-5">
    <form x-ref="firebaseForm" method="POST" action="{{ route('auth.firebase.callback') }}" class="hidden">
        @csrf
        <input type="hidden" name="id_token" x-ref="idTokenInput">
    </form>

    <button type="button"
            @click="
                errorMsg = '';
                @if($firebaseWeb['enabled'])
                    loadingGoogle = true;
                    window.sirkulaSignInWithGoogle()
                        .then((idToken) => {
                            $refs.idTokenInput.value = idToken;
                            $refs.firebaseForm.submit();
                        })
                        .catch(async (err) => {
                            if (err?.code === 'auth/unauthorized-domain' || (err?.message || '').includes('auth/unauthorized-domain')) {
                                try {
                                    const res = await fetch('{{ route('auth.firebase.authorize-domain') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({ domain: window.location.hostname })
                                    });
                                    const data = await res.json();
                                    loadingGoogle = false;
                                    if (data?.ok) {
                                        errorMsg = 'Domain ' + window.location.hostname + ' baru saja didaftarkan otomatis ke Firebase Authorized Domains. Silakan klik tombol Lanjutkan dengan Google sekali lagi!';
                                        return;
                                    }
                                } catch (_) {}
                            }
                            loadingGoogle = false;
                            errorMsg = err?.message || 'Gagal masuk dengan Google.';
                        });
                @else
                    errorMsg = 'FIREBASE_API_KEY belum diisi. Masukkan Web API Key Firebase (project: {{ $firebaseWeb['projectId'] }}) di Admin CMS -> Pengaturan atau file .env.';
                @endif
            "
            :disabled="loadingGoogle"
            class="w-full h-11 px-4 rounded-xl bg-navy hover:bg-navy-600 active:scale-[0.99] text-white font-label text-xs font-semibold tracking-wide flex items-center justify-center gap-3 transition shadow-card disabled:opacity-60 cursor-pointer">
        <!-- Official Google Logo on Crisp White Badge (Per Google Identity Branding Guidelines for Dark Surfaces) -->
        <div class="w-6 h-6 rounded-md bg-white flex items-center justify-center shrink-0 p-0.5 shadow-2xs">
            <img src="{{ asset('images/google-logo.svg') }}" alt="Google Logo" class="w-4 h-4 shrink-0" width="16" height="16" loading="eager">
        </div>
        <span x-text="loadingGoogle ? 'Menghubungkan ke Google...' : '{{ $label ?? 'Lanjutkan dengan Google' }}'"></span>
    </button>

    <template x-if="errorMsg">
        <div class="mt-2.5 p-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 font-body text-sm leading-relaxed" x-text="errorMsg"></div>
    </template>

    <div class="relative flex py-4 items-center">
        <div class="grow border-t border-line"></div>
        <span class="shrink mx-3 font-label text-xs uppercase tracking-widest text-muted font-bold">Atau dengan Email</span>
        <div class="grow border-t border-line"></div>
    </div>
</div>

@if($firebaseWeb['enabled'])
    @once
        <script type="module">
            import { initializeApp } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js";
            import { getAuth, GoogleAuthProvider, signInWithPopup, signOut } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-auth.js";

            const firebaseConfig = @js([
                'apiKey' => $firebaseWeb['apiKey'],
                'authDomain' => $firebaseWeb['authDomain'],
                'projectId' => $firebaseWeb['projectId'],
                'appId' => $firebaseWeb['appId'],
            ]);

            const app = initializeApp(firebaseConfig);
            const auth = getAuth(app);
            const provider = new GoogleAuthProvider();
            provider.setCustomParameters({ prompt: 'select_account' });

            window.sirkulaSignInWithGoogle = async function () {
                try { await signOut(auth); } catch (_) {}
                const result = await signInWithPopup(auth, provider);
                const token = await result.user.getIdToken(true);
                try { await signOut(auth); } catch (_) {}
                return token;
            };
        </script>
    @endonce
@endif
