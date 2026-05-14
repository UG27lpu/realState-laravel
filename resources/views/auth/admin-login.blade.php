@extends('layouts.app')

@section('title', 'Admin sign in')

@section('content')
    <div class="mx-auto flex min-h-[70vh] max-w-md flex-col justify-center px-4 py-12 sm:px-6">
        <div class="text-center">
            <span class="inline-flex items-center gap-1 rounded-full bg-zinc-900 px-3 py-1 text-xs font-medium uppercase tracking-wider text-white dark:bg-white dark:text-zinc-900">
                Admin area
            </span>
            <h1 class="mt-4 text-2xl font-bold tracking-tight">Administrator sign in</h1>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Restricted to platform administrators.</p>
        </div>

        <x-card class="mt-8 p-6">
            <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
                @csrf
                <x-input label="Email" name="email" type="email" autocomplete="email" autofocus required />
                <x-input label="Password" name="password" type="password" autocomplete="current-password" required />
                <label class="inline-flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                    <input type="checkbox" name="remember" value="1" class="rounded border-zinc-300 dark:border-zinc-700"> Remember this device
                </label>
                <x-button type="submit" class="w-full">Sign in</x-button>
            </form>
        </x-card>

        <p class="mt-6 text-center text-xs text-zinc-500">
            Not an admin? <a href="{{ route('login') }}" class="hover:underline">Use the regular sign-in</a>.
        </p>
    </div>
@endsection
