@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')

    <h2>Edit Buku</h2>

    <form action="{{ route('books.update', $book) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Judul</label>
            <input type="text" name="title" value="{{ $book->title }}" required>
        </div>

        <br>

        <div>
            <label>Penulis</label>
            <input type="text" name="author" value="{{ $book->author }}" required>
        </div>

        <br>

        <div>
            <label>Tahun Terbit</label>
            <input type="number" name="year" value="{{ $book->year }}" required>
        </div>

        <br>

        <div>
            <label>Stok</label>
            <input type="number" name="stock" value="{{ $book->stock }}" required>
        </div>

        <br>

        <button type="submit">Update</button>
        <a href="{{ route('books.index') }}">Kembali</a>
    </form>

@endsection