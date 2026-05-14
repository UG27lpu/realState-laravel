@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'hint' => null,
    'required' => false,
])

@php
    $errorKey = $name;
@endphp

<label class="block">
    @if ($label)
        <span class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
            {{ $label }} @if ($required)<span class="text-rose-500">*</span>@endif
        </span>
    @endif

    <input
        {{ $attributes->merge([
            'type' => $type,
            'name' => $name,
            'value' => old($name, $value),
            'class' => 'block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-zinc-400 focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 disabled:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:placeholder:text-zinc-500 dark:focus:border-zinc-400 dark:focus:ring-zinc-400',
        ]) }}
    />

    @error($errorKey)
        <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
    @enderror

    @if ($hint)
        <span class="mt-1 block text-xs text-zinc-500 dark:text-zinc-400">{{ $hint }}</span>
    @endif
</label>
