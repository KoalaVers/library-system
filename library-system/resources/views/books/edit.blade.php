@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
    <h2>Edit Data Buku</h2>

    <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div>
            <label>Judul Buku:</label><br>
            <input type="text" name="title" value="{{ old('title', $book->title) }}">
            @error('title')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>
        <br>
        <div>
            <label for="category_id">Kategori:</label><br>
            <select name="category_id" id="category_id">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>
        <br>
        <div>
            <label>Penulis:</label><br>
            <input type="text" name="author" value="{{ old('author', $book->author) }}">
            @error('author')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>
        <br>
        <div>
            <label>Tahun Terbit:</label><br>
            <input type="text" name="year" value="{{ old('year', $book->year) }}">
            @error('year')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>
        <br>
        <div>
            <label>Stok:</label><br>
            <input type="text" name="stock" value="{{ old('stock', $book->stock) }}">
            @error('stock')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>
        <br>
        <div>
            <label>Cover Buku Saat Ini:</label><br>
            @if($book->cover)
                <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" width="100" style="margin-top: 5px; margin-bottom: 5px;"><br>
            @else
                <p style="color: #666; margin: 5px 0;"><em>Belum ada cover</em></p>
            @endif
            <label>Ganti Cover Buku (Opsional, JPG/PNG/WEBP, Maks 2MB):</label><br>
            <input type="file" name="cover" accept="image/*">
            @error('cover')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>
        <br>
        <button type="submit">Update</button>
        <a href="{{ route('books.index') }}">Batal</a>
    </form>
@endsection