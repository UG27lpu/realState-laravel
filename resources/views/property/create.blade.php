@extends('layouts.app')

@section('title', 'List a property')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold tracking-tight">List a property</h1>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Fill in the details and submit for admin review.</p>

        <x-card class="mt-6 p-6">
            <x-property-form :types="$types" :statuses="$statuses"
                             :action="route('properties.store')" submit-label="Submit for review" />
        </x-card>
    </div>
@endsection
