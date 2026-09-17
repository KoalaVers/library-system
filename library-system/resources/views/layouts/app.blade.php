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
        <a href="/dashboard">Dashboard</a> |
        <a href="/books">Books</a> |
        <a href="/categories">Categories</a> |
        <a href="/members">Members</a>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>Library System</p>
    </footer>

</body>
</html>