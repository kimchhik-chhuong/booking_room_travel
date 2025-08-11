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

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
