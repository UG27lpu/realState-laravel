@extends('layouts.app')

@section('title', 'Property verification')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center gap-2">
            <h1 class="text-2xl font-bold tracking-tight">Verification check</h1>
            <x-demo-tag label="Demo verification" />
        </div>

        <x-card class="p-6">
            <div class="flex flex-wrap items-start gap-6">
                <img src="{{ $property->coverUrl() }}" class="h-32 w-44 rounded-xl object-cover" alt="">
                <div class="flex-1 space-y-1">
                    <a href="{{ route('properties.show', $property) }}" class="block text-lg font-semibold hover:underline">{{ $property->title }}</a>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $property->address }}, {{ $property->city }}</p>
                    <p class="text-xs text-zinc-500">Listed by {{ $property->owner?->name }} ({{ $property->owner?->agency_name ?? 'Independent' }})</p>
                </div>
                <img src="{{ route('verify.qr', $property) }}" alt="QR" class="h-32 w-32 rounded-xl border border-zinc-200 bg-white p-2 dark:border-zinc-800 dark:bg-zinc-900">
            </div>

            <hr class="my-6 border-zinc-200 dark:border-zinc-800">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-wider text-zinc-500">Registration status</p>
                    <div class="mt-1">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $property->approval_status?->badgeClasses() }}">
                            {{ $property->approval_status?->label() }}
                        </span>
                    </div>
                    @if ($property->approved_at)
                        <p class="mt-1 text-xs text-zinc-500">Approved on {{ $property->approved_at->format('d M Y') }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-zinc-500">Legal verification (demo)</p>
                    <div class="mt-1">
                        <x-badge :tone="app(\App\Services\Demo\LegalVerificationService::class)->badgeTone($legal['status'])">
                            {{ app(\App\Services\Demo\LegalVerificationService::class)->statusLabel($legal['status']) }}
                        </x-badge>
                    </div>
                    @if (! empty($legal['reasons']))
                        <ul class="mt-1 space-y-1 text-xs text-zinc-500">
                            @foreach ($legal['reasons'] as $r)
                                <li>&bull; {{ $r }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </x-card>

        <x-card class="mt-6 p-6">
            <div class="flex items-center gap-2">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-zinc-500">Digital signature</h2>
                <x-demo-tag label="Simulated" />
            </div>
            <div class="mt-3 rounded-xl border-2 border-dashed border-indigo-300 bg-indigo-50/50 p-5 dark:border-indigo-500/30 dark:bg-indigo-500/5">
                <p class="font-mono text-sm tracking-wider text-indigo-700 dark:text-indigo-300">{{ $signature['reference'] }}</p>
                <p class="mt-2 italic text-sm text-zinc-700 dark:text-zinc-300" style="font-family: 'Brush Script MT', cursive;">{{ $signature['signed_by'] }}</p>
                <p class="mt-1 text-xs text-zinc-500">Issued: {{ $signature['issued_at'] }}</p>
            </div>
            <p class="mt-3 text-xs text-zinc-500">{{ $signature['note'] }}</p>
        </x-card>
    </div>
@endsection
