<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'URL Shortener' }}</title>
    @if (!app()->environment('testing'))
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    @endif
</head>
<body class="min-h-full bg-slate-100">
<div class="flex min-h-screen flex-col">
    <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/70 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-slate-900">URL Shortener</a>
            <nav class="flex items-center gap-4 text-sm font-medium">
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
                    <a href="{{ route('urls.index') }}" class="hover:text-indigo-600">Short URLs</a>
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                        <a href="{{ route('invitations.index') }}" class="hover:text-indigo-600">Invitations</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-slate-500 transition hover:text-rose-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-10 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-sm">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-slate-200/80 bg-white/70 py-6 text-sm text-slate-500">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <p>&copy; {{ now()->year }} URL Shortener. All rights reserved.</p>
        </div>
    </footer>
</div>
</body>
</html>
