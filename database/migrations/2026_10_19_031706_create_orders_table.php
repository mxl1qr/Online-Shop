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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Contoh: "ORD-20261019-ABCD"
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Relasi ke pembeli
            $table->unsignedBigInteger('total_amount'); // Total nominal belanja
            $table->string('status')->default('pending'); // 'pending', 'processing', 'completed', 'cancelled'
            
            // Data Pengiriman
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->text('shipping_address');
            $table->text('shipping_notes')->nullable();
            
            // Metode Pembayaran
            $table->string('payment_method')->default('bank_transfer');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
