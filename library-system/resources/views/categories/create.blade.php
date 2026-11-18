@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <h2>Tambah Kategori Baru</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Nama Kategori:</label><br>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>
        <br>
        <button type="submit">Simpan</button>
        <a href="{{ route('categories.index') }}">Batal</a>
    </form>
@endsection