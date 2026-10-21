
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku</title>
</head>
<body>
    <h1>Tambah Buku</h1>

    <a href="{{ route('books.index') }}">Kembali ke Daftar Buku</a>

    @if ($errors->any())
        <div>
            <strong>Periksa kembali input berikut:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('books.store') }}" method="POST">
        @csrf

        <div>
            <label for="title">Judul Buku</label><br>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
                required
            >
            @error('title')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="author">Penulis</label><br>
            <input
                type="text"
                id="author"
                name="author"
                value="{{ old('author') }}"
                required
            >
            @error('author')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="year">Tahun Terbit</label><br>
            <input
                type="number"
                id="year"
                name="year"
                value="{{ old('year') }}"
                min="1900"
                max="{{ date('Y') }}"
                required
            >
            @error('year')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="stock">Stok</label><br>
            <input
                type="number"
                id="stock"
                name="stock"
                value="{{ old('stock') }}"
                min="0"
                max="1000"
                required
            >
            @error('stock')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category_id">Kategori</label><br>
            <select id="category_id" name="category_id" required>
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(old('category_id') == $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <br>
        <button type="submit">Simpan Buku</button>
    </form>
</body>
</html>
