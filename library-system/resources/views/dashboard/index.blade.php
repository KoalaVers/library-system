
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h2>{{ $title }}</h2>

    <p>{{ $description }}</p>

    <h3>Ringkasan Perpustakaan</h3>

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">

        <div style="border: 1px solid #ddd; padding: 20px; min-width: 180px;">
            <h4>Total Judul Buku</h4>
            <h2>{{ $bookCount }}</h2>
        </div>

        <div style="border: 1px solid #ddd; padding: 20px; min-width: 180px;">
            <h4>Total Stok Buku</h4>
            <h2>{{ number_format($totalStock, 0, ',', '.') }}</h2>
        </div>

        <div style="border: 1px solid #ddd; padding: 20px; min-width: 180px;">
            <h4>Total Anggota</h4>
            <h2>{{ $memberCount }}</h2>
        </div>

        <div style="border: 1px solid #ddd; padding: 20px; min-width: 180px;">
            <h4>Peminjaman Aktif</h4>
            <h2>{{ $activeBorrowings }}</h2>
        </div>

    </div>

@endsection
