@extends('layouts.app')

@section('title', 'Admin dashboard')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1 rounded-full bg-zinc-900 px-2.5 py-0.5 text-xs font-medium uppercase tracking-wider text-white dark:bg-white dark:text-zinc-900">Admin area</span>
                <h1 class="mt-2 text-2xl font-bold tracking-tight">Platform overview</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                <x-button as="a" href="{{ route('admin.properties.index') }}" variant="outline">Approvals</x-button>
                <x-button as="a" href="{{ route('admin.users.index') }}" variant="outline">Users</x-button>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @php
                $cards = [
                    ['label' => 'Users', 'value' => $summary['totalUsers']],
                    ['label' => 'Agents', 'value' => $summary['totalAgents']],
                    ['label' => 'Approved listings', 'value' => $summary['approvedProperties']],
                    ['label' => 'Pending review', 'value' => $summary['pendingProperties']],
                    ['label' => 'Appointments', 'value' => $summary['appointments']],
                    ['label' => 'Total property views', 'value' => $summary['totalViews']],
                ];
            @endphp
            @foreach ($cards as $card)
                <x-card class="p-5 dashboard-card-hover animate-card-float">
                    <p class="text-xs uppercase tracking-wider text-zinc-500">{{ $card['label'] }}</p>
                    <p class="mt-2 text-3xl font-semibold">{{ number_format($card['value']) }}</p>
                </x-card>
            @endforeach
        </div>

        <div class="mt-10 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <x-card class="p-6 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Monthly registrations</h2>
                    <p class="text-xs text-zinc-500">Last 12 months</p>
                </div>
                <canvas id="regChart" class="mt-4" height="120"></canvas>
            </x-card>

            <x-card class="p-6">
                <h2 class="text-lg font-semibold">Listings by type</h2>
                <canvas id="typeChart" class="mt-4" height="180"></canvas>
            </x-card>
        </div>

        <div class="mt-10">
            <x-card class="p-6">
                <h2 class="text-lg font-semibold">Most viewed properties</h2>
                <ul class="mt-3 divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse ($mostViewed as $p)
                        <li class="flex items-center gap-3 py-3 text-sm">
                            <img src="{{ $p->coverUrl() }}" class="h-10 w-14 rounded-md object-cover">
                            <div class="flex-1">
                                <a href="{{ route('properties.show', $p) }}" class="font-medium hover:underline">{{ $p->title }}</a>
                                <p class="text-xs text-zinc-500">{{ $p->city }}</p>
                            </div>
                            <p class="text-xs text-zinc-500">{{ number_format($p->view_count) }} views</p>
                        </li>
                    @empty
                        <li class="py-3 text-sm text-zinc-500">No view data yet.</li>
                    @endforelse
                </ul>
            </x-card>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            import Chart from 'https://esm.sh/chart.js@4.4.4/auto';
            const isDark = document.documentElement.classList.contains('dark');
            const grid = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
            const fg   = isDark ? '#e4e4e7' : '#52525b';

            const reg = document.getElementById('regChart');
            if (reg) {
                new Chart(reg, {
                    type: 'line',
                    data: {
                        labels: @json($registrations['labels']),
                        datasets: [{
                            label: 'New users',
                            data: @json($registrations['counts']),
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99,102,241,0.18)',
                            tension: 0.3,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { labels: { color: fg } } },
                        scales: {
                            x: { ticks: { color: fg }, grid: { color: grid } },
                            y: { ticks: { color: fg }, grid: { color: grid }, beginAtZero: true }
                        }
                    }
                });
            }

            const t = document.getElementById('typeChart');
            if (t) {
                new Chart(t, {
                    type: 'doughnut',
                    data: {
                        labels: @json(array_keys($byType)),
                        datasets: [{
                            data: @json(array_values($byType)),
                            backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ec4899'],
                            borderWidth: 0,
                        }]
                    },
                    options: { plugins: { legend: { position: 'bottom', labels: { color: fg } } } }
                });
            }
        </script>
    @endpush
@endsection
