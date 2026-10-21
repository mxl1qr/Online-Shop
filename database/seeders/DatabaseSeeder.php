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
        // 1. Akun Admin Toko
        User::updateOrCreate(
            ['email' => 'admin@toko.com'],
            [
                'name' => 'Admin Sirkula',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Akun Customer Pembeli
        User::updateOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name' => 'Budi Pembeli',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // 3. Kategori Fashion Sirkular
        $tshirt = Category::updateOrCreate(['slug' => 'kaos-t-shirt'], ['name' => 'Kaos & T-Shirt']);
        $shirt  = Category::updateOrCreate(['slug' => 'kemeja'], ['name' => 'Kemeja Casual']);
        $jacket = Category::updateOrCreate(['slug' => 'outerwear-jaket'], ['name' => 'Outerwear & Jaket']);
        $pants  = Category::updateOrCreate(['slug' => 'celana-bawahan'], ['name' => 'Celana & Bawahan']);

        // 4. Data Produk Pakaian Sirkula Terkurasi

        // Produk 1: Heavyweight Boxy T-Shirt
        $p1 = Product::updateOrCreate(
            ['slug' => 'heavyweight-boxy-t-shirt'],
            [
                'category_id' => $tshirt->id,
                'name' => 'Heavyweight Boxy T-Shirt',
                'description' => "Kaos katun premium 24s dengan siluet boxy fit kontemporer. Telah melewati proses sanitasi ganda (deep steam & ozon) berstandar SirkulaCare.\n\nBahan: 100% Cotton Combed Heavyweight 240 GSM\nKondisi: Grade A+ (Like New, tanpa cacat)\nUkuran Fisik (Flat-lay M): Lebar Dada 56 cm, Panjang 70 cm.",
                'base_price' => 129000,
                'image' => 'products/tshirt-boxy-1.jpg',
                'is_active' => true,
            ]
        );
        $this->createVariants($p1, ['Hitam', 'Putih', 'Sage Green'], ['S', 'M', 'L', 'XL'], 12);

        // Produk 2: Vintage Washed Oversized Tee
        $p2 = Product::updateOrCreate(
            ['slug' => 'vintage-washed-oversized-tee'],
            [
                'category_id' => $tshirt->id,
                'name' => 'Vintage Washed Oversized Tee',
                'description' => "Kaos efek acid/faded wash dengan tekstur jatuh yang lembut dan estetika streetwear vintage. Hasil kurasi batch pabrik garmen ekspor.\n\nBahan: 100% Pre-washed Cotton 20s\nKondisi: Grade A (Sangat Baik, warna pudar alami estetik)\nUkuran Fisik (Flat-lay L): Lebar Dada 58 cm, Panjang 72 cm.",
                'base_price' => 119000,
                'image' => 'products/crewneck-1.jpg',
                'is_active' => true,
            ]
        );
        $this->createVariants($p2, ['Charcoal Faded', 'Khaki Sand'], ['M', 'L', 'XL'], 8);

        // Produk 3: Flannel Casual Long Sleeve Shirt
        $p3 = Product::updateOrCreate(
            ['slug' => 'flannel-casual-long-sleeve-shirt'],
            [
                'category_id' => $shirt->id,
                'name' => 'Flannel Casual Long Sleeve Shirt',
                'description' => "Kemeja flanel lengan panjang motif tartan klasik dengan serat berbulu halus yang hangat dan nyaman. Cocok untuk outer harian.\n\nBahan: 100% Brushed Cotton Flannel\nKondisi: Grade A+ (Like New, kancing orisinal lengkap)\nUkuran Fisik (Flat-lay L): Lebar Dada 55 cm, Panjang Lengan 62 cm, Panjang Badan 73 cm.",
                'base_price' => 189000,
                'image' => 'products/flannel-1.jpg',
                'is_active' => true,
            ]
        );
        $this->createVariants($p3, ['Navy Plaid', 'Forest Green Plaid'], ['S', 'M', 'L', 'XL'], 7);

        // Produk 4: Linen Blend Camp Collar Shirt
        $p4 = Product::updateOrCreate(
            ['slug' => 'linen-blend-camp-collar-shirt'],
            [
                'category_id' => $shirt->id,
                'name' => 'Linen Blend Camp Collar Shirt',
                'description' => "Kemeja santai kerah terbuka (camp collar) berbahan campuran linen alami yang breathable untuk iklim tropis. Potongan rileks.\n\nBahan: 55% Linen Alami, 45% Katun\nKondisi: Grade A+ (Pabrik Overrun tanpa cacat)\nUkuran Fisik (Flat-lay M): Lebar Dada 54 cm, Panjang 69 cm.",
                'base_price' => 179000,
                'image' => 'products/linen-shirt-1.jpg',
                'is_active' => true,
            ]
        );
        $this->createVariants($p4, ['Broken White', 'Terracotta'], ['M', 'L', 'XL'], 6);

        // Produk 5: Heavy Cotton Canvas Workwear Jacket
        $p5 = Product::updateOrCreate(
            ['slug' => 'heavy-cotton-canvas-workwear-jacket'],
            [
                'category_id' => $jacket->id,
                'name' => 'Heavy Cotton Canvas Workwear Jacket',
                'description' => "Jaket kerja kanvas katun tebal dengan kerah corduroy kontras dan resleting kuningan YKK. Material tangguh dan tahan lama.\n\nBahan: 100% Heavyweight Cotton Canvas 12oz\nKondisi: Grade A (Arsip kurasi vintage terpilih)\nUkuran Fisik (Flat-lay L): Lebar Dada 60 cm, Panjang 68 cm.",
                'base_price' => 279000,
                'image' => 'products/worker-jacket-1.jpg',
                'is_active' => true,
            ]
        );
        $this->createVariants($p5, ['Brown Duck', 'Deep Navy'], ['M', 'L', 'XL'], 5);

        // Produk 6: Minimalist Zip-Up Fleece Hoodie
        $p6 = Product::updateOrCreate(
            ['slug' => 'minimalist-zip-up-fleece-hoodie'],
            [
                'category_id' => $jacket->id,
                'name' => 'Minimalist Zip-Up Fleece Hoodie',
                'description' => "Hoodie fleece beresleting penuh dengan bagian dalam berbulu lembut dan potongan bahu drop shoulder. Hangat dan praktis.\n\nBahan: 80% Katun, 20% Poliester Daur Ulang\nKondisi: Grade A+ (Like New, karet rib kencang)\nUkuran Fisik (Flat-lay L): Lebar Dada 59 cm, Panjang 71 cm.",
                'base_price' => 229000,
                'image' => 'products/denim-jacket-1.jpg',
                'is_active' => true,
            ]
        );
        $this->createVariants($p6, ['Oatmeal Heather', 'Solid Black'], ['S', 'M', 'L', 'XL'], 9);

        // Produk 7: Pleated Wide-Leg Trousers
        $p7 = Product::updateOrCreate(
            ['slug' => 'pleated-wide-leg-trousers'],
            [
                'category_id' => $pants->id,
                'name' => 'Pleated Wide-Leg Trousers',
                'description' => "Celana panjang potongan wide-leg dengan aksen lipit (pleats) ganda di pinggang depan. Memberikan siluet drape yang rapi dan elegan.\n\nBahan: Semi-Wool Polyester Blend\nKondisi: Grade A+ (QC Passed)\nUkuran Fisik (Size 32): Lingkar Pinggang 82 cm, Panjang 102 cm, Open Leg 23 cm.",
                'base_price' => 199000,
                'image' => 'products/chino-pants-1.jpg',
                'is_active' => true,
            ]
        );
        $this->createVariants($p7, ['Olive Drab', 'Charcoal Grey'], ['S', 'M', 'L', 'XL'], 6);

        // Produk 8: Relaxed Fit Raw Denim Jeans
        $p8 = Product::updateOrCreate(
            ['slug' => 'relaxed-fit-raw-denim-jeans'],
            [
                'category_id' => $pants->id,
                'name' => 'Relaxed Fit Raw Denim Jeans',
                'description' => "Celana jeans selvedge-accent potongan lurus rileks. Siap membentuk fading unik seiring pemakaian oleh pemilik baru.\n\nBahan: 100% Rigid Raw Cotton Denim 13.5oz\nKondisi: Grade A+ (Unwashed Deadstock)\nUkuran Fisik (Size 32): Lingkar Pinggang 84 cm, Panjang 104 cm.",
                'base_price' => 249000,
                'image' => 'products/ankle-pants-1.jpg',
                'is_active' => true,
            ]
        );
        $this->createVariants($p8, ['Dark Indigo', 'Washed Blue'], ['M', 'L', 'XL'], 8);
    }

    private function createVariants(Product $product, array $colors, array $sizes, int $defaultStock): void
    {
        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                $code = strtoupper(substr(Str::slug($product->name), 0, 3) . '-' . substr(Str::slug($color), 0, 3) . "-{$size}");
                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'color' => $color,
                        'size' => $size,
                    ],
                    [
                        'stock' => $defaultStock,
                        'sku' => $code,
                        'additional_price' => 0,
                    ]
                );
            }
        }
    }
}
