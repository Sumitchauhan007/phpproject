<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'URL Shortener' }}</title>
</head>
<body>
<header>
    <nav>
        @auth
            <a href="{{ route('dashboard') }}">Dashboard</a>
            @if(!auth()->user()->isSuperAdmin())
                <a href="{{ route('urls.index') }}">Short URLs</a>
            @endif
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <a href="{{ route('invitations.index') }}">Invitations</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endauth
    </nav>
</header>

<main>
    @if(session('status'))
        <p>{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>
