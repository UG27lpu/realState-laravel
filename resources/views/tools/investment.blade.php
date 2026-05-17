@extends('layouts.app')

@section('title', 'Investment return calculator')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold tracking-tight">Investment return calculator</h1>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Project your property's value over time with optional yearly top-ups and rental yield.</p>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <x-card class="p-6 lg:col-span-1">
                <form method="GET" class="space-y-4">
                    <x-input label="Initial outlay ({{ config('estatify.currency.code') }})" name="principal" type="number" min="1" :value="$input['principal'] ?? 8000000" required />
                    <x-input label="Expected appreciation (% per year)" name="growth" type="number" step="0.01" min="0" max="50" :value="$input['growth'] ?? 7" required />
                    <x-input label="Horizon (years)" name="years" type="number" min="1" max="50" :value="$input['years'] ?? 10" required />
                    <x-input label="Annual top-up (optional)" name="top_up" type="number" min="0" :value="$input['top_up'] ?? 0" />
                    <x-input label="Rental yield (% per year, optional)" name="rental" type="number" step="0.01" min="0" max="30" :value="$input['rental'] ?? 3.5" />
                    <x-button type="submit" class="w-full">Project</x-button>
                </form>
            </x-card>

            <div class="lg:col-span-2 space-y-4">
                @if ($result)
                    <x-card class="p-6">
                        <p class="text-xs uppercase tracking-wider text-zinc-500">Projected value</p>
                        <p class="mt-1 text-3xl font-bold">{{ config('estatify.currency.symbol') }}{{ number_format($result['final_value']) }}</p>
                        <div class="mt-4 grid grid-cols-2 gap-4 text-sm sm:grid-cols-4">
                            <div><p class="text-xs text-zinc-500">Total invested</p><p class="font-semibold">{{ config('estatify.currency.symbol') }}{{ number_format($result['total_invested']) }}</p></div>
                            <div><p class="text-xs text-zinc-500">Rental income</p><p class="font-semibold">{{ config('estatify.currency.symbol') }}{{ number_format($result['total_rental']) }}</p></div>
                            <div><p class="text-xs text-zinc-500">Total return</p><p class="font-semibold">{{ config('estatify.currency.symbol') }}{{ number_format($result['total_return']) }}</p></div>
                            <div><p class="text-xs text-zinc-500">ROI</p><p class="font-semibold">{{ $result['roi_percent'] }}%</p></div>
                        </div>
                    </x-card>

                    <x-card class="p-6">
                        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-zinc-500">Year-by-year</h2>
                        <table class="w-full text-xs">
                            <thead class="text-zinc-500">
                                <tr><th class="py-1 text-left">Year</th><th class="py-1 text-right">Value</th><th class="py-1 text-right">Rental</th><th class="py-1 text-right">Invested</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($result['schedule'] as $row)
                                    <tr class="border-t border-zinc-100 dark:border-zinc-800">
                                        <td class="py-1">Year {{ $row['year'] }}</td>
                                        <td class="py-1 text-right">{{ number_format($row['value']) }}</td>
                                        <td class="py-1 text-right">{{ number_format($row['rental']) }}</td>
                                        <td class="py-1 text-right">{{ number_format($row['invested']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-card>
                @else
                    <x-alert tone="info">Fill the form on the left to see the projection.</x-alert>
                @endif
            </div>
        </div>
    </div>
@endsection
