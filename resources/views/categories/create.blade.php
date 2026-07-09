@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('content')
<div class="pg-h"><h1>Tambah Kategori</h1><a href="{{ route('categories.index') }}" class="btn btn-outline">Kembali</a></div>
<div class="g2"><div class="card"><div class="card-b">
    <form action="{{ route('categories.store') }}" method="POST">@csrf
    <div class="mb-3"><label class="form-l">Nama *</label><input type="text" name="name" class="form-i w-full" required></div>
    <div class="mb-3"><label class="form-l">Deskripsi</label><textarea name="description" class="form-i w-full" rows="3"></textarea></div>
    <div class="fx jc-e g-2"><a href="{{ route('categories.index') }}" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>
@endsection