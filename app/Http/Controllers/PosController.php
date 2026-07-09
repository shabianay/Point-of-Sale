<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockMovement;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Owner,Admin,Kasir');
    }

    public function index()
    {
        $store = StoreSetting::first();
        $categories = \App\Models\Category::with('products')->get();
        $products = Product::where('is_active', true)->where('stock', '>', 0)->get();

        return view('pos.index', compact('store', 'categories', 'products'));
    }

    public function addItem(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $price = $product->selling_price;
        if ($request->has('price')) {
            $price = $request->price;
        }

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'stock' => $product->stock,
                'unit' => $product->unit,
            ]
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,qris,card,transfer,gopay',
            'paid_amount' => 'required|numeric|min:0',
            'order_type' => 'nullable|in:dine_in,takeaway',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $items = [];
            $deactivatedProducts = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->name} tidak mencukupi (tersedia: {$product->stock})"
                    ], 422);
                }
                $itemSubtotal = $item['quantity'] * $item['price'];
                $subtotal += $itemSubtotal;
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $itemSubtotal,
                    'notes' => $item['notes'] ?? null,
                ];
            }

            $settings = StoreSetting::first();
            $taxAmount = $subtotal * ($settings->tax_rate / 100);
            $serviceChargeAmount = $subtotal * ($settings->service_charge / 100);

            // Diskon transaksi: jika diakhiri "%", berarti persen, else nominal
            $discountRaw = $request->discount ?? '0';
            $discountAmount = 0;
            if (str_ends_with($discountRaw, '%')) {
                $percent = (float) rtrim($discountRaw, '%');
                $discountAmount = $subtotal * ($percent / 100);
            } else {
                $discountAmount = (float) $discountRaw;
            }
            $discountAmount = min($discountAmount, $subtotal);

            $total = $subtotal + $taxAmount + $serviceChargeAmount - $discountAmount;
            $paidAmount = $request->paid_amount;
            $changeAmount = max(0, $paidAmount - $total);

            $transaction = Transaction::create([
                'code' => (new Transaction)->generateCode(),
                'customer_name' => $request->customer_name ?? 'Walk-in Customer',
                'table_number' => $request->table_number ?? '-',
                'order_type' => $request->order_type ?? 'dine_in',
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'service_charge_amount' => $serviceChargeAmount,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
            ]);

            foreach ($items as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'notes' => $item['notes'] ?? null,
                ]);

                $item['product']->decrement('stock', $item['quantity']);
                $item['product']->refresh();

                // Auto-deactivate produk jika stok habis
                if ($item['product']->stock <= 0) {
                    $item['product']->update(['is_active' => false]);
                    $deactivatedProducts[] = $item['product']->name;
                }

                StockMovement::create([
                    'product_id' => $item['product']->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'reference_type' => 'transaction',
                    'reference_id' => $transaction->id,
                    'notes' => 'Transaksi #' . $transaction->code,
                    'user_id' => Auth::id(),
                ]);
            }

            DB::commit();

            $store = StoreSetting::first();
            $printData = [
                'store_name' => $store->store_name ?? 'Toko',
                'store_address' => $store->store_address ?? '',
                'store_phone' => $store->store_phone ?? '',
                'receipt_footer' => $store->receipt_footer ?? 'Terima kasih telah berbelanja',
                'code' => $transaction->code,
                'cashier' => Auth::user()->name,
                'customer_name' => $request->customer_name ?? 'Walk-in Customer',
                'table_number' => $request->table_number ?? '-',
                'order_type' => $request->order_type ?? 'dine_in',
                'date' => $transaction->created_at->format('d/m/Y H:i:s'),
                'items' => collect($items)->map(function ($item) {
                    return [
                        'name' => $item['product']->name,
                        'qty' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                        'notes' => $item['notes'] ?? null,
                    ];
                })->toArray(),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'service_charge_amount' => $serviceChargeAmount,
                'service_charge_rate' => $settings->service_charge,
                'tax_rate' => $settings->tax_rate,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $request->payment_method,
            ];

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'code' => $transaction->code,
                'change_amount' => $changeAmount,
                'receipt_url' => route('transactions.receipt', $transaction),
                'print_data' => $printData,
                'deactivated_products' => $deactivatedProducts,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function calculateChange(Request $request)
    {
        $total = $request->total;
        $paid = $request->paid_amount;
        return response()->json([
            'change' => max(0, $paid - $total)
        ]);
    }
}
