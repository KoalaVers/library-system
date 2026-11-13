
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Peminjaman</title>
</head>
<body>
    <h1>Tambah Peminjaman Buku</h1>

    <a href="{{ route('borrowings.index') }}">Kembali</a>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('borrowings.store') }}" method="POST">
        @csrf

        <label>Anggota:</label>
        <select name="member_id" required>
            <option value="">-- Pilih Anggota --</option>
            @foreach ($members as $member)
                <option value="{{ $member->id }}">
                    {{ $member->name }} - {{ $member->member_code }}
                </option>
            @endforeach
        </select>

        <h3>Pilih Buku</h3>

        @foreach ($books as $book)
            <div>
                <label>
                    <input
                        type="checkbox"
                        name="books[{{ $loop->index }}][book_id]"
                        value="{{ $book->id }}"
                    >
                    {{ $book->title }}
                    (Stok: {{ $book->stock }})
                </label>

                Jumlah:
                <input
                    type="number"
                    name="books[{{ $loop->index }}][quantity]"
                    min="1"
                    max="{{ $book->stock }}"
                    value="1"
                >
            </div>
        @endforeach

        <br>
        <button type="submit">Simpan Peminjaman</button>
    </form>
</body>
</html>