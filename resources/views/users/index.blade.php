@extends('layouts.app')
@section('title', 'Pengguna')
@section('content')
<div class="pg-h"><h1>Pengguna</h1><a href="{{ route('users.create') }}" class="btn btn-primary"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg> Tambah</a></div>
<div class="card"><div class="t-wrap"><table class="tbl"><thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
    @foreach($users as $user)
    <tr><td class="fw-6">{{ $user->name }}</td><td style="color:var(--400)">{{ $user->email }}</td><td>@foreach($user->roles as $role)<span class="b b-o">{{ $role->name }}</span> @endforeach</td><td>{!! $user->is_active ? '<span class="b b-g">Aktif</span>' : '<span class="b b-r">Nonaktif</span>' !!}</td><td><div class="fx g-1"><a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a><form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Nonaktifkan?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Nonaktifkan</button></form></div></td></tr>
    @endforeach
</tbody></table></div></div>
@endsection