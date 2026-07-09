@extends('layouts.app')
@section('title', 'Manajemen Stok')
@section('content')
<div class="pg-h"><h1>Manajemen Stok</h1><div class="fx g-2"><a href="{{ route('stock.restock') }}" class="btn btn-primary"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg> Barang Masuk</a><a href="{{ route('stock.opname') }}" class="btn btn-outline">Stok Opname</a></div></div>
<div class="card"><div class="t-wrap"><table class="tbl"><thead><tr><th>Waktu</th><th>Produk</th><th>Tipe</th><th class="text-center">Qty</th><th>Keterangan</th><th>Oleh</th></tr></thead>
<tbody>
    @forelse($movements as $m)
    <tr><td style="font-size:.8rem;color:var(--400)">{{ $m->created_at->format('d/m/Y H:i') }}</td><td class="fw-6">{{ $m->product->name }}</td><td>{!! $m->type == 'in' ? '<span class="b b-g">Masuk</span>' : '<span class="b b-r">Keluar</span>' !!}</td><td class="text-center fw-7">{{ $m->quantity }}</td><td style="color:var(--400);font-size:.82rem">{{ $m->notes ?? '-' }}</td><td>{{ $m->user->name }}</td></tr>
    @empty<tr><td colspan="6" class="ta-c" style="padding:40px;color:var(--300)">Belum ada pergerakan stok</td></tr>@endforelse
</tbody></table></div><div class="page-p">{{ $movements->links() }}</div></div>
@endsection