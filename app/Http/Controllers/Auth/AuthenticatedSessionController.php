<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        LoginActivity::create([
            'user_id' => $request->user()->id,
            'provider' => 'email',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'logged_in_at' => now(),
        ]);

        if ($request->user()?->twoFactorRecentlyVerified()) {
            $request->session()->put('two_factor.verified', true);
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $code = TwoFactorController::sendCode($request);

        return redirect()->route('two-factor.show')
            ->with(app()->isLocal() ? ['dev_code' => $code] : []);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->forget(['two_factor.code', 'two_factor.expires_at', 'two_factor.verified']);

        return redirect()->route('login');
    }
}
