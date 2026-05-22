@extends('layouts.app')

@section('title', 'Find your next home')

@section('content')
    {{-- Hero --}}
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

    {{-- Stats strip --}}
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

    {{-- Browse by type --}}
    @php
        $typeImages = [
            'house'      => 'photo-1600585154340-be6161a56a0c',
            'apartment'  => 'photo-1493809842364-78817add7ffb',
            'commercial' => 'photo-1497366216548-37526070297c',
            'land'       => 'photo-1500382017468-9049fed747ef',
        ];
    @endphp
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <h2 class="mb-6 text-2xl font-bold tracking-tight">Browse by type</h2>
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach (\App\Enums\PropertyType::cases() as $ptype)
                @php
                    $imgId = $typeImages[$ptype->value] ?? 'photo-1600585154340-be6161a56a0c';
                    $count = \Illuminate\Support\Facades\Schema::hasTable('properties')
                        ? \App\Models\Property::visible()->where('type', $ptype->value)->count()
                        : 0;
                @endphp
                <a href="{{ route('properties.index', ['type' => $ptype->value]) }}"
                   class="group relative aspect-[4/3] overflow-hidden rounded-2xl">
                    <img src="https://images.unsplash.com/{{ $imgId }}?auto=format&fit=crop&w=600&q=80"
                         alt="{{ $ptype->label() }}"
                         class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                         loading="lazy" decoding="async">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-4 text-white">
                        <p class="text-sm font-semibold">{{ $ptype->label() }}</p>
                        <p class="text-xs opacity-75">{{ $count }} {{ Str::plural('listing', $count) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured properties --}}
    @php
        $featuredProps = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('properties')) {
            $featuredProps = \App\Models\Property::with('images')
                ->visible()
                ->featured()
                ->latest('approved_at')
                ->take(3)
                ->get();
            if ($featuredProps->isEmpty()) {
                $featuredProps = \App\Models\Property::with('images')
                    ->visible()
                    ->latest('approved_at')
                    ->take(3)
                    ->get();
            }
        }
    @endphp
    @if ($featuredProps->isNotEmpty())
        <section class="border-t border-zinc-200/70 bg-zinc-50/60 py-16 dark:border-zinc-800/70 dark:bg-zinc-950/60">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-6 flex items-end justify-between">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight">Featured properties</h2>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Hand-picked listings from top agents</p>
                    </div>
                    <x-button as="a" href="{{ route('properties.index') }}" variant="outline" size="sm">View all &rarr;</x-button>
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredProps as $fp)
                        <x-property-card :property="$fp" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Platform highlights --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            @foreach ([
                [
                    'title' => 'Verified listings',
                    'body'  => 'Every property goes through a moderation step before going live.',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
                ],
                [
                    'title' => 'Built-in calculators',
                    'body'  => 'EMI and investment return calculators with full amortisation tables.',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z"/>',
                ],
                [
                    'title' => 'Side-by-side compare',
                    'body'  => 'Shortlist up to four properties and compare them on every spec.',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>',
                ],
            ] as $feature)
                <x-card class="dashboard-card-hover p-6">
                    <svg class="mb-3 h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        {!! $feature['icon'] !!}
                    </svg>
                    <h3 class="font-semibold">{{ $feature['title'] }}</h3>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $feature['body'] }}</p>
                </x-card>
            @endforeach
        </div>
    </section>
@endsection
