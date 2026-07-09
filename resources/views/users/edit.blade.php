@extends('layouts.app')
@section('title', 'Edit Pengguna')
@section('content')
<div class="pg-h"><h1>Edit: {{ $user->name }}</h1><a href="{{ route('users.index') }}" class="btn btn-outline">Kembali</a></div>
<div class="g2"><div class="card"><div class="card-b">
    <form action="{{ route('users.update', $user) }}" method="POST">@csrf @method('PUT')
    <div class="mb-3"><label class="form-l">Nama *</label><input type="text" name="name" class="form-i w-full" value="{{ $user->name }}" required></div>
    <div class="mb-3"><label class="form-l">Email *</label><input type="email" name="email" class="form-i w-full" value="{{ $user->email }}" required></div>
    <div class="mb-3"><label class="form-l">Password (kosongkan jika tidak diubah)</label><input type="password" name="password" class="form-i w-full"></div>
    <div class="mb-3"><label class="form-l">Role *</label><select name="role" class="form-s w-full" required>@foreach($roles as $id=>$name)<option value="{{ $id }}" {{ $user->roles->first()?->id == $id ? 'selected' : '' }}>{{ $name }}</option>@endforeach</select></div>
    <div class="fx jc-e g-2"><a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">Update</button></div>
    </form>
</div></div></div>
@endsection