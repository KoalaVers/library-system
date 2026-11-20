@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

    <h1>Daftar Buku</h1>

    @if (session('success'))
        <div style="color: green; padding: 10px; margin-bottom: 15px; border: 1px solid green;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="color: red; padding: 10px; margin-bottom: 15px; border: 1px solid red;">
            {{ session('error') }}
        </div>
    @endif

    <p>
        @can('create', App\Models\Book::class)
            <a href="{{ route('books.create') }}">Tambah Buku</a>
            @if (\Illuminate\Support\Facades\Route::has('books.export'))
                |
            @endif
        @endcan

        @if (\Illuminate\Support\Facades\Route::has('books.export'))
            <a href="{{ route('books.export', request()->query()) }}">Export CSV</a>
        @endif
    </p>

    {{-- Form Pencarian & Filter --}}
    <form action="{{ route('books.index') }}" method="GET" style="margin-bottom: 15px;">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari judul atau penulis..."
        >

        <select name="category_id">
            <option value="">-- Semua Kategori --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="stock_status">
            <option value="">-- Semua Stok --</option>
            <option value="available" @selected(request('stock_status') === 'available')>Tersedia (Stok &gt; 0)</option>
            <option value="out_of_stock" @selected(request('stock_status') === 'out_of_stock')>Habis (Stok = 0)</option>
        </select>

        <button type="submit">Filter</button>
        @if (request()->hasAny(['search', 'category_id', 'stock_status']))
            <a href="{{ route('books.index') }}">Reset</a>
        @endif
    </form>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Cover</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($books as $book)
                <tr>
                    <td>{{ ($books->currentPage() - 1) * $books->perPage() + $loop->iteration }}</td>
                    <td>
                        @if ($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" width="50">
                        @else
                            <span>No Cover</span>
                        @endif
                    </td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->year }}</td>
                    <td>{{ $book->stock }}</td>
                    <td>{{ $book->category?->name ?? 'Belum ada kategori' }}</td>
                    <td>
                        <a href="{{ route('books.show', $book->id) }}">Detail</a>

                        @can('update', $book)
                            |
                            <a href="{{ route('books.edit', $book->id) }}">Edit</a>
                        @endcan

                        @can('delete', $book)
                            |
                            <form
                                action="{{ route('books.destroy', $book->id) }}"
                                method="POST"
                                style="display: inline;"
                                onsubmit="return confirm('Yakin ingin menghapus buku ini?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit">Hapus</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada data buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        {{ $books->links() }}
    </div>

@endsection