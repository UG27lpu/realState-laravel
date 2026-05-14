@props([
    'tone' => 'info',
    'title' => null,
])

@php
    $tones = [
        'info'    => 'border-sky-200 bg-sky-50 text-sky-800 dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-200',
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200',
        'error'   => 'border-rose-200 bg-rose-50 text-rose-800 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200',
        'demo'    => 'border-purple-200 bg-gradient-to-r from-purple-50 to-amber-50 text-purple-900 dark:border-purple-500/30 dark:from-purple-500/10 dark:to-amber-500/10 dark:text-purple-200',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border p-4 text-sm '.($tones[$tone] ?? $tones['info'])]) }}>
    @if ($title)
        <p class="mb-1 font-medium">{{ $title }}</p>
    @endif
    {{ $slot }}
</div>
