@extends('layouts.app')

@section('title', $property->title)

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <x-badge tone="indigo">{{ $property->type?->label() }}</x-badge>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 font-medium {{ $property->status?->badgeClasses() }}">
                        {{ $property->status?->label() }}
                    </span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 font-medium {{ $property->approval_status?->badgeClasses() }}">
                        {{ $property->approval_status?->label() }}
                    </span>
                    @if ($property->is_featured)
                        <x-badge tone="amber">Featured</x-badge>
                    @endif
                </div>
                <h1 class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">{{ $property->title }}</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $property->address }}, {{ $property->city }}</p>
            </div>

            <div class="text-right">
                <p class="text-3xl font-bold">{{ config('estatify.currency.symbol', '₹') }}{{ number_format((float) $property->price) }}</p>
                <p class="text-xs text-zinc-500">{{ ucfirst(str_replace('_', ' ', $property->status?->value)) }}</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
                     x-data="{
                        active: 0,
                        autoplay: false,
                        timer: null,
                        images: @js($property->images->map(fn($i) => $i->url())->all() ?: [$property->coverUrl()]),
                        next() { this.active = (this.active + 1) % this.images.length; },
                        prev() { this.active = (this.active - 1 + this.images.length) % this.images.length; },
                        toggle360() {
                            this.autoplay = !this.autoplay;
                            if (this.autoplay) { this.timer = setInterval(() => this.next(), 1100); }
                            else { clearInterval(this.timer); }
                        }
                     }">
                    <div class="relative">
                        <template x-for="(src, idx) in images" :key="idx">
                            <img x-show="active === idx" :src="src" class="aspect-[16/9] w-full object-cover" alt="">
                        </template>
                        <button type="button" @click="prev()" class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-1.5 shadow hover:bg-white">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                        </button>
                        <button type="button" @click="next()" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-1.5 shadow hover:bg-white">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                        </button>
                        <button type="button" @click="toggle360()"
                                class="absolute bottom-2 right-2 inline-flex items-center gap-1 rounded-full bg-zinc-900/80 px-3 py-1 text-xs font-medium text-white backdrop-blur hover:bg-zinc-900">
                            <span x-show="!autoplay">Start 360° tour</span>
                            <span x-show="autoplay" x-cloak>Stop tour</span>
                        </button>
                    </div>
                    @if ($property->images->count() > 1)
                        <div class="grid grid-cols-4 gap-2 p-2 sm:grid-cols-6">
                            @foreach ($property->images->take(12) as $idx => $image)
                                <button type="button" @click="active = {{ $idx }}"
                                        :class="active === {{ $idx }} ? 'ring-2 ring-indigo-500' : ''"
                                        class="overflow-hidden rounded-lg">
                                    <img src="{{ $image->url() }}" alt="" class="aspect-square w-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if ($property->videos->isNotEmpty())
                    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                        <video src="{{ $property->videos->first()->url() }}" controls class="aspect-video w-full"></video>
                    </div>
                @endif

                <x-card class="p-6">
                    <h2 class="text-lg font-semibold">About this property</h2>
                    <p class="mt-2 whitespace-pre-line text-sm text-zinc-700 dark:text-zinc-300">
                        {{ $property->description ?: 'No description provided yet.' }}
                    </p>
                </x-card>

                <x-card class="p-6">
                    <h2 class="mb-4 text-lg font-semibold">Key details</h2>
                    <dl class="grid grid-cols-2 gap-y-3 text-sm sm:grid-cols-3">
                        <div><dt class="text-xs text-zinc-500">Type</dt><dd>{{ $property->type?->label() }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Area</dt><dd>{{ $property->area ? number_format((float)$property->area).' '.$property->area_unit : '—' }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Bedrooms</dt><dd>{{ $property->bedrooms ?? '—' }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Bathrooms</dt><dd>{{ $property->bathrooms ?? '—' }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Floors</dt><dd>{{ $property->floors ?? '—' }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Year built</dt><dd>{{ $property->year_built ?? '—' }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Furnished</dt><dd>{{ $property->furnished ? 'Yes' : 'No' }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Parking</dt><dd>{{ $property->parking ? 'Yes' : 'No' }}</dd></div>
                        <div><dt class="text-xs text-zinc-500">Survey #</dt><dd>{{ $property->survey_number ?? '—' }}</dd></div>
                    </dl>
                </x-card>

                @if ($property->documents->isNotEmpty())
                    <x-card class="p-6">
                        <div class="mb-3 flex items-center gap-2">
                            <h2 class="text-lg font-semibold">Documents</h2>
                            <x-demo-tag label="Sample documents only" />
                        </div>
                        <ul class="space-y-2">
                            @foreach ($property->documents as $doc)
                                <li class="flex items-center justify-between rounded-xl border border-zinc-200 p-3 text-sm dark:border-zinc-800">
                                    <div>
                                        <p class="font-medium">{{ $doc->label }}</p>
                                        <p class="text-xs text-zinc-500">{{ ucfirst($doc->type) }} &middot; <x-demo-tag label="Demo" /></p>
                                    </div>
                                    <a href="{{ $doc->url() }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">Open</a>
                                </li>
                            @endforeach
                        </ul>
                    </x-card>
                @endif

                <x-card class="p-6">
                    <h2 class="mb-3 text-lg font-semibold">Location</h2>
                    <p class="mb-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $property->address }}, {{ $property->city }} {{ $property->pincode }}</p>
                    <x-property-map :lat="$property->latitude" :lng="$property->longitude" :label="$property->title" />
                </x-card>

                @if (! empty($property->nearby_facilities))
                    <x-card class="p-6">
                        <h2 class="mb-3 text-lg font-semibold">Nearby</h2>
                        <ul class="flex flex-wrap gap-2 text-sm">
                            @foreach ($property->nearby_facilities as $facility)
                                <li class="rounded-full border border-zinc-200 px-3 py-1 text-zinc-700 dark:border-zinc-800 dark:text-zinc-300">{{ $facility }}</li>
                            @endforeach
                        </ul>
                    </x-card>
                @endif
            </div>

            <aside class="space-y-4">
                <x-card class="p-6">
                    <div class="flex items-center gap-2">
                        <p class="text-xs uppercase tracking-wider text-zinc-500">Estimated value</p>
                        <x-demo-tag label="Demo only" />
                    </div>
                    <p class="mt-2 text-2xl font-semibold">
                        {{ config('estatify.currency.symbol') }}{{ number_format($priceDemo['estimate']) }}
                    </p>
                    <p class="text-xs text-zinc-500">
                        Range: {{ config('estatify.currency.symbol') }}{{ number_format($priceDemo['low']) }}
                        – {{ config('estatify.currency.symbol') }}{{ number_format($priceDemo['high']) }}
                        &middot; ~{{ config('estatify.currency.symbol') }}{{ number_format($priceDemo['per_sqft']) }}/{{ $property->area_unit }}
                    </p>
                    <p class="mt-2 text-[11px] text-zinc-500">{{ $priceDemo['note'] }}</p>
                </x-card>

                <x-card class="p-6">
                    <div class="flex items-center gap-2">
                        <p class="text-xs uppercase tracking-wider text-zinc-500">Legal verification</p>
                        <x-demo-tag label="Simulated" />
                    </div>
                    <div class="mt-2">
                        <x-badge :tone="app(\App\Services\Demo\LegalVerificationService::class)->badgeTone($legalDemo['status'])">
                            {{ app(\App\Services\Demo\LegalVerificationService::class)->statusLabel($legalDemo['status']) }}
                        </x-badge>
                    </div>
                    @if (! empty($legalDemo['reasons']))
                        <ul class="mt-3 space-y-1 text-xs text-zinc-600 dark:text-zinc-400">
                            @foreach ($legalDemo['reasons'] as $reason)
                                <li>&bull; {{ $reason }}</li>
                            @endforeach
                        </ul>
                    @endif
                </x-card>

                <x-card class="p-6">
                    <div class="flex items-center gap-2">
                        <p class="text-xs uppercase tracking-wider text-zinc-500">QR verification</p>
                        <x-demo-tag label="Demo" />
                    </div>
                    <img src="{{ route('verify.qr', $property) }}" alt="QR" class="mt-3 h-32 w-32 rounded-xl border border-zinc-200 bg-white p-2 dark:border-zinc-800 dark:bg-zinc-900">
                    <a href="{{ route('verify.show', $property) }}" class="mt-2 block text-xs text-indigo-600 hover:underline dark:text-indigo-400">Open verification page &rarr;</a>
                </x-card>

                @auth
                    @if (auth()->id() !== $property->owner_id)
                        <x-card class="p-6">
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-zinc-500">Book a visit</h2>
                            <form method="POST" action="{{ route('appointments.store', $property) }}" class="mt-3 space-y-3">
                                @csrf
                                <input type="datetime-local" name="scheduled_for" required min="{{ now()->format('Y-m-d\TH:i') }}"
                                       class="block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                                <textarea name="notes" rows="2" placeholder="Any notes for the agent?"
                                       class="block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900"></textarea>
                                <x-button type="submit" class="w-full">Request appointment</x-button>
                            </form>
                        </x-card>
                    @endif
                @endauth

                <x-card class="p-6">
                    <p class="text-xs uppercase tracking-wider text-zinc-500">Listed by</p>
                    <div class="mt-2 flex items-center gap-3">
                        <img src="{{ $property->owner?->avatarUrl() }}" alt="" class="h-10 w-10 rounded-full">
                        <div>
                            <p class="text-sm font-semibold">{{ $property->owner?->name }}</p>
                            <p class="text-xs text-zinc-500">{{ $property->owner?->agency_name ?? 'Independent agent' }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-col gap-2">
                        @auth
                            @if (auth()->id() !== $property->owner_id)
                                <form method="POST" action="{{ route('chat.start', $property) }}">
                                    @csrf
                                    <x-button type="submit" variant="primary" class="w-full">Message agent</x-button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('wishlist.toggle', $property) }}">
                                @csrf
                                <x-button type="submit" variant="outline" class="w-full">
                                    @if (auth()->user()->wishlistedProperties()->where('property_id', $property->id)->exists())
                                        Saved to wishlist
                                    @else
                                        Save to wishlist
                                    @endif
                                </x-button>
                            </form>
                        @else
                            <x-button as="a" href="{{ route('login') }}" variant="primary" class="w-full">Sign in to message agent</x-button>
                        @endauth
                        <form method="POST" action="{{ route('compare.add', $property) }}">
                            @csrf
                            <x-button type="submit" variant="ghost" class="w-full">Add to compare</x-button>
                        </form>
                        @auth
                            @can('update', $property)
                                <x-button as="a" href="{{ route('properties.edit', $property) }}" variant="ghost" class="w-full">Edit listing</x-button>
                            @endcan
                        @endauth
                    </div>
                </x-card>
            </aside>
        </div>

        @if ($related->isNotEmpty())
            <section class="mt-12">
                <div class="mb-3 flex items-center gap-2">
                    <h2 class="text-lg font-semibold">Recommended for you</h2>
                    <x-demo-tag label="AI demo" />
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($related as $p)
                        <x-property-card :property="$p" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
