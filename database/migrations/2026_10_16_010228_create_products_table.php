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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel categories: jika kategori dihapus, produknya ikut terhapus (cascade)
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Contoh: "Oversized Boxy Tee"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('base_price'); // Harga dasar dalam Rupiah (contoh: 125000)
            $table->string('image')->nullable(); // Lokasi path foto utama produk
            $table->boolean('is_active')->default(true); // Status apakah tampil di katalog atau tidak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
