@extends('layouts.app')

@section('title', 'Set a new password')

@section('content')
    <div class="mx-auto flex min-h-[70vh] max-w-md flex-col justify-center px-4 py-12 sm:px-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold tracking-tight">Choose a new password</h1>
        </div>

        <x-card class="mt-8 p-6">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <x-input label="Email" name="email" type="email" :value="$request->email" autocomplete="email" required />
                <x-input label="New password" name="password" type="password" autocomplete="new-password" required hint="At least 8 characters, mixed case with a number." />
                <x-input label="Confirm new password" name="password_confirmation" type="password" autocomplete="new-password" required />
                <x-button type="submit" class="w-full">Reset password</x-button>
            </form>
        </x-card>
    </div>
@endsection
