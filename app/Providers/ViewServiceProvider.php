<?php

namespace App\Providers;

use App\Models\Excursion;
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
        // Controlla se ci sono rotte
        if (Route::count() > 0) {
            $tratte = Route::all();
            View::share('tratte', $tratte);
        }

        // Controlla se ci sono servizi
        if (Service::count() > 0) {
            $services = Service::all();
            View::share('services', $services);
        }

        if (Excursion::count() > 0) {
            $excursions = Excursion::paginate(5);
            View::share('excursions', $excursions);
        }
    }
}
