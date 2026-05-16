<header class="sticky top-0 z-40 border-b border-zinc-200/70 bg-white/80 backdrop-blur dark:border-zinc-800/70 dark:bg-zinc-950/80">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold tracking-tight">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 3l9 6.5"/><path d="M5 9.5V21h14V9.5"/><path d="M9 21v-7h6v7"/></svg>
            </span>
            <span class="text-lg">{{ config('app.name', 'Estatify') }}</span>
        </a>

        <nav class="hidden items-center gap-1 text-sm md:flex">
            <a href="{{ route('properties.index') }}" class="rounded-lg px-3 py-2 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-900 dark:hover:text-white">Properties</a>
            <a href="{{ route('compare.index') }}" class="rounded-lg px-3 py-2 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-900 dark:hover:text-white">Compare</a>
            <a href="{{ route('tools.emi') }}" class="rounded-lg px-3 py-2 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-900 dark:hover:text-white">EMI</a>
            <a href="{{ route('tools.investment') }}" class="rounded-lg px-3 py-2 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-900 dark:hover:text-white">Investment</a>
            <a href="{{ route('pages.about') }}" class="rounded-lg px-3 py-2 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-900 dark:hover:text-white">About</a>
        </nav>

        <div class="flex items-center gap-2">
            <button type="button"
                    @click="dark = !dark; localStorage.setItem('estatify-theme', dark ? 'dark' : 'light')"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-200 text-zinc-600 transition hover:bg-zinc-100 dark:border-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-900"
                    aria-label="Toggle theme">
                <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                <svg x-show="dark" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
            </button>

            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 rounded-lg border border-zinc-200 px-2 py-1.5 text-sm hover:bg-zinc-100 dark:border-zinc-800 dark:hover:bg-zinc-900">
                        <img src="{{ auth()->user()->avatarUrl() }}" alt="" class="h-6 w-6 rounded-full"/>
                        <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl border border-zinc-200 bg-white p-1 text-sm shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-800">Admin dashboard</a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-800">My dashboard</a>
                        <a href="{{ route('wishlist.index') }}" class="block rounded-lg px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-800">Wishlist</a>
                        <a href="{{ route('chat.index') }}" class="block rounded-lg px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-800">Messages</a>
                        <a href="{{ route('appointments.index') }}" class="block rounded-lg px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-800">Appointments</a>
                        <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-800">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full rounded-lg px-3 py-2 text-left text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10">Log out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="hidden rounded-lg px-3 py-2 text-sm text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-900 sm:inline">Log in</a>
                <a href="{{ route('register') }}" class="rounded-lg bg-zinc-900 px-3 py-2 text-sm font-medium text-white hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">Get started</a>
            @endauth
        </div>
    </div>
</header>
