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
            $services = Service::visible()->with('images')->orderBy('id', 'asc')->get();
            View::share('services', $services);
        }

        if (Excursion::query()->exists()) {
            $excursions = Excursion::visible()->with('images')->orderBy('name_it', 'asc')->get();
            View::share('excursions', $excursions);
        }

        if (OwnerData::query()->exists()) {
            $ownerdata = OwnerData::with('images')->first();
            View::share('ownerdata', $ownerdata);
        }

        if (Page::query()->exists()) {
            $pages = Page::visible()->with('images')->orderBy('order')->get();
            View::share('pages', $pages);
        }

        if (Content::query()->exists()) {
            $contents = Content::visible()->with('images')->get();
            View::share('contents', $contents);
        }
    }
}
