@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('content')
<div class="pg-h"><h1>Edit Kategori</h1><a href="{{ route('categories.index') }}" class="btn btn-outline">Kembali</a></div>
<div class="g2"><div class="card"><div class="card-b">
    <form action="{{ route('categories.update', $category) }}" method="POST">@csrf @method('PUT')
    <div class="mb-3"><label class="form-l">Nama *</label><input type="text" name="name" class="form-i w-full" value="{{ $category->name }}" required></div>
    <div class="mb-3"><label class="form-l">Deskripsi</label><textarea name="description" class="form-i w-full" rows="3">{{ $category->description }}</textarea></div>
    <div class="fx jc-e g-2"><a href="{{ route('categories.index') }}" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">Update</button></div>
    </form>
</div></div></div>
@endsection