<?php

namespace App\Providers;

use App\Models\Content;
use App\Models\Excursion;
use App\Models\OwnerData;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Condividi le variabili globalmente in modo sicuro
        View::composer('*', function ($view) {
            try {
                if (! $view->offsetExists('services')) {
                    $services = Cache::remember('services', 86400, function () {
                        return Service::visible()->with('images')->orderBy('id', 'asc')->get();
                    });
                    $view->with('services', $services);
                }

                if (! $view->offsetExists('excursions')) {
                    $excursions = Cache::remember('excursions', 86400, function () {
                        return Excursion::visible()->with('images')->orderBy('name_it', 'asc')->get();
                    });
                    $view->with('excursions', $excursions);
                }

                if (! $view->offsetExists('ownerdata')) {
                    $ownerdata = Cache::remember('ownerdata', 86400, function () {
                        return OwnerData::with('images')->first();
                    });
                    $view->with('ownerdata', $ownerdata);
                }

                if (! $view->offsetExists('pages')) {
                    $pages = Cache::remember('pages', 86400, function () {
                        return Page::visible()->orderBy('order')->get();
                    });
                    $view->with('pages', $pages);
                }

                if (! $view->offsetExists('contents')) {
                    $contents = Cache::remember('contents', 86400, function () {
                        return Content::visible()->with('images')->get();
                    });
                    $view->with('contents', $contents);
                }
            } catch (\Exception $e) {
                // Se il DB è irraggiungibile, evita di far crashare il bootstrap dell'app
                Log::error('Impossibile caricare i dati condivisi nella vista: '.$e->getMessage());
            }
        });
    }
}
