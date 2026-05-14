@props(['property'])

<a href="{{ route('properties.show', $property) }}"
   class="group block overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
    <div class="relative aspect-[4/3] overflow-hidden bg-zinc-100 dark:bg-zinc-800">
        <img src="{{ $property->coverUrl() }}" alt="{{ $property->title }}"
             class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
             loading="lazy">

        <div class="absolute left-3 top-3 flex flex-wrap gap-1">
            <x-badge tone="indigo">{{ $property->type?->label() }}</x-badge>
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $property->status?->badgeClasses() }}">
                {{ $property->status?->label() }}
            </span>
            @if ($property->is_featured)
                <x-badge tone="amber">Featured</x-badge>
            @endif
        </div>
    </div>

    <div class="p-4">
        <h3 class="line-clamp-1 text-base font-semibold text-zinc-900 dark:text-zinc-100">{{ $property->title }}</h3>
        <p class="mt-1 line-clamp-1 text-xs text-zinc-500 dark:text-zinc-400">{{ $property->address }}, {{ $property->city }}</p>

        <div class="mt-3 flex items-center justify-between">
            <p class="text-lg font-bold text-zinc-900 dark:text-white">
                {{ config('estatify.currency.symbol', '₹') }}{{ number_format((float) $property->price) }}
            </p>
            <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                @if ($property->bedrooms !== null)
                    <span>{{ $property->bedrooms }} bd</span>
                @endif
                @if ($property->bathrooms !== null)
                    <span>{{ $property->bathrooms }} ba</span>
                @endif
                @if ($property->area)
                    <span>{{ number_format((float) $property->area) }} {{ $property->area_unit }}</span>
                @endif
            </div>
        </div>
    </div>
</a>
