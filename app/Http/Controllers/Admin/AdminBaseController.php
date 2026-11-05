<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\View;

class AdminBaseController extends Controller
{
    public function __construct()
    {
        // Bagikan data count sidebar secara efisien ke layout admin
        if (!app()->runningInConsole()) {
            View::share('sidebarCounts', [
                'pending'    => Order::where('status', 'pending')->count(),
                'products'   => Product::count(),
                'categories' => Category::count(),
                'users'      => User::count(),
            ]);

            View::share('lowStockCount', ProductVariant::where('stock', '<=', 1)->count());
        }
    }

    public static function getStoreConfig(): array
    {
        $path = storage_path('app/store_config.json');
        if (!file_exists($path)) {
            $defaultConfig = [
                'announcement'            => '100% Terkurasi & Higienis (Sanitized) • Garansi Keaslian SirkulaVerify • Gratis Ongkir min. Rp250.000',
                'free_shipping_threshold' => 250000,
                'bank_bca'                => '123-456-7890 a/n PT SIRKULASI INDONESIA LESTARI',
                'bank_mandiri'            => '900-00-1234567-8 a/n PT SIRKULASI INDONESIA LESTARI',
                'bank_bri'                => '0341-01-000789-30-5 a/n PT SIRKULASI INDONESIA LESTARI',
                'whatsapp_cs'             => '6281234567890',
                'firebase_api_key'        => (string) env('FIREBASE_API_KEY', ''),
                'coupons'                 => [
                    ['code' => 'SIRKULAFREE', 'title' => 'Thank You Coupon - Rp25.000', 'discount' => 25000, 'min_purchase' => 200000, 'expires' => '31/12/2026'],
                    ['code' => 'ARCHIVE55', 'title' => 'Diskon Koleksi Archive - Rp55.000', 'discount' => 55000, 'min_purchase' => 450000, 'expires' => '31/12/2026'],
                ],
            ];
            @file_put_contents($path, json_encode($defaultConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            return $defaultConfig;
        }

        $content = (string) file_get_contents($path);
        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : [];
    }
}
