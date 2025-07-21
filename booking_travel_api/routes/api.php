<?php

use App\Http\Controllers\TravelerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/travelers', [TravelerController::class, 'index']);
