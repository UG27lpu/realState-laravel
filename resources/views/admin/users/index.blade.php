@extends('layouts.app')

@section('title', 'Manage users')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <span class="inline-flex items-center gap-1 rounded-full bg-zinc-900 px-2.5 py-0.5 text-xs font-medium uppercase tracking-wider text-white dark:bg-white dark:text-zinc-900">Admin area</span>
                <h1 class="mt-2 text-2xl font-bold tracking-tight">Users</h1>
            </div>

            <form method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="q" value="{{ $q }}" placeholder="Search name or email"
                       class="rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                <select name="role" class="rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <option value="">Any role</option>
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}" @selected($role === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-button type="submit">Filter</x-button>
            </form>
        </div>

        <x-card class="mt-6">
            <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-zinc-500">
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Joined</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @foreach ($users as $user)
                        <tr>
                            <td class="flex items-center gap-3 px-4 py-3">
                                <img src="{{ $user->avatarUrl() }}" class="h-8 w-8 rounded-full">
                                <div>
                                    <p class="font-medium">{{ $user->name }}</p>
                                    <p class="text-xs text-zinc-500">{{ $user->email }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @foreach ($user->getRoleNames() as $r)
                                    <x-badge tone="indigo">{{ ucfirst($r) }}</x-badge>
                                @endforeach
                            </td>
                            <td class="px-4 py-3">
                                <x-badge :tone="$user->is_active ? 'green' : 'red'">
                                    {{ $user->is_active ? 'Active' : 'Suspended' }}
                                </x-badge>
                            </td>
                            <td class="px-4 py-3 text-xs text-zinc-500">{{ $user->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                    @csrf
                                    <x-button type="submit" size="sm" :variant="$user->is_active ? 'danger' : 'success'">
                                        {{ $user->is_active ? 'Deactivate' : 'Reactivate' }}
                                    </x-button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-card>

        <div class="mt-6">{{ $users->links() }}</div>
    </div>
@endsection
