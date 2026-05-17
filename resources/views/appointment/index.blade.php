@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold tracking-tight">Appointments</h1>

        @if ($appointments->isEmpty())
            <x-alert class="mt-6" tone="info">No visits scheduled yet. Open a property and book a viewing.</x-alert>
        @else
            <x-card class="mt-6">
                <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wider text-zinc-500">
                            <th class="px-4 py-3">Property</th>
                            <th class="px-4 py-3">With</th>
                            <th class="px-4 py-3">When</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach ($appointments as $appt)
                            @php $other = auth()->id() === $appt->buyer_id ? $appt->agent : $appt->buyer; @endphp
                            <tr>
                                <td class="px-4 py-3">
                                    <a href="{{ route('properties.show', $appt->property) }}" class="font-medium hover:underline">{{ $appt->property?->title }}</a>
                                    <p class="text-xs text-zinc-500">{{ $appt->property?->city }}</p>
                                </td>
                                <td class="px-4 py-3">{{ $other?->name }}</td>
                                <td class="px-4 py-3">{{ $appt->scheduled_for?->format('D, M j • g:i A') }}</td>
                                <td class="px-4 py-3">
                                    <x-badge :tone="$appt->statusBadge()">{{ ucfirst($appt->status) }}</x-badge>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('appointments.update', $appt) }}" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="rounded-lg border border-zinc-300 bg-white px-2 py-1 text-xs dark:border-zinc-700 dark:bg-zinc-900">
                                            @foreach (\App\Models\Appointment::STATUSES as $s)
                                                <option value="{{ $s }}" @selected($appt->status === $s)>{{ ucfirst($s) }}</option>
                                            @endforeach
                                        </select>
                                        <x-button type="submit" size="sm" variant="outline">Save</x-button>
                                    </form>
                                    <a href="{{ route('appointments.receipt', $appt) }}" class="ml-2 inline-block text-xs text-indigo-600 hover:underline dark:text-indigo-400">Receipt PDF</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>
        @endif
    </div>
@endsection
