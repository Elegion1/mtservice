<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'home'])->name('home');

// gestione rotte
Route::get('/routes/create', [RouteController::class, 'create'])->name('routes.create');
Route::post('/routes', [RouteController::class, 'store'])->name('routes.store');
Route::put('/routes/{route}', [RouteController::class, 'update'])->name('routes.update');
Route::delete('/routes/{route}', [RouteController::class, 'destroy'])->name('routes.destroy');