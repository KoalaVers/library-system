@extends('layouts.app')

@section('title', 'Members')

@section('content')

    <h2>Data Member</h2>

    <ul>
        @foreach($members as $member)
            <li>{{ $member }}</li>
        @endforeach
    </ul>

@endsection