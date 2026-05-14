@props([
    'tone' => 'zinc',
])

@php
    $tones = [
        'zinc'    => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200',
        'green'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
        'blue'    => 'bg-sky-100 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300',
        'amber'   => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
        'red'     => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300',
        'indigo'  => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300',
        'purple'  => 'bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300',
        'demo'    => 'bg-gradient-to-r from-amber-100 to-rose-100 text-amber-800 dark:from-amber-500/10 dark:to-rose-500/10 dark:text-amber-200',
    ];
    $tone = $tones[$tone] ?? $tones['zinc'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium '.$tone]) }}>
    {{ $slot }}
</span>
