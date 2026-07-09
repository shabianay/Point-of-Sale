@extends('layouts.app')
@section('title', 'Kategori')
@section('content')
<div class="pg-h"><h1>Kategori</h1><a href="{{ route('categories.create') }}" class="btn btn-primary"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg> Tambah</a></div>
<div class="card"><div class="t-wrap"><table class="tbl">
    <thead><tr><th>Nama</th><th>Deskripsi</th><th class="text-center">Produk</th><th>Aksi</th></tr></thead>
    <tbody>
        @forelse($categories as $cat)
        <tr><td class="fw-6">{{ $cat->name }}</td><td style="color:var(--400)">{{ $cat->description ?? '-' }}</td><td class="text-center"><span class="b b-o">{{ $cat->products_count }}</span></td><td>
            <div class="fx g-1">
                <a href="{{ route('categories.edit', $cat) }}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">Hapus</button></form>
            </div></td>
        </tr>
        @empty<tr><td colspan="4" class="ta-c" style="padding:40px;color:var(--300)">Belum ada kategori</td></tr>@endforelse
    </tbody>
</table></div></div>
@endsection