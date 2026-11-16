
@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
    <h2>Detail Peminjaman Buku</h2>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <a href="{{ route('borrowings.index') }}">
        Kembali ke Daftar Peminjaman
    </a>

    <hr>

    <h3>Informasi Peminjaman</h3>

    <p><strong>ID Transaksi:</strong> {{ $borrowing->id }}</p>
    <p><strong>Nama Anggota:</strong> {{ $borrowing->member->name }}</p>
    <p><strong>Kode Anggota:</strong> {{ $borrowing->member->member_code }}</p>
    <p><strong>Petugas:</strong> {{ $borrowing->user->name }}</p>
    <p><strong>Tanggal Pinjam:</strong> {{ $borrowing->borrow_date->format('d-m-Y') }}</p>
    <p><strong>Jatuh Tempo:</strong> {{ $borrowing->due_date->format('d-m-Y') }}</p>
    <p><strong>Tanggal Kembali:</strong>
        {{ $borrowing->return_date?->format('d-m-Y') ?? '-' }}
    </p>
    <p><strong>Status:</strong> {{ $borrowing->status }}</p>

    <h3>Daftar Buku</h3>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowing->details as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->book->title }}</td>
                    <td>{{ $detail->book->author }}</td>
                    <td>{{ $detail->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($borrowing->status === 'borrowed')
        <form
            action="{{ route('borrowings.return', $borrowing->id) }}"
            method="POST"
            onsubmit="return confirm('Yakin ingin memproses pengembalian buku ini?')"
        >
            @csrf
            <br>
            <button type="submit">Proses Pengembalian</button>
        </form>
    @else
        <p style="color: green;">Buku sudah dikembalikan.</p>
    @endif
@endsection