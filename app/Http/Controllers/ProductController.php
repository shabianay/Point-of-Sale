<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Owner,Admin');
    }

    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->boolean('low_stock')) {
            $query->whereColumn('stock', '<=', 'minimum_stock');
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'discount_type' => 'nullable|in:percent,nominal',
            'discount_value' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
        ]);

        $data = $request->only(['name', 'category_id', 'sku', 'description', 'selling_price', 'discount_type', 'discount_value', 'unit', 'stock', 'minimum_stock']);
        $data['discount_type'] = $request->discount_type ?: null;
        $data['discount_value'] = $request->discount_value ?? 0;
        if (empty($data['sku'])) {
            $data['sku'] = 'SKU-' . strtoupper(substr($data['name'], 0, 3)) . rand(100, 999);
        }
        if ($request->hasFile('image')) {
            $data['image'] = ImageHelper::uploadAndConvertToWebp($request->file('image'), 'products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'discount_type' => 'nullable|in:percent,nominal',
            'discount_value' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
        ]);

        $data = $request->only(['name', 'category_id', 'sku', 'description', 'selling_price', 'discount_type', 'discount_value', 'unit', 'minimum_stock']);
        $data['discount_type'] = $request->discount_type ?: null;
        $data['discount_value'] = $request->discount_value ?? 0;
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            if ($product->image) {
                ImageHelper::deleteImage($product->image);
            }
            $data['image'] = ImageHelper::uploadAndConvertToWebp($request->file('image'), 'products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            ImageHelper::deleteImage($product->image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
    }

    public function deleteImage(Product $product)
    {
        if ($product->image) {
            ImageHelper::deleteImage($product->image);
            $product->update(['image' => null]);
        }
        return back()->with('success', 'Gambar produk berhasil dihapus');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'Produk berhasil ' . $status);
    }
}
