<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Route;
use App\Models\Review;
use App\Models\Content;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Excursion;
use App\Models\OwnerData;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
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
        if (Service::query()->exists()) {
            $services = Cache::remember('services', 86400, function () {
                return Service::visible()->with('images')->orderBy('id', 'asc')->get();
            });
            View::share('services', $services);
        }

        if (Excursion::query()->exists()) {
            $excursions = Cache::remember('excursions', 86400, function () {
                return Excursion::visible()->with('images')->orderBy('name_it', 'asc')->get();
            });
            View::share('excursions', $excursions);
        }

        if (OwnerData::query()->exists()) {
            $ownerdata = Cache::remember('ownerdata', 86400, function () {
                return OwnerData::with('images')->first();
            });
            View::share('ownerdata', $ownerdata);
        }

        if (Page::query()->exists()) {
            $pages = Cache::remember('pages', 86400, function () {
                return Page::visible()->orderBy('order')->get();
            });
            View::share('pages', $pages);
        }

        if (Content::query()->exists()) {
            $contents = Cache::remember('contents', 86400, function () {
                return Content::visible()->with('images')->get();
            });
            View::share('contents', $contents);
        }
    }
}
