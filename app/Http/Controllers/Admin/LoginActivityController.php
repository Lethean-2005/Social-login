<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginActivityController extends Controller
{
    public function index(Request $request): View
    {
        $query = LoginActivity::with('user')->latest('logged_in_at');

        if ($userId = $request->integer('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($provider = $request->string('provider')->toString()) {
            $query->where('provider', $provider);
        }

        $activities = $query->paginate(25)->withQueryString();

        $stats = [
            'total' => LoginActivity::count(),
            'today' => LoginActivity::whereDate('logged_in_at', now()->toDateString())->count(),
            'last7' => LoginActivity::where('logged_in_at', '>=', now()->subDays(7))->count(),
            'unique' => LoginActivity::distinct('user_id')->count('user_id'),
        ];

        return view('admin.logins.index', [
            'activities' => $activities,
            'stats' => $stats,
            'users' => User::orderBy('name')->get(['id', 'name', 'email']),
            'filters' => [
                'user_id' => $request->integer('user_id'),
                'provider' => $request->string('provider')->toString(),
            ],
        ]);
    }
}
