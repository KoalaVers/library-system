
@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')

    <h1>Detail Buku</h1>

    <p>
        <strong>Judul:</strong> {{ $book->title }}
    </p>

    <p>
        <strong>Penulis:</strong> {{ $book->author }}
    </p>

    <p>
        <strong>Tahun Terbit:</strong> {{ $book->year }}
    </p>

    <p>
        <strong>Stok:</strong> {{ $book->stock }}
    </p>

    <p>
        <strong>Kategori:</strong>
        {{ $book->category?->name ?? 'Belum ada kategori' }}
    </p>

    <p>
        <a href="{{ route('books.index') }}">Kembali</a>
        |
        <a href="{{ route('books.edit', $book->id) }}">Edit Buku</a>
    </p>

@endsection
