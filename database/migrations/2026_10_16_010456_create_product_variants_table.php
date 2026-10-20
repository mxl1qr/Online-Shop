<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            // Relasi ke produk induk
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('size');  // Contoh: 'S', 'M', 'L', 'XL', 'XXL'
            $table->string('color'); // Contoh: 'Hitam', 'Putih', 'Sage Green'
            $table->integer('stock')->default(0); // Stok spesifik untuk warna & ukuran ini
            $table->string('sku')->nullable(); // Kode barcode / SKU toko (opsional)
            $table->integer('additional_price')->default(0); // Jika ukuran XXL ada tambahan harga misal +10.000
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
