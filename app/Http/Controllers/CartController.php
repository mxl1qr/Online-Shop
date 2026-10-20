<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // 1. Nampilin halaman daftar isi keranjang
    public function index()
    {
        $cart = session()->get('cart',[]);

        // Hitung total belanjaan
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    // 2. Logika add barang ke keranjang
    public function add(Request $request)
    {
        // Validasi input: ensure variant_id ada dan qty min. 1
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        // Fetch variant data including main product from database
        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);

        // Cek apakah stok barang mencukupi
        if($variant->stock < $request->quantity) {
            return back()->with('error', 'Maaf, stok barang ini tidak mencukupi.');
        }

        // Fetch keranjang saat ini dari Session (jika belum ada, default array kosong[])
        $cart = session()->get('cart', []);

        // Harga final = base product price + tambahan harga varian (kalo ada)
        $price = $variant->product->base_price + $variant->additional_price;

        // Cek apakah varian baju ini SUDAH ADA di keranjang sebelumnya?
        if (isset($cart[$variant->id])) {
            // Jika sudah ada, cukup tambahkan quantity-nya
            $newQuantity = $cart[$variant->id]['quantity'] + $request->quantity;
            
            // Pastikan total gabungan tidak melebihi stok yang ada
            if ($variant->stock < $newQuantity) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
            }
            $cart[$variant->id]['quantity'] = $newQuantity;
        } else {
            // Jika BELUM ADA, buat baris item baru di keranjang
            $cart[$variant->id] = [
                'variant_id'   => $variant->id,
                'product_name' => $variant->product->name,
                'slug'         => $variant->product->slug,
                'category'     => $variant->product->category->name,
                'image'        => $variant->product->image,
                'color'        => $variant->color,
                'size'         => $variant->size,
                'price'        => $price,
                'quantity'     => (int) $request->quantity,
                'max_stock'    => $variant->stock,
            ];
        }
        // Simpan kembali array cart yang sudah diperbarui ke dalam Session
        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Baju berhasil ditambahkan ke keranjang!');
    }
    // 3. Mengubah jumlah barang (+ / -) langsung dari halaman keranjang
    public function update(Request $request, $variantId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$variantId])) {
            $variant = ProductVariant::find($variantId);
            $qty = (int) $request->quantity;
            if ($qty > 0 && $variant && $qty <= $variant->stock) {
                $cart[$variantId]['quantity'] = $qty;
                session()->put('cart', $cart);
                return back()->with('success', 'Jumlah berhasil diperbarui.');
            }
        }
        return back()->with('error', 'Gagal memperbarui jumlah.');
    }
    // 4. Menghapus satu item pakaian dari keranjang
    public function remove($variantId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$variantId])) {
            unset($cart[$variantId]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Barang dihapus dari keranjang.');
    }
}
