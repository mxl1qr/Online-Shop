<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Menampilkan daftar seluruh pesanan milik user yang sedang login
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['items.product', 'items.variant'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Menampilkan detail 1 pesanan tertentu milik user
    public function show($orderNumber)
    {
        $order = Order::with(['items.product', 'items.variant'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id()) // Proteksi: pastikan order milik user yang login
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}