<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\BookingController;

Route::get('/', [HomePageController::class, 'homePage']);
// Route::post('/booking/store', [BookingController::class, 'bookingStore'])->name('booking.store');


Route::post('/booking',                  [BookingController::class, 'bookingStore'])->name('booking.store');
Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('booking.success');
