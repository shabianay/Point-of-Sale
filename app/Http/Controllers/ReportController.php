<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Exports\SalesExport;
use App\Exports\BestProductsExport;
use App\Exports\ProfitExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Owner,Admin');
    }

    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from) : Carbon::now()->startOfMonth();
        $to = $request->to ? Carbon::parse($request->to) : Carbon::now();

        $transactions = Transaction::with('user')->where('status', 'completed')
            ->whereBetween('created_at', [$from, $to])
            ->latest()->get();

        $total = $transactions->sum('total');
        $count = $transactions->count();

        return view('reports.sales', compact('transactions', 'total', 'count', 'from', 'to'));
    }

    public function bestProducts(Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from) : Carbon::now()->startOfMonth();
        $to = $request->to ? Carbon::parse($request->to) : Carbon::now();

        $products = \Illuminate\Support\Facades\DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.created_at', [$from, $to])
            ->select(
                'products.name',
                \Illuminate\Support\Facades\DB::raw('SUM(transaction_items.quantity) as total_qty'),
                \Illuminate\Support\Facades\DB::raw('SUM(transaction_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->get();

        return view('reports.best-products', compact('products', 'from', 'to'));
    }

    public function profit(Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from) : Carbon::now()->startOfMonth();
        $to = $request->to ? Carbon::parse($request->to) : Carbon::now();

        $transactions = Transaction::with('user')->where('status', 'completed')
            ->whereBetween('created_at', [$from, $to])->get();
        
        $totalRevenue = $transactions->sum('total');
        $transactionCount = $transactions->count();

        return view('reports.profit', compact('transactions', 'from', 'to', 'totalRevenue', 'transactionCount'));
    }

    public function exportExcel($type, Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from) : Carbon::now()->startOfMonth();
        $to = $request->to ? Carbon::parse($request->to) : Carbon::now();

        switch ($type) {
            case 'sales':
                $transactions = Transaction::with('user')->where('status', 'completed')
                    ->whereBetween('created_at', [$from, $to])->latest()->get();
                return Excel::download(new SalesExport($transactions), 'penjualan.xlsx');
            case 'best-products':
                $products = $this->getBestProductsData($from, $to);
                return Excel::download(new BestProductsExport($products), 'produk-terlaris.xlsx');
            case 'profit':
                $transactions = Transaction::with('user')->where('status', 'completed')
                    ->whereBetween('created_at', [$from, $to])->latest()->get();
                return Excel::download(new ProfitExport($transactions), 'laba.xlsx');
        }

        return redirect()->back()->with('error', 'Tipe laporan tidak ditemukan');
    }

    public function exportPdf($type, Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from) : Carbon::now()->startOfMonth();
        $to = $request->to ? Carbon::parse($request->to) : Carbon::now();
        $data = [];
        $view = '';

        switch ($type) {
            case 'sales':
                $transactions = Transaction::with('user')->where('status', 'completed')
                    ->whereBetween('created_at', [$from, $to])->latest()->get();
                $total = $transactions->sum('total');
                $data = compact('transactions', 'total', 'from', 'to');
                $view = 'reports.pdf-sales';
                break;
            case 'profit':
                $transactions = Transaction::with('user')->where('status', 'completed')
                    ->whereBetween('created_at', [$from, $to])->latest()->get();
                $totalRevenue = $transactions->sum('total');
                $data = compact('transactions', 'totalRevenue', 'from', 'to');
                $view = 'reports.pdf-profit';
                break;
        }

        if (!$view) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan');
        }

        $pdf = Pdf::loadView($view, $data);
        return $pdf->download('laporan-' . $type . '-' . Carbon::now()->format('Ymd') . '.pdf');
    }

    private function getBestProductsData($from, $to)
    {
        return \Illuminate\Support\Facades\DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.created_at', [$from, $to])
            ->select(
                'products.name',
                \Illuminate\Support\Facades\DB::raw('SUM(transaction_items.quantity) as total_qty'),
                \Illuminate\Support\Facades\DB::raw('SUM(transaction_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->get();
    }
}
