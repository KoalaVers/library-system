
@extends('layouts.app')

@section('title', 'Books')

@section('content')
    <h2>Daftar Buku</h2>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <a href="{{ route('books.create') }}">Tambah Buku</a>

    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($books as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->year }}</td>
                    <td>{{ $book->stock }}</td>
                    <td>
                        <a href="{{ route('books.show', $book) }}">
                            Detail
                        </a>

                        <a href="{{ route('books.edit', $book) }}">
                            Edit
                        </a>

                        <form
                            action="{{ route('books.destroy', $book) }}"
                            method="POST"
                            style="display: inline;"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                onclick="return confirm('Yakin ingin menghapus buku ini?')"
                            >
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada data buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
