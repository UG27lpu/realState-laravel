@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-2xl font-bold tracking-tight">Notifications</h1>
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <x-button type="submit" variant="outline" size="sm">Mark all read</x-button>
            </form>
        </div>

        @if ($notifications->isEmpty())
            <x-alert class="mt-6" tone="info">Nothing yet. Approvals, messages and bookings will appear here.</x-alert>
        @else
            <x-card class="mt-6 divide-y divide-zinc-200 dark:divide-zinc-800">
                @foreach ($notifications as $n)
                    @php $data = $n->data; @endphp
                    <a href="{{ route('notifications.read', $n->id) }}" class="block p-4 transition hover:bg-zinc-50 dark:hover:bg-zinc-800/40 {{ $n->read_at ? 'opacity-70' : '' }}">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5">
                                <x-badge tone="indigo">{{ ucfirst($data['type'] ?? 'update') }}</x-badge>
                            </div>
                            <div class="min-w-0 flex-1">
                                @if (($data['type'] ?? '') === 'message')
                                    <p class="text-sm font-medium">New message from {{ $data['sender'] ?? '—' }}</p>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $data['preview'] ?? '' }}</p>
                                @elseif (($data['type'] ?? '') === 'appointment')
                                    <p class="text-sm font-medium">{{ $data['buyer'] ?? '—' }} booked a viewing</p>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $data['property_title'] ?? '' }} @ {{ $data['scheduled_for'] ?? '' }}</p>
                                @elseif (($data['type'] ?? '') === 'approval')
                                    <p class="text-sm font-medium">Property {{ $data['decision'] ?? 'updated' }}</p>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $data['property_title'] ?? '' }}</p>
                                @else
                                    <p class="text-sm">{{ json_encode($data) }}</p>
                                @endif
                            </div>
                            <p class="text-xs text-zinc-500">{{ $n->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @endforeach
            </x-card>

            <div class="mt-6">{{ $notifications->links() }}</div>
        @endif
    </div>
@endsection
