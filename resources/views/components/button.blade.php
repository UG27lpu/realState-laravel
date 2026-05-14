@props([
    'variant' => 'primary',
    'size' => 'md',
    'as' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-xl font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-zinc-50 dark:focus:ring-offset-zinc-950 disabled:opacity-60 disabled:cursor-not-allowed';

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-3 text-base',
    ];

    $variants = [
        'primary' => 'bg-zinc-900 text-white hover:bg-zinc-700 focus:ring-zinc-900 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 dark:focus:ring-white',
        'secondary' => 'bg-zinc-100 text-zinc-900 hover:bg-zinc-200 focus:ring-zinc-300 dark:bg-zinc-800 dark:text-zinc-100 dark:hover:bg-zinc-700',
        'ghost' => 'text-zinc-700 hover:bg-zinc-100 focus:ring-zinc-200 dark:text-zinc-300 dark:hover:bg-zinc-900',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-500 focus:ring-rose-500',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-500 focus:ring-emerald-500',
        'outline' => 'border border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-50 focus:ring-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800',
    ];

    $classes = $base.' '.($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($as === 'a')
    <a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>{{ $slot }}</button>
@endif
