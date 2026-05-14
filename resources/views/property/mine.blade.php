@extends('layouts.app')

@section('title', 'My listings')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">My listings</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Track approval status and update details.</p>
            </div>
            <x-button as="a" href="{{ route('properties.create') }}">Add listing</x-button>
        </div>

        @if ($properties->isEmpty())
            <x-alert class="mt-6" tone="info">You haven't listed any properties yet.</x-alert>
        @else
            <x-card class="mt-6">
                <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
                    <thead class="text-xs uppercase tracking-wider text-zinc-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Title</th>
                            <th class="px-4 py-3 text-left">Type</th>
                            <th class="px-4 py-3 text-left">Approval</th>
                            <th class="px-4 py-3 text-right">Price</th>
                            <th class="px-4 py-3 text-right">Views</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach ($properties as $property)
                            <tr>
                                <td class="px-4 py-3">
                                    <a href="{{ route('properties.show', $property) }}" class="font-medium hover:underline">{{ $property->title }}</a>
                                    <p class="text-xs text-zinc-500">{{ $property->city }}</p>
                                </td>
                                <td class="px-4 py-3">{{ $property->type?->label() }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $property->approval_status?->badgeClasses() }}">
                                        {{ $property->approval_status?->label() }}
                                    </span>
                                    @if ($property->rejection_reason)
                                        <p class="mt-1 text-[11px] text-rose-500">Reason: {{ $property->rejection_reason }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">{{ config('estatify.currency.symbol') }}{{ number_format((float) $property->price) }}</td>
                                <td class="px-4 py-3 text-right">{{ $property->view_count }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('properties.edit', $property) }}" class="text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>

            <div class="mt-6">{{ $properties->links() }}</div>
        @endif
    </div>
@endsection
