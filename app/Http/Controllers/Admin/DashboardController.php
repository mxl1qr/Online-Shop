<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends AdminBaseController
{
    public function index()
    {
        $counts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        $totalRevenue = (float) Order::whereIn('status', ['processing', 'completed'])->sum('total_amount');
        $pendingRevenue = (float) Order::where('status', 'pending')->sum('total_amount');
        $totalStock = (int) ProductVariant::sum('stock');
        $lowStockVariants = ProductVariant::with('product')->where('stock', '<=', 1)->orderBy('stock')->get();

        $totalStockValue = (float) ProductVariant::with('product')->get()->sum(function ($v) {
            return $v->stock * (($v->product->base_price ?? 0) + $v->additional_price);
        });

        $paymentBreakdown = Order::select('payment_method', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(total_amount) as revenue'))
            ->where('status', '!=', 'cancelled')
            ->groupBy('payment_method')
            ->get();

        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $productsCount = Product::count();
        $usersCount = User::count();

        return view('admin.dashboard', compact(
            'counts',
            'totalRevenue',
            'pendingRevenue',
            'totalStock',
            'lowStockVariants',
            'totalStockValue',
            'paymentBreakdown',
            'topProducts',
            'productsCount',
            'usersCount'
        ));
    }

    public function exportCsv(Request $request, string $type)
    {
        if ($type === 'orders') {
            $orders = Order::with(['items.product', 'user'])->latest()->get();
            $filename = 'sirkula-orders-' . date('Ymd-His') . '.csv';

            $headers = [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $callback = function () use ($orders) {
                $handle = fopen('php://output', 'w');
                fputs($handle, "\xEF\xBB\xBF");
                fputcsv($handle, ['No. Order', 'Waktu', 'Pembeli', 'Email', 'No. HP', 'Alamat', 'Metode Bayar', 'Status', 'Total Biaya', 'Item Pakaian']);

                foreach ($orders as $order) {
                    $itemNames = $order->items->map(function ($it) {
                        return "{$it->product_name} ({$it->variant_info}) x{$it->quantity}";
                    })->implode(' | ');

                    fputcsv($handle, [
                        $order->order_number,
                        $order->created_at->format('Y-m-d H:i:s'),
                        $order->shipping_name,
                        $order->user ? $order->user->email : '-',
                        $order->shipping_phone,
                        $order->shipping_address,
                        strtoupper($order->payment_method),
                        $order->status,
                        $order->total_amount,
                        $itemNames,
                    ]);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        if ($type === 'inventory') {
            $variants = ProductVariant::with('product.category')->orderBy('stock')->get();
            $filename = 'sirkula-inventory-sku-' . date('Ymd-His') . '.csv';

            $headers = [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($variants) {
                $handle = fopen('php://output', 'w');
                fputs($handle, "\xEF\xBB\xBF");
                fputcsv($handle, ['SKU', 'Nama Produk', 'Kategori', 'Size', 'Warna', 'Stok', 'Harga Jual', 'Status Produk']);

                foreach ($variants as $v) {
                    $price = ($v->product->base_price ?? 0) + $v->additional_price;
                    fputcsv($handle, [
                        $v->sku,
                        $v->product ? $v->product->name : '-',
                        ($v->product && $v->product->category) ? $v->product->category->name : '-',
                        $v->size,
                        $v->color,
                        $v->stock,
                        $price,
                        ($v->product && $v->product->is_active) ? 'Aktif' : 'Arsip',
                    ]);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        abort(404, 'Tipe ekspor CSV tidak dikenali.');
    }
}
