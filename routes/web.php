<?php

use App\Http\Controllers\Admin\LoginActivityController as AdminLoginActivityController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ConnectionsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'two-factor'])->name('dashboard');

Route::middleware(['auth', 'two-factor'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/connections', [ConnectionsController::class, 'index'])->name('connections.index');
    Route::delete('/connections/{provider}', [ConnectionsController::class, 'destroy'])->name('connections.destroy');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::get('logins', [AdminLoginActivityController::class, 'index'])->name('logins.index');
    });
});

require __DIR__.'/auth.php';
