<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Akun Customer untuk uji coba belanja
        User::create([
            'name' => 'Budi Pembeli',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // 3. Kategori Pakaian
        $tshirt = Category::create(['name' => 'Kaos & T-Shirt', 'slug' => 'kaos-t-shirt']);
        $shirt  = Category::create(['name' => 'Kemeja', 'slug' => 'kemeja']);
        $hoodie = Category::create(['name' => 'Hoodie & Jaket', 'slug' => 'hoodie-jaket']);

        // 4. Contoh Produk 1: Kaos Oversize
        $kaos = Product::create([
            'category_id' => $tshirt->id,
            'name' => 'Heavyweight Boxy T-Shirt',
            'slug' => 'heavyweight-boxy-t-shirt',
            'description' => 'Kaos katun 24s dengan potongan boxy fit yang nyaman dan trendi.',
            'base_price' => 129000,
            'image' => null,
            'is_active' => true,
        ]);

        // Varian ukuran & warna untuk Kaos
        $sizes = ['S', 'M', 'L', 'XL'];
        foreach ($sizes as $size) {
            ProductVariant::create([
                'product_id' => $kaos->id,
                'size' => $size,
                'color' => 'Hitam',
                'stock' => 10,
                'sku' => "BOXY-BLK-{$size}",
            ]);
            ProductVariant::create([
                'product_id' => $kaos->id,
                'size' => $size,
                'color' => 'Putih',
                'stock' => 5,
                'sku' => "BOXY-WHT-{$size}",
            ]);
        }

        // 5. Contoh Produk 2: Kemeja Flanel
        $flanel = Product::create([
            'category_id' => $shirt->id,
            'name' => 'Flannel Casual Long Sleeve Shirt',
            'slug' => 'flannel-casual-long-sleeve-shirt',
            'description' => 'Kemeja flanel lengan panjang motif kotak klasik berbahan lembut.',
            'base_price' => 189000,
            'image' => null,
            'is_active' => true,
        ]);

        foreach (['M', 'L', 'XL'] as $size) {
            ProductVariant::create([
                'product_id' => $flanel->id,
                'size' => $size,
                'color' => 'Navy Plaid',
                'stock' => 8,
                'sku' => "FLN-NVY-{$size}",
            ]);
        }
    }
}