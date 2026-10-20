<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // 1. Tampilkan Halaman Form Checkout
    public function index()
    {
        $cart = session()->get('cart', []);

        // Jika keranjang kosong, kembalikan ke halaman katalog
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang Anda masih kosong.');
        }

        // Hitung total belanja
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    // 2. Proses Transaksi & Simpan Pesanan (DB Transaction)
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja kosong.');
        }

        // Validasi data pengiriman pembeli
        $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_notes'   => 'nullable|string',
            'payment_method'   => 'required|string',
        ]);

        // Hitung total harga
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // BUNGKUS DENGAN DB::transaction AGAR AMAN DARI RACE CONDITION & ERROR
        try {
            $order = DB::transaction(function () use ($request, $cart, $totalAmount) {
                
                // A. Buat Header Pesanan (Order)
                $order = Order::create([
                    'order_number'     => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                    'user_id'          => Auth::id(),
                    'total_amount'     => $totalAmount,
                    'status'           => 'pending',
                    'shipping_name'    => $request->shipping_name,
                    'shipping_phone'   => $request->shipping_phone,
                    'shipping_address' => $request->shipping_address,
                    'shipping_notes'   => $request->shipping_notes,
                    'payment_method'   => $request->payment_method,
                ]);

                // B. Simpan Tiap Item & Kurangi Stok Varian Baju
                foreach ($cart as $item) {
                    $variant = ProductVariant::lockForUpdate()->find($item['variant_id']);

                    // Validasi ketersediaan stok saat detik-detik checkout
                    if (!$variant || $variant->stock < $item['quantity']) {
                        throw new \Exception("Maaf, stok untuk {$item['product_name']} ({$item['color']} - {$item['size']}) tidak mencukupi.");
                    }

                    // Potong stok varian baju
                    $variant->decrement('stock', $item['quantity']);

                    // Catat Order Item
                    OrderItem::create([
                        'order_id'           => $order->id,
                        'product_id'         => $variant->product_id,
                        'product_variant_id' => $variant->id,
                        'product_name'       => $item['product_name'],
                        'variant_info'       => "Warna: {$item['color']} | Ukuran: {$item['size']}",
                        'quantity'           => $item['quantity'],
                        'unit_price'         => $item['price'],
                        'subtotal'           => $item['price'] * $item['quantity'],
                    ]);
                }

                return $order;
            });

            // C. Kosongkan Keranjang Belanja di Session
            session()->forget('cart');

            // D. Arahkan ke halaman sukses / invoice transfer
            return redirect()->route('checkout.success', $order->order_number)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            // Jika ada error apa pun, DB otomatis membatalkan pemotongan stok
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    // 3. Halaman Konfirmasi Pesanan & Instruksi Transfer Bank
    public function success($orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        return view('checkout.success', compact('order'));
    }
}