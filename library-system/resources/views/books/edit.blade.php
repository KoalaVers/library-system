
@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
    <h2>Edit Buku</h2>

    <form action="{{ route('books.update', $book) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="title">Judul Buku</label>
            <input type="text" id="title" name="title"
                value="{{ old('title', $book->title) }}">

            @error('title')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <br>

        <div>
            <label for="author">Penulis</label>
            <input type="text" id="author" name="author"
                value="{{ old('author', $book->author) }}">

            @error('author')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <br>

        <div>
            <label for="year">Tahun Terbit</label>
            <input type="number" id="year" name="year"
                value="{{ old('year', $book->year) }}">

            @error('year')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <br>

        <div>
            <label for="stock">Stok</label>
            <input type="number" id="stock" name="stock"
                value="{{ old('stock', $book->stock) }}">

            @error('stock')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <br>

        <button type="submit">Update</button>
        <a href="{{ route('books.index') }}">Kembali</a>
    </form>
@endsection
