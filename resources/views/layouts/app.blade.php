<!doctype html>
<html lang="en" class="h-full"
      x-data="{ dark: localStorage.getItem('estatify-theme') === 'dark' || (!localStorage.getItem('estatify-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
      :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $appName ?? 'Estatify') &middot; {{ $appName ?? 'Estatify' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-full bg-zinc-50 text-zinc-900 antialiased transition-colors duration-300 dark:bg-zinc-950 dark:text-zinc-100">
    <div class="flex min-h-screen flex-col">
        @include('layouts.partials.navbar')

        <main class="flex-1">
            @if (session('status'))
                <div class="mx-auto mt-4 max-w-7xl px-4">
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        @include('layouts.partials.footer')
    </div>

    @stack('scripts')
</body>
</html>
