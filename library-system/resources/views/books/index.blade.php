@extends('layouts.app')

@section('title', 'Books')

@section('content')

    <h2>Daftar Buku</h2>

    @if($stock > 0)
        <p>Buku tersedia: {{ $stock }}</p>
    @else
        <p>Buku sedang habis.</p>
    @endif

    <ul>
        @foreach($books as $book)
            <li>
                <strong>{{ $book['title'] }}</strong>
                - {{ $book['author'] }}
                - {{ $book['year'] }}
            </li>
        @endforeach
    </ul>

@endsection