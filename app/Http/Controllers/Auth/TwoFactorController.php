<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public static function sendCode(Request $request): string
    {
        $code = (string) random_int(100000, 999999);

        $request->session()->put('two_factor.code', Hash::make($code));
        $request->session()->put('two_factor.expires_at', now()->addMinutes(10)->toIso8601String());
        $request->session()->put('two_factor.verified', false);

        $user = $request->user();
        if ($user && $user->email) {
            Mail::raw("Your verification code is: {$code}\n\nIt expires in 10 minutes.", function ($message) use ($user) {
                $message->to($user->email)->subject('Your verification code');
            });
        }

        return $code;
    }

    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('two_factor.code')) {
            return redirect()->route('login');
        }

        return view('auth.verify-code');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ]);

        $hashed = $request->session()->get('two_factor.code');
        $expiresAt = $request->session()->get('two_factor.expires_at');

        if (! $hashed || ! $expiresAt || now()->greaterThan($expiresAt)) {
            throw ValidationException::withMessages([
                'code' => __('The code has expired. Please request a new one.'),
            ]);
        }

        if (! Hash::check($request->input('code'), $hashed)) {
            throw ValidationException::withMessages([
                'code' => __('The code is incorrect.'),
            ]);
        }

        $request->session()->forget(['two_factor.code', 'two_factor.expires_at']);
        $request->session()->put('two_factor.verified', true);
        $request->session()->regenerate();

        $user = $request->user();
        if ($user) {
            $user->forceFill(['two_factor_verified_at' => now()])->save();
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function resend(Request $request): RedirectResponse
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $code = self::sendCode($request);

        return back()->with('status', app()->isLocal()
            ? "A new code has been generated (dev mode: {$code})."
            : 'A new code has been sent to your email.');
    }
}
