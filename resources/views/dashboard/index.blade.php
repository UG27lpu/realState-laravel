@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Signed in as</p>
                <h1 class="text-2xl font-bold tracking-tight">{{ $user->name }}</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Role: <span class="font-medium capitalize">{{ $role }}</span></p>
            </div>
            <div class="flex gap-2">
                @if ($user->isAgent())
                    <x-button as="a" href="{{ route('properties.create') }}">Add a property</x-button>
                @endif
                <x-button as="a" href="{{ route('properties.index') }}" variant="outline">Browse properties</x-button>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <x-card class="p-5 dashboard-card-hover">
                <p class="text-xs uppercase tracking-wider text-zinc-500">Wishlist</p>
                <p class="mt-2 text-3xl font-semibold">{{ $wishlistCount }}</p>
                <a href="{{ route('wishlist.index') }}" class="mt-3 inline-block text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">Go to wishlist &rarr;</a>
            </x-card>
            <x-card class="p-5 dashboard-card-hover">
                <p class="text-xs uppercase tracking-wider text-zinc-500">Appointments</p>
                <p class="mt-2 text-3xl font-semibold">{{ $appointmentsCount }}</p>
                <a href="{{ route('appointments.index') }}" class="mt-3 inline-block text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">Manage bookings &rarr;</a>
            </x-card>
            @if ($user->isAgent())
                <x-card class="p-5 dashboard-card-hover">
                    <p class="text-xs uppercase tracking-wider text-zinc-500">Active listings</p>
                    <p class="mt-2 text-3xl font-semibold">{{ $propertiesCount }}</p>
                    <a href="{{ route('properties.mine') }}" class="mt-3 inline-block text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">Manage listings &rarr;</a>
                </x-card>
            @endif
        </div>
    </div>
@endsection
