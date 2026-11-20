@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <h2>Detail Buku</h2>

    <div style="margin-bottom: 15px;">
        @if($book->cover)
            <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" width="150" style="border: 1px solid #ccc;">
        @else
            <div style="width: 150px; height: 210px; border: 1px solid #999; display: flex; align-items: center; justify-content: center; color: #666;">
                Tidak Ada Cover
            </div>
        @endif
    </div>

    <p><strong>ID:</strong> {{ $book->id }}</p>
    <p><strong>Judul Buku:</strong> {{ $book->title }}</p>
    <p><strong>Kategori:</strong> {{ $book->category?->name ?? 'Belum ada kategori' }}</p>
    <p><strong>Penulis:</strong> {{ $book->author }}</p>
    <p><strong>Tahun Terbit:</strong> {{ $book->year }}</p>
    <p><strong>Stok Tersedia:</strong> {{ $book->stock }}</p>

    <br>
    <a href="{{ route('books.edit', $book) }}">Edit Buku Ini</a> | 
    <a href="{{ route('books.index') }}">&laquo; Kembali ke Daftar Buku</a>
@endsection