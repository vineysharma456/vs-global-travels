<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\VisaApplyController;
use Twilio\Rest\Client;
use App\Http\Controllers\PaymentController;

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


Route::get('/', [HomePageController::class, 'homePage'])->name('home-page');
// Route::post('/booking/store', [BookingController::class, 'bookingStore'])->name('booking.store');


Route::post('/booking',                  [BookingController::class, 'bookingStore'])->name('booking.store');
Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('booking.success');
Route::get('/countries',[CountryController::class,'index'])->name('country-list');
Route::get('/countries/{country}',[CountryController::class,'countryType'])->name('country-type');
Route::get('/start-application/{country}',[VisaApplyController::class,'startApplication'])->name('visa.apply');
Route::post('/scan-passport', [VisaApplyController::class, 'scan']);
Route::post('/save-passport-data', [VisaApplyController::class, 'savePassport']);
  // Full page — traveler documents
    Route::get('/{country}/travelers', [VisaApplyController::class, 'travelerDocuments'])
         ->name('traveler-documents');
 
    // AJAX — render a new traveler card fragment
    Route::post('/add-traveler', [VisaApplyController::class, 'addTraveler'])
         ->name('visa.add-traveler');
 
    // AJAX — persist upload state
    Route::post('/save-travelers', [VisaApplyController::class, 'saveTravelers'])
         ->name('visa.save-travelers');
// Route::post('/save-travelers', [VisaApplyController::class, 'saveTravelers']);
Route::get('/payment', [VisaApplyController::class, 'payment']);
Route::post('/create-order', [PaymentController::class, 'createOrder']);
Route::post('/payment-success', [PaymentController::class, 'paymentSuccess']);
Route::get('/thank-you',[PaymentController::class,'paymentInvoice'])->name('thankyou-invoice');
Route::get('/invoice/download/{id}', [PaymentController::class, 'downloadInvoice'])
    ->name('invoice.download');
// thank-you

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
    Route::get('/country-list',[CountryController::class,'countryList'])->name('admin.country-list');
    Route::get('/country-image/{id}',[CountryController::class,'countryImages'])->name('admin.country.images');
    Route::post('/admin/country/images/store', [CountryController::class, 'storeCountryImages'])
    ->name('admin.country.images.store');
    Route::patch('admin/country/images/reorder', [CountryController::class, 'reorder'])
        ->name('admin.country.images.reorder');
    Route::delete('admin/country/images/{image}', [CountryController::class, 'destroy'])
     ->name('admin.country.images.destroy');

    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

});