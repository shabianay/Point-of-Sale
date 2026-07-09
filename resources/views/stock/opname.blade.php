@extends('layouts.app')
@section('title', 'Stok Opname')
@section('content')
<div class="pg-h"><h1>Stok Opname</h1><a href="{{ route('stock.index') }}" class="btn btn-outline">Kembali</a></div>
<div class="g2"><div class="card"><div class="card-b">
    <form action="{{ route('stock.opname.store') }}" method="POST">@csrf
    <div class="mb-3"><label class="form-l">Produk *</label><select name="product_id" class="form-s w-full" id="product-select" required><option value="">Pilih</option>@foreach($products as $p)<option value="{{ $p->id }}" data-stock="{{ $p->stock }}">{{ $p->name }} (Stok: {{ $p->stock }})</option>@endforeach</select></div>
    <div class="mb-3"><label class="form-l">Stok Sistem</label><input type="text" id="system-stock" class="form-i w-full" readonly></div>
    <div class="mb-3"><label class="form-l">Stok Fisik *</label><input type="number" name="physical_stock" class="form-i w-full" min="0" required></div>
    <div class="mb-3"><label class="form-l">Keterangan</label><textarea name="notes" class="form-i w-full" rows="2"></textarea></div>
    <div class="fx jc-e g-2"><a href="{{ route('stock.index') }}" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">Simpan Opname</button></div>
    </form>
</div></div></div>
<script>document.getElementById('product-select')?.addEventListener('change',function(){var e=this.options[this.selectedIndex];document.getElementById('system-stock').value=e?.dataset?.stock||''})</script>
@endsection