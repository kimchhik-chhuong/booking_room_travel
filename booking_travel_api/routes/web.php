<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\PackageController;
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

/*
|--------------------------------------------------------------------------
| Authenticated Routes (must be logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/home', fn() => redirect()->route('dashboard'))->name('home');

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
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', fn() => view('bookings.index'))->name('index');
        Route::get('/create', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
    });

    /*
    |--------------------------------------------------------------------------
    | Calendar
    |--------------------------------------------------------------------------
    */
    Route::get('/calendar', fn() => view('calendar'))->name('calendar');

    /*
    |--------------------------------------------------------------------------
    | Travelers CRUD
    |--------------------------------------------------------------------------
    */
    Route::prefix('travelers')->name('travelers.')->group(function () {
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
});

/*
|--------------------------------------------------------------------------
| Image & Upload Routes (with CORS headers)
|--------------------------------------------------------------------------
*/
Route::get('/uploads/adventures/default-adventure.jpg', function () {
    $defaultImage = base64_decode('...'); // your base64 image
    return response($defaultImage)->header('Content-Type', 'image/jpeg')->header('Access-Control-Allow-Origin', '*');
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
