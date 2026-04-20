<x-guest-layout>
    @php $userEmail = auth()->user()?->email; @endphp

    <div x-data="verifyCode()" class="w-full">

        <!-- Header -->
        <div class="text-center mb-6">
            <div class="mx-auto h-14 w-14 rounded-full bg-indigo-100 flex items-center justify-center">
                <svg class="h-7 w-7 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </div>
            <h2 class="mt-4 text-xl font-semibold text-gray-900">{{ __('Check your email') }}</h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('We sent a 6-digit code to') }}
                @if ($userEmail)
                    <span class="font-medium text-gray-900">{{ $userEmail }}</span>
                @else
                    {{ __('your inbox') }}
                @endif
                .
            </p>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800 text-center">
                {{ session('status') }}
            </div>
        @endif

        @if (session('dev_code'))
            <div class="mb-4 rounded-md border border-amber-300 bg-amber-50 p-3 text-xs text-amber-900 text-center">
                <span class="font-semibold uppercase tracking-wide">{{ __('Dev mode') }}</span> ·
                {{ __('your code is') }}
                <span class="ml-1 font-mono text-base tracking-[0.3em] text-amber-900">{{ session('dev_code') }}</span>
            </div>
        @endif

        <!-- Code form -->
        <form method="POST" action="{{ route('two-factor.store') }}" x-ref="form">
            @csrf
            <input type="hidden" name="code" x-ref="hidden">

            <div class="flex justify-between gap-2" @paste="handlePaste($event)">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text"
                           inputmode="numeric"
                           maxlength="1"
                           pattern="[0-9]"
                           autocomplete="one-time-code"
                           :class="boxClasses"
                           class="h-14 w-12 text-center text-2xl font-semibold rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                           @input="onInput($event, {{ $i }})"
                           @keydown.backspace="onBackspace($event, {{ $i }})"
                           @keydown.arrow-left.prevent="focusBox({{ $i - 1 }})"
                           @keydown.arrow-right.prevent="focusBox({{ $i + 1 }})"
                           x-ref="box{{ $i }}"
                           @if ($i === 0) autofocus @endif
                           required>
                @endfor
            </div>

            @error('code')
                <p class="mt-3 text-sm text-red-600 text-center">{{ $message }}</p>
            @enderror

            <div class="mt-6 flex flex-col gap-3">
                <x-primary-button class="w-full justify-center py-2.5">
                    {{ __('Verify and continue') }}
                </x-primary-button>

                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span x-text="timerText"></span>
                    <button type="button"
                            @click="resend()"
                            :disabled="!canResend"
                            :class="canResend ? 'text-indigo-600 hover:underline cursor-pointer' : 'text-gray-400 cursor-not-allowed'"
                            class="font-medium">
                        {{ __('Resend code') }}
                    </button>
                </div>
            </div>
        </form>

        <!-- Resend form (invisible helper) -->
        <form id="resend-form" method="POST" action="{{ route('two-factor.resend') }}" class="hidden">
            @csrf
        </form>

        <!-- Cancel -->
        <form method="POST" action="{{ route('logout') }}" class="mt-6 text-center">
            @csrf
            <button type="submit" class="text-xs text-gray-500 hover:text-gray-700 underline">
                {{ __('Use a different account') }}
            </button>
        </form>
    </div>

    <script>
        function verifyCode() {
            return {
                boxClasses: '',
                secondsLeft: 600,
                resendCooldown: 30,
                timerInterval: null,
                get canResend() {
                    return this.resendCooldown <= 0;
                },
                get timerText() {
                    if (this.secondsLeft <= 0) return '{{ __('Code expired') }}';
                    const m = Math.floor(this.secondsLeft / 60);
                    const s = (this.secondsLeft % 60).toString().padStart(2, '0');
                    return `{{ __('Expires in') }} ${m}:${s}`;
                },
                init() {
                    this.timerInterval = setInterval(() => {
                        if (this.secondsLeft > 0) this.secondsLeft--;
                        if (this.resendCooldown > 0) this.resendCooldown--;
                    }, 1000);
                },
                focusBox(i) {
                    if (i < 0 || i > 5) return;
                    this.$refs['box' + i]?.focus();
                    this.$refs['box' + i]?.select();
                },
                collect() {
                    let code = '';
                    for (let i = 0; i < 6; i++) code += this.$refs['box' + i].value || '';
                    return code;
                },
                submitIfComplete() {
                    const code = this.collect();
                    if (code.length === 6 && /^\d{6}$/.test(code)) {
                        this.$refs.hidden.value = code;
                        this.$refs.form.submit();
                    }
                },
                onInput(e, i) {
                    const v = e.target.value.replace(/\D/g, '').slice(0, 1);
                    e.target.value = v;
                    if (v && i < 5) this.focusBox(i + 1);
                    this.submitIfComplete();
                },
                onBackspace(e, i) {
                    if (!e.target.value && i > 0) {
                        this.focusBox(i - 1);
                        this.$refs['box' + (i - 1)].value = '';
                        e.preventDefault();
                    }
                },
                handlePaste(e) {
                    const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                    if (!text) return;
                    e.preventDefault();
                    for (let i = 0; i < 6; i++) this.$refs['box' + i].value = text[i] || '';
                    this.focusBox(Math.min(text.length, 5));
                    this.submitIfComplete();
                },
                resend() {
                    if (!this.canResend) return;
                    document.getElementById('resend-form').submit();
                },
            };
        }
    </script>
</x-guest-layout>
