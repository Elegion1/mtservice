<?php

namespace App\Providers;

use Carbon\Carbon;

use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(ViewServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::macro('defaultFormat', function () {
            $format = __('ui.date_format');  // Recupera il formato dal file di traduzione
            return $this->format($format);
        });
    }
}
