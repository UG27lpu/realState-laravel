@extends('layouts.app')

@section('title', 'Create an account')

@section('content')
    <div class="mx-auto flex min-h-[70vh] max-w-md flex-col justify-center px-4 py-12 sm:px-6"
         x-data="{ role: '{{ old('role', 'user') }}' }">
        <div class="text-center">
            <h1 class="text-2xl font-bold tracking-tight">Create your account</h1>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Browse, list and manage real estate in one place.</p>
        </div>

        <x-card class="mt-8 p-6">
            <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                @csrf
                <x-input label="Full name" name="name" autocomplete="name" autofocus required />
                <x-input label="Email" name="email" type="email" autocomplete="email" required />
                <x-input label="Phone (optional)" name="phone" type="tel" autocomplete="tel" />

                <div>
                    <span class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">I'm joining as</span>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($roles as $value => $label)
                            <label class="flex cursor-pointer items-center gap-2 rounded-xl border px-3 py-2 text-sm transition"
                                   :class="role === '{{ $value }}' ? 'border-zinc-900 bg-zinc-900 text-white dark:border-white dark:bg-white dark:text-zinc-900' : 'border-zinc-300 dark:border-zinc-700'">
                                <input type="radio" name="role" value="{{ $value }}" x-model="role" class="hidden" {{ old('role', 'user') === $value ? 'checked' : '' }}/>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('role')<span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>@enderror
                </div>

                <template x-if="role === 'agent'">
                    <div>
                        <x-input label="Agency name" name="agency_name" />
                    </div>
                </template>

                <x-input label="Password" name="password" type="password" autocomplete="new-password" required hint="At least 8 characters, mixed case with a number." />
                <x-input label="Confirm password" name="password_confirmation" type="password" autocomplete="new-password" required />

                <label class="flex items-start gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                    <input type="checkbox" name="terms" value="1" class="mt-1 rounded border-zinc-300 dark:border-zinc-700">
                    <span>I understand this is a demo platform and some systems (AI suggestions, legal verification) are simulated.</span>
                </label>
                @error('terms')<span class="block text-xs text-rose-600">{{ $message }}</span>@enderror

                <x-button type="submit" class="w-full">Create account</x-button>
            </form>
        </x-card>

        <p class="mt-6 text-center text-sm text-zinc-600 dark:text-zinc-400">
            Already have an account? <a href="{{ route('login') }}" class="font-medium text-zinc-900 underline-offset-2 hover:underline dark:text-white">Log in</a>
        </p>
    </div>
@endsection
