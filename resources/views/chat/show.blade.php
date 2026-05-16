@extends('layouts.app')

@section('title', 'Conversation')

@section('content')
    @php $me = auth()->user(); $other = $conversation->counterpartFor($me); @endphp
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <p class="text-xs text-zinc-500">About</p>
                <a href="{{ route('properties.show', $conversation->property) }}" class="font-semibold hover:underline">{{ $conversation->property?->title }}</a>
                <p class="text-xs text-zinc-500">With {{ $other?->name }}</p>
            </div>
            <x-button as="a" href="{{ route('chat.index') }}" variant="outline" size="sm">All conversations</x-button>
        </div>

        <x-card class="mt-6 flex h-[60vh] flex-col">
            <div class="flex-1 space-y-3 overflow-y-auto p-4" id="chat-window">
                @foreach ($conversation->messages as $message)
                    @php $mine = $message->sender_id === $me->id; @endphp
                    <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%] rounded-2xl px-3 py-2 text-sm shadow {{ $mine ? 'bg-indigo-600 text-white' : 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100' }}">
                            <p class="whitespace-pre-line">{{ $message->body }}</p>
                            <p class="mt-1 text-[10px] opacity-70">{{ $message->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
                @if ($conversation->messages->isEmpty())
                    <p class="text-center text-sm text-zinc-500">Say hello to start the conversation.</p>
                @endif
            </div>
            <form method="POST" action="{{ route('chat.send', $conversation) }}" class="border-t border-zinc-200 p-3 dark:border-zinc-800">
                @csrf
                <div class="flex items-center gap-2">
                    <input type="text" name="body" required maxlength="2000" placeholder="Type a message…"
                           class="flex-1 rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <x-button type="submit">Send</x-button>
                </div>
            </form>
        </x-card>
    </div>

    @push('scripts')
        <script>
            const w = document.getElementById('chat-window');
            if (w) w.scrollTop = w.scrollHeight;
        </script>
    @endpush
@endsection
