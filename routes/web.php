<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| 1. Route Publik (Dapat Diakses Siapa Saja)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| 2. Route Keranjang Belanja (Sesi Cart)
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{variantId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{variantId}', [CartController::class, 'remove'])->name('cart.remove');

/*
|--------------------------------------------------------------------------
| 3. Route Pengguna Terdaftar (Wajib Login / Customer Area)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Dashboard & Profil Bawaan Breeze
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout & Transaksi
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Riwayat Pesanan Saya
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| 4. Route Area Administrator (Wajib Login + Role Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Route Autentikasi (Breeze)
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| 5. Utility Deployment Setup Route (Untuk Wasmer / Cloud tanpa Terminal)
|--------------------------------------------------------------------------
*/
Route::get('/deploy-setup', function (\Illuminate\Http\Request $request) {
    if ($request->query('key') !== 'sirkula2026') {
        abort(403, 'Akses Ditolak: Kunci Rahasia Salah');
    }

    $outputs = [];

    // 1. Run migrations
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $outputs[] = "=== [1/3] MIGRATE ===\n" . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Throwable $e) {
        $outputs[] = "=== [1/3] MIGRATE ERROR ===\n" . $e->getMessage();
    }

    // 2. Run seeders
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $outputs[] = "=== [2/3] SEED DATABASE ===\n" . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Throwable $e) {
        $outputs[] = "=== [2/3] SEED ERROR ===\n" . $e->getMessage();
    }

    // 3. Run storage:link
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $outputs[] = "=== [3/3] STORAGE LINK ===\n" . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Throwable $e) {
        $outputs[] = "=== [3/3] STORAGE LINK NOTICE ===\n" . $e->getMessage();
    }

    return response('<pre style="background:#002547;color:#34d399;padding:28px;font-family:monospace;font-size:14px;border-radius:10px;line-height:1.6;">' . implode("\n\n", $outputs) . "\n\n=== SEMUA SETUP SELESAI! Silakan buka kembali homepage https://sirkula1.wasmer.app ===</pre>");
});
