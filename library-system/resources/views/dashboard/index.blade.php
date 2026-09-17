@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h2>{{ $title }}</h2>

    <p>{{ $description }}</p>

    <p>Jumlah Buku: {{ $bookCount }}</p>
    <p>Jumlah Member: {{ $memberCount }}</p>
    <p>Jumlah Kategori: {{ $categoryCount }}</p>

@endsection