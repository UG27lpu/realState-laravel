@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <div class="mx-auto flex min-h-[70vh] max-w-md flex-col justify-center px-4 py-12 sm:px-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold tracking-tight">Welcome back</h1>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Log in to manage your listings, wishlist and bookings.</p>
        </div>

        <x-card class="mt-8 p-6">
            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf
                <x-input label="Email" name="email" type="email" autocomplete="email" autofocus required />
                <x-input label="Password" name="password" type="password" autocomplete="current-password" required />

                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
                        <input type="checkbox" name="remember" value="1" class="rounded border-zinc-300 dark:border-zinc-700"> Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="text-zinc-700 underline-offset-2 hover:underline dark:text-zinc-300">Forgot password?</a>
                </div>

                <x-button type="submit" class="w-full">Log in</x-button>
            </form>
        </x-card>

        <p class="mt-6 text-center text-sm text-zinc-600 dark:text-zinc-400">
            New here? <a href="{{ route('register') }}" class="font-medium text-zinc-900 underline-offset-2 hover:underline dark:text-white">Create an account</a>
        </p>
        <p class="mt-2 text-center text-xs text-zinc-500 dark:text-zinc-500">
            Administrator? <a href="{{ route('admin.login') }}" class="hover:underline">Use the admin sign-in</a>.
        </p>
    </div>
@endsection
