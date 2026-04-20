<x-dashboard-layout title="Login Activity">
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Stat strip -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs uppercase text-gray-500">{{ __('Total logins') }}</div>
                <div class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs uppercase text-gray-500">{{ __('Today') }}</div>
                <div class="mt-1 text-2xl font-bold text-indigo-600">{{ $stats['today'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs uppercase text-gray-500">{{ __('Last 7 days') }}</div>
                <div class="mt-1 text-2xl font-bold text-amber-600">{{ $stats['last7'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs uppercase text-gray-500">{{ __('Unique users') }}</div>
                <div class="mt-1 text-2xl font-bold text-green-600">{{ $stats['unique'] }}</div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="bg-white rounded-lg shadow p-4 flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs uppercase text-gray-500 mb-1">{{ __('User') }}</label>
                <select name="user_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">{{ __('All users') }}</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" @selected($filters['user_id'] === $u->id)>
                            {{ $u->name }} — {{ $u->email }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs uppercase text-gray-500 mb-1">{{ __('Provider') }}</label>
                <select name="provider" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">{{ __('Any provider') }}</option>
                    <option value="google" @selected($filters['provider'] === 'google')>Google</option>
                    <option value="email" @selected($filters['provider'] === 'email')>Email</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                    {{ __('Filter') }}
                </button>
                <a href="{{ route('admin.logins.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    {{ __('Reset') }}
                </a>
            </div>
        </form>

        <!-- Activity table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">{{ __('Recent logins') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium">{{ __('User') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Provider') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('IP') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Device / Browser') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('When') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($activities as $a)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($a->user?->avatar)
                                            <img src="{{ $a->user->avatar }}" class="h-8 w-8 rounded-full" alt="" referrerpolicy="no-referrer">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">
                                                {{ strtoupper(substr($a->user?->name ?? '?', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $a->user?->name ?? __('(deleted)') }}</div>
                                            <div class="text-xs text-gray-500">{{ $a->user?->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    @if ($a->provider === 'google')
                                        <span class="inline-flex rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700">Google</span>
                                    @elseif ($a->provider === 'email')
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">Email</span>
                                    @else
                                        <span class="text-xs text-gray-400">{{ $a->provider ?? '—' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-700 font-mono text-xs">{{ $a->ip_address ?? '—' }}</td>
                                <td class="px-6 py-3 text-xs text-gray-600 max-w-xs truncate" title="{{ $a->user_agent }}">
                                    {{ $a->user_agent ?? '—' }}
                                </td>
                                <td class="px-6 py-3 text-gray-600">
                                    <div>{{ $a->logged_in_at?->format('M j, Y · g:i A') }}</div>
                                    <div class="text-xs text-gray-400">{{ $a->logged_in_at?->diffForHumans() }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('No login activity yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($activities->hasPages())
                <div class="px-6 py-3 border-t border-gray-200">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>

    </div>
</x-dashboard-layout>
