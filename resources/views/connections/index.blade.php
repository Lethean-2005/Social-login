<x-dashboard-layout title="Connections">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Status messages -->
        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->has('unlink'))
            <div class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                {{ $errors->first('unlink') }}
            </div>
        @endif

        <!-- Intro -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('Connected accounts') }}</h3>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('These are the external accounts you\'ve connected to this app. Disconnecting will remove our ability to sign you in with that provider.') }}
            </p>
            @unless ($canUnlink)
                <div class="mt-3 rounded-md border border-amber-200 bg-amber-50 p-3 text-xs text-amber-800">
                    {{ __('You signed in via a social provider and haven\'t set a password yet. Set one on the') }}
                    <a href="{{ route('profile.edit') }}" class="underline font-medium">{{ __('Profile page') }}</a>
                    {{ __('before you can safely disconnect.') }}
                </div>
            @endunless
        </div>

        <!-- Provider cards -->
        <div class="space-y-4">
            @foreach ($connections as $c)
                <div class="bg-white rounded-lg shadow p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                    <!-- Icon / avatar -->
                    <div class="shrink-0">
                        @if ($c['avatar'])
                            <img src="{{ $c['avatar'] }}" alt="{{ $c['label'] }} avatar" referrerpolicy="no-referrer" class="h-14 w-14 rounded-full ring-2 ring-gray-100">
                        @else
                            <div class="h-14 w-14 rounded-full bg-gray-100 flex items-center justify-center">
                                @if ($c['key'] === 'google')
                                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/><path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"/><path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238C29.211 35.091 26.715 36 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/><path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.792 2.237-2.231 4.166-4.087 5.571l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/></svg>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <h4 class="text-base font-semibold text-gray-900">{{ $c['label'] }}</h4>
                            @if ($c['linked'])
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700">
                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    {{ __('Connected') }}
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                                    {{ __('Not connected') }}
                                </span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-gray-600">{{ $c['description'] }}</p>

                        @if ($c['linked'])
                            <dl class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                <div>
                                    <dt class="text-gray-500">{{ __('Connected since') }}</dt>
                                    <dd class="text-gray-900 font-medium">{{ $c['linked_at']?->format('M j, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500">{{ __('Permissions granted') }}</dt>
                                    <dd class="flex flex-wrap gap-1 mt-1">
                                        @foreach ($c['scopes'] as $scope)
                                            <span class="inline-flex items-center rounded bg-indigo-50 px-1.5 py-0.5 text-xs text-indigo-700 font-mono">{{ $scope }}</span>
                                        @endforeach
                                    </dd>
                                </div>
                            </dl>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex sm:flex-col gap-2 sm:items-end">
                        @if ($c['linked'])
                            <a href="{{ $c['manage_url'] }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                                {{ __('Manage on') }} {{ $c['label'] }}
                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            </a>
                            <form method="POST" action="{{ route('connections.destroy', $c['key']) }}"
                                  onsubmit="return confirm('Remove this connection?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        @if (! $canUnlink) disabled title="Set a password first" @endif
                                        class="inline-flex items-center rounded-md px-3 py-1.5 text-xs font-medium whitespace-nowrap
                                               {{ $canUnlink
                                                    ? 'border border-red-300 text-red-700 bg-white hover:bg-red-50'
                                                    : 'border border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed' }}">
                                    {{ __('Remove') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('social.redirect', $c['key']) }}"
                               class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-500">
                                {{ __('Connect') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer: Google-side link -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-sm font-semibold text-gray-900">{{ __('Third-party apps connected to your Google Account') }}</h4>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('To see every app and service that has access to your Google Account, open the Google dashboard.') }}
            </p>
            <a href="https://myaccount.google.com/connections" target="_blank" rel="noopener"
               class="mt-3 inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                {{ __('Open Google Connections') }}
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
            </a>
        </div>

    </div>
</x-dashboard-layout>
