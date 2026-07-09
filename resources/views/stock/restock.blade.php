@extends('layouts.app')
@section('title', 'Barang Masuk')
@section('content')
<div class="pg-h"><h1>Barang Masuk</h1><a href="{{ route('stock.index') }}" class="btn btn-outline">Kembali</a></div>
<div class="g2"><div class="card"><div class="card-b">
    <form action="{{ route('stock.restock.store') }}" method="POST">@csrf
    <div class="mb-3"><label class="form-l">Produk *</label><select name="product_id" class="form-s w-full" required><option value="">Pilih</option>@foreach($products as $p)<option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ $p->name }} (Stok: {{ $p->stock }})</option>@endforeach</select></div>
    <div class="mb-3"><label class="form-l">Jumlah *</label><input type="number" name="quantity" class="form-i w-full" min="1" required></div>
    <div class="mb-3"><label class="form-l">Keterangan</label><textarea name="notes" class="form-i w-full" rows="2" placeholder="Restock dari supplier"></textarea></div>
    <div class="fx jc-e g-2"><a href="{{ route('stock.index') }}" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>
@endsection