<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class OrderController extends AdminBaseController
{
    public function index(Request $request)
    {
        // Backward-compatibility: arahkan parameter ?tab= ke sub-halaman mandiri
        if ($request->query('tab') === 'dashboard') {
            return redirect()->route('admin.dashboard');
        }
        if ($request->query('tab') === 'products') {
            return redirect()->route('admin.products.index');
        }
        if ($request->query('tab') === 'categories') {
            return redirect()->route('admin.categories.index');
        }
        if ($request->query('tab') === 'users') {
            return redirect()->route('admin.users.index');
        }
        if ($request->query('tab') === 'settings') {
            return redirect()->route('admin.settings.index');
        }

        $orderQuery = Order::with(['user', 'items.product'])->latest();

        if ($request->filled('status') && in_array($request->status, ['pending', 'processing', 'completed', 'cancelled'], true)) {
            $orderQuery->where('status', $request->status);
        }

        if ($request->filled('q_order')) {
            $q = $request->q_order;
            $orderQuery->where(function ($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhere('shipping_name', 'like', "%{$q}%")
                    ->orWhere('shipping_phone', 'like', "%{$q}%");
            });
        }

        $orders = $orderQuery->paginate(15)->withQueryString();

        $counts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        if ($oldStatus === $newStatus) {
            return back()->with('info', "Status pesanan #{$order->order_number} tidak berubah.");
        }

        // LEAK GUARD: Bila pesanan dibatalkan -> kembalikan kuota stok
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    ProductVariant::where('id', $item->product_variant_id)->increment('stock', $item->quantity);
                }
            }
        }

        // LEAK GUARD: Bila pesanan dari cancelled diaktifkan lagi -> potong kuota stok kembali
        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant && $variant->stock >= $item->quantity) {
                        $variant->decrement('stock', $item->quantity);
                    }
                }
            }
        }

        $order->update(['status' => $newStatus]);

        return back()->with('success', "Status pesanan #{$order->order_number} berhasil diperbarui menjadi \"{$newStatus}\".");
    }

    public function destroyOrder($id)
    {
        $order = Order::with('items')->findOrFail($id);

        // Jika pesanan belum dibatalkan, kembalikan stok fisik pakaian
        if ($order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    ProductVariant::where('id', $item->product_variant_id)->increment('stock', $item->quantity);
                }
            }
        }

        $num = $order->order_number;
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', "Pesanan #{$num} berhasil dihapus permanen dan stok dikembalikan.");
    }
}
