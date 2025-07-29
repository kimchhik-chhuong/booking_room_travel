<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Auth::routes();

// Default Route
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('auth.login');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard Route
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Packages
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::view('/', 'packages.index')->name('index');
    });

    // Bookings
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::view('/', 'bookings.index')->name('index');
    });

    // Calendar
    Route::view('/calendar', 'calendar')->name('calendar');

    // Travelers
    Route::prefix('travelers')->name('travelers.')->group(function () {
        Route::view('/', 'travelers.index')->name('index');
    });

    // Guides
    Route::prefix('guides')->name('guides.')->group(function () {
        Route::view('/', 'guides.index')->name('index');
    });

    // Gallery
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::view('/', 'gallery.index')->name('index');
    });

    // Messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::view('/', 'messages.index')->name('index');
    });

    // Deals
    Route::prefix('deals')->name('deals.')->group(function () {
        Route::view('/', 'deals.index')->name('index');
    });

    // Feedback
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::view('/', 'feedback.index')->name('index');
    });
});

// Optional: Fallback route for unknown URLs
Route::fallback(function () {
    return view('errors.404'); // Make sure you have resources/views/errors/404.blade.php
});
