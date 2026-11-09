
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h2>{{ $title }}</h2>

    <p>{{ $description }}</p>

    <h3>Ringkasan Perpustakaan</h3>

    <p>Total Judul Buku: {{ $bookCount }}</p>

    <p>Total Stok Buku: {{ $totalStock }}</p>

@endsection
