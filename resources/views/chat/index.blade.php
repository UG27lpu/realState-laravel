@extends('layouts.app')

@section('title', 'Conversations')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold tracking-tight">Conversations</h1>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Buyer ↔ agent chats organised by property.</p>

        @if ($conversations->isEmpty())
            <x-alert class="mt-6" tone="info">No conversations yet. Open a property page and message the agent to start one.</x-alert>
        @else
            <x-card class="mt-6 divide-y divide-zinc-200 dark:divide-zinc-800">
                @foreach ($conversations as $c)
                    @php $other = $c->counterpartFor(auth()->user()); @endphp
                    <a href="{{ route('chat.show', $c) }}" class="flex items-center gap-3 p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                        <img src="{{ $c->property?->coverUrl() }}" class="h-12 w-16 rounded-lg object-cover">
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-medium">{{ $c->property?->title }}</p>
                            <p class="truncate text-xs text-zinc-500">With {{ $other?->name ?? '—' }}</p>
                        </div>
                        <p class="text-xs text-zinc-500">{{ optional($c->last_message_at)->diffForHumans() ?? 'New' }}</p>
                    </a>
                @endforeach
            </x-card>
        @endif
    </div>
@endsection
