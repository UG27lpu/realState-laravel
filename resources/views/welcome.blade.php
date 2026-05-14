@extends('layouts.app')

@section('title', 'Find your next home')

@section('content')
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-32 left-1/2 h-96 w-[40rem] -translate-x-1/2 rounded-full bg-gradient-to-br from-indigo-400/30 via-fuchsia-400/20 to-rose-400/30 blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white/60 px-3 py-1 text-xs font-medium text-zinc-700 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/60 dark:text-zinc-300">
                    Modern listings, real workflow
                </span>
                <h1 class="mt-6 text-4xl font-bold tracking-tight sm:text-6xl">
                    Find the place that fits your <span class="bg-gradient-to-r from-indigo-500 via-purple-500 to-rose-500 bg-clip-text text-transparent">next chapter</span>.
                </h1>
                <p class="mt-6 text-base text-zinc-600 dark:text-zinc-300">
                    Browse vetted listings, talk to agents, run the numbers with built-in
                    calculators, and keep your shortlist in one place.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <x-button as="a" href="{{ route('properties.index') }}" size="lg">Browse properties</x-button>
                    <x-button as="a" href="{{ route('register') }}" size="lg" variant="outline">Create an account</x-button>
                </div>
            </div>
        </div>
    </section>
@endsection
