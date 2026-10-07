@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')

    <h2>Tambah Buku</h2>

    <form action="{{ route('books.store') }}" method="POST">
        @csrf

        <div>
            <label>Judul</label>
            <input type="text" name="title" required>
        </div>

        <br>

        <div>
            <label>Penulis</label>
            <input type="text" name="author" required>
        </div>

        <br>

        <div>
            <label>Tahun Terbit</label>
            <input type="number" name="year" required>
        </div>

        <br>

        <div>
            <label>Stok</label>
            <input type="number" name="stock" required>
        </div>

        <br>

        <button type="submit">Simpan</button>
        <a href="{{ route('books.index') }}">Kembali</a>
    </form>

@endsection