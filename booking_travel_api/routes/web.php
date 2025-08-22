<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\RoomTypeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Default redirect to login or dashboard based on auth status
Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
})->name('home');

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
});

// Public routes
Route::prefix('hotels')->name('hotels.')->group(function () {
    Route::get('/', [\App\Http\Controllers\HotelMetadataController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\HotelMetadataController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\HotelMetadataController::class, 'store'])->name('store');
    
    // Explicitly define routes with hotel_id parameter
    Route::get('/{hotel}', [\App\Http\Controllers\HotelMetadataController::class, 'show'])
        ->name('show')
        ->where('hotel', '[0-9]+');
        
    Route::get('/{hotel}/edit', [\App\Http\Controllers\HotelMetadataController::class, 'edit'])
        ->name('edit')
        ->where('hotel', '[0-9]+');
        
    Route::put('/{hotel}', [\App\Http\Controllers\HotelMetadataController::class, 'update'])
        ->name('update')
        ->where('hotel', '[0-9]+');
        
    Route::delete('/{hotel}', [\App\Http\Controllers\HotelMetadataController::class, 'destroy'])
        ->name('destroy')
        ->where('hotel', '[0-9]+');
});

// Authenticated routes (Logged-in users)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard Route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Hotel management routes
    Route::resource('hotels', \App\Http\Controllers\HotelMetadataController::class)->except(['index', 'show']);
    
    // Room types management
    Route::resource('hotels.room-types', \App\Http\Controllers\RoomTypeController::class)->shallow();

    // Packages Routes
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::get('/province/{id}', [PackageController::class, 'showProvince'])->name('province');
        
        // Province CRUD routes
        Route::get('/provinces/create', [PackageController::class, 'create'])->name('provinces.create');
        Route::post('/provinces', [PackageController::class, 'store'])->name('provinces.store');
        Route::put('/provinces/{id}', [PackageController::class, 'update'])->name('provinces.update');
        Route::delete('/provinces/{id}', [PackageController::class, 'destroy'])->name('provinces.destroy');
    });

    // Adventures Routes
    Route::prefix('adventures')->name('adventures.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdventureController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\AdventureController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\AdventureController::class, 'store'])->name('store');
        
        // Explicitly define routes with adventure parameter
        Route::get('/{adventure}', [\App\Http\Controllers\AdventureController::class, 'show'])
            ->name('show')
            ->where('adventure', '[0-9]+');
            
        Route::get('/{adventure}/edit', [\App\Http\Controllers\AdventureController::class, 'edit'])
            ->name('edit')
            ->where('adventure', '[0-9]+');
            
        Route::put('/{adventure}', [\App\Http\Controllers\AdventureController::class, 'update'])
            ->name('update')
            ->where('adventure', '[0-9]+');
            
        Route::delete('/{adventure}', [\App\Http\Controllers\AdventureController::class, 'destroy'])
            ->name('destroy')
            ->where('adventure', '[0-9]+');
            
        // Province-based filtering
        Route::get('/province/{province}', [\App\Http\Controllers\AdventureController::class, 'byProvince'])
            ->name('province')
            ->where('province', '[0-9]+');
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
    // Removed this route as it's now handled by the 'home' route
});

// Route::resource('bookings', BookingController::class);
Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

// Specific route for default adventure image
Route::get('/uploads/adventures/default-adventure.jpg', function () {
    // Create a simple default image (orange gradient)
    $defaultImage = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCAAyADIDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9/KKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA//Z');
    
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
