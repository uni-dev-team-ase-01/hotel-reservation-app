<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\CheckoutController;

Auth::routes();

Volt::route('/', 'customer.home')->name('home');
Volt::route('/hotels', 'customer.hotels.index')->name('hotels');
Volt::route('/about', 'customer.about')->name('about');
Volt::route('/contact', 'customer.contact')->name('contact');
Route::get('/checkout', [CheckoutController::class, 'index']);
Route::post('/checkout/process', [CheckoutController::class, 'process']);

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



