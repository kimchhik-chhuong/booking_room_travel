<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\PackageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest routes (Unauthenticated users)
Route::middleware('guest')->group(function () {
    // Show Login Page
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    // Handle Login Form Submission
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Show Register Page
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    // Handle Register Form Submission
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Default redirect to login
    Route::get('/', function () {
        return redirect()->route('login');
    });
});

// Authenticated routes (Logged-in users)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard Route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Packages Routes
    Route::prefix('packages')->name('packages.')->group(function () {
    Route::get('/', [PackageController::class, 'index'])->name('index');
    });

    // Bookings Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', function () {
            return view('bookings.index');
        })->name('index');
        // Additional booking routes can go here
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
        // Additional traveler routes can go here
    });

    // Guides Routes
    Route::prefix('guides')->name('guides.')->group(function () {
        Route::get('/', function () {
            return view('guides.index');
        })->name('index');
        // Additional guide routes can go here
    });

    // Gallery Routes
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', function () {
            return view('gallery.index');
        })->name('index');
        // Additional gallery routes can go here
    });

    // Messages Routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', function () {
            return view('messages.index');
        })->name('index');
        // Additional message routes can go here
    });

    // Deals Routes
    Route::prefix('deals')->name('deals.')->group(function () {
        Route::get('/', function () {
            return view('deals.index');
        })->name('index');
        // Additional deals routes can go here
    });

    // Feedback Routes
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/', function () {
            return view('feedback.index');
        })->name('index');
        // Additional feedback routes can go here
    });

    // Redirect root path to dashboard for authenticated users
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
});

// Route::resource('bookings', BookingController::class);
Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
