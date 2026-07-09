<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Auth;

class StockMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Owner,Admin');
    }

    public function index()
    {
        $movements = StockMovement::with('product', 'user')->latest()->paginate(20);
        $products = Product::where('is_active', true)->get();
        return view('stock.index', compact('movements', 'products'));
    }

    public function createRestock()
    {
        $products = Product::where('is_active', true)->get();
        return view('stock.restock', compact('products'));
    }

    public function storeRestock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->increment('stock', $request->quantity);
        $product->refresh();

        // Auto-activate produk jika stok tersedia
        if ($product->stock > 0 && !$product->is_active) {
            $product->update(['is_active' => true]);
        }

        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => $request->quantity,
            'notes' => $request->notes ?? 'Restock dari supplier',
            'reference_type' => 'restock',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('stock.index')->with('success', 'Stok berhasil ditambahkan');
    }

    public function createOpname()
    {
        $products = Product::where('is_active', true)->get();
        return view('stock.opname', compact('products'));
    }

    public function storeOpname(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'physical_stock' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $diff = $request->physical_stock - $product->stock;

        if ($diff !== 0) {
            $product->update(['stock' => $request->physical_stock]);
            $product->refresh();

            // Auto-activate jika stok > 0
            if ($product->stock > 0 && !$product->is_active) {
                $product->update(['is_active' => true]);
            }

            StockMovement::create([
                'product_id' => $product->id,
                'type' => $diff > 0 ? 'in' : 'out',
                'quantity' => abs($diff),
                'notes' => $request->notes ?? 'Stok opname',
                'reference_type' => 'opname',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('stock.index')->with('success', 'Stok opname berhasil disimpan');
    }
}
