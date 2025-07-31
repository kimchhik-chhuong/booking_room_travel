<?php
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CambodiaTripController;
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
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\AdventureController;
use App\Http\Controllers\FakeDataController;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('booking', BookingController::class);
Route::apiResource('destination', DestinationController::class);
Route::apiResource('hotelbooking', HotelBookingController::class);
Route::apiResource('hotelmetadata', HotelMetadataController::class);
Route::apiResource('notification', NotificationController::class);
Route::apiResource('payment', PaymentController::class);
Route::apiResource('restaurantmetadata', RestaurantMetadataController::class);
Route::apiResource('review', ReviewController::class);
//travelers
Route::get('/travelers', [TravelerController::class, 'index']);
Route::get('/travelers/{id}', [TravelerController::class, 'show']);
Route::post('/travelers', [TravelerController::class, 'store']);
Route::put('/travelers/{id}', [TravelerController::class, 'update']);
Route::delete('/travelers/{id}', [TravelerController::class, 'destroy']);

// New route for provinces in Cambodia
Route::get('/provinces', [DestinationController::class, 'getProvinces']);

// New route to get hotels for a given province
Route::get('/provinces/{id}/hotels', [HotelController::class, 'getHotelsByProvince']);

// New routes for adventures
Route::get('/provinces/{province}/adventures', [AdventureController::class, 'getAdventuresByProvince']);
Route::get('/provinces/{province}/adventures-fake', [FakeDataController::class, 'getAdventuresByProvinceFake']);
Route::get('/adventures/{adventure}/hotels', [AdventureController::class, 'getHotelsByAdventure']);
Route::get('/adventures/{adventure}/hotels-fake', [FakeDataController::class, 'getHotelsByAdventureFake']);

//
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
});

Route::post('/cambodia-trips', [CambodiaTripController::class, 'store']);
