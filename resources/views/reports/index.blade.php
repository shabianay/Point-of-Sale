@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
<div class="pg-h"><h1>Laporan</h1></div>
<div class="grid-3">
    <a href="{{ route('reports.sales') }}" class="card h-full" style="transition:all .2s var(--ease);text-decoration:none">
        <div style="padding:32px;text-align:center;display:flex;flex-direction:column;align-items:center">
            <div style="width:56px;height:56px;background:var(--accent-subtle);color:var(--accent);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h6 style="font-weight:700;color:var(--text-primary);font-size:1rem;margin:0">Penjualan</h6>
            <p style="font-size:.8rem;color:var(--text-tertiary);margin:4px 0 0">Data penjualan harian, mingguan, bulanan</p>
        </div>
    </a>
    <a href="{{ route('reports.best-products') }}" class="card h-full" style="transition:all .2s var(--ease);text-decoration:none">
        <div style="padding:32px;text-align:center;display:flex;flex-direction:column;align-items:center">
            <div style="width:56px;height:56px;background:#ECFDF5;color:#059669;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <h6 style="font-weight:700;color:var(--text-primary);font-size:1rem;margin:0">Produk Terlaris</h6>
            <p style="font-size:.8rem;color:var(--text-tertiary);margin:4px 0 0">Analisis produk paling laku</p>
        </div>
    </a>
    <a href="{{ route('reports.profit') }}" class="card h-full" style="transition:all .2s var(--ease);text-decoration:none">
        <div style="padding:32px;text-align:center;display:flex;flex-direction:column;align-items:center">
            <div style="width:56px;height:56px;background:#EFF6FF;color:#2563EB;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <h6 style="font-weight:700;color:var(--text-primary);font-size:1rem;margin:0">Pendapatan</h6>
            <p style="font-size:.8rem;color:var(--text-tertiary);margin:4px 0 0">Total pendapatan per periode</p>
        </div>
    </a>
</div>
@endsection