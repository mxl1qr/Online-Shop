<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // Menampilkan semua pesanan dari semua customer (bisa difilter status)
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        // Filter status jika admin memilih tab tertentu
        if ($request->has('status') && in_array($request->status, ['pending', 'processing', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);

        // Menghitung ringkasan jumlah pesanan
        $counts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    // Mengubah status pesanan (misal: setelah dicek transfernya masuk)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', "Status pesanan {$order->order_number} berhasil diubah menjadi " . strtoupper($request->status));
    }
}