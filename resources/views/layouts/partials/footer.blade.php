<footer class="border-t border-zinc-200/70 bg-white/60 py-8 backdrop-blur dark:border-zinc-800/70 dark:bg-zinc-950/60">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 text-sm text-zinc-500 dark:text-zinc-400 sm:flex-row sm:px-6 lg:px-8">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Estatify') }}. Demo educational build.</p>
        <p class="flex items-center gap-3">
            <a href="{{ route('pages.about') }}" class="hover:text-zinc-900 dark:hover:text-white">About</a>
            <span aria-hidden="true">&middot;</span>
            <a href="{{ route('pages.legal') }}" class="hover:text-zinc-900 dark:hover:text-white">Demo disclosure</a>
        </p>
    </div>
</footer>
