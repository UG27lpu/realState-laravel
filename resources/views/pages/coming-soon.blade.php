@extends('layouts.app')

@section('title', 'Coming soon')

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-24 text-center sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight">This area is being built</h1>
        <p class="mt-4 text-zinc-600 dark:text-zinc-300">
            The current feature stage is still in progress. Pull the latest changes once it lands.
        </p>
        <div class="mt-8">
            <x-button as="a" href="{{ url('/') }}">Back home</x-button>
        </div>
    </div>
@endsection
