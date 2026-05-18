@extends('layouts.app')

@section('title', 'Find your next home')

@section('content')
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-32 left-1/2 h-96 w-[40rem] -translate-x-1/2 rounded-full bg-gradient-to-br from-indigo-400/30 via-fuchsia-400/20 to-rose-400/30 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-gradient-to-br from-emerald-400/30 to-sky-400/30 blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white/60 px-3 py-1 text-xs font-medium text-zinc-700 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/60 dark:text-zinc-300">
                    Modern listings, real workflow
                </span>
                <h1 class="mt-6 text-4xl font-bold tracking-tight sm:text-6xl">
                    Find the place that fits your <span class="bg-gradient-to-r from-indigo-500 via-purple-500 to-rose-500 bg-clip-text text-transparent">next chapter</span>.
                </h1>
                <p class="mt-6 text-base text-zinc-600 dark:text-zinc-300">
                    Browse vetted listings, talk to agents, run the numbers with built-in
                    calculators, and keep your shortlist in one place.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <x-button as="a" href="{{ route('properties.index') }}" size="lg">Browse properties</x-button>
                    <x-button as="a" href="{{ route('register') }}" size="lg" variant="outline">Create an account</x-button>
                </div>
            </div>

            <form method="GET" action="{{ route('properties.index') }}" class="mx-auto mt-10 max-w-3xl">
                <div class="glass flex flex-col gap-2 rounded-2xl p-2 sm:flex-row">
                    <input name="q" placeholder="Search by city, neighbourhood, title…"
                           class="flex-1 rounded-xl border border-transparent bg-white/50 px-4 py-3 text-sm placeholder:text-zinc-500 focus:bg-white focus:outline-none dark:bg-zinc-900/40 dark:focus:bg-zinc-900">
                    <select name="type" class="rounded-xl border border-transparent bg-white/50 px-4 py-3 text-sm dark:bg-zinc-900/40">
                        <option value="">Any type</option>
                        @foreach (\App\Enums\PropertyType::options() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-button type="submit" size="lg">Search</x-button>
                </div>
            </form>
        </div>
    </section>

    @php
        $stats = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('properties')) {
            $stats[] = ['Listings', \App\Models\Property::query()->visible()->count()];
            $stats[] = ['Cities', \App\Models\Property::query()->visible()->distinct()->count('city')];
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
            $stats[] = ['Active agents', \App\Models\User::role('agent')->count()];
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('wishlists')) {
            $stats[] = ['Saved by users', \App\Models\Wishlist::query()->count()];
        }
    @endphp
    @if (count($stats))
        <section class="border-y border-zinc-200/70 bg-white/60 py-12 backdrop-blur dark:border-zinc-800/70 dark:bg-zinc-950/60">
            <div class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-4 sm:grid-cols-4 sm:px-6 lg:px-8">
                @foreach ($stats as [$label, $value])
                    <div class="glass animate-card-float rounded-2xl p-5 text-center">
                        <p class="text-3xl font-bold">{{ number_format($value) }}</p>
                        <p class="text-xs uppercase tracking-wider text-zinc-500">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            @foreach ([
                ['title' => 'Verified listings', 'body' => 'Every property goes through a moderation step before going live.'],
                ['title' => 'Built-in calculators', 'body' => 'EMI and investment return calculators with full amortisation.'],
                ['title' => 'Side-by-side compare', 'body' => 'Shortlist up to four properties and compare them on every spec.'],
            ] as $feature)
                <x-card class="dashboard-card-hover p-6">
                    <h3 class="font-semibold">{{ $feature['title'] }}</h3>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $feature['body'] }}</p>
                </x-card>
            @endforeach
        </div>
    </section>
@endsection
