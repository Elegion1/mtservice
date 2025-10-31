<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarPriceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ExcursionController;
use App\Http\Controllers\OwnerDataController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TimePeriodController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', function () {
    $locale = App::getLocale(); // Recupera il locale predefinito

    return redirect()->to($locale); // Reindirizza alla homepage con il locale
});

Route::get('/assets/{file}', function ($file) {
    return response()->file(public_path("assets/$file"), [
        'Cache-Control' => 'max-age=31536000, public',
    ]);
});

// navigazione
Route::prefix('{locale}')
    ->where(['locale' => '[a-zA-Z]{2}'])
    ->middleware('locale')
    ->group(function () {

        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle);
        });

        Route::get('/', [PublicController::class, 'home'])->name('home');
        Route::get('/rent/cars/trapani', [PublicController::class, 'noleggio'])->name('noleggio');
        Route::get('/transfer-taxi/trapani', [PublicController::class, 'transfer'])->name('transfer');
        Route::get('/excursions/trapani', [PublicController::class, 'escursioni'])->name('escursioni');
        Route::get('/prices-and-destinations', [PublicController::class, 'prezziDestinazioni'])->name('prezziDestinazioni');
        Route::get('/reviews', [PublicController::class, 'diconoDiNoi'])->name('diconoDiNoi');
        Route::get('/contact-us', [PublicController::class, 'contattaci'])->name('contattaci');
        Route::get('/partners', [PublicController::class, 'partners'])->name('partners');
        Route::get('/FAQ', [PublicController::class, 'faq'])->name('faq');
        Route::get('/privacy-terms-and-conditions', [PublicController::class, 'privacy'])->name('privacy');
        Route::get('/services', [PublicController::class, 'servizi'])->name('services.index');
        Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('service.show');
        Route::get('/excursions-trapani/{slug}', [ExcursionController::class, 'show'])->name('excursion.show');
        Route::get('/transfer/{departure}/{arrival}', [RouteController::class, 'show'])->name('transfer.show');

        // Contattaci
        Route::post('/info-request', [ContactController::class, 'invia'])->name('inviaForm');

        // visualizza stato prenotazione
        Route::get('/booking/status', [PublicController::class, 'bookingStatus'])->name('booking.status');
        Route::post('/booking/status', [PublicController::class, 'bookingStatusCheck'])->name('booking.status.check');

        // recensioni
        Route::get('/reviews/create/{booking_code}', [ReviewController::class, 'createReview'])->name('reviews.create');
        Route::post('/reviews/save', [ReviewController::class, 'saveReview'])->name('customer.reviews.store');

        Route::get('/not-found', function () {
            return view('errors.404');
        })->name('not-found');
    });

Route::get('dashboard/bookingfromreact/getBookingCode', [BookingController::class, 'getBookingCode']);
Route::get('dashboard/bookingfromreact/getBookingData', [BookingController::class, 'getBookingData']);

// gestione stato prenotazione
Route::middleware(['auth'])->prefix('dashboard')->group(function () {

    Route::put('/bookings/{booking}/update-status', [BookingController::class, 'update'])->name('bookings.update');
    Route::get('/booking/status/to-do', [BookingController::class, 'bookingToDo'])->name('booking.todo');
    Route::get('/booking/status/rejected', [BookingController::class, 'bookingRejected'])->name('booking.rejected');

    // Route::get('/booking/confirm/{booking}', [PublicController::class, 'confirmBooking'])->name('booking.confirm');
    // Route::get('/booking/reject/{booking}', [PublicController::class, 'rejectBooking'])->name('booking.reject');

    // DASHBOARD

    // vista dashboard
    Route::get('/', [PublicController::class, 'dashboard'])->name('dashboard');

    // impostazioni
    Route::get('/settings/', [SettingController::class, 'index'])->name('dashboard.settings'); // Visualizza tutte le impostazioni
    Route::get('/settings/create', [SettingController::class, 'create'])->name('settings.create'); // Mostra il form per creare una nuova impostazione
    Route::post('/settings/', [SettingController::class, 'store'])->name('settings.store'); // Salva una nuova impostazione
    Route::put('/settings/{setting}', [SettingController::class, 'update'])->name('settings.update'); // Aggiorna un'impostazione esistente
    Route::delete('/settings/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy'); // Elimina un'impostazione

    // testing
    Route::get('/testing', [TestController::class, 'test'])->name('dashboard.testing');
    Route::get('/generate/pdf/{bookingId}/{lang}', [TestController::class, 'pdf'])->name('testing.genPDF');
    Route::get('/email/preview/{mailType}/{bookingId?}/{lang?}', [TestController::class, 'emailPreview'])->name('email.view');
    Route::get('/jobs', [TestController::class, 'jobsIndex'])->name('dashboard.jobs');
    Route::get('/logs', [TestController::class, 'showLogs'])->name('dashboard.logs');

    // eliminazione immagini
    Route::delete('/images/{id}', [PublicController::class, 'deleteImage'])->name('images.delete');

    // Gestione rotte
    Route::get('/routes', [RouteController::class, 'create'])->name('dashboard.route');
    Route::post('/routes', [RouteController::class, 'store'])->name('routes.store');
    Route::put('/routes/{route}', [RouteController::class, 'update'])->name('routes.update');
    Route::delete('/routes/{route}', [RouteController::class, 'destroy'])->name('routes.destroy');

    // Gestione destinazioni
    Route::get('/destinations', [DestinationController::class, 'create'])->name('dashboard.destination');
    Route::post('/destinations', [DestinationController::class, 'store'])->name('destinations.store');
    Route::put('/destinations/{destination}', [DestinationController::class, 'update'])->name('destinations.update');
    Route::delete('/destinations/{destination}', [DestinationController::class, 'destroy'])->name('destinations.destroy');

    // Gestione escursioni
    Route::get('/excursions', [ExcursionController::class, 'index'])->name('dashboard.excursion');
    Route::get('/excursion/create', [ExcursionController::class, 'create'])->name('excursion.create');
    Route::get('/excursions/edit/{excursion}', [ExcursionController::class, 'edit'])->name('excursion.edit');
    Route::post('/excursions', [ExcursionController::class, 'store'])->name('excursions.store');
    Route::put('/excursions/{excursion}', [ExcursionController::class, 'update'])->name('excursions.update');
    Route::delete('/excursions/{excursion}', [ExcursionController::class, 'destroy'])->name('excursions.destroy');

    // Gestione auto
    Route::get('/cars', [CarController::class, 'create'])->name('dashboard.car');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');

    // Gestione prezzi auto per periodi
    Route::post('/carprices/', [CarPriceController::class, 'store'])->name('carprices.store');
    Route::put('/carprices/{carPrice}', [CarPriceController::class, 'update'])->name('carprices.update');
    Route::delete('/carprices/{carPrice}', [CarPriceController::class, 'destroy'])->name('carprices.destroy');

    // Gestione periodi
    Route::post('/timeperiods', [TimePeriodController::class, 'store'])->name('timeperiods.store');
    Route::put('/timeperiods/{timePeriod}', [TimePeriodController::class, 'update'])->name('timeperiods.update');
    Route::delete('/timeperiods/{timePeriod}', [TimePeriodController::class, 'destroy'])->name('timeperiods.destroy');

    // Gestione recensioni
    Route::get('/reviews', [ReviewController::class, 'create'])->name('dashboard.review');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Gestione prenotazioni
    Route::get('/bookings', [BookingController::class, 'index'])->name('dashboard.booking');

    // Visit tracking
    Route::prefix('visits')->name('visits.')->group(function () {
        Route::get('/', [\App\Http\Controllers\VisitController::class, 'index'])->name('index');
        Route::get('/dashboard', [\App\Http\Controllers\VisitController::class, 'dashboard'])->name('dashboard');
        Route::get('/stats', [\App\Http\Controllers\VisitController::class, 'stats'])->name('stats');
        Route::delete('/clear', [\App\Http\Controllers\VisitController::class, 'clearAll'])->name('clear');
        Route::delete('/{visit}', [\App\Http\Controllers\VisitController::class, 'destroy'])->name('destroy');
    });
    Route::get('/bookings/list', [BookingController::class, 'list'])->name('dashboard.bookingList');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    // Route::get('/bookings/pdf/{id}', [BookingController::class, 'showPdf'])->name('booking.pdf');

    // Gestione messaggi
    Route::get('/contacts', [ContactController::class, 'index'])->name('dashboard.contact');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    // Route::put('/contacts/mark-all-read', [ContactController::class, 'markAllRead'])->name('contacts.markAllRead');
    // Route::put('/contacts/mark-all-unread', [ContactController::class, 'markAllUnread'])->name('contacts.markAllUnread');

    // Gestione servizi
    Route::get('/services', [ServiceController::class, 'index'])->name('dashboard.service');
    Route::get('/service/create', [ServiceController::class, 'create'])->name('service.create');
    Route::get('/services/edit/{service}', [ServiceController::class, 'edit'])->name('service.edit');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // Gestione Partner
    Route::get('/partners', [PartnerController::class, 'index'])->name('dashboard.partner');
    Route::post('/partners', [PartnerController::class, 'store'])->name('partners.store');
    Route::put('/partners/{partner}', [PartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partners/{partner}', [PartnerController::class, 'destroy'])->name('partners.destroy');

    // Gestione Dati Azienda
    Route::get('/owner', [OwnerDataController::class, 'index'])->name('dashboard.ownerData');
    Route::post('/owner', [OwnerDataController::class, 'store'])->name('owner.store');
    Route::put('/owner/{ownerData}', [OwnerDataController::class, 'update'])->name('owner.update');
    Route::delete('/owner/{id}', [OwnerDataController::class, 'destroy'])->name('owner.destroy');

    // Gestione contenuti
    Route::get('/contents', [ContentController::class, 'index'])->name('dashboard.content');
    Route::get('/content/create', [ContentController::class, 'create'])->name('content.create');
    Route::get('/contents/edit/{content}', [ContentController::class, 'edit'])->name('content.edit');
    Route::post('/contents', [ContentController::class, 'store'])->name('contents.store');
    Route::put('/contents/{content}', [ContentController::class, 'update'])->name('contents.update');
    Route::delete('/contents/{content}', [ContentController::class, 'destroy'])->name('contents.destroy');

    // Gestione pagine
    Route::get('/pages', [PageController::class, 'index'])->name('dashboard.page');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');

    // Gestione clienti
    Route::get('/customers', [CustomerController::class, 'index'])->name('dashboard.customer');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Gestione sconti
    Route::get('/discounts', [DiscountController::class, 'index'])->name('dashboard.discount');
    Route::post('/discounts', [DiscountController::class, 'store'])->name('discounts.store');
    Route::put('/discounts/{discount}', [DiscountController::class, 'update'])->name('discounts.update');
    Route::delete('/discounts/{discount}', [DiscountController::class, 'destroy'])->name('discounts.destroy');

    // Gestione utenti
    Route::get('/users', [UserController::class, 'index'])->name('dashboard.users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
