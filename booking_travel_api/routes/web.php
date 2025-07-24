<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard route - only for admin and employee roles
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        /** @var \App\Models\User|null $user */
        $user = Auth::user(); // Use Auth facade instead of auth()->user()
        if ($user && ($user->role === 'admin' || $user->role === 'employee')) {
            return view('dashboard');
        }
        abort(403, 'Unauthorized');
    })->name('dashboard');
});
