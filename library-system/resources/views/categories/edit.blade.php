@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <h2>Edit Kategori</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Nama Kategori:</label><br>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
        </div>
        <br>
        <button type="submit">Update</button>
        <a href="{{ route('categories.index') }}">Batal</a>
    </form>
@endsection