@extends('layouts.app')

@section('title', 'Tambah Anggota')

@section('content')
    <h2>Tambah Anggota Baru</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('members.store') }}" method="POST">
        @csrf
        <div>
            <label>Kode Anggota:</label><br>
            <input type="text" name="member_code" value="{{ old('member_code') }}" placeholder="MB-001" required>
        </div>
        <br>
        <div>
            <label>Nama Lengkap:</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <br>
        <div>
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <br>
        <div>
            <label>No. Telepon:</label><br>
            <input type="text" name="phone" value="{{ old('phone') }}" required>
        </div>
        <br>
        <div>
            <label>Alamat:</label><br>
            <textarea name="address">{{ old('address') }}</textarea>
        </div>
        <br>
        <button type="submit">Simpan</button>
        <a href="{{ route('members.index') }}">Batal</a>
    </form>
@endsection