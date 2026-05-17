@extends('layouts.app')

@section('title', 'Property approvals')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1 rounded-full bg-zinc-900 px-2.5 py-0.5 text-xs font-medium uppercase tracking-wider text-white dark:bg-white dark:text-zinc-900">Admin area</span>
                <h1 class="mt-2 text-2xl font-bold tracking-tight">Property approvals</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Review submitted listings and decide what goes live.</p>
            </div>

            <form method="GET" class="flex items-center gap-2">
                @foreach ($statuses as $value => $label)
                    <a href="{{ route('admin.properties.index', ['status' => $value]) }}"
                       class="rounded-full border px-3 py-1 text-xs font-medium {{ ($status ?? '') === $value ? 'border-zinc-900 bg-zinc-900 text-white dark:border-white dark:bg-white dark:text-zinc-900' : 'border-zinc-300 text-zinc-600 hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </form>
        </div>

        <x-card class="mt-6">
            <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-zinc-500">
                        <th class="px-4 py-3">Property</th>
                        <th class="px-4 py-3">Owner</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Submitted</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse ($properties as $property)
                        <tr>
                            <td class="flex items-center gap-3 px-4 py-3">
                                <img src="{{ $property->coverUrl() }}" class="h-10 w-14 rounded-md object-cover">
                                <div>
                                    <a href="{{ route('properties.show', $property) }}" class="font-medium hover:underline">{{ $property->title }}</a>
                                    <p class="text-xs text-zinc-500">{{ $property->city }} &middot; {{ $property->type?->label() }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $property->owner?->name }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $property->approval_status?->badgeClasses() }}">
                                    {{ $property->approval_status?->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-zinc-500">{{ $property->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.properties.review', $property) }}" class="text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">Review &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-sm text-zinc-500">No properties in this state.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>

        <div class="mt-6">{{ $properties->links() }}</div>
    </div>
@endsection
