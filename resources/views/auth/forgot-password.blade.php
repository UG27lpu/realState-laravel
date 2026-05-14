@extends('layouts.app')

@section('title', 'Forgot password')

@section('content')
    <div class="mx-auto flex min-h-[70vh] max-w-md flex-col justify-center px-4 py-12 sm:px-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold tracking-tight">Reset your password</h1>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                Enter your email and we'll send a reset link.
            </p>
        </div>

        <x-card class="mt-8 p-6">
            @if (session('status'))
                <x-alert tone="success" class="mb-4">{{ session('status') }}</x-alert>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <x-input label="Email" name="email" type="email" autocomplete="email" required />
                <x-button type="submit" class="w-full">Email reset link</x-button>
            </form>
        </x-card>

        <p class="mt-6 text-center text-sm text-zinc-600 dark:text-zinc-400">
            Remembered it? <a href="{{ route('login') }}" class="font-medium text-zinc-900 underline-offset-2 hover:underline dark:text-white">Log in</a>
        </p>
    </div>
@endsection
