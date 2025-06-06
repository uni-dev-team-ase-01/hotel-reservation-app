<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TravelCompanyController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels');
Route::controller(HotelController::class)->prefix('hotels')->group(function () {
    Route::get('/', 'index')->name('hotels');
    Route::get('/getHotels', 'getHotels')->name('hotels.get');
    Route::get('/select-options', 'selectOptions');

});

Route::get('hotel/{hotel}/rooms', [RoomController::class, 'showRooms']);
Route::get('hotel/{hotel}/available-rooms', [RoomController::class, 'availableRooms']);

Route::get('hotel/search', [HotelController::class, 'search']);
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Route::middleware(['auth', 'role:super-admin'])->group(function () {
//     Volt::route('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
// });

// Route::middleware(['auth', 'role:hotel-manager'])->group(function () {
//     Volt::route('/manager/dashboard', 'manager.dashboard')->name('manager.dashboard');
// });

// Route::middleware(['auth', 'role:hotel-clerk'])->group(function () {
//     Volt::route('/clerk/dashboard', 'clerk.dashboard')->name('clerk.dashboard');
// });

// Route::middleware(['auth', 'role:travel-company'])->group(function () {
//     Volt::route('/travel/dashboard', 'travel.dashboard')->name('travel.dashboard');
// });

Route::apiResource('api/hotels', HotelController::class);
Route::get('/travel-agent', [TravelCompanyController::class, 'index']);
Route::post('/travel-agent/submit', [TravelCompanyController::class, 'store'])->name('travel-agent.submit');