<x-dashboard-layout title="User Management">
    <div class="max-w-6xl mx-auto space-y-6">

        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->has('user'))
            <div class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                {{ $errors->first('user') }}
            </div>
        @endif

        <!-- Stat strip -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs uppercase text-gray-500">{{ __('Total users') }}</div>
                <div class="mt-1 text-2xl font-bold text-gray-900">{{ $counts['total'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs uppercase text-gray-500">{{ __('Admins') }}</div>
                <div class="mt-1 text-2xl font-bold text-indigo-600">{{ $counts['admins'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs uppercase text-gray-500">{{ __('Google sign-ins') }}</div>
                <div class="mt-1 text-2xl font-bold text-red-600">{{ $counts['google'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs uppercase text-gray-500">{{ __('Email verified') }}</div>
                <div class="mt-1 text-2xl font-bold text-green-600">{{ $counts['verified'] }}</div>
            </div>
        </div>

        <!-- Users table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">{{ __('All users') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium">{{ __('User') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Provider') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Role') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Joined') }}</th>
                            <th class="px-6 py-3 text-right font-medium">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $u)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($u->avatar)
                                            <img src="{{ $u->avatar }}" class="h-8 w-8 rounded-full" alt="" referrerpolicy="no-referrer">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $u->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    @if ($u->provider_name)
                                        <span class="inline-flex rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 capitalize">{{ $u->provider_name }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">{{ __('Email') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    @if ($u->is_admin)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700">
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1l2.42 4.9L18 7l-4 3.9.94 5.5L10 13.8l-4.94 2.6L6 10.9 2 7l5.58-1.1L10 1z" clip-rule="evenodd"/></svg>
                                            {{ __('Admin') }}
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">{{ __('User') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-600">{{ $u->created_at?->diffForHumans() }}</td>
                                <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                    @if ($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-admin', $u) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="text-xs font-medium {{ $u->is_admin ? 'text-gray-600 hover:text-gray-900' : 'text-indigo-600 hover:text-indigo-800' }}">
                                                {{ $u->is_admin ? __('Demote') : __('Make admin') }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline"
                                              onsubmit="return confirm('Delete {{ $u->email }}? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">{{ __('(you)') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('No users yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="px-6 py-3 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
</x-dashboard-layout>
