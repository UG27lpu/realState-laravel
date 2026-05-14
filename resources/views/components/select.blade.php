@props([
    'label' => null,
    'name',
    'options' => [],
    'value' => null,
    'placeholder' => null,
])

<label class="block">
    @if ($label)
        <span class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $label }}</span>
    @endif

    <select
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100',
        ]) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>

    @error($name)
        <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
    @enderror
</label>
