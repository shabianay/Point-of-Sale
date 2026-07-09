<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px 8px; border: 1px solid #ddd; }
        th { background: #f5f5f5; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        h2 { text-align: center; }
        .info { text-align: center; margin-bottom: 16px; color: #666; font-size: 11px; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan</h2>
    <div class="info">Periode: {{ $from->format('d/m/Y') }} - {{ $to->format('d/m/Y') }} | Total: Rp {{ number_format($total, 0, ',', '.') }}</div>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Kasir</th>
                <th class="text-right">Total</th>
                <th>Metode</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ $t->code }}</td>
                <td>{{ $t->user->name ?? '-' }}</td>
                <td class="text-right">{{ number_format($t->total, 0, ',', '.') }}</td>
                <td>{{ $t->payment_method }}</td>
                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
