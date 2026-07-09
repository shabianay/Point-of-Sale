@extends('layouts.app')
@section('title', 'Transaksi')
@section('content')
<div class="pg-h"><h1>Transaksi</h1><a href="{{ route('pos.index') }}" class="btn btn-primary"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg> Transaksi Baru</a></div>
<div class="card">
    <div class="card-h">
        <form class="fx fx-w g-2">
            <input type="date" name="date_from" class="form-i" value="{{ request('date_from') }}" style="min-width:130px;flex:1">
            <input type="date" name="date_to" class="form-i" value="{{ request('date_to') }}" style="min-width:130px;flex:1">
            <select name="status" class="form-s" style="min-width:120px;flex:0 1 auto"><option value="">Semua</option><option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option><option value="voided" {{ request('status') == 'voided' ? 'selected' : '' }}>Voided</option></select>
            <button class="btn btn-primary btn-sm" type="submit">Filter</button>
        </form>
    </div>
    <div class="t-wrap"><table class="tbl">
        <thead><tr><th>Kode</th><th>Kasir</th><th class="ta-r">Total</th><th>Metode</th><th>Status</th><th>Waktu</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($transactions as $t)
            <tr><td><span class="code code-o">{{ $t->code }}</span></td><td>{{ $t->user->name }}</td><td class="fw-7">Rp {{ number_format($t->total, 0, ',', '.') }}</td><td><span class="b b-o">{{ $t->payment_method }}</span></td><td>{!! $t->status == 'completed' ? '<span class="b b-g">Completed</span>' : '<span class="b b-r">Voided</span>' !!}</td><td style="font-size:.8rem;color:var(--400)">{{ $t->created_at->format('d/m/Y H:i') }}</td><td><div class="fx g-1"><a href="{{ route('transactions.show', $t) }}" class="btn btn-primary btn-sm">Detail</a><a href="{{ route('transactions.receipt', $t) }}" class="btn btn-outline btn-sm" target="_blank">Struk</a></div></td></tr>
            @empty<tr><td colspan="7" class="ta-c" style="padding:40px;color:var(--300)">Belum ada transaksi</td></tr>@endforelse
        </tbody>
    </table></div>
    <div class="page-p">{{ $transactions->withQueryString()->links() }}</div>
</div>
@endsection