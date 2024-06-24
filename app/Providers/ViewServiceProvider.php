<?php

namespace App\Providers;

use App\Models\Route;
use App\Models\Review;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Excursion;
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
            $excursions = Excursion::paginate(4);
            View::share('excursions', $excursions);
        }

        if (Partner::count() > 0) {
            $partners = Partner::paginate(10);
            View::share('partners', $partners);
        }

        if (Review::count() > 0) {
            $reviews = Review::all();
            View::share('reviews', $reviews);
        }
    }
}
