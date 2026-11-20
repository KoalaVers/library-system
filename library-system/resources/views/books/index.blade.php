@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <h2>Daftar Buku</h2>

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <p>
        <a href="{{ route('books.create') }}">+ Tambah Buku Baru</a>
        @if(Route::has('books.export'))
            | <a href="{{ route('books.export', request()->query()) }}">Export CSV</a>
        @endif
    </p>

    {{-- Form Pencarian & Filter --}}
    <form action="{{ route('books.index') }}" method="GET" style="margin-bottom: 15px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau penulis...">

        <select name="category_id">
            <option value="">-- Semua Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="stock_status">
            <option value="">-- Semua Stok --</option>
            <option value="available" @selected(request('stock_status') === 'available')>Tersedia (Stok > 0)</option>
            <option value="out_of_stock" @selected(request('stock_status') === 'out_of_stock')>Habis (Stok = 0)</option>
        </select>

        <button type="submit">Cari / Filter</button>

        @if(request()->hasAny(['search', 'category_id', 'stock_status']) && (request('search') || request('category_id') || request('stock_status')))
            <a href="{{ route('books.index') }}">Reset Filter</a>
        @endif
    </form>

    @if($books->isEmpty())
        <p><em>Tidak ada data buku yang sesuai dengan pencarian atau filter yang dipilih.</em></p>
    @else
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Cover</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $index => $book)
                    <tr>
                        <td>{{ $books->firstItem() + $index }}</td>
                        <td align="center">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" width="50" height="70" style="object-fit: cover;">
                            @else
                                <div style="width: 50px; height: 70px; border: 1px solid #999; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #666;">
                                    No Cover
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong><a href="{{ route('books.show', $book) }}">{{ $book->title }}</a></strong>
                        </td>
                        <td>{{ $book->author }}</td>
                        <td><em>{{ $book->category?->name ?? 'Belum ada kategori' }}</em></td>
                        <td>{{ $book->year }}</td>
                        <td>{{ $book->stock }}</td>
                        <td>
                            <a href="{{ route('books.show', $book) }}">Detail</a> |
                            <a href="{{ route('books.edit', $book) }}">Edit</a> |
                            <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Navigasi Pagination --}}
        <div style="margin-top: 15px;">
            {{ $books->links() }}
        </div>
    @endif
@endsection