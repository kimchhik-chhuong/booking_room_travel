<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within the "web" middleware group.
|
*/

// Laravel authentication routes (login, register, etc.)
Auth::routes();

// Root URL: redirect based on authentication status
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('auth.login');
});

// Authenticated routes group
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Packages
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', function () {
            return view('packages.index');
        })->name('index');
        // More package routes (create, store, edit, etc.) can be added here
    });

    // Bookings
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', function () {
            return view('bookings.index');
        })->name('index');
        // More booking routes
    });

    // Calendar
    Route::get('/calendar', function () {
        return view('calendar');
    })->name('calendar');

    // Travelers
    Route::prefix('travelers')->name('travelers.')->group(function () {
        Route::get('/', function () {
            return view('travelers.index');
        })->name('index');
        // More traveler routes
    });

    // Guides
    Route::prefix('guides')->name('guides.')->group(function () {
        Route::get('/', function () {
            return view('guides.index');
        })->name('index');
        // More guide routes
    });

    // Gallery
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', function () {
            return view('gallery.index');
        })->name('index');
        // More gallery routes
    });

    // Messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', function () {
            return view('messages.index');
        })->name('index');
        // More message routes
    });

    // Deals
    Route::prefix('deals')->name('deals.')->group(function () {
        Route::get('/', function () {
            return view('deals.index');
        })->name('index');
        // More deal routes
    });

    // Feedback
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/', function () {
            return view('feedback.index');
        })->name('index');
        // More feedback routes
    });
});

// Optional: Fallback route for unknown URLs
Route::fallback(function () {
    return view('errors.404'); // Make sure you have resources/views/errors/404.blade.php
});
