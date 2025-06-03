<?php

use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hotels', [RoomController::class, 'index'])->name('hotels');
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
