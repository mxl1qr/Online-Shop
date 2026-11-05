<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

/*
|--------------------------------------------------------------------------
| 1. Route Publik (Dapat Diakses Siapa Saja)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/wishlist', [ProductController::class, 'wishlist'])->name('wishlist.index');

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
    // Dashboard Member Sirkula (Ala Uniqlo /member & /dashboard)
    // Jika Admin mengakses /dashboard atau /member tanpa ?preview_member=1, arahkan ke CMS Admin
    $memberHandler = function (\Illuminate\Http\Request $request) {
        if (\Illuminate\Support\Facades\Auth::user()->isAdmin() && !$request->boolean('preview_member')) {
            return redirect()->route('admin.orders.index');
        }

        $allOrders = \Illuminate\Support\Facades\Auth::user()->orders()
            ->with(['items.product', 'items.variant'])
            ->latest()
            ->get();
        $recentOrders = $allOrders->take(5);
        return view('dashboard', compact('recentOrders', 'allOrders'));
    };

    Route::get('/dashboard', $memberHandler)->middleware('verified')->name('dashboard');
    Route::get('/member', $memberHandler)->name('member');

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
| 4. Route Area Administrator / Full CMS Panel (Wajib Login + Role Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Ringkasan Eksekutif & Analitik
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    // Pesanan & Logistik Pengiriman
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{id}', [AdminOrderController::class, 'destroyOrder'])->name('orders.destroy');

    // Produk & Varian/Stok CRUD
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{productId}/variants', [AdminProductController::class, 'storeVariant'])->name('variants.store');
    Route::patch('/variants/{id}', [AdminProductController::class, 'updateVariant'])->name('variants.update');
    Route::delete('/variants/{id}', [AdminProductController::class, 'destroyVariant'])->name('variants.destroy');

    // Kategori Koleksi CRUD
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Pelanggan & Hak Akses CRUD
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::patch('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Pengaturan Toko, Kupon & Ekspor Laporan CSV
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/firebase/snapshot', [AdminSettingController::class, 'generateFirebaseSnapshot'])->name('firebase.snapshot');
    Route::get('/export/{type}', [AdminDashboardController::class, 'exportCsv'])->name('export');
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

    // 3. Setup static assets in public/storage (Avoid symlink 403 on Wasmer phpix)
    try {
        $publicStorage = public_path('storage');
        if (is_link($publicStorage)) {
            unlink($publicStorage);
        }
        if (!is_dir($publicStorage)) {
            mkdir($publicStorage, 0755, true);
        }

        $sourceDir = storage_path('app/public');
        if (is_dir($sourceDir)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            $copiedCount = 0;
            foreach ($iterator as $item) {
                $dest = $publicStorage . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
                if ($item->isDir()) {
                    if (!is_dir($dest)) mkdir($dest, 0755, true);
                } else {
                    copy($item->getPathname(), $dest);
                    $copiedCount++;
                }
            }
            $outputs[] = "=== [3/3] STATIC STORAGE SYNC ===\nSuccessfully synced {$copiedCount} lookbook and banner assets directly into public/storage!";
        }
    } catch (\Throwable $e) {
        $outputs[] = "=== [3/3] STATIC STORAGE NOTICE ===\n" . $e->getMessage();
    }

    return response('<pre style="background:#002547;color:#34d399;padding:28px;font-family:monospace;font-size:14px;border-radius:10px;line-height:1.6;">' . implode("\n\n", $outputs) . "\n\n=== SEMUA SETUP SELESAI! Silakan buka kembali homepage https://sirkula1.wasmer.app ===</pre>");
});

/*
|--------------------------------------------------------------------------
| 6. Direct Storage Asset Fallback Route (Wasmer phpix support)
|--------------------------------------------------------------------------
*/
Route::get('/storage/{path}', function ($path) {
    $publicPath = public_path('storage/' . $path);
    if (file_exists($publicPath) && !is_dir($publicPath)) {
        return response()->file($publicPath);
    }

    $storagePath = storage_path('app/public/' . $path);
    if (file_exists($storagePath) && !is_dir($storagePath)) {
        return response()->file($storagePath);
    }

    abort(404);
})->where('path', '.*');
