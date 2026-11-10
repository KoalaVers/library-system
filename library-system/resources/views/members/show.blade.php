@extends('layouts.app')

@section('title', 'Detail Anggota')

@section('content')
    <h2>Detail Anggota</h2>

    <p><strong>Kode Anggota:</strong> {{ $member->member_code }}</p>
    <p><strong>Nama Lengkap:</strong> {{ $member->name }}</p>
    <p><strong>Email:</strong> {{ $member->email }}</p>
    <p><strong>No. Telepon:</strong> {{ $member->phone }}</p>
    <p><strong>Alamat:</strong> {{ $member->address ?? '-' }}</p>
    <p><strong>Terdaftar Pada:</strong> {{ $member->created_at->format('d M Y') }}</p>

    <br>
    <a href="{{ route('members.edit', $member) }}">Edit</a> | 
    <a href="{{ route('members.index') }}">Kembali</a>
@endsection