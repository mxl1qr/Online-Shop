<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FirebaseAuthController extends Controller
{
    public function callback(Request $request, FirebaseService $firebaseService): RedirectResponse
    {
        $request->validate([
            'id_token' => ['required', 'string'],
        ]);

        try {
            $claims = $firebaseService->verifyIdToken((string) $request->input('id_token'));
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Autentikasi Google Firebase gagal: ' . $e->getMessage()]);
        }

        // Prioritaskan pencarian berdasarkan firebase_uid, lalu email
        $user = User::where('firebase_uid', $claims['uid'])->first()
            ?? User::where('email', $claims['email'])->first();

        if (!$user) {
            $user = User::create([
                'name'              => $claims['name'],
                'email'             => $claims['email'],
                'firebase_uid'      => $claims['uid'],
                'avatar'            => $claims['picture'],
                'password'          => Hash::make(Str::random(32)),
                'role'              => 'customer',
                'email_verified_at' => now(),
            ]);
        } else {
            $updates = [];
            if ($user->firebase_uid !== $claims['uid']) {
                $updates['firebase_uid'] = $claims['uid'];
            }
            if (!empty($claims['picture']) && $user->avatar !== $claims['picture']) {
                $updates['avatar'] = $claims['picture'];
            }
            if (empty($user->email_verified_at) && $claims['email_verified']) {
                $updates['email_verified_at'] = now();
            }
            if (!empty($updates)) {
                $user->update($updates);
            }
        }

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        $intended = (string) $request->session()->get('url.intended', '');
        $intendedPath = parse_url($intended, PHP_URL_PATH) ?? '';

        if ($user->isAdmin()) {
            if (!str_starts_with($intendedPath, '/admin')) {
                $request->session()->forget('url.intended');
            }
            return redirect()->intended(route('admin.orders.index', absolute: false))
                ->with('success', 'Berhasil masuk ke CMS Admin menggunakan Google (' . $user->email . ').');
        }

        // Pastikan Customer Google tidak pernah dilempar ke /admin/* (mencegah error 403)
        if (str_starts_with($intendedPath, '/admin')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended(route('products.index', absolute: false))
            ->with('success', 'Selamat datang di Sirkula, ' . $user->name . '!');
    }

    public function authorizeDomain(Request $request, FirebaseService $firebaseService)
    {
        $domain = trim((string) $request->input('domain', $request->getHost()));
        if ($domain === '') {
            return response()->json(['ok' => false, 'message' => 'Domain kosong.'], 422);
        }

        try {
            $domains = $firebaseService->syncAuthorizedDomains([$domain]);
            return response()->json([
                'ok'      => true,
                'domain'  => $domain,
                'domains' => $domains,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok'      => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
