@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('content')
<div class="pg-h"><h1>Transaksi {{ $transaction->code }}</h1><div class="fx g-2"><a href="{{ route('transactions.receipt', $transaction) }}" class="btn btn-outline" target="_blank">Cetak Struk</a><a href="{{ route('transactions.receipt-pdf', $transaction) }}" class="btn btn-outline">PDF</a><a href="{{ route('transactions.index') }}" class="btn btn-outline">Kembali</a></div></div>
<div class="g2">
    <div class="card"><div class="card-b-sm"><h6 style="font-weight:700;font-size:.9rem;margin-bottom:16px">Item Transaksi</h6>
        <div class="t-wrap"><table class="tbl"><thead><tr><th>Produk</th><th class="text-center">Qty</th><th class="ta-r">Harga</th><th class="ta-r">Subtotal</th></tr></thead><tbody>
            @foreach($transaction->transactionItems as $item)
            <tr><td class="fw-6">{{ $item->product->name }}@if($item->notes)<br><small style="color:var(--400);font-style:italic">📝 {{ $item->notes }}</small>@endif</td><td class="text-center">{{ $item->quantity }}</td><td class="ta-r">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td><td class="fw-7">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td></tr>
            @endforeach
        </tbody></table></div>
    </div></div>
    <div class="card"><div class="card-b-sm"><h6 style="font-weight:700;font-size:.9rem;margin-bottom:16px">Ringkasan</h6>
        <table class="tbl tbl-nb">
            <tr><td style="color:var(--400)">Kasir</td><td class="ta-r">{{ $transaction->user->name }}</td></tr>
            <tr><td style="color:var(--400)">Subtotal</td><td class="ta-r">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td></tr>
            <tr><td style="color:var(--400)">Diskon</td><td class="c-danger">- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</td></tr>
            @if($transaction->tax_amount > 0)
            <tr><td style="color:var(--400)">Pajak</td><td class="ta-r">Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</td></tr>
            @endif
            @if($transaction->service_charge_amount > 0)
            <tr><td style="color:var(--400)">Service</td><td class="ta-r">Rp {{ number_format($transaction->service_charge_amount, 0, ',', '.') }}</td></tr>
            @endif
            <tr><td class="fw-7">Total</td><td class="fw-8 c-orange" style="font-size:1.1rem">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td></tr>
            <tr><td style="color:var(--400)">Dibayar</td><td class="ta-r">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td></tr>
            <tr><td class="fw-7 c-success">Kembalian</td><td class="fw-8 c-success">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td></tr>
        </table>
        <div class="mt-4 pt-4" style="border-top:1px solid var(--200)">
            <span class="me-3">Status:</span>
            {!! $transaction->status == 'completed' ? '<span class="b b-g">Completed</span>' : '<span class="b b-r">Voided</span>' !!}
            <span class="me-3">Bayar:</span><span class="b b-o">{{ $transaction->payment_method }}</span>
        </div>
        @if($transaction->status == 'completed' && auth()->user()->can('void transactions'))
        <div class="card mt-4" style="border:1px solid #FECACA"><div class="card-b-sm"><h6 style="font-weight:700;font-size:.9rem;color:#DC2626;margin-bottom:12px">Void Transaksi</h6><form action="{{ route('transactions.void', $transaction) }}" method="POST">@csrf<div class="mb-3"><textarea name="reason" class="form-i w-full" rows="3" placeholder="Alasan void..." required></textarea></div><button type="submit" class="btn btn-danger w-full" onclick="return confirm('Yakin void transaksi?')">Konfirmasi Void</button></form></div></div>
        @endif
    </div></div>
</div>
@endsection