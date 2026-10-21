
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku</title>
</head>
<body>
    <h1>Detail Buku</h1>

    <p><strong>Judul:</strong> {{ $book->title }}</p>
    <p><strong>Penulis:</strong> {{ $book->author }}</p>
    <p><strong>Tahun Terbit:</strong> {{ $book->year }}</p>
    <p><strong>Stok:</strong> {{ $book->stock }}</p>
    <p>
        <strong>Kategori:</strong>
        {{ $book->category?->name ?? 'Belum ada kategori' }}
    </p>

    <a href="{{ route('books.index') }}">Kembali</a>
    |
    <a href="{{ route('books.edit', $book->id) }}">Edit Buku</a>
</body>
</html>
