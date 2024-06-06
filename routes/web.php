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
Route::get('/dashboard/routes', [RouteController::class, 'create'])->name('dashboard.route');
Route::post('dashboard/routes', [RouteController::class, 'store'])->name('routes.store');
Route::put('dashboard/routes/{route}', [RouteController::class, 'update'])->name('routes.update');
Route::delete('dashboard/routes/{route}', [RouteController::class, 'destroy'])->name('routes.destroy');

// Gestione destinazioni
Route::get('/dashboard/destinations', [DestinationController::class, 'create'])->name('dashboard.destination');
Route::post('dashboard/destinations', [DestinationController::class, 'store'])->name('destinations.store');
Route::put('dashboard/destinations/{destination}', [DestinationController::class, 'update'])->name('destinations.update');
Route::delete('dashboard/destinations/{destination}', [DestinationController::class, 'destroy'])->name('destinations.destroy');

// Gestione escursioni
Route::get('/dashboard/excursions', [ExcursionController::class, 'create'])->name('dashboard.excursion');
Route::post('dashboard/excursions', [ExcursionController::class, 'store'])->name('excursions.store');
Route::put('dashboard/excursions/{excursion}', [ExcursionController::class, 'update'])->name('excursions.update');
Route::delete('dashboard/excursions/{excursion}', [ExcursionController::class, 'destroy'])->name('excursions.destroy');

// Gestione auto
Route::get('/dashboard/cars', [CarController::class, 'create'])->name('dashboard.car');
Route::post('/dashboard/cars', [CarController::class, 'store'])->name('cars.store');
Route::put('/dashboard/cars/{car}', [CarController::class, 'update'])->name('cars.update');
Route::delete('/dashboard/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');

// Gestione recensioni
Route::get('/dashboard/reviews', [ReviewController::class, 'create'])->name('dashboard.review');
Route::post('/dashboard/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::put('/dashboard/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/dashboard/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

// Gestione prenotazioni
Route::get('/dashboard/bookings', [BookingController::class, 'index'])->name('dashboard.booking');
Route::delete('/dashboard/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');