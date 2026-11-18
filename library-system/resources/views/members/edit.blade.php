@extends('layouts.app')

@section('title', 'Edit Anggota')

@section('content')
    <h2>Edit Data Anggota</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('members.update', $member) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label>Kode Anggota:</label><br>
            <input type="text" name="member_code" value="{{ old('member_code', $member->member_code) }}" required>
        </div>
        <br>
        <div>
            <label>Nama Lengkap:</label><br>
            <input type="text" name="name" value="{{ old('name', $member->name) }}" required>
        </div>
        <br>
        <div>
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email', $member->email) }}" required>
        </div>
        <br>
        <div>
            <label>No. Telepon:</label><br>
            <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" required>
        </div>
        <br>
        <div>
            <label>Alamat:</label><br>
            <textarea name="address">{{ old('address', $member->address) }}</textarea>
        </div>
        <br>
        <button type="submit">Update</button>
        <a href="{{ route('members.index') }}">Batal</a>
    </form>
@endsection