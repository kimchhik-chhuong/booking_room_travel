<?php

use App\Http\Controllers\BookingHistoryController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HotelBookingController;
use App\Http\Controllers\HotelMetadataController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RestaurantMetadataController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TravelerController;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingHistoryController as ControllersBookingHistoryController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\AdventureController;

// Authentication routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Booking History Routes
Route::get('/booking-history', [BookingHistoryController::class, 'index']);
Route::get('/booking-history/{id}', [BookingHistoryController::class, 'show']);
Route::get('/booking-history/statistics', [BookingHistoryController::class, 'statistics']);

// Province Routes
Route::get('/provinces', [ProvinceController::class, 'index']);
Route::post('/provinces', [ProvinceController::class, 'store']);
Route::get('/provinces/{province}', [ProvinceController::class, 'show']);
Route::put('/provinces/{province}', [ProvinceController::class, 'update']);
Route::delete('/provinces/{province}', [ProvinceController::class, 'destroy']);
Route::get('/provinces/{province}/adventures', [ProvinceController::class, 'getAdventures']);
Route::get('/provinces/search', [ProvinceController::class, 'search']);

// Adventure Routes
Route::get('/adventures', [AdventureController::class, 'index']);
Route::post('/adventures', [AdventureController::class, 'store']);
Route::get('/adventures/{adventure}', [AdventureController::class, 'show']);
Route::put('/adventures/{adventure}', [AdventureController::class, 'update']);
Route::delete('/adventures/{adventure}', [AdventureController::class, 'destroy']);
Route::get('/adventures/search', [AdventureController::class, 'search']);
Route::get('/adventures/paginate', [AdventureController::class, 'paginate']);

// API route to serve adventure images with CORS headers
Route::get('/images/adventures/{filename}', function ($filename) {
    $path = public_path("uploads/adventures/{$filename}");
    
    if (!file_exists($path)) {
        // Return a simple default image
        $defaultImage = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCAAyADIDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9/KKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA//Z');
        return response($defaultImage, 200)
            ->header('Content-Type', 'image/jpeg')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
    }
    
    $file = file_get_contents($path);
    $type = mime_content_type($path);
    
    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
});

// Hotel Metadata Routes
Route::apiResource('hotelmetadata', HotelMetadataController::class);
Route::get('/hotelmetadata/search', [HotelMetadataController::class, 'search']);
Route::get('/hotelmetadata/price-range', [HotelMetadataController::class, 'getByPriceRange']);
Route::get('/hotelmetadata/top-rated', [HotelMetadataController::class, 'getTopRated']);
Route::get('/hotelmetadata/paginate', [HotelMetadataController::class, 'paginate']);

// Resource routes
Route::apiResource('booking', BookingController::class);
Route::apiResource('destination', DestinationController::class);
Route::apiResource('hotelbooking', HotelBookingController::class);
Route::apiResource('notification', NotificationController::class);
Route::apiResource('payment', PaymentController::class);
Route::apiResource('restaurantmetadata', RestaurantMetadataController::class);
Route::apiResource('review', ReviewController::class);

// Travelers
Route::get('/travelers', [TravelerController::class, 'index']);
Route::get('/travelers/{id}', [TravelerController::class, 'show']);
Route::post('/travelers', [TravelerController::class, 'store']);
Route::put('/travelers/{id}', [TravelerController::class, 'update']);
Route::delete('/travelers/{id}', [TravelerController::class, 'destroy']);

// Deals
Route::get('/deals', [DealController::class, 'index'])->name('deals.index');
Route::post('/deals', [DealController::class, 'store'])->name('deals.store');
Route::get('/deals/create', [DealController::class, 'create'])->name('deals.create');
Route::get('/deals/{id}', [DealController::class, 'show'])->name('deals.show');
Route::put('/deals/{id}', [DealController::class, 'update'])->name('deals.update');
Route::delete('/deals/{id}', [DealController::class, 'destroy'])->name('deals.destroy');

// Message routes
Route::get('/messages', [MessageController::class, 'index']);
Route::get('/messages/{id}', [MessageController::class, 'show']);
Route::post('/messages/send', [MessageController::class, 'store']);

//notification
Route::get('/notifications/count', [NotificationController::class, 'count'])->middleware('auth:api');

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
