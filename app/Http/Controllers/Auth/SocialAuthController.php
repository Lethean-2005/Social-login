<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;

class SocialAuthController extends Controller
{
    private const PROVIDERS = ['google'];

    public function redirect(string $provider): SymfonyRedirect|RedirectResponse
    {
        if (! in_array($provider, self::PROVIDERS, true)) {
            return redirect()->route('login')->withErrors(['provider' => 'Unsupported login provider.']);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        if (! in_array($provider, self::PROVIDERS, true)) {
            return redirect()->route('login')->withErrors(['provider' => 'Unsupported login provider.']);
        }

        try {
            $social = Socialite::driver($provider)->user();
        } catch (\Throwable $e) {
            Log::error("Social login failed for {$provider}: ".$e->getMessage(), ['exception' => $e]);

            return redirect()->route('login')->withErrors([
                'provider' => app()->isLocal()
                    ? "Could not sign you in with {$provider}: ".$e->getMessage()
                    : "Could not sign you in with {$provider}. Please try again.",
            ]);
        }

        $email = $social->getEmail();
        if (! $email) {
            return redirect()->route('login')->withErrors([
                'provider' => "The {$provider} account did not return an email address.",
            ]);
        }

        $user = User::where('provider_name', $provider)
            ->where('provider_id', $social->getId())
            ->first();

        if (! $user) {
            $user = User::firstOrNew(['email' => $email]);
            $user->name = $user->name ?: ($social->getName() ?: Str::before($email, '@'));
            $user->provider_name = $provider;
            $user->provider_id = $social->getId();
            $user->avatar = $social->getAvatar();
            $user->email_verified_at = $user->email_verified_at ?: now();
            $user->save();
        }

        Auth::login($user, remember: true);

        $code = TwoFactorController::sendCode(request());

        return redirect()->route('two-factor.show')
            ->with(app()->isLocal() ? ['dev_code' => $code] : []);
    }
}
