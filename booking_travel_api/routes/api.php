<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\TravelerController;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('/users', UserController::class);
Route::apiResource('/booking', Bookingcontroller::class);
Route::apiResource('/destination', Destinationcontroller::class);
Route::get('/travelers', [TravelerController::class, 'index']);
