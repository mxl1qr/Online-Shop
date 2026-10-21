<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan halaman utama katalog pakaian & PLP
    public function index(Request $request)
    {
        $categories = Category::all();
        $allSizes = ['S', 'M', 'L', 'XL'];

        // Query produk yang aktif dengan relasi kategori & varian
        $query = Product::with(['category', 'variants'])->where('is_active', true);

        // Filter berdasarkan kategori jika dipilih oleh pengunjung
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter berdasarkan ukuran (S, M, L, XL)
        if ($request->filled('size')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('size', $request->size);
            });
        }

        // Pencarian produk berdasarkan nama atau deskripsi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting (Urutan)
        if ($request->get('sort') === 'price_asc') {
            $query->orderBy('base_price', 'asc');
        } elseif ($request->get('sort') === 'price_desc') {
            $query->orderBy('base_price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products', 'categories', 'allSizes'));
    }

    // Menampilkan detail 1 produk baju beserta varian ukuran & warnanya
    public function show($slug)
    {
        $categories = Category::all();

        $product = Product::with(['category', 'variants'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'variants'])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts', 'categories'));
    }
}