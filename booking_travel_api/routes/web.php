<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Login routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Dashboard
Route::middleware(['auth', \App\Http\Middleware\NoCache::class])->get('/dashboard', function () {
    $user = Auth::user();
    if ($user && in_array($user->role, ['admin', 'employee'])) {
        return view('dashboard');
    }
    abort(403, 'Unauthorized');
})->name('dashboard');
