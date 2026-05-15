@extends('layouts.app')

@section('title', 'Properties')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Browse properties</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ $properties->total() }} {{ Str::plural('listing', $properties->total()) }} match your filters.
                </p>
            </div>

            @auth
                @if (auth()->user()->isAgent() || auth()->user()->isAdmin())
                    <x-button as="a" href="{{ route('properties.create') }}">List a property</x-button>
                @endif
            @endauth
        </div>

        <x-card class="mt-6 p-5">
            <form method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                <div class="lg:col-span-2">
                    <x-input name="q" :value="$filters['q'] ?? ''" placeholder="Search by title, city, address…" />
                </div>
                <x-select name="type" :options="$types" :value="$filters['type'] ?? null" placeholder="Any type" />
                <x-select name="status" :options="$statuses" :value="$filters['status'] ?? null" placeholder="Any status" />
                <x-input name="city" :value="$filters['city'] ?? ''" placeholder="City" />
                <x-input name="bedrooms" type="number" min="0" :value="$filters['bedrooms'] ?? ''" placeholder="Min beds" />
                <x-input name="price_min" type="number" :value="$filters['price_min'] ?? ''" placeholder="Min price" />
                <x-input name="price_max" type="number" :value="$filters['price_max'] ?? ''" placeholder="Max price" />
                <x-input name="area_min" type="number" :value="$filters['area_min'] ?? ''" placeholder="Min area" />
                <x-input name="area_max" type="number" :value="$filters['area_max'] ?? ''" placeholder="Max area" />
                <x-select name="sort" :options="$sorts" :value="$filters['sort'] ?? 'newest'" />
                <div class="flex gap-2 lg:col-span-2">
                    <x-button type="submit" class="w-full">Apply</x-button>
                    <x-button as="a" href="{{ route('properties.index') }}" variant="outline" class="w-full">Reset</x-button>
                </div>
            </form>
        </x-card>

        @if (empty($filters['q']) && empty($filters['type']) && $featured->isNotEmpty())
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

            <section class="mt-10">
                <div class="mb-3 flex items-center gap-2">
                    <h2 class="text-lg font-semibold">Recently added</h2>
                </div>
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
                <x-alert tone="info">No properties match your filters.</x-alert>
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
