<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends AdminBaseController
{
    public function index(Request $request)
    {
        $productQuery = Product::with(['category', 'variants'])->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $productQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhereHas('variants', function ($v) use ($q) {
                        $v->where('sku', 'like', "%{$q}%")
                          ->orWhere('color', 'like', "%{$q}%");
                    });
            });
        }

        if ($request->filled('category_id')) {
            $productQuery->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $productQuery->whereHas('variants', function ($v) {
                    $v->where('stock', '<=', 1)->where('stock', '>', 0);
                });
            } elseif ($request->stock_status === 'out') {
                $productQuery->whereDoesntHave('variants', function ($v) {
                    $v->where('stock', '>', 0);
                });
            }
        }

        $products = $productQuery->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        $stockStats = [
            'total' => Product::count(),
            'low'   => Product::whereHas('variants', fn($v) => $v->where('stock', '<=', 1)->where('stock', '>', 0))->count(),
            'out'   => Product::whereDoesntHave('variants', fn($v) => $v->where('stock', '>', 0))->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'stockStats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'base_price'     => 'required|numeric|min:0',
            'description'    => 'required|string',
            'is_active'      => 'nullable|boolean',
            'image_file'     => 'nullable|file|mimes:jpeg,png,jpg,webp|max:4096',
            'image_files'    => 'nullable|array|max:10',
            'image_files.*'  => 'nullable|file|mimes:jpeg,png,jpg,webp|max:4096',
            'initial_size'   => 'nullable|string|max:20',
            'initial_color'  => 'nullable|string|max:50',
            'initial_stock'  => 'nullable|integer|min:0',
        ]);

        $slug = Str::slug($validated['name']) . '-' . Str::lower(Str::random(4));
        $images = [];

        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $idx => $f) {
                if ($f) {
                    $images[] = $this->handleImageUpload($f, $slug, $idx + 1);
                }
            }
        } elseif ($request->hasFile('image_file')) {
            $images[] = $this->handleImageUpload($request->file('image_file'), $slug, 1);
        }

        if (empty($images)) {
            $images = ['products/linen-shirt-1.jpg', 'products/linen-shirt-2.jpg'];
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'],
            'base_price'  => $validated['base_price'],
            'image'       => $images[0],
            'images'      => $images,
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

        return redirect()->route('admin.products.index')
            ->with('success', "Produk \"{$product->name}\" beserta varian awal dan galeri lookbook berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'base_price'        => 'required|numeric|min:0',
            'description'       => 'required|string',
            'is_active'         => 'nullable|boolean',
            'image_file'        => 'nullable|file|mimes:jpeg,png,jpg,webp|max:4096',
            'new_image_files'   => 'nullable|array|max:10',
            'new_image_files.*' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:4096',
            'existing_images'   => 'nullable|string',
        ]);

        $existingImages = [];
        if ($request->filled('existing_images')) {
            $decoded = json_decode((string) $request->existing_images, true);
            if (is_array($decoded)) {
                foreach ($decoded as $p) {
                    if (is_string($p) && str_starts_with($p, 'products/')) {
                        $existingImages[] = $p;
                    }
                }
            }
        } else {
            $existingImages = $product->gallery_images;
        }

        if ($request->hasFile('new_image_files')) {
            foreach ($request->file('new_image_files') as $idx => $f) {
                if ($f) {
                    $existingImages[] = $this->handleImageUpload($f, $product->slug, count($existingImages) + $idx + 1);
                }
            }
        } elseif ($request->hasFile('image_file')) {
            $uploaded = $this->handleImageUpload($request->file('image_file'), $product->slug, count($existingImages) + 1);
            array_unshift($existingImages, $uploaded);
        }

        $finalImages = array_values(array_unique($existingImages));
        if (empty($finalImages)) {
            $finalImages = [$product->image ?: 'products/linen-shirt-1.jpg'];
        }

        $data = [
            'name'        => $validated['name'],
            'category_id' => $validated['category_id'],
            'base_price'  => $validated['base_price'],
            'description' => $validated['description'],
            'image'       => $finalImages[0],
            'images'      => $finalImages,
            'is_active'   => $request->boolean('is_active', false),
        ];

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', "Produk \"{$product->name}\" dan urutan lookbook berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (OrderItem::where('product_id', $product->id)->exists()) {
            return redirect()->route('admin.products.index')
                ->with('error', "Produk \"{$product->name}\" memiliki riwayat transaksi pesanan pelanggan. Demi integritas riwayat pesanan, produk ini tidak dapat dihapus permanen. Silakan nonaktifkan (arsip) status produk.");
        }

        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
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
        ]);

        $sku = 'SRK-' . strtoupper(Str::random(6));

        $variant = ProductVariant::create([
            'product_id'       => $product->id,
            'size'             => strtoupper(trim($validated['size'])),
            'color'            => trim($validated['color']),
            'stock'            => $validated['stock'],
            'sku'              => $sku,
            'additional_price' => $validated['additional_price'] ?? 0,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', "Varian {$variant->sku} ({$variant->size} / {$variant->color}) berhasil ditambahkan ke produk \"{$product->name}\".");
    }

    public function updateVariant(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);

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
            return redirect()->route('admin.products.index')
                ->with('error', "Varian {$variant->sku} ({$variant->size}) pernah dipesan dalam transaksi pelanggan. Demi integritas audit riwayat belanja, varian tidak dapat dihapus permanen. Silakan ubah stok menjadi 0.");
        }

        if ($variant->product->variants()->count() <= 1) {
            return redirect()->route('admin.products.index')
                ->with('error', "Varian {$variant->sku} adalah satu-satunya varian untuk produk \"{$variant->product->name}\". Produk wajib memiliki minimal 1 varian.");
        }

        $sku = $variant->sku;
        $variant->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Varian {$sku} berhasil dihapus.");
    }

    private function handleImageUpload($file, string $slug, ?int $index = null): string
    {
        $ext = strtolower($file->extension() ?: $file->getClientOriginalExtension() ?: 'jpg');
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $ext = 'jpg';
        }
        $cleanSlug = Str::slug($slug);
        $idxStr = $index !== null ? "-{$index}-" : '-';
        $filename = ($cleanSlug ?: 'product') . $idxStr . time() . '-' . Str::lower(Str::random(4)) . '.' . $ext;
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
