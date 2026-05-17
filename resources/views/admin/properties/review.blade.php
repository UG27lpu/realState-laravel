@extends('layouts.app')

@section('title', 'Review property')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-2">
            <div>
                <a href="{{ route('admin.properties.index') }}" class="text-xs text-zinc-500 hover:underline">&larr; Back to queue</a>
                <h1 class="mt-2 text-2xl font-bold tracking-tight">{{ $property->title }}</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $property->address }}, {{ $property->city }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $property->approval_status?->badgeClasses() }}">
                    {{ $property->approval_status?->label() }}
                </span>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-4">
                <x-card class="p-0 overflow-hidden">
                    <img src="{{ $property->coverUrl() }}" class="aspect-[16/9] w-full object-cover">
                </x-card>
                <x-card class="p-6">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-zinc-500">Description</h2>
                    <p class="mt-2 whitespace-pre-line text-sm text-zinc-700 dark:text-zinc-300">{{ $property->description ?: '—' }}</p>
                </x-card>
                <x-card class="p-6">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-zinc-500">Demo documents</h2>
                    @if ($property->documents->isEmpty())
                        <p class="mt-2 text-sm text-zinc-500">No documents uploaded.</p>
                    @else
                        <ul class="mt-3 space-y-2">
                            @foreach ($property->documents as $doc)
                                <li class="flex items-center justify-between rounded-xl border border-zinc-200 p-3 text-sm dark:border-zinc-800">
                                    <span>{{ $doc->label }} <x-demo-tag label="Demo" /></span>
                                    <a href="{{ $doc->url() }}" target="_blank" class="text-indigo-600 hover:underline dark:text-indigo-400">Open</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-card>
            </div>

            <aside class="space-y-4">
                <x-card class="p-6">
                    <p class="text-xs uppercase tracking-wider text-zinc-500">Owner</p>
                    <p class="mt-1 font-medium">{{ $property->owner?->name }}</p>
                    <p class="text-xs text-zinc-500">{{ $property->owner?->email }}</p>
                </x-card>

                <x-card class="p-6">
                    <p class="text-xs uppercase tracking-wider text-zinc-500">Decision</p>
                    <form method="POST" action="{{ route('admin.properties.approve', $property) }}" class="mt-3">
                        @csrf
                        <x-button type="submit" variant="success" class="w-full">Approve</x-button>
                    </form>
                    <form method="POST" action="{{ route('admin.properties.under-review', $property) }}" class="mt-2">
                        @csrf
                        <x-button type="submit" variant="outline" class="w-full">Mark under review</x-button>
                    </form>

                    <form method="POST" action="{{ route('admin.properties.reject', $property) }}" class="mt-4 space-y-2">
                        @csrf
                        <textarea name="reason" required rows="3" placeholder="Reason for rejection"
                                  class="block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">{{ $property->rejection_reason }}</textarea>
                        <x-button type="submit" variant="danger" class="w-full">Reject</x-button>
                    </form>
                </x-card>
            </aside>
        </div>
    </div>
@endsection
