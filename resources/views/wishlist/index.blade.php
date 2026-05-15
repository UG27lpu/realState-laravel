@extends('layouts.app')

@section('title', 'My wishlist')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold tracking-tight">My wishlist</h1>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Properties you've saved for later.</p>

        @if ($items->isEmpty())
            <x-alert class="mt-6" tone="info">Nothing saved yet. Browse listings and tap the heart to save them here.</x-alert>
        @else
            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($items as $property)
                    <div class="relative">
                        <x-property-card :property="$property" />
                        <form method="POST" action="{{ route('wishlist.toggle', $property) }}" class="absolute right-3 top-3">
                            @csrf
                            <button class="rounded-full bg-white/90 p-1.5 text-rose-500 shadow hover:bg-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21s-7-4.35-10-9.5C-1.6 4 6 0 12 6c6-6 13.6-2 10 5.5C19 16.65 12 21 12 21z"/></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
