<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminOrderController extends Controller
{
    public static function getStoreConfig(): array
    {
        $path = storage_path('app/store_config.json');
        $defaults = [
            'announcement'            => '100% Terkurasi & Higienis (Sanitized) • Garansi Keaslian SirkulaVerify • Gratis Ongkir min. Rp250.000',
            'free_shipping_threshold' => 250000,
            'bank_bca'                => '123-456-7890 a/n PT SIRKULASI INDONESIA LESTARI',
            'bank_mandiri'            => '900-00-1234567-8 a/n PT SIRKULASI INDONESIA LESTARI',
            'bank_bri'                => '0341-01-000789-30-5 a/n PT SIRKULASI INDONESIA LESTARI',
            'whatsapp_cs'             => '6281234567890',
            'firebase_api_key'        => (string) env('FIREBASE_API_KEY', ''),
            'firebase_auth_domain'    => (string) env('FIREBASE_AUTH_DOMAIN', 'nuevauno-1.firebaseapp.com'),
            'firebase_app_id'         => (string) env('FIREBASE_APP_ID', ''),
            'coupons'                 => [
                ['code' => 'SIRKULAFREE', 'title' => 'Thank You Coupon - Rp25.000', 'discount' => 25000, 'min_purchase' => 200000, 'expires' => '31/12/2026'],
                ['code' => 'ARCHIVE55', 'title' => 'Diskon Koleksi Archive - Rp55.000', 'discount' => 55000, 'min_purchase' => 450000, 'expires' => '31/12/2026'],
                ['code' => 'CARE3DAYS', 'title' => 'Voucher SirkulaCare Sanitasi & Ongkir', 'discount' => 20000, 'min_purchase' => 150000, 'expires' => '31/12/2026'],
            ],
        ];

        if (file_exists($path)) {
            $saved = json_decode((string) file_get_contents($path), true);
            if (is_array($saved)) {
                return array_merge($defaults, $saved);
            }
        }

        return $defaults;
    }

    public function index(Request $request)
    {
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

        $orders = $orderQuery->paginate(15)->withQueryString()->appends(['tab' => 'orders']);

        $counts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        $products = Product::with(['category', 'variants'])->latest()->get();
        $categories = Category::withCount('products')->orderBy('name')->get();
        $users = User::withCount('orders')->withSum('orders', 'total_amount')->latest()->get();

        // KPI & Analytics
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

        $storeConfig = self::getStoreConfig();
        $firebaseStatus = app(FirebaseService::class)->getStatus();
        $activeTab = $request->query('tab', $request->has('status') || $request->has('q_order') ? 'orders' : 'dashboard');

        return view('admin.orders.index', compact(
            'orders',
            'counts',
            'products',
            'categories',
            'users',
            'totalRevenue',
            'pendingRevenue',
            'totalStock',
            'lowStockVariants',
            'totalStockValue',
            'paymentBreakdown',
            'topProducts',
            'storeConfig',
            'firebaseStatus',
            'activeTab'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::with('items')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return back()->with('success', "Status pesanan {$order->order_number} tetap " . strtoupper($newStatus));
        }

        try {
            DB::transaction(function () use ($order, $oldStatus, $newStatus) {
                // Kembalikan stok jika pesanan aktif dibatalkan
                if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                    foreach ($order->items as $item) {
                        if ($item->product_variant_id) {
                            ProductVariant::where('id', $item->product_variant_id)->increment('stock', $item->quantity);
                        }
                    }
                }
                // Kurangi kembali stok jika pesanan yang sudah dibatalkan diaktifkan kembali (cegah kebocoran inflasi stok)
                elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                    foreach ($order->items as $item) {
                        if ($item->product_variant_id) {
                            $variant = ProductVariant::lockForUpdate()->find($item->product_variant_id);
                            if (!$variant || $variant->stock < $item->quantity) {
                                throw new \RuntimeException("Stok varian untuk \"{$item->product_name}\" tidak mencukupi untuk mengaktifkan kembali pesanan yang telah dibatalkan.");
                            }
                            $variant->decrement('stock', $item->quantity);
                        }
                    }
                }

                $order->update(['status' => $newStatus]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Status pesanan {$order->order_number} berhasil diubah menjadi " . strtoupper($newStatus));
    }

    public function destroyOrder($id)
    {
        $order = Order::with('items')->findOrFail($id);

        DB::transaction(function () use ($order) {
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        ProductVariant::where('id', $item->product_variant_id)->increment('stock', $item->quantity);
                    }
                }
            }
            $order->delete();
        });

        return redirect()->route('admin.orders.index', ['tab' => 'orders'])
            ->with('success', "Pesanan {$order->order_number} berhasil dihapus.");
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'base_price'   => 'required|numeric|min:0',
            'description'  => 'required|string',
            'is_active'    => 'nullable|boolean',
            'image_file'   => 'nullable|image|max:4096',
            'initial_size' => 'nullable|string|max:20',
            'initial_color'=> 'nullable|string|max:50',
            'initial_stock'=> 'nullable|integer|min:0',
        ]);

        $slug = Str::slug($validated['name']) . '-' . Str::lower(Str::random(4));
        $imagePath = 'products/linen-shirt-1.jpg';

        if ($request->hasFile('image_file')) {
            $imagePath = $this->handleImageUpload($request->file('image_file'), $slug);
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'],
            'base_price'  => $validated['base_price'],
            'image'       => $imagePath,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        ProductVariant::create([
            'product_id'       => $product->id,
            'size'             => $validated['initial_size'] ?: 'L',
            'color'            => $validated['initial_color'] ?: 'Olive',
            'stock'            => $validated['initial_stock'] ?? 1,
            'sku'              => 'SRK-' . strtoupper(Str::random(6)),
            'additional_price' => 0,
        ]);

        return redirect()->route('admin.orders.index', ['tab' => 'products'])
            ->with('success', "Produk \"{$product->name}\" beserta varian awalnya berhasil ditambahkan.");
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'base_price'  => 'required|numeric|min:0',
            'description' => 'required|string',
            'is_active'   => 'nullable|boolean',
            'image_file'  => 'nullable|image|max:4096',
        ]);

        $data = [
            'name'        => $validated['name'],
            'category_id' => $validated['category_id'],
            'base_price'  => $validated['base_price'],
            'description' => $validated['description'],
            'is_active'   => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image_file')) {
            $data['image'] = $this->handleImageUpload($request->file('image_file'), $product->slug);
        }

        $product->update($data);

        return redirect()->route('admin.orders.index', ['tab' => 'products'])
            ->with('success', "Produk \"{$product->name}\" berhasil diperbarui.");
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);

        if (OrderItem::where('product_id', $product->id)->exists()) {
            return redirect()->route('admin.orders.index', ['tab' => 'products'])
                ->with('error', "Produk \"{$product->name}\" memiliki riwayat transaksi pesanan pelanggan. Demi integritas riwayat pesanan, produk ini tidak dapat dihapus permanen. Silakan nonaktifkan (arsip) status produk.");
        }

        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.orders.index', ['tab' => 'products'])
            ->with('success', "Produk \"{$name}\" berhasil dihapus.");
    }

    public function storeVariant(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $validated = $request->validate([
            'size'             => 'required|string|max:20',
            'color'            => 'required|string|max:50',
            'stock'            => 'required|integer|min:0',
            'additional_price' => 'nullable|numeric|min:0',
            'sku'              => 'nullable|string|max:60',
        ]);

        ProductVariant::create([
            'product_id'       => $product->id,
            'size'             => strtoupper($validated['size']),
            'color'            => $validated['color'],
            'stock'            => $validated['stock'],
            'additional_price' => $validated['additional_price'] ?? 0,
            'sku'              => $validated['sku'] ?: ('SRK-' . strtoupper(Str::random(6))),
        ]);

        return redirect()->route('admin.orders.index', ['tab' => 'products'])
            ->with('success', "Varian baru untuk \"{$product->name}\" berhasil ditambahkan.");
    }

    public function updateVariant(Request $request, $id)
    {
        $variant = ProductVariant::with('product')->findOrFail($id);

        $validated = $request->validate([
            'stock'            => 'required|integer|min:0',
            'additional_price' => 'nullable|numeric|min:0',
            'size'             => 'nullable|string|max:20',
            'color'            => 'nullable|string|max:50',
        ]);

        $variant->update([
            'stock'            => $validated['stock'],
            'additional_price' => $validated['additional_price'] ?? $variant->additional_price,
            'size'             => $validated['size'] ?? $variant->size,
            'color'            => $validated['color'] ?? $variant->color,
        ]);

        return back()->with('success', "Stok/Varian {$variant->sku} berhasil diperbarui menjadi {$variant->stock} pcs.");
    }

    public function destroyVariant($id)
    {
        $variant = ProductVariant::findOrFail($id);

        if (OrderItem::where('product_variant_id', $variant->id)->exists()) {
            return redirect()->route('admin.orders.index', ['tab' => 'products'])
                ->with('error', "Varian {$variant->sku} ({$variant->size}) pernah dipesan dalam transaksi pelanggan. Demi integritas audit riwayat belanja, varian tidak dapat dihapus permanen. Silakan ubah stok menjadi 0.");
        }

        if ($variant->product->variants()->count() <= 1) {
            return redirect()->route('admin.orders.index', ['tab' => 'products'])
                ->with('error', "Varian {$variant->sku} adalah satu-satunya varian untuk produk \"{$variant->product->name}\". Produk wajib memiliki minimal 1 varian.");
        }

        $sku = $variant->sku;
        $variant->delete();

        return redirect()->route('admin.orders.index', ['tab' => 'products'])
            ->with('success', "Varian {$sku} berhasil dihapus.");
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'nullable|string|max:120|unique:categories,slug',
        ]);

        $slug = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        Category::create(['name' => $validated['name'], 'slug' => $slug]);

        return redirect()->route('admin.orders.index', ['tab' => 'categories'])
            ->with('success', "Kategori \"{$validated['name']}\" berhasil dibuat.");
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'nullable|string|max:120|unique:categories,slug,' . $category->id,
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.orders.index', ['tab' => 'categories'])
            ->with('success', "Kategori \"{$category->name}\" berhasil diperbarui.");
    }

    public function destroyCategory($id)
    {
        $category = Category::withCount('products')->findOrFail($id);

        if ($category->products_count > 0) {
            return redirect()->route('admin.orders.index', ['tab' => 'categories'])
                ->with('error', "Kategori \"{$category->name}\" masih memiliki {$category->products_count} produk. Pindahkan atau hapus produknya terlebih dahulu.");
        }

        $category->delete();

        return redirect()->route('admin.orders.index', ['tab' => 'categories'])
            ->with('success', "Kategori berhasil dihapus.");
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:customer,admin',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('admin.orders.index', ['tab' => 'users'])
            ->with('success', "Pengguna \"{$validated['name']}\" ({$validated['role']}) berhasil ditambahkan.");
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'         => 'required|in:customer,admin',
            'new_password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => strtolower($validated['email']),
            'role'  => $validated['role'],
        ];

        if (!empty($validated['new_password'])) {
            $data['password'] = Hash::make($validated['new_password']);
        }

        $user->update($data);

        return redirect()->route('admin.orders.index', ['tab' => 'users'])
            ->with('success', "Data pengguna \"{$user->name}\" berhasil diperbarui.");
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.orders.index', ['tab' => 'users'])
                ->with('error', 'Anda tidak dapat menghapus akun Administrator yang sedang Anda gunakan.');
        }

        if ($user->orders()->exists()) {
            return redirect()->route('admin.orders.index', ['tab' => 'users'])
                ->with('error', "Pengguna \"{$user->name}\" memiliki riwayat pesanan pelanggan. Demi integritas audit keuangan, pengguna ini tidak dapat dihapus.");
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.orders.index', ['tab' => 'users'])
            ->with('success', "Akun pengguna \"{$name}\" berhasil dihapus.");
    }

    public function updateSettings(Request $request)
    {
        $current = self::getStoreConfig();

        $current['announcement'] = (string) $request->input('announcement', $current['announcement']);
        $current['free_shipping_threshold'] = (int) $request->input('free_shipping_threshold', $current['free_shipping_threshold']);
        $current['bank_bca'] = (string) $request->input('bank_bca', $current['bank_bca']);
        $current['bank_mandiri'] = (string) $request->input('bank_mandiri', $current['bank_mandiri']);
        $current['bank_bri'] = (string) $request->input('bank_bri', $current['bank_bri']);
        $current['whatsapp_cs'] = (string) $request->input('whatsapp_cs', $current['whatsapp_cs']);

        if ($request->has('firebase_api_key')) {
            $current['firebase_api_key'] = trim((string) $request->input('firebase_api_key'));
        }
        if ($request->has('firebase_auth_domain')) {
            $current['firebase_auth_domain'] = trim((string) $request->input('firebase_auth_domain'));
        }
        if ($request->has('firebase_app_id')) {
            $current['firebase_app_id'] = trim((string) $request->input('firebase_app_id'));
        }

        if ($request->filled('new_coupon_code') && $request->filled('new_coupon_title')) {
            $current['coupons'][] = [
                'code'         => strtoupper(trim($request->input('new_coupon_code'))),
                'title'        => trim($request->input('new_coupon_title')),
                'discount'     => (int) $request->input('new_coupon_discount', 25000),
                'min_purchase' => (int) $request->input('new_coupon_min', 150000),
                'expires'      => $request->input('new_coupon_expires', '31/12/2026'),
            ];
        }

        if ($request->filled('remove_coupon_index')) {
            $idx = (int) $request->input('remove_coupon_index');
            if (isset($current['coupons'][$idx])) {
                array_splice($current['coupons'], $idx, 1);
            }
        }

        file_put_contents(storage_path('app/store_config.json'), json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return redirect()->route('admin.orders.index', ['tab' => 'settings'])
            ->with('success', 'Pengaturan toko, rekening bank, konfigurasi Firebase, dan daftar kupon berhasil disimpan.');
    }

    public function generateFirebaseSnapshot(FirebaseService $firebase)
    {
        $result = $firebase->exportSnapshotToFile();
        $counts = $result['counts'];
        $summary = sprintf(
            '%d kategori, %d produk, %d pesanan, %d pengguna (%.2f KB)',
            $counts['categories'] ?? 0,
            $counts['products'] ?? 0,
            $counts['orders'] ?? 0,
            $counts['users'] ?? 0,
            ($result['size'] ?? 0) / 1024
        );

        return redirect()->route('admin.orders.index', ['tab' => 'settings'])
            ->with('success', "Snapshot NoSQL Firestore berhasil diperbarui di storage/firebase/firestore-snapshot.json ({$summary}).");
    }

    public function exportCsv(string $type)
    {
        $filename = "sirkula-{$type}-" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        if ($type === 'inventory') {
            $variants = ProductVariant::with('product.category')->get();
            $callback = function () use ($variants) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['SKU', 'Produk', 'Kategori', 'Warna', 'Ukuran', 'Harga Dasar', 'Harga Tambahan', 'Stok (pcs)', 'Status']);
                foreach ($variants as $v) {
                    fputcsv($out, [
                        $v->sku,
                        $v->product->name ?? '-',
                        $v->product->category->name ?? '-',
                        $v->color,
                        $v->size,
                        $v->product->base_price ?? 0,
                        $v->additional_price,
                        $v->stock,
                        ($v->product->is_active ?? false) ? 'Aktif' : 'Arsip',
                    ]);
                }
                fclose($out);
            };
            return response()->stream($callback, 200, $headers);
        }

        $orders = Order::with(['user', 'items'])->latest()->get();
        $callback = function () use ($orders) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['No. Pesanan', 'Tanggal', 'Pelanggan', 'Email', 'Penerima', 'Telepon', 'Alamat', 'Metode Bank', 'Total (Rp)', 'Status', 'Jumlah Item']);
            foreach ($orders as $o) {
                fputcsv($out, [
                    $o->order_number,
                    $o->created_at->format('Y-m-d H:i'),
                    $o->user->name ?? 'Guest',
                    $o->user->email ?? '-',
                    $o->shipping_name,
                    $o->shipping_phone,
                    $o->shipping_address,
                    strtoupper($o->payment_method),
                    $o->total_amount,
                    strtoupper($o->status),
                    $o->items->sum('quantity'),
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function handleImageUpload($file, string $slug): string
    {
        $ext = strtolower($file->extension() ?: $file->getClientOriginalExtension() ?: 'jpg');
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
            $ext = 'jpg';
        }
        $filename = $slug . '-1.' . $ext;
        $storageDir = storage_path('app/public/products');
        $publicDir = public_path('storage/products');

        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }
        if (!is_dir($publicDir)) {
            mkdir($publicDir, 0755, true);
        }

        $file->move($storageDir, $filename);
        @copy($storageDir . DIRECTORY_SEPARATOR . $filename, $publicDir . DIRECTORY_SEPARATOR . $filename);

        return 'products/' . $filename;
    }
}
