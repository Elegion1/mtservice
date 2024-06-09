<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ExcursionController;
use App\Http\Controllers\DestinationController;

//navigazione

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/dashboard', [PublicController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/noleggio/auto', [PublicController::class, 'noleggio'])->name('noleggio');
Route::get('/transfer', [PublicController::class, 'transfer'])->name('transfer');
Route::get('/escursioni', [PublicController::class, 'escursioni'])->name('escursioni');
Route::get('/prezzi-e-destinazioni', [PublicController::class, 'prezziDestinazioni'])->name('prezziDestinazioni');
Route::get('/dicono-di-noi', [PublicController::class, 'diconoDiNoi'])->name('diconoDiNoi');

// DASHBOARD
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
Route::get('/dashboard/excursions', [ExcursionController::class, 'create'])->name('dashboard.excursion')->middleware('auth');
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
Route::delete('/dashboard/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy')->middleware('auth');
Route::get('/dashboard/bookings/pdf/{id}', [BookingController::class, 'showPdf'])->name('booking.pdf')->middleware('auth');