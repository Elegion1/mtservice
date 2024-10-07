<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ExcursionController;
use App\Http\Controllers\OwnerDataController;
use App\Http\Controllers\DestinationController;


//navigazione
if (App::getLocale() == "it") {
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/noleggio/auto/trapani', [PublicController::class, 'noleggio'])->name('noleggio');
    Route::get('/transfer-taxi-trapani', [PublicController::class, 'transfer'])->name('transfer');
    Route::get('/escursioni-trapani', [PublicController::class, 'escursioni'])->name('escursioni');
    Route::get('/prezzi-e-destinazioni', [PublicController::class, 'prezziDestinazioni'])->name('prezziDestinazioni');
    Route::get('/dicono-di-noi', [PublicController::class, 'diconoDiNoi'])->name('diconoDiNoi');
    Route::get('/contattaci-richiesta-informazioni', [PublicController::class, 'contattaci'])->name('contattaci');
    Route::get('/partners', [PublicController::class, 'partners'])->name('partners');
    Route::get('/FAQ', [PublicController::class, 'faq'])->name('faq');
    Route::get('/privacy-terms-and-conditions', [PublicController::class, 'privacy'])->name('privacy');
    Route::get('/servizi/{title}/{id}', [ServiceController::class, 'show'])->name('service.show');
    Route::get('/escursioni/provincia-di-trapani/{name}/{id}', [ExcursionController::class, 'show'])->name('excursion.show');
    Route::get('/servizi', [PublicController::class, 'servizi'])->name('services.index');
} elseif (App::getLocale() == "en") {
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/en/rent/cars/trapani', [PublicController::class, 'noleggio'])->name('noleggio');
    Route::get('/en/transfer-taxi-trapani', [PublicController::class, 'transfer'])->name('transfer');
    Route::get('/en/excursions-trapani', [PublicController::class, 'escursioni'])->name('escursioni');
    Route::get('/en/prices-and-destinations', [PublicController::class, 'prezziDestinazioni'])->name('prezziDestinazioni');
    Route::get('/en/about-us', [PublicController::class, 'diconoDiNoi'])->name('diconoDiNoi');
    Route::get('/en/contact-us', [PublicController::class, 'contattaci'])->name('contattaci');
    Route::get('/en/partners', [PublicController::class, 'partners'])->name('partners');
    Route::get('/en/FAQ', [PublicController::class, 'faq'])->name('faq');
    Route::get('/en/privacy-terms-and-conditions', [PublicController::class, 'privacy'])->name('privacy');
    Route::get('/en/services/{title}/{id}', [ServiceController::class, 'show'])->name('service.show');
    Route::get('/en/excursions/trapani/{name}/{id}', [ExcursionController::class, 'show'])->name('excursion.show');
    Route::get('/en/services', [PublicController::class, 'servizi'])->name('services.index');
}

// vista dashboard
Route::get('/dashboard', [PublicController::class, 'dashboard'])->name('dashboard')->middleware('auth');

// Vista PDF 
Route::get('/view/pdf', [PublicController::class, 'pdf'])->name('pdf')->middleware('auth');


// Contattaci
Route::post('/richiedi-informazioni', [ContactController::class, 'invia'])->name('inviaForm');

// Lingua
Route::post('/lingua/{lang}', [PublicController::class, 'setLanguage'])->name('setLocale');
// DASHBOARD

// eliminazione immagini
Route::delete('/dashboard/images/{id}', [PublicController::class, 'deleteImage'])->name('images.delete')->middleware('auth');

// Gestione rotte
Route::get('/dashboard/routes', [RouteController::class, 'create'])->name('dashboard.route')->middleware('auth');
Route::post('dashboard/routes', [RouteController::class, 'store'])->name('routes.store')->middleware('auth');
Route::put('dashboard/routes/{route}', [RouteController::class, 'update'])->name('routes.update')->middleware('auth');
Route::delete('dashboard/routes/{route}', [RouteController::class, 'destroy'])->name('routes.destroy')->middleware('auth');

// Gestione destinazioni
Route::get('/dashboard/destinations', [DestinationController::class, 'create'])->name('dashboard.destination')->middleware('auth');
Route::post('dashboard/destinations', [DestinationController::class, 'store'])->name('destinations.store')->middleware('auth');
Route::put('dashboard/destinations/{destination}', [DestinationController::class, 'update'])->name('destinations.update')->middleware('auth');
Route::delete('dashboard/destinations/{destination}', [DestinationController::class, 'destroy'])->name('destinations.destroy')->middleware('auth');

// Gestione escursioni
Route::get('/dashboard/excursions', [ExcursionController::class, 'index'])->name('dashboard.excursion')->middleware('auth');
Route::get('/dashboard/excursion/create', [ExcursionController::class, 'create'])->name('excursion.create')->middleware('auth');
Route::get('/dashboard/excursions/edit/{excursion}', [ExcursionController::class, 'edit'])->name('excursion.edit')->middleware('auth');
Route::post('dashboard/excursions', [ExcursionController::class, 'store'])->name('excursions.store')->middleware('auth');
Route::put('dashboard/excursions/{excursion}', [ExcursionController::class, 'update'])->name('excursions.update')->middleware('auth');
Route::delete('dashboard/excursions/{excursion}', [ExcursionController::class, 'destroy'])->name('excursions.destroy')->middleware('auth');


// Gestione auto
Route::get('/dashboard/cars', [CarController::class, 'create'])->name('dashboard.car')->middleware('auth');
Route::post('/dashboard/cars', [CarController::class, 'store'])->name('cars.store')->middleware('auth');
Route::put('/dashboard/cars/{car}', [CarController::class, 'update'])->name('cars.update')->middleware('auth');
Route::delete('/dashboard/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy')->middleware('auth');


// Gestione recensioni
Route::get('/dashboard/reviews', [ReviewController::class, 'create'])->name('dashboard.review')->middleware('auth');
Route::post('/dashboard/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::put('/dashboard/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update')->middleware('auth');
Route::delete('/dashboard/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('auth');

// Gestione prenotazioni
Route::get('/dashboard/bookings', [BookingController::class, 'index'])->name('dashboard.booking')->middleware('auth');
Route::get('/dashboard/bookings/list', [BookingController::class, 'list'])->name('dashboard.bookingList')->middleware('auth');
Route::delete('/dashboard/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy')->middleware('auth');
Route::get('/dashboard/bookings/pdf/{id}', [BookingController::class, 'showPdf'])->name('booking.pdf')->middleware('auth');

// Gestione messaggi
Route::get('/dashboard/contacts', [ContactController::class, 'index'])->name('dashboard.contact')->middleware('auth');
Route::delete('/dashboard/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy')->middleware('auth');

// Gestione servizi
Route::get('/dashboard/services', [ServiceController::class, 'index'])->name('dashboard.service')->middleware('auth');
Route::get('/dashboard/service/create', [ServiceController::class, 'create'])->name('service.create')->middleware('auth');
Route::get('/dashboard/services/edit/{service}', [ServiceController::class, 'edit'])->name('service.edit')->middleware('auth');
Route::post('/dashboard/services', [ServiceController::class, 'store'])->name('services.store')->middleware('auth');
Route::put('/dashboard/services/{service}', [ServiceController::class, 'update'])->name('services.update')->middleware('auth');
Route::delete('/dashboard/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy')->middleware('auth');


// Gestione Partner
Route::get('/dashboard/partners', [PartnerController::class, 'index'])->name('dashboard.partner')->middleware('auth');
Route::post('/dashboard/partners', [PartnerController::class, 'store'])->name('partners.store')->middleware('auth');
Route::put('/dashboard/partners/{partner}', [PartnerController::class, 'update'])->name('partners.update')->middleware('auth');
Route::delete('/dashboard/partners/{partner}', [PartnerController::class, 'destroy'])->name('partners.destroy')->middleware('auth');


// Gestione Dati Azienda
Route::get('/dashboard/owner', [OwnerDataController::class, 'index'])->name('dashboard.ownerData')->middleware('auth');
Route::post('/dashboard/owner', [OwnerDataController::class, 'store'])->name('owner.store')->middleware('auth');
Route::put('/dashboard/owner/{ownerData}', [OwnerDataController::class, 'update'])->name('owner.update')->middleware('auth');
Route::delete('/dashboard/owner/{id}', [OwnerDataController::class, 'destroy'])->name('owner.destroy')->middleware('auth');


// Gestione contenuti
Route::get('/dashboard/contents', [ContentController::class, 'index'])->name('dashboard.content')->middleware('auth');
Route::get('/dashboard/content/create', [ContentController::class, 'create'])->name('content.create')->middleware('auth');
Route::get('/dashboard/contents/edit/{content}', [ContentController::class, 'edit'])->name('content.edit')->middleware('auth');
Route::post('/dashboard/contents', [ContentController::class, 'store'])->name('contents.store')->middleware('auth');
Route::put('/dashboard/contents/{content}', [ContentController::class, 'update'])->name('contents.update')->middleware('auth');
Route::delete('/dashboard/contents/{content}', [ContentController::class, 'destroy'])->name('contents.destroy')->middleware('auth');


// Gestione pagine
Route::get('/dashboard/pages', [PageController::class, 'index'])->name('dashboard.page')->middleware('auth');
Route::post('/dashboard/pages', [PageController::class, 'store'])->name('pages.store')->middleware('auth');
Route::put('/dashboard/pages/{page}', [PageController::class, 'update'])->name('pages.update')->middleware('auth');
Route::delete('/dashboard/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy')->middleware('auth');

// Gestione clienti
Route::get('/dashboard/customers', [CustomerController::class, 'index'])->name('dashboard.customer')->middleware('auth');
Route::post('/dashboard/customers', [CustomerController::class, 'store'])->name('customers.store')->middleware('auth');
Route::put('/dashboard/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update')->middleware('auth');
Route::delete('/dashboard/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy')->middleware('auth');

// Gestione sconti
Route::get('/dashboard/discounts', [DiscountController::class, 'index'])->name('dashboard.discount')->middleware('auth');
Route::post('/dashboard/discounts', [DiscountController::class, 'store'])->name('discounts.store')->middleware('auth');
Route::put('/dashboard/discounts/{discount}', [DiscountController::class, 'update'])->name('discounts.update')->middleware('auth');
Route::delete('/dashboard/discounts/{discount}', [DiscountController::class, 'destroy'])->name('discounts.destroy')->middleware('auth');
