<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        if ($request->filled('redirect')) {
            $request->session()->put('url.intended', $request->query('redirect'));
        } elseif (!$request->session()->has('url.intended')) {
            $previous = url()->previous();
            $appUrl = rtrim(config('app.url'), '/');
            $path = parse_url($previous, PHP_URL_PATH) ?? '/';
            $authPaths = ['/login', '/register', '/logout', '/forgot-password', '/reset-password'];

            if (
                $previous
                && (str_starts_with($previous, $appUrl) || str_starts_with($previous, $request->getSchemeAndHttpHost()))
                && !in_array($path, $authPaths, true)
                && !str_starts_with($path, '/admin')
            ) {
                $request->session()->put('url.intended', $previous);
            }
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $intended = (string) $request->session()->get('url.intended', '');
        $intendedPath = parse_url($intended, PHP_URL_PATH) ?? '';

        if ($request->user() && $request->user()->isAdmin()) {
            // Admin selalu diarahkan ke CMS Admin kecuali url.intended memang sub-halaman /admin/*
            if (!str_starts_with($intendedPath, '/admin')) {
                $request->session()->forget('url.intended');
            }
            return redirect()->intended(route('admin.orders.index', absolute: false));
        }

        // Customer tidak boleh diarahkan ke halaman /admin/* (mencegah 403 Akses Ditolak)
        if (str_starts_with($intendedPath, '/admin')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended(route('products.index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
