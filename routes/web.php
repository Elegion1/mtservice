<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ExcursionController;
use App\Http\Controllers\DestinationController;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/dashboard', [PublicController::class, 'dashboard'])->name('dashboard');

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

