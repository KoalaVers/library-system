<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
</head>
<body>

    <header>
        <h1>Library System</h1>
    </header>

<nav>
    <a href="{{ route('dashboard') }}">Dashboard</a> |
    <a href="{{ route('books.index') }}">Books</a> |
    <a href="{{ route('categories.index') }}">Categories</a> |
    <a href="{{ route('members.index') }}">Members</a>

    @can('manage-users')
        | <a href="{{ route('users.index') }}">Kelola Pengguna</a>
    @endcan

    @auth
        | <span>
            {{ auth()->user()->name }}
            ({{ ucfirst(auth()->user()->role) }})
        </span>

        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @endauth
</nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>Library System</p>
    </footer>

</body>
</html>