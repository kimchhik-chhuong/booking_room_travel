<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\TravelerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

/*
|--------------------------------------------------------------------------
| Guest Routes (not logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Register
    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Default root → login
    Route::get('/', fn() => redirect()->route('login'));
});

// Profile Routes (moved outside auth middleware)
Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (must be logged in)
|--------------------------------------------------------------------------
*/
// Public routes
Route::prefix('hotels')->name('hotels.')->group(function () {
    // Hotel CRUD routes
    Route::get('/', [\App\Http\Controllers\HotelMetadataController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\HotelMetadataController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\HotelMetadataController::class, 'store'])->name('store');
    
    // Hotel-specific routes
    Route::get('/{hotel}', [\App\Http\Controllers\HotelMetadataController::class, 'show'])->name('show');
    Route::get('/{hotel}/edit', [\App\Http\Controllers\HotelMetadataController::class, 'edit'])->name('edit');
    Route::put('/{hotel}', [\App\Http\Controllers\HotelMetadataController::class, 'update'])->name('update');
    Route::delete('/{hotel}', [\App\Http\Controllers\HotelMetadataController::class, 'destroy'])->name('destroy');
    
    // Hotel Booking routes
    Route::get('/{hotel}/book', [\App\Http\Controllers\HotelBookingController::class, 'create'])
        ->name('book')
        ->where('hotel', '[0-9]+');
    Route::post('/{hotel}/bookings', [\App\Http\Controllers\HotelBookingController::class, 'store'])
        ->name('bookings.store')
        ->where('hotel', '[0-9]+');
    
    // Room Type routes
    Route::prefix('{hotel}')->group(function () {
        Route::get('/room-types', [RoomTypeController::class, 'index'])->name('room-types.index');
        Route::get('/room-types/create', [RoomTypeController::class, 'create'])->name('room-types.create');
        Route::post('/room-types', [RoomTypeController::class, 'store'])->name('room-types.store');
        
        // Explicitly define routes with parameters in correct order
        Route::prefix('room-types/{roomType}')->group(function () {
            Route::get('/edit', [RoomTypeController::class, 'edit'])->name('room-types.edit');
            Route::put('/', [RoomTypeController::class, 'update'])->name('room-types.update');
            Route::delete('/', [RoomTypeController::class, 'destroy'])->name('room-types.destroy');
        });
    })->where(['hotel' => '[0-9]+']);
    
})->middleware(['auth']);

// Authenticated routes (Logged-in users)
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Travelers Resource
    Route::resource('travelers', TravelerController::class);

    // Bookings
    Route::resource('bookings', BookingController::class);
    
    // Additional booking routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::patch('{bookingId}/check-in', [BookingController::class, 'checkIn'])->name('check-in');
        Route::patch('{bookingId}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::post('{id}/cancel', [BookingController::class, 'cancelUserBooking'])->name('user.cancel');
    });

    /*
    |--------------------------------------------------------------------------
    | Packages
    |--------------------------------------------------------------------------
    */
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::get('/province/{id}', [PackageController::class, 'showProvince'])->name('province');

        // Province CRUD
        Route::get('/provinces/create', [PackageController::class, 'create'])->name('provinces.create');
        Route::post('/provinces', [PackageController::class, 'store'])->name('provinces.store');
        Route::put('/provinces/{id}', [PackageController::class, 'update'])->name('provinces.update');
        Route::delete('/provinces/{id}', [PackageController::class, 'destroy'])->name('provinces.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */
    // Hotel Booking Routes
    Route::prefix('hotels')->name('hotels.')->group(function () {
        // Existing hotel routes...
        
        // Add these new routes for hotel bookings
        Route::post('/{hotel}/bookings', [\App\Http\Controllers\HotelBookingController::class, 'storeBooking'])
            ->name('bookings.store')
            ->where('hotel', '[0-9]+');
            
        Route::post('/{hotel}/room-types', [RoomTypeController::class, 'store'])->name('room-types.store');
    });

    // Add booking routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])
            ->name('show')
            ->where('booking', '[0-9]+');
        Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\BookingController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\BookingController::class, 'store'])->name('store');
        Route::patch('/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])
            ->name('cancel')
            ->where('booking', '[0-9]+');
        Route::patch('/{booking}/check-in', [\App\Http\Controllers\BookingController::class, 'checkIn'])
            ->name('check-in')
            ->where('booking', '[0-9]+');
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
        Route::get('/province/{provinceId}', [\App\Http\Controllers\AdventureController::class, 'byProvince'])
            ->name('province')
            ->where('provinceId', '[0-9]+');
    });

    // Bookings Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\BookingController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\BookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])
            ->name('show')
            ->where('booking', '[0-9]+');
    });

    // Payment Routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/booking/{bookingId}', [\App\Http\Controllers\PaymentController::class, 'showPaymentForm'])
            ->name('show')
            ->middleware('auth');
        
        Route::post('/process/{bookingId}', [\App\Http\Controllers\PaymentController::class, 'processPayment'])
            ->name('process')
            ->middleware('auth');
    });

    /*
    |--------------------------------------------------------------------------
    | Calendar
    |--------------------------------------------------------------------------
    */
    Route::get('/calendar', fn() => view('calendar'))->name('calendar');

    // Traveler Management Routes
    Route::middleware('auth')->prefix('travelers')->name('travelers.')->group(function () {
        Route::get('/', [TravelerController::class, 'index'])->name('index');
        Route::get('/create', [TravelerController::class, 'create'])->name('create');
        Route::post('/', [TravelerController::class, 'store'])->name('store');
        Route::get('/{traveler}', [TravelerController::class, 'show'])->name('show');
        Route::get('/{traveler}/edit', [TravelerController::class, 'edit'])->name('edit');
        Route::put('/{traveler}', [TravelerController::class, 'update'])->name('update');
        Route::delete('/{traveler}', [TravelerController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Guides
    |--------------------------------------------------------------------------
    */
    Route::prefix('guides')->name('guides.')->group(function () {
        Route::get('/', fn() => view('guides.index'))->name('index');
    });

    /*
    |--------------------------------------------------------------------------
    | Gallery
    |--------------------------------------------------------------------------
    */
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', fn() => view('gallery.index'))->name('index');
    });

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', fn() => view('messages.index'))->name('index');
    });

    /*
    |--------------------------------------------------------------------------
    | Deals
    |--------------------------------------------------------------------------
    */
    Route::prefix('deals')->name('deals.')->group(function () {
        Route::get('/', fn() => view('deals.index'))->name('index');
    });

    /*
    |--------------------------------------------------------------------------
    | Feedback
    |--------------------------------------------------------------------------
    */
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/', fn() => view('feedback.index'))->name('index');
    });

    // Root redirect to dashboard (for logged in users)
    Route::get('/', fn() => redirect()->route('dashboard'));
    // Temporary debug route - remove after testing
    Route::get('/debug/hotel/{id}', function($id) {
        $hotel = \App\Models\HotelMetadata::with('user')->findOrFail($id);
        return [
            'hotel' => $hotel->toArray(),
            'user' => $hotel->user ? $hotel->user->toArray() : null,
            'user_id' => $hotel->user_id
        ];
    })->middleware('auth');

    // Debug route to check user roles - remove after testing
    Route::get('/debug/check-roles', function() {
        $user = auth()->user();
        return [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames()->toArray(),
            'permissions' => $user->getAllPermissions()->pluck('name')
        ];
    })->middleware('auth');

    // Redirect root path to dashboard for authenticated users
    // Removed this route as it's now handled by the 'home' route
});

/*
|--------------------------------------------------------------------------
| Image & Upload Routes (with CORS headers)
|--------------------------------------------------------------------------
*/
Route::get('/uploads/adventures/default-adventure.jpg', function () {
    $defaultImage = base64_decode('...'); // your base64 image
    return response($defaultImage)->header('Content-Type', 'image/jpeg')->header('Access-Control-Allow-Origin', '*');
    // Create a simple default image (orange gradient)
    $defaultImage = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCAAyADIDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9/KKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA//Z');
    
    return response($defaultImage)
        ->header('Content-Type', 'image/jpeg')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
});

Route::get('/uploads/{folder}/{filename}', function ($folder, $filename) {
    $path = public_path("uploads/{$folder}/{$filename}");

    if (!file_exists($path)) {
        $transparentPng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        return response($transparentPng)->header('Content-Type', 'image/png')->header('Access-Control-Allow-Origin', '*');
    }

    $file = file_get_contents($path);
    $type = mime_content_type($path);

    return response($file)->header('Content-Type', $type)->header('Access-Control-Allow-Origin', '*');
})->where(['folder' => '[a-zA-Z0-9_-]+', 'filename' => '[a-zA-Z0-9._-]+']);

Route::options('/uploads/{folder}/{filename}', fn() => response('', 200)
    ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
    ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization')
    ->header('Access-Control-Max-Age', '86400')
)->where(['folder' => '[a-zA-Z0-9_-]+', 'filename' => '[a-zA-Z0-9._-]+']);
