<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('We\'ve sent a 6-digit verification code to your email.') }}
        <div class="mt-1 text-xs text-gray-500">
            {{ __('Check your inbox, or open') }} <code class="bg-gray-100 px-1 rounded">storage/logs/laravel.log</code>
            {{ __('if you\'re running the dev mail driver.') }}
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-md border border-yellow-300 bg-yellow-50 p-3 text-sm text-yellow-800">
            {{ session('status') }}
        </div>
    @endif

    @if (session('dev_code'))
        <div class="mb-4 rounded-md border border-blue-300 bg-blue-50 p-3 text-sm text-blue-800">
            <strong>{{ __('Dev mode:') }}</strong> {{ __('your code is') }}
            <span class="font-mono text-lg tracking-widest">{{ session('dev_code') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('two-factor.store') }}">
        @csrf

        <div>
            <x-input-label for="code" :value="__('Verification code')" />
            <x-text-input id="code"
                          class="block mt-1 w-full text-center font-mono text-2xl tracking-[0.5em]"
                          type="text"
                          name="code"
                          inputmode="numeric"
                          pattern="[0-9]{6}"
                          maxlength="6"
                          autocomplete="one-time-code"
                          autofocus
                          required />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('two-factor.resend') }}"
               onclick="event.preventDefault(); document.getElementById('resend-form').submit();"
               class="text-sm text-gray-600 hover:text-gray-900 underline">
                {{ __('Resend code') }}
            </a>

            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>

    <form id="resend-form" method="POST" action="{{ route('two-factor.resend') }}" class="hidden">
        @csrf
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-6 text-center">
        @csrf
        <button type="submit" class="text-xs text-gray-500 hover:text-gray-700 underline">
            {{ __('Cancel and log out') }}
        </button>
    </form>
</x-guest-layout>
