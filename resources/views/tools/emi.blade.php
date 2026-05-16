@extends('layouts.app')

@section('title', 'EMI calculator')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold tracking-tight">EMI calculator</h1>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Estimate your monthly home loan instalment.</p>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <x-card class="p-6 lg:col-span-1">
                <form method="GET" class="space-y-4">
                    <x-input label="Loan amount ({{ config('estatify.currency.code') }})" name="principal" type="number" step="0.01" min="1" :value="$input['principal'] ?? 5000000" required />
                    <x-input label="Annual interest rate (%)" name="rate" type="number" step="0.01" min="0" :value="$input['rate'] ?? 8.5" required />
                    <x-input label="Tenure (months)" name="tenure" type="number" min="1" max="480" :value="$input['tenure'] ?? 240" required />
                    <x-button type="submit" class="w-full">Calculate</x-button>
                </form>
            </x-card>

            <div class="lg:col-span-2 space-y-4">
                @if ($result)
                    <x-card class="p-6">
                        <p class="text-xs uppercase tracking-wider text-zinc-500">Monthly EMI</p>
                        <p class="mt-1 text-3xl font-bold">{{ config('estatify.currency.symbol') }}{{ number_format($result['monthly_emi']) }}</p>
                        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-zinc-500">Total interest</p>
                                <p class="font-semibold">{{ config('estatify.currency.symbol') }}{{ number_format($result['total_interest']) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-zinc-500">Total payable</p>
                                <p class="font-semibold">{{ config('estatify.currency.symbol') }}{{ number_format($result['total_payable']) }}</p>
                            </div>
                        </div>
                    </x-card>

                    <x-card class="p-6">
                        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-zinc-500">First-year amortisation</h2>
                        <table class="w-full text-xs">
                            <thead class="text-zinc-500">
                                <tr><th class="py-1 text-left">Month</th><th class="py-1 text-right">Principal</th><th class="py-1 text-right">Interest</th><th class="py-1 text-right">Balance</th></tr>
                            </thead>
                            <tbody>
                                @foreach (array_slice($result['schedule'], 0, 12) as $row)
                                    <tr class="border-t border-zinc-100 dark:border-zinc-800">
                                        <td class="py-1">#{{ $row['month'] }}</td>
                                        <td class="py-1 text-right">{{ number_format($row['principal']) }}</td>
                                        <td class="py-1 text-right">{{ number_format($row['interest']) }}</td>
                                        <td class="py-1 text-right">{{ number_format($row['balance']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-card>
                @else
                    <x-alert tone="info">Enter your loan details on the left to see EMI and amortisation.</x-alert>
                @endif
            </div>
        </div>
    </div>
@endsection
