@extends('layouts.app')

@section('title', 'About')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight">About {{ config('app.name') }}</h1>
        <p class="mt-4 text-zinc-600 dark:text-zinc-300">
            A modern Laravel-powered real estate platform built as a learning and
            demonstration project. It covers everything from listings and bookings to
            admin analytics and PDF generation.
        </p>
    </div>
@endsection
