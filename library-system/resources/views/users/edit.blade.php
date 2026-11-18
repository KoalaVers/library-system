
@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <h2>Edit Pengguna</h2>

    <p>Biarkan password kosong jika tidak ingin mengubahnya.</p>

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <p>
            <label>Nama Pengguna</label><br>
            <input type="text" name="name"
                   value="{{ old('name', $user->name) }}" required>
        </p>

        <p>
            <label>Email</label><br>
            <input type="email" name="email"
                   value="{{ old('email', $user->email) }}" required>
        </p>

        <p>
            <label>Password Baru</label><br>
            <input type="password" name="password">
        </p>

        <p>
            <label>Konfirmasi Password Baru</label><br>
            <input type="password" name="password_confirmation">
        </p>

        <p>
            <label>Role</label><br>
            <select name="role" required>
                <option value="admin"
                    {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                    Admin
                </option>

                <option value="petugas"
                    {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>
                    Petugas
                </option>
            </select>
        </p>

        <button type="submit">Perbarui</button>
        <a href="{{ route('users.index') }}">Kembali</a>
    </form>
@endsection
