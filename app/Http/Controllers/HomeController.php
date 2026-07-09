<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Category;
use App\Models\StoreSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $store = StoreSetting::first();
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $todaySales = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');

        $thisWeekSales = Transaction::where('created_at', '>=', $thisWeek)
            ->where('status', 'completed')
            ->sum('total');

        $thisMonthSales = Transaction::where('created_at', '>=', $thisMonth)
            ->where('status', 'completed')
            ->sum('total');

        $todayTransactions = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        $totalProducts = Product::count();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')
            ->where('is_active', true)
            ->count();

        // Produk yang dinonaktifkan otomatis karena stok habis
        $outOfStockProducts = Product::where('is_active', false)
            ->where('stock', '<=', 0)
            ->get();

        $bestProducts = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed')
            ->where('transactions.created_at', '>=', $thisMonth)
            ->select('products.name', DB::raw('SUM(transaction_items.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $recentTransactions = Transaction::with('user')
            ->where('status', 'completed')
            ->latest()
            ->limit(10)
            ->get();

        // Chart: 7 hari terakhir
        $dailySales = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $total = Transaction::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total');
            $dailySales->push([
                'date' => $date->format('D'),
                'total' => (int) $total,
            ]);
        }

        // Chart: 12 bulan terakhir
        $monthlySales = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $total = Transaction::where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total');
            $monthlySales->push([
                'month' => $date->format('M'),
                'total' => (int) $total,
            ]);
        }

        // Chart: metode pembayaran
        $paymentMethods = Transaction::where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)
            ->select('payment_method', DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        return view('home', compact(
            'store', 'todaySales', 'thisWeekSales', 'thisMonthSales',
            'todayTransactions', 'totalProducts', 'lowStockProducts',
            'bestProducts', 'recentTransactions',
            'dailySales', 'monthlySales', 'paymentMethods',
            'outOfStockProducts'
        ));
    }

    public function paymentChartData(Request $request)
    {
        $period = $request->get('period', 'month');

        $startDate = match ($period) {
            'today' => Carbon::today(),
            'week'  => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year'  => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        $data = Transaction::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->select('payment_method', DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        return response()->json([
            'labels' => $data->keys(),
            'data' => $data->values(),
        ]);
    }

    public function bestProductsData(Request $request)
    {
        $period = $request->get('period', 'month');

        $startDate = match ($period) {
            'today' => Carbon::today(),
            'week'  => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year'  => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        $data = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed')
            ->where('transactions.created_at', '>=', $startDate)
            ->select('products.name', DB::raw('SUM(transaction_items.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return response()->json($data);
    }
}
