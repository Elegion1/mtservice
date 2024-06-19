<?php

namespace App\Providers;

use App\Models\Route;
use App\Models\Service;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $tratte = Route::all();
        $services = Service::all();

        // Condividi la variabile $tratte con tutte le viste
        View::share('tratte', $tratte);
        View::share('services', $services);
    }
}
