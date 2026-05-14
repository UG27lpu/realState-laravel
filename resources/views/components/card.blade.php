@props([
    'glass' => false,
    'as' => 'div',
])

@php
    $base = 'rounded-2xl border shadow-sm transition';
    $solid = 'border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900';
    $glassy = 'border-white/20 bg-white/60 backdrop-blur-xl dark:border-white/5 dark:bg-zinc-900/40';
    $classes = $base.' '.($glass ? $glassy : $solid);
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $as }}>
