<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConnectionsController extends Controller
{
    private const PROVIDERS = [
        'google' => [
            'label' => 'Google',
            'description' => 'Sign in and share your name, email, and profile picture.',
            'scopes' => ['openid', 'profile', 'email'],
            'manage_url' => 'https://myaccount.google.com/connections',
        ],
    ];

    public function index(Request $request): View
    {
        $user = $request->user();

        $connections = collect(self::PROVIDERS)->map(function ($meta, $key) use ($user) {
            $linked = $user->provider_name === $key;

            return [
                'key' => $key,
                'label' => $meta['label'],
                'description' => $meta['description'],
                'scopes' => $meta['scopes'],
                'manage_url' => $meta['manage_url'],
                'linked' => $linked,
                'linked_at' => $linked ? $user->created_at : null,
                'avatar' => $linked ? $user->avatar : null,
            ];
        })->values();

        return view('connections.index', [
            'connections' => $connections,
            'canUnlink' => (bool) $user->password,
        ]);
    }

    public function destroy(Request $request, string $provider): RedirectResponse
    {
        if (! array_key_exists($provider, self::PROVIDERS)) {
            abort(404);
        }

        $user = $request->user();

        if ($user->provider_name !== $provider) {
            return back()->with('status', __('That account was not connected.'));
        }

        if (! $user->password) {
            return back()->withErrors([
                'unlink' => __('Set a password first — otherwise you would lose access to your account after disconnecting.'),
            ]);
        }

        $user->update([
            'provider_name' => null,
            'provider_id' => null,
            'avatar' => null,
        ]);

        return back()->with('status', __('Connection removed.'));
    }
}
