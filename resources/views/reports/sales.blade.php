@extends('layouts.app')
@section('title', 'Laporan Penjualan')
@section('content')
<div class="pg-h"><h1>Laporan Penjualan</h1><div class="fx g-2"><a href="{{ route('reports.index') }}" class="btn btn-outline">Kembali</a><a href="{{ route('reports.export-pdf-range', ['sales', request('from', now()->startOfMonth()->format('Y-m-d')), request('to', now()->format('Y-m-d'))]) }}" class="btn btn-outline" target="_blank">Export PDF</a><a href="{{ route('reports.export-excel-range', ['sales', request('from', now()->startOfMonth()->format('Y-m-d')), request('to', now()->format('Y-m-d'))]) }}" class="btn btn-outline">Export Excel</a></div></div>
<div class="card mb-4">
    <div class="card-b">
        <form class="fx fx-w g-2" style="gap:10px;">
            <input type="date" name="from" class="form-i" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}" style="flex:1;min-width:130px">
            <input type="date" name="to" class="form-i" value="{{ request('to', now()->format('Y-m-d')) }}" style="flex:1;min-width:130px">
            <button class="btn btn-primary" type="submit">Filter</button>
        </form>
    </div>
</div>
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="stat-c">
        <div class="stat-l">Jumlah Transaksi</div>
        <div class="stat-v mt-2">{{ $count }}</div>
    </div>
    <div class="stat-c">
        <div class="stat-l">Total Penjualan</div>
        <div class="stat-v mt-2 c-accent">Rp {{ number_format($total, 0, ',', '.') }}</div>
    </div>
</div>

<div class="card" style="margin-top:22px">
    <div class="t-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Kasir</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td><span class="code code-o">{{ $t->code }}</span></td>
                    <td>{{ $t->user->name ?? '-' }}</td>
                    <td class="fw-7">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                    <td><span class="b b-o">{{ $t->payment_method }}</span></td>
                    <td style="font-size:.8rem;color:var(--text-tertiary)">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="ta-c" style="padding:40px;color:var(--text-tertiary)">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection