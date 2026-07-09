<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Struk - {{ $transaction->code }}</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Courier New',monospace;font-size:10px;margin:0;padding:6px}
.r{max-width:58mm;margin:0 auto}
table{width:100%;border-collapse:collapse}
td{padding:1px 3px;vertical-align:top}
.ct{text-align:center}
.lt{text-align:left}
.rt{text-align:right}
.b{font-weight:bold}
.d{border-top:1px dashed #000;margin:3px 0}
.s{font-size:14px;font-weight:bold}
.btn-cetak{display:block;margin:0 auto 12px;padding:10px 24px;background:#FF6A00;color:#fff;border:none;border-radius:8px;font-size:14px;cursor:pointer;font-family:inherit}
.btn-kembali{display:block;margin:6px auto 12px;padding:8px 20px;background:transparent;color:#666;border:1px solid #ddd;border-radius:8px;font-size:12px;cursor:pointer;text-decoration:none;text-align:center;font-family:inherit}
.btn-kembali:hover{background:#f5f5f5}
@media print{@page{size:58mm auto;margin:0}body{margin:0;padding:0}.np{display:none!important}}
</style>
</head>
<body>
<button class="btn-cetak np" onclick="window.print()">Cetak Struk</button>
<a href="{{ route('transactions.show', $transaction) }}" class="btn-kembali np">&#8592; Kembali</a>

<div class="r">
    <div class="ct"><b style="font-size:14px">{{ $store->store_name ?? 'Toko' }}</b><br>
    {{ $store->store_address ?? '' }}<br>
    Telp: {{ $store->store_phone ?? '' }}</div>
    <div class="d"></div>

    <table>
        <tr><td>No</td><td class="rt">{{ $transaction->code }}</td></tr>
        <tr><td>Kasir</td><td class="rt">{{ $transaction->user->name }}</td></tr>
        <tr><td>Pelanggan</td><td class="rt">{{ $transaction->customer_name ?? 'Walk-in Customer' }}</td></tr>
        <tr><td>Meja</td><td class="rt">@if($transaction->order_type === 'dine_in'){{ $transaction->table_number ?? '-' }} | Dine In @else Takeaway @endif</td></tr>
        <tr><td>Tgl</td><td class="rt">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td></tr>
    </table>
    <div class="d"></div>

    <table>
        <tr><td class="b">Item</td><td class="rt b">Qty</td><td class="rt b">Harga</td><td class="rt b">Subtotal</td></tr>
        @foreach($transaction->transactionItems as $item)
        <tr>
            <td class="lt">{{ $item->product->name }}</td>
            <td class="rt">{{ $item->quantity }}</td>
            <td class="rt">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
            <td class="rt">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($item->notes)
        <tr><td colspan="4" style="font-size:9px;color:#666;">  * {{ $item->notes }}</td></tr>
        @endif
        @endforeach
    </table>
    <div class="d"></div>

    <table>
        <tr><td>Subtotal</td><td class="rt">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td></tr>
        @if($transaction->discount_amount > 0)
        <tr><td>Diskon</td><td class="rt">(Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }})</td></tr>
        @endif
        @if($transaction->tax_amount > 0)
        <tr><td>Pajak ({{ $store->tax_rate }}%)</td><td class="rt">Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</td></tr>
        @endif
        @if($transaction->service_charge_amount > 0)
        <tr><td>Service ({{ $store->service_charge }}%)</td><td class="rt">Rp {{ number_format($transaction->service_charge_amount, 0, ',', '.') }}</td></tr>
        @endif
    </table>
    <div class="d"></div>

    <table>
        <tr class="s"><td class="b">TOTAL</td><td class="rt b">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td></tr>
    </table>
    <div class="d"></div>

    <table>
        <tr><td>Tunai</td><td class="rt">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td></tr>
        <tr><td>Kembalian</td><td class="rt">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td></tr>
    </table>
    <div class="d"></div>

    <div class="ct">{!! nl2br(e($store->receipt_footer ?? 'Terima kasih telah berbelanja')) !!}</div>
</div>
</body>
</html>
