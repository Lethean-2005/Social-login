@php
    $user = auth()->user();
    $totalUsers = \App\Models\User::count();
    $googleUsers = \App\Models\User::where('provider_name', 'google')->count();
    $verifiedUsers = \App\Models\User::whereNotNull('email_verified_at')->count();
    $recentUsers = \App\Models\User::latest()->take(5)->get();
    $accountAgeDays = $user->created_at ? (int) $user->created_at->diffInDays(now()) : 0;
@endphp

<x-dashboard-layout title="Dashboard">

    <!-- Welcome banner -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg shadow p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
        @if ($user->avatar)
            <img src="{{ $user->avatar }}" alt="avatar" class="h-16 w-16 rounded-full ring-2 ring-white/40">
        @else
            <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-semibold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        <div class="flex-1">
            <h2 class="text-xl sm:text-2xl font-semibold">{{ __('Welcome back,') }} {{ $user->name }} 👋</h2>
            <p class="text-sm text-indigo-100 mt-1">
                {{ __('Here\'s what\'s happening with your account today.') }}
            </p>
        </div>
        <div class="text-right text-xs text-indigo-100">
            <div>{{ __('Last sign-in') }}</div>
            <div class="text-sm font-medium text-white">{{ now()->format('M j, Y · g:i A') }}</div>
        </div>
    </div>

    <!-- Stat cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <x-stat-card icon="users" label="Total Users" :value="$totalUsers" color="indigo" />
        <x-stat-card icon="google" label="Google Sign-ins" :value="$googleUsers" color="red" />
        <x-stat-card icon="shield" label="Verified Accounts" :value="$verifiedUsers" color="green" />
        <x-stat-card icon="calendar" label="Account Age" :value="$accountAgeDays . ' days'" color="amber" />
    </div>

    <!-- Two-column section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        <!-- Recent signups table -->
        <div class="bg-white rounded-lg shadow lg:col-span-2">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">{{ __('Recent Signups') }}</h3>
                <span class="text-xs text-gray-500">{{ __('Latest 5 users') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium">{{ __('User') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Provider') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Joined') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($recentUsers as $recent)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($recent->avatar)
                                            <img src="{{ $recent->avatar }}" class="h-8 w-8 rounded-full" alt="">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">
                                                {{ strtoupper(substr($recent->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $recent->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $recent->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    @if ($recent->provider_name)
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 capitalize">
                                            {{ $recent->provider_name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">Email</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-600">
                                    {{ $recent->created_at?->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-6 text-center text-gray-500">{{ __('No users yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Account info -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">{{ __('Account Info') }}</h3>
            </div>
            <dl class="px-6 py-4 divide-y divide-gray-100 text-sm">
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">{{ __('Email') }}</dt>
                    <dd class="text-gray-900 font-medium truncate ml-3">{{ $user->email }}</dd>
                </div>
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">{{ __('Provider') }}</dt>
                    <dd class="text-gray-900 font-medium capitalize">{{ $user->provider_name ?? 'Email' }}</dd>
                </div>
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">{{ __('Email verified') }}</dt>
                    <dd>
                        @if ($user->email_verified_at)
                            <span class="inline-flex items-center gap-1 text-green-700 font-medium">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                {{ __('Yes') }}
                            </span>
                        @else
                            <span class="text-gray-500">{{ __('No') }}</span>
                        @endif
                    </dd>
                </div>
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">{{ __('2FA status') }}</dt>
                    <dd class="text-green-700 font-medium">{{ __('Verified') }}</dd>
                </div>
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">{{ __('Member since') }}</dt>
                    <dd class="text-gray-900 font-medium">{{ $user->created_at?->format('M j, Y') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Quick actions -->
    <div class="bg-white rounded-lg shadow mt-6 p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">{{ __('Quick Actions') }}</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('profile.edit') }}" class="group flex flex-col items-center justify-center gap-2 rounded-lg border border-gray-200 p-4 hover:border-indigo-500 hover:bg-indigo-50 transition">
                <svg class="h-6 w-6 text-gray-400 group-hover:text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-700">{{ __('Edit Profile') }}</span>
            </a>
            <a href="#" class="group flex flex-col items-center justify-center gap-2 rounded-lg border border-gray-200 p-4 hover:border-indigo-500 hover:bg-indigo-50 transition">
                <svg class="h-6 w-6 text-gray-400 group-hover:text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-700">{{ __('Security') }}</span>
            </a>
            <a href="#" class="group flex flex-col items-center justify-center gap-2 rounded-lg border border-gray-200 p-4 hover:border-indigo-500 hover:bg-indigo-50 transition">
                <svg class="h-6 w-6 text-gray-400 group-hover:text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-700">{{ __('Notifications') }}</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="contents">
                @csrf
                <button type="submit" class="group flex flex-col items-center justify-center gap-2 rounded-lg border border-gray-200 p-4 hover:border-red-500 hover:bg-red-50 transition">
                    <svg class="h-6 w-6 text-gray-400 group-hover:text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-red-700">{{ __('Log out') }}</span>
                </button>
            </form>
        </div>
    </div>

</x-dashboard-layout>
