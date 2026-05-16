@props([
    'lat' => null,
    'lng' => null,
    'label' => null,
    'zoom' => 14,
    'height' => '320px',
])

@php
    $hasCoords = ! is_null($lat) && ! is_null($lng);
    $googleKey = config('estatify.maps.google_maps_key');
    $latF = $hasCoords ? (float) $lat : null;
    $lngF = $hasCoords ? (float) $lng : null;
@endphp

@if (! $hasCoords)
    <div class="flex items-center justify-center rounded-2xl border border-dashed border-zinc-300 p-6 text-sm text-zinc-500 dark:border-zinc-700"
         style="height: {{ $height }}">
        Location not set yet.
    </div>
@elseif ($googleKey)
    <iframe
        src="https://www.google.com/maps/embed/v1/place?key={{ $googleKey }}&q={{ $latF }},{{ $lngF }}&zoom={{ $zoom }}"
        loading="lazy"
        class="w-full rounded-2xl border border-zinc-200 dark:border-zinc-800"
        style="height: {{ $height }}"
        allowfullscreen></iframe>
@else
    <iframe
        src="https://www.openstreetmap.org/export/embed.html?bbox={{ $lngF - 0.01 }}%2C{{ $latF - 0.01 }}%2C{{ $lngF + 0.01 }}%2C{{ $latF + 0.01 }}&layer=mapnik&marker={{ $latF }}%2C{{ $lngF }}"
        loading="lazy"
        class="w-full rounded-2xl border border-zinc-200 dark:border-zinc-800"
        style="height: {{ $height }}"></iframe>
    <p class="mt-1 text-[11px] text-zinc-500">
        <a href="https://www.openstreetmap.org/?mlat={{ $latF }}&mlon={{ $lngF }}#map={{ $zoom }}/{{ $latF }}/{{ $lngF }}"
           target="_blank" class="hover:underline">View larger map &rarr;</a>
    </p>
@endif
