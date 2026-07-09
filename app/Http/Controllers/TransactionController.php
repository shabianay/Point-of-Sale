<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(20);
        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('transactionItems.product', 'user');
        return view('transactions.show', compact('transaction'));
    }

    public function void(Request $request, Transaction $transaction)
    {
        $request->validate(['reason' => 'required|string']);

        if ($transaction->status === 'voided') {
            return back()->with('error', 'Transaksi sudah dibatalkan sebelumnya');
        }

        $transaction->update([
            'status' => 'voided',
            'void_reason' => $request->reason,
        ]);

        foreach ($transaction->transactionItems as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        ActivityLog::create([
            'action' => 'void_transaction',
            'subject_type' => 'Transaction',
            'subject_id' => $transaction->id,
            'changes' => json_encode(['reason' => $request->reason]),
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Transaksi berhasil dibatalkan');
    }

    public function receipt(Transaction $transaction)
    {
        $store = StoreSetting::first();
        $transaction->load('transactionItems.product', 'user');
        return view('transactions.receipt', compact('transaction', 'store'));
    }

    public function receiptPdf(Transaction $transaction)
    {
        $store = StoreSetting::first();
        $transaction->load('transactionItems.product', 'user');
        $pdf = Pdf::loadView('transactions.receipt-pdf', compact('transaction', 'store'));
        return $pdf->download('struk-' . $transaction->code . '.pdf');
    }
}
