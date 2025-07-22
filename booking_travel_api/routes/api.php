<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelerController;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//travelers
Route::get('/travelers', [TravelerController::class, 'index']);
Route::get('/travelers/{id}', [TravelerController::class, 'show']);
Route::post('/travelers', [TravelerController::class, 'store']);
Route::put('/travelers/{id}', [TravelerController::class, 'update']);
Route::delete('/travelers/{id}', [TravelerController::class, 'destroy']);

//
