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
        Route::get('/province/{id}', [PackageController::class, 'showProvince'])->name('province');
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

// Specific route for default adventure image
Route::get('/uploads/adventures/default-adventure.jpg', function () {
    // Create a simple default image (orange gradient)
    $defaultImage = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCAAyADIDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9/KKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA//Z');
    
    return response($defaultImage)
        ->header('Content-Type', 'image/jpeg')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
});

// Route to serve images with CORS headers
Route::get('/uploads/{folder}/{filename}', function ($folder, $filename) {
    $path = public_path("uploads/{$folder}/{$filename}");
    
    if (!file_exists($path)) {
        // Return a 1x1 transparent PNG as fallback
        $transparentPng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        return response($transparentPng)
            ->header('Content-Type', 'image/png')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
    }
    
    $file = file_get_contents($path);
    $type = mime_content_type($path);
    
    return response($file)
        ->header('Content-Type', $type)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
})->where(['folder' => '[a-zA-Z0-9_-]+', 'filename' => '[a-zA-Z0-9._-]+']);

// Handle OPTIONS requests for CORS preflight
Route::options('/uploads/{folder}/{filename}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization')
        ->header('Access-Control-Max-Age', '86400');
})->where(['folder' => '[a-zA-Z0-9_-]+', 'filename' => '[a-zA-Z0-9._-]+']);
