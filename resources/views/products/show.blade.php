@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div class="pg-h"><h1>{{ $product->name }}</h1><div class="pg-h-acts"><a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Edit</a><a href="{{ route('products.index') }}" class="btn btn-outline">Kembali</a></div></div>
<div class="g15">
    <div class="card"><div class="card-b-sm">
        @if($product->image)
        <div style="width:100%;height:200px;border-radius:var(--md);overflow:hidden;margin-bottom:16px">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover">
        </div>
        @endif
        <h6 style="font-weight:700;font-size:.9rem;margin-bottom:16px">Informasi Produk</h6>
        <table class="tbl tbl-nb">
            <tr><td style="color:var(--400);width:40%">Nama</td><td class="fw-6">{{ $product->name }}</td></tr>
            <tr><td style="color:var(--400)">SKU</td><td><span class="code">{{ $product->sku }}</span></td></tr>
            <tr><td style="color:var(--400)">Deskripsi</td><td style="color:var(--500);font-size:.85rem">{{ $product->description ?? '-' }}</td></tr>
            <tr><td style="color:var(--400)">Kategori</td><td><span class="b b-o">{{ $product->category->name }}</span></td></tr>
            <tr><td style="color:var(--400)">H. Jual</td><td class="fw-7">
                @if($product->has_discount)
                <span style="text-decoration:line-through;color:var(--400);margin-right:6px">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                <span style="color:#EF4444">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                @else
                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                @endif
            </td></tr>
            <tr><td style="color:var(--400)">Diskon</td><td>
                @if($product->has_discount)
                <span class="b b-r">{{ $product->discount_type == 'percent' ? $product->discount_value.'%' : 'Rp '.number_format($product->discount_value,0,',','.') }}</span>
                @else<span class="c-muted">-</span>@endif
            </td></tr>
            <tr><td style="color:var(--400)">Stok</td><td>{!! $product->stock <= $product->minimum_stock ? '<span class="b b-r">'.$product->stock.' '.$product->unit.'</span>' : '<span class="b b-g">'.$product->stock.' '.$product->unit.'</span>' !!}</td></tr>
            <tr>
                <td style="color:var(--400)">Status</td>
                <td>
                    <form action="{{ route('products.toggle-status', $product) }}" method="POST" style="display:inline">
                        @csrf
                        <label class="tog">
                            <input type="checkbox" onchange="this.form.submit()" {{ $product->is_active ? 'checked' : '' }}>
                            <span class="tog-sl"></span>
                        </label>
                    </form>
                </td>
            </tr>
        </table>
    </div></div>
    <div class="card"><div class="card-b-sm"><h6 style="font-weight:700;font-size:.9rem;margin-bottom:16px">Riwayat Stok</h6>
        <div class="t-wrap"><table class="tbl">
            <thead><tr><th>Tanggal</th><th>Tipe</th><th class="text-center">Qty</th><th>Keterangan</th></tr></thead>
            <tbody>
                @forelse($product->stockMovements()->latest()->limit(15)->get() as $m)
                <tr>
                    <td style="font-size:.8rem;color:var(--400)">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td>{!! $m->type == 'in' ? '<span class="b b-g">Masuk</span>' : '<span class="b b-r">Keluar</span>' !!}</td>
                    <td class="text-center fw-7">{{ $m->quantity }}</td>
                    <td style="color:var(--400);font-size:.82rem">{{ $m->notes }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="ta-c" style="padding:32px;color:var(--300)">Belum ada riwayat</td></tr>
                @endforelse
            </tbody>
        </table></div>
    </div></div>
</div>
@endsection