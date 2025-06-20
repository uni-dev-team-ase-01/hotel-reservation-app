<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TravelCompanyController;
use App\Http\Controllers\BookingSelectionController; // Add this
use Illuminate\Support\Facades\Route;


Route::post('/reservation/ajax-confirm', [ReservationController::class, 'ajaxConfirm'])->name('reservation.ajaxConfirm');

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
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
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');


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

Route::get('/hotel/{hotel}/rooms/{rooms}/book', [ReservationController::class, 'startBooking'])->name('reservation.startBooking');

Route::get('/reservation/payment', [ReservationController::class, 'paymentForm'])->middleware('auth')->name('reservation.paymentForm');
Route::post('/reservation/payment', [ReservationController::class, 'processPayment'])->middleware('auth')->name('reservation.processPayment');
Route::post('/stripe/intent', [ReservationController::class, 'createStripeIntent'])->middleware('auth')->name('stripe.intent');

Route::get('/reservation/success/{reservation?}', [ReservationController::class, 'success'])->middleware('auth')->name('reservation.success');

Route::get('policy', function () {
    return view('policy.index');
})->name('policy');

