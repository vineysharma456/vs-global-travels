<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\VisaApplyController;
use Twilio\Rest\Client;


Route::get('/test-whatsapp', function () {
    $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

    $client->messages->create(
        'whatsapp:+917888845434',
        [
            'from' => env('TWILIO_WHATSAPP_FROM'),
            'body' => 'Test message from vsglobaltravels'
        ]
    );

    return 'Message Sent';
});


Route::get('/', [HomePageController::class, 'homePage']);
// Route::post('/booking/store', [BookingController::class, 'bookingStore'])->name('booking.store');


Route::post('/booking',                  [BookingController::class, 'bookingStore'])->name('booking.store');
Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('booking.success');
Route::get('/countries',[CountryController::class,'index'])->name('country-list');
Route::get('/countries/{country}',[CountryController::class,'countryType'])->name('country-type');
Route::get('/start-application/{country}',[VisaApplyController::class,'startApplication'])->name('visa.apply');
Route::post('/scan-passport', [VisaApplyController::class, 'scan']);
Route::post('/save-passport-data', [VisaApplyController::class, 'savePassport']);

Route::post('/save-travelers', [VisaApplyController::class, 'saveTravelers']);
Route::get('/payment', [VisaApplyController::class, 'payment']);
//  admin 
// Route::get('/admin',[AdminController::class,'showLogin'])->name('admin.login');


// Admin Authentication Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Protected Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    Route::get('/add-countries',[CountryController::class,'addCountries'])->name('admin.add-country');
    Route::post('/admin/countries', [CountryController::class, 'store'])->name('admin.countries.store');



    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

});