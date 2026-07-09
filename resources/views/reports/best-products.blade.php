@extends('layouts.app')
@section('title', 'Produk Terlaris')
@section('content')
<div class="pg-h"><h1>Produk Terlaris</h1><div class="fx g-2"><a href="{{ route('reports.index') }}" class="btn btn-outline">Kembali</a><a href="{{ route('reports.export-pdf-range', ['sales', request('from', now()->startOfMonth()->format('Y-m-d')), request('to', now()->format('Y-m-d'))]) }}" class="btn btn-outline" target="_blank">Export PDF</a><a href="{{ route('reports.export-excel-range', ['best-products', request('from', now()->startOfMonth()->format('Y-m-d')), request('to', now()->format('Y-m-d'))]) }}" class="btn btn-outline">Export Excel</a></div></div>
<div class="card mb-4">
    <div class="card-b">
        <form class="fx fx-w g-2">
            <input type="date" name="from" class="form-i" value="{{ $from->format('Y-m-d') }}" style="flex:1;min-width:130px">
            <input type="date" name="to" class="form-i" value="{{ $to->format('Y-m-d') }}" style="flex:1;min-width:130px">
            <button class="btn btn-primary" type="submit">Filter</button>
        </form>
    </div>
</div>
<div class="card">
    <div class="t-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th>Terjual</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $i => $p)
                <tr>
                    <td><span class="b b-o">{{ $i + 1 }}</span></td>
                    <td class="fw-6">{{ $p->name }}</td>
                    <td class="fw-7">{{ $p->total_qty }} unit</td>
                    <td class="fw-7">Rp {{ number_format($p->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="ta-c" style="padding:40px;color:var(--text-tertiary)">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection