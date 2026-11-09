
@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <h2>Tambah Pengguna</h2>

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <p>
            <label>Nama Pengguna</label><br>
            <input type="text" name="name"
                   value="{{ old('name') }}" required>
        </p>

        <p>
            <label>Email</label><br>
            <input type="email" name="email"
                   value="{{ old('email') }}" required>
        </p>

        <p>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </p>

        <p>
            <label>Konfirmasi Password</label><br>
            <input type="password" name="password_confirmation" required>
        </p>

        <p>
            <label>Role</label><br>
            <select name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                    Admin
                </option>
                <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>
                    Petugas
                </option>
            </select>
        </p>

        <button type="submit">Simpan</button>
        <a href="{{ route('users.index') }}">Kembali</a>
    </form>
@endsection
