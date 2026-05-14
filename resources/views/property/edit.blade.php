@extends('layouts.app')

@section('title', 'Edit listing')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold tracking-tight">Edit listing</h1>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Adjust details, swap photos, or remove the listing.</p>

        <x-card class="mt-6 p-6">
            <x-property-form :property="$property" :types="$types" :statuses="$statuses"
                             :action="route('properties.update', $property)" method="PUT" submit-label="Save changes" />
        </x-card>

        <form method="POST" action="{{ route('properties.destroy', $property) }}" class="mt-6"
              onsubmit="return confirm('Remove this listing? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="danger">Delete listing</x-button>
        </form>
    </div>
@endsection
