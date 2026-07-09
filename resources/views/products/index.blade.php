@extends('layouts.app')
@section('title', 'Produk')
@section('content')
<div class="pg-h">
    <h1>Produk</h1>
    @can('manage products')
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Tambah Produk
    </a>
    @endcan
</div>

<div class="card">
    <div class="card-h">
        <form class="fx fx-w g-2 ai-c" style="width:100%">
            <input type="text" name="search" class="form-i" style="flex:1;min-width:150px" placeholder="Cari nama atau SKU..." value="{{ request('search') }}">
            <select name="category_id" class="form-s" style="min-width:140px;flex:0 1 auto">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <label style="display:flex;align-items:center;gap:6px;font-size:.82rem;color:var(--500);white-space:nowrap;cursor:pointer;flex-shrink:0">
                <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} style="accent-color:var(--o)">
                Stok Menipis
            </label>
            <button class="btn btn-primary btn-sm" type="submit">Filter</button>
            <a href="{{ route('products.index') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
    </div>
    <div class="t-wrap">
        <table class="tbl">
            <thead><tr><th>Produk</th><th>SKU</th><th>Kategori</th><th>H. Jual</th><th class="text-center">Stok</th><th class="text-center">Min</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($products as $p)
                <tr style="{{ $p->stock <= $p->minimum_stock ? 'background:#FFFBF5' : '' }}">
                    <td class="fw-6">
                        <div class="fx ai-c g-2">
                            @if($p->image)
                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover">
                            @else
                            <div style="width:40px;height:40px;background:var(--100);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--300);font-size:1.2rem">🍽️</div>
                            @endif
                            <span>{{ $p->name }}</span>
                        </div>
                    </td>
                    <td><span class="code">{{ $p->sku }}</span></td>
                    <td><span class="b b-o">{{ $p->category->name }}</span></td>
                    <td class="fw-7">
                        @if($p->has_discount)
                        <span style="text-decoration:line-through;color:var(--400);font-size:.75rem">Rp {{ number_format($p->selling_price, 0, ',', '.') }}</span><br>
                        <span style="color:#EF4444">Rp {{ number_format($p->final_price, 0, ',', '.') }}</span>
                        @else
                        Rp {{ number_format($p->selling_price, 0, ',', '.') }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if($p->stock <= $p->minimum_stock)
                            <span class="b b-r">{{ $p->stock }}</span>
                        @else
                            <span class="b b-g">{{ $p->stock }}</span>
                        @endif
                    </td>
                    <td class="text-center" style="color:var(--400)">{{ $p->minimum_stock }}</td>
                    <td>
                        <div class="fx g-1 ai-c">
                            <a href="{{ route('products.show', $p) }}" class="btn btn-outline btn-sm">Detail</a>
                            <a href="{{ route('products.edit', $p) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk {{ $p->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm" style="color:#EF4444;border-color:#FECACA">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="ta-c" style="padding:40px;color:var(--300)">Tidak ada produk</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="page-p">{{ $products->withQueryString()->links() }}</div>
</div>
@endsection