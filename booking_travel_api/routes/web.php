<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MessageController;

// Public route - welcome page
Route::get('/', function () {
    return view('welcome');
});

// Login routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout route - requires authentication
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Protected routes with auth and no cache middleware
Route::middleware(['auth', \App\Http\Middleware\NoCache::class])->group(function () {
    
    // Dashboard route - only admin or employee can access
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user && in_array($user->role, ['admin', 'employee'])) {
            return view('dashboard');
        }
        abort(403, 'Unauthorized');
    })->name('dashboard');

    // Dashboard controller index (alternative)
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes for packages, bookings, messages
    Route::resource('packages', PackageController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('messages', MessageController::class);

    // Other pages (optional)
    Route::get('/calendar', function () {
        return view('calendar');
    })->name('calendar');

    Route::get('/travelers', function () {
        return view('travelers.index');
    })->name('travelers.index');

    Route::get('/guides', function () {
        return view('guides.index');
    })->name('guides.index');

    Route::get('/gallery', function () {
        return view('gallery.index');
    })->name('gallery.index');

    Route::get('/deals', function () {
        return view('deals.index');
    })->name('deals.index');

    Route::get('/feedback', function () {
        return view('feedback.index');
    })->name('feedback.index');
});
