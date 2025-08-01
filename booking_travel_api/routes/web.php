<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use Illuminate\Support\Facades\Auth;
=======
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
>>>>>>> 316e3bd8ee17b8501293b2fe95084baccd38bd73

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
<<<<<<< HEAD
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes
Auth::routes();

// Root URL – redirect based on authentication status
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('auth.login');
});

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
=======
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
>>>>>>> 316e3bd8ee17b8501293b2fe95084baccd38bd73

    // Dashboard Route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Packages Routes
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', function () {
            return view('packages.index');
        })->name('index');
<<<<<<< HEAD
        // Add more package-related routes here
        // Route::get('/create', ...); etc.
=======
        // Additional package routes can go here
>>>>>>> 316e3bd8ee17b8501293b2fe95084baccd38bd73
    });

    // Bookings Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', function () {
            return view('bookings.index');
        })->name('index');
<<<<<<< HEAD
        // Add more booking-related routes here
=======
        // Additional booking routes can go here
>>>>>>> 316e3bd8ee17b8501293b2fe95084baccd38bd73
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
<<<<<<< HEAD
        // Add more traveler-related routes here
=======
        // Additional traveler routes can go here
>>>>>>> 316e3bd8ee17b8501293b2fe95084baccd38bd73
    });

    // Guides Routes
    Route::prefix('guides')->name('guides.')->group(function () {
        Route::get('/', function () {
            return view('guides.index');
        })->name('index');
<<<<<<< HEAD
        // Add more guide-related routes here
=======
        // Additional guide routes can go here
>>>>>>> 316e3bd8ee17b8501293b2fe95084baccd38bd73
    });

    // Gallery Routes
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', function () {
            return view('gallery.index');
        })->name('index');
<<<<<<< HEAD
        // Add more gallery-related routes here
=======
        // Additional gallery routes can go here
>>>>>>> 316e3bd8ee17b8501293b2fe95084baccd38bd73
    });

    // Messages Routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', function () {
            return view('messages.index');
        })->name('index');
<<<<<<< HEAD
        // Add more message-related routes here
=======
        // Additional message routes can go here
>>>>>>> 316e3bd8ee17b8501293b2fe95084baccd38bd73
    });

    // Deals Routes
    Route::prefix('deals')->name('deals.')->group(function () {
        Route::get('/', function () {
            return view('deals.index');
        })->name('index');
<<<<<<< HEAD
        // Add more deal-related routes here
=======
        // Additional deals routes can go here
>>>>>>> 316e3bd8ee17b8501293b2fe95084baccd38bd73
    });

    // Feedback Routes
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/', function () {
            return view('feedback.index');
        })->name('index');
<<<<<<< HEAD
        // Add more feedback-related routes here
    });
});
=======
        // Additional feedback routes can go here
    });

    // Redirect root path to dashboard for authenticated users
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
});
>>>>>>> 316e3bd8ee17b8501293b2fe95084baccd38bd73
