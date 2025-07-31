<?php
use App\Http\Controllers\Auth\LoginController;
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
use App\Models\CambodiaTrip;

Route::get('/test-cambodia-trip-factory', function () {
    $trips = CambodiaTrip::factory()->count(5)->make();
    $hotelNames = $trips->pluck('hotel_name')->toArray();
    return response()->json([
        'hotel_names' => $hotelNames,
    ]);
});


// Authentication Routes
Auth::routes();

// Root URL – redirect based on authentication status
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('auth.login');
});
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
// Routes that require authentication
Route::middleware(['auth'])->group(function () {

    // Dashboard Route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Packages Routes
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', function () {
            return view('packages.index');
        })->name('index');
        // Add more package-related routes here
        // Route::get('/create', ...); etc.
    });

    // Bookings Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', function () {
            return view('bookings.index');
        })->name('index');
        // Add more booking-related routes here
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
        // Add more traveler-related routes here
    });

    // Guides Routes
    Route::prefix('guides')->name('guides.')->group(function () {
        Route::get('/', function () {
            return view('guides.index');
        })->name('index');
        // Add more guide-related routes here
    });

    // Gallery Routes
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', function () {
            return view('gallery.index');
        })->name('index');
        // Add more gallery-related routes here
    });

    // Messages Routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', function () {
            return view('messages.index');
        })->name('index');
        // Add more message-related routes here
    });

    // Deals Routes
    Route::prefix('deals')->name('deals.')->group(function () {
        Route::get('/', function () {
            return view('deals.index');
        })->name('index');
        // Add more deal-related routes here
    });

    // Feedback Routes
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/', function () {
            return view('feedback.index');
        })->name('index');
        // Add more feedback-related routes here
    });
});
