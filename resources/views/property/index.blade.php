@extends('layouts.app')

@section('title', 'Properties')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Browse properties</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ $properties->total() }} active {{ Str::plural('listing', $properties->total()) }}.
                </p>
            </div>

            @auth
                @if (auth()->user()->isAgent() || auth()->user()->isAdmin())
                    <x-button as="a" href="{{ route('properties.create') }}">List a property</x-button>
                @endif
            @endauth
        </div>

        <div class="mt-6 flex flex-wrap gap-2">
            <a href="{{ route('properties.index') }}"
               class="rounded-full border px-3 py-1 text-xs font-medium {{ ! $typeFilter ? 'border-zinc-900 bg-zinc-900 text-white dark:border-white dark:bg-white dark:text-zinc-900' : 'border-zinc-300 text-zinc-600 hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">All</a>
            @foreach ($types as $value => $label)
                <a href="{{ route('properties.index', ['type' => $value]) }}"
                   class="rounded-full border px-3 py-1 text-xs font-medium {{ $typeFilter === $value ? 'border-zinc-900 bg-zinc-900 text-white dark:border-white dark:bg-white dark:text-zinc-900' : 'border-zinc-300 text-zinc-600 hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if ($featured->isNotEmpty())
            <section class="mt-10">
                <div class="mb-3 flex items-center gap-2">
                    <h2 class="text-lg font-semibold">Featured listings</h2>
                    <x-badge tone="amber">Hand-picked</x-badge>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featured as $property)
                        <x-property-card :property="$property" />
                    @endforeach
                </div>
            </section>
        @endif

        @if ($recentlyAdded->isNotEmpty())
            <section class="mt-10">
                <h2 class="mb-3 text-lg font-semibold">Recently added</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($recentlyAdded as $property)
                        <x-property-card :property="$property" />
                    @endforeach
                </div>
            </section>
        @endif

        <section class="mt-12">
            <h2 class="mb-3 text-lg font-semibold">All listings</h2>
            @if ($properties->isEmpty())
                <x-alert tone="info">No properties match your selection yet.</x-alert>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($properties as $property)
                        <x-property-card :property="$property" />
                    @endforeach
                </div>
                <div class="mt-6">{{ $properties->links() }}</div>
            @endif
        </section>
    </div>
@endsection
