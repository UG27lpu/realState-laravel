@extends('layouts.app')

@section('title', 'Compare properties')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Compare properties</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Side-by-side view of up to 4 listings.</p>
            </div>
            @if ($properties->isNotEmpty())
                <form method="POST" action="{{ route('compare.clear') }}">
                    @csrf
                    <x-button type="submit" variant="outline">Clear all</x-button>
                </form>
            @endif
        </div>

        @if ($properties->isEmpty())
            <x-alert class="mt-6" tone="info">Nothing to compare yet. From a listing, hit "Add to compare" to start.</x-alert>
        @else
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wider text-zinc-500">
                            <th class="w-40 px-3 py-3"></th>
                            @foreach ($properties as $p)
                                <th class="px-3 py-3">
                                    <a href="{{ route('properties.show', $p) }}" class="font-semibold text-zinc-900 hover:underline dark:text-zinc-100">{{ $p->title }}</a>
                                    <form method="POST" action="{{ route('compare.remove', $p) }}" class="mt-1">
                                        @csrf
                                        <button class="text-xs text-rose-600 hover:underline">Remove</button>
                                    </form>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @php
                            $rows = [
                                'Price'    => fn($p) => config('estatify.currency.symbol').number_format((float) $p->price),
                                'Type'     => fn($p) => $p->type?->label(),
                                'Status'   => fn($p) => $p->status?->label(),
                                'Area'     => fn($p) => $p->area ? number_format((float)$p->area).' '.$p->area_unit : '—',
                                'Bedrooms' => fn($p) => $p->bedrooms ?? '—',
                                'Bathrooms' => fn($p) => $p->bathrooms ?? '—',
                                'City'     => fn($p) => $p->city,
                                'Furnished' => fn($p) => $p->furnished ? 'Yes' : 'No',
                                'Parking'  => fn($p) => $p->parking ? 'Yes' : 'No',
                                'Views'    => fn($p) => $p->view_count,
                            ];
                        @endphp
                        @foreach ($rows as $label => $resolver)
                            <tr>
                                <td class="px-3 py-3 text-xs font-medium text-zinc-500">{{ $label }}</td>
                                @foreach ($properties as $p)
                                    <td class="px-3 py-3">{{ $resolver($p) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
