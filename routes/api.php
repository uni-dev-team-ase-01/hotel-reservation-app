<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\RoomApiController; // Commented out due to missing controller

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/webhook/stripe', [\App\Http\Controllers\StripeWebhookController::class, 'handleWebhook']);

// Route for fetching available rooms for a hotel
// Route::get('/hotels/{hotel}/rooms', [RoomApiController::class, 'index'])->name('api.hotel.rooms'); // Commented out due to missing controller
