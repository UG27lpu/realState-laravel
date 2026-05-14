@props([
    'label' => 'Demo only',
])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full bg-gradient-to-r from-amber-400/20 to-rose-400/20 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-amber-700 ring-1 ring-amber-400/30 dark:text-amber-200']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
    {{ $label }}
</span>
