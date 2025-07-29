<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes (Login, Register, etc.)
Auth::routes();

// Routes that require authentication
Route::middleware(['auth'])->group(function () {

    // Dashboard Route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Default redirect after login
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Packages Routes
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', function () {
            return view('packages.index');
        })->name('index');
        // More routes: create, store, show, edit, update, destroy
    });

    // Bookings Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', function () {
            return view('bookings.index');
        })->name('index');
        // More routes
    });

    // Calendar Route
    Route::get('/calendar', function () {
        return view('calendar');
    })->name('calendar');

    // Travelers Routes
    Route::prefix('travelers')->name('travelers.')->group(function () {
        Route::get('/', function () {
            return view('travelers.index');
        })->name('index');
    });

    // Guides Routes
    Route::prefix('guides')->name('guides.')->group(function () {
        Route::get('/', function () {
            return view('guides.index');
        })->name('index');
    });

    // Gallery Routes
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', function () {
            return view('gallery.index');
        })->name('index');
    });

    // Messages Routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', function () {
            return view('messages.index');
        })->name('index');
    });

    // Deals Routes
    Route::prefix('deals')->name('deals.')->group(function () {
        Route::get('/', function () {
            return view('deals.index');
        })->name('index');
    });

    // Feedback Routes
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/', function () {
            return view('feedback.index');
        })->name('index');
    });
});

// Redirect unauthenticated users to login page
Route::get('/', function () {
    return view('auth.login');
});
