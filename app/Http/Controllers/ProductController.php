<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan halaman utama katalog pakaian
    public function index(Request $request)
    {
        $categories = Category::all();

        // Query produk yang aktif dengan relasi kategori & varian
        $query = Product::with(['category', 'variants'])->where('is_active', true);

        // Filter berdasarkan kategori jika dipilih oleh pengunjung
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $products = $query->latest()->paginate(8);

        return view('products.index', compact('products', 'categories'));
    }

    // Menampilkan detail 1 produk baju beserta varian ukuran & warnanya
    public function show($slug)
    {
        $product = Product::with(['category', 'variants'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('products.show', compact('product'));
    }
}