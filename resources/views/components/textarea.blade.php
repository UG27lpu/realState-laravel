@props([
    'label' => null,
    'name',
    'rows' => 4,
    'value' => null,
    'hint' => null,
])

<label class="block">
    @if ($label)
        <span class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $label }}</span>
    @endif

    <textarea
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge([
            'class' => 'block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-zinc-400 focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:placeholder:text-zinc-500',
        ]) }}>{{ old($name, $value) }}</textarea>

    @error($name)
        <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
    @enderror

    @if ($hint)
        <span class="mt-1 block text-xs text-zinc-500 dark:text-zinc-400">{{ $hint }}</span>
    @endif
</label>
