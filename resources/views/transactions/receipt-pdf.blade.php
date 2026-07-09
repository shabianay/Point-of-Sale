<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk - {{ $transaction->code }}</title>
    <style>
            body { font-family: 'Courier New', monospace; font-size: 10px; }
            .receipt { width: 58mm; max-width: 100%; margin: 0 auto; }
            .receipt table { width: 100%; border-collapse: collapse; }
            th, td { padding: 1px 2px; word-break: break-all; }
            .divider { border-top: 1px dashed #000; margin: 4px 0; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .fw-bold { font-weight: bold; }
            h4 { margin-bottom: 0; }
            small { font-size: 9px; }
        </style>
</head>
<body>
    <div class="receipt">
        <div class="text-center">
            <h4 style="margin-bottom:0">{{ $store->store_name ?? 'Toko' }}</h4>
            <small>{{ $store->store_address ?? '' }}</small><br>
            <small>Telp: {{ $store->store_phone ?? '' }}</small>
        </div>
        <div class="divider"></div>
        <div>
            <small>No: {{ $transaction->code }}</small><br>
            <small>Kasir: {{ $transaction->user->name }}</small><br>
            <small>Pelanggan: {{ $transaction->customer_name ?? 'Walk-in Customer' }}</small><br>
            @if ($transaction->order_type == 'dine_in')
                <small>Meja: {{ $transaction->table_number ?? '-' }} | Dine In</small><br>
            @else
                <small>Takeaway</small><br>
            @endif
            <small>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</small>
        </div>
        <div class="divider"></div>
        <table>
            <thead>
                <tr>
                    <th style="text-align:left">Item</th>
                    <th style="text-align:center">Qty</th>
                    <th style="text-align:right">Harga</th>
                    <th style="text-align:right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->transactionItems as $item)
                <tr>
                    <td>
                        {{ $item->product->name }}
                        @if($item->notes)
                        <br><small style="color:#888">{{ $item->notes }}</small>
                        @endif
                    </td>
                    <td style="text-align:center">{{ $item->quantity }}</td>
                    <td style="text-align:right">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td style="text-align:right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="divider"></div>
        <table>
            <tr><td>Subtotal</td><td style="text-align:right">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td></tr>
            @if($transaction->discount_amount > 0)
            <tr><td>Diskon</td><td style="text-align:right">(Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }})</td></tr>
            @endif
            @if($transaction->tax_amount > 0)
            <tr><td>Pajak</td><td style="text-align:right">Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</td></tr>
            @endif
            @if($transaction->service_charge_amount > 0)
            <tr><td>Service</td><td style="text-align:right">Rp {{ number_format($transaction->service_charge_amount, 0, ',', '.') }}</td></tr>
            @endif
            <tr style="font-weight:bold;font-size:14px">
                <td>Total</td><td style="text-align:right">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
            </tr>
            <tr><td>Tunai</td><td style="text-align:right">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td></tr>
            <tr><td>Kembali</td><td style="text-align:right">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td></tr>
        </table>
        <div class="divider"></div>
        <div class="text-center" style="white-space:pre-line;">
            @if($store->receipt_footer)
                <small>{!! nl2br(e($store->receipt_footer)) !!}</small>
            @else
                <small>Terima kasih telah berbelanja</small>
            @endif
        </div>
    </div>
</body>
</html>
