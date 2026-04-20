<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::latest()->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'counts' => [
                'total' => User::count(),
                'admins' => User::where('is_admin', true)->count(),
                'google' => User::where('provider_name', 'google')->count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
            ],
        ]);
    }

    public function toggleAdmin(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'You cannot change your own admin status.']);
        }

        $user->forceFill(['is_admin' => ! $user->is_admin])->save();

        return back()->with('status', $user->is_admin
            ? "{$user->email} is now an admin."
            : "{$user->email} is no longer an admin.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'You cannot delete your own account from here.']);
        }

        $email = $user->email;
        $user->delete();

        return back()->with('status', "Deleted {$email}.");
    }
}
