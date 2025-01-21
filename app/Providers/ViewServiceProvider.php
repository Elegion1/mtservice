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
        // if (Route::query()->exists()) {
        //     $tratte = Route::where('show', 1)->take(5)->get();
        //     View::share('tratte',  $tratte);
        // }

        if (Service::query()->exists()) {
            $services = Service::where('show', 1)->get();
            View::share('services', $services);
        }

        if (Excursion::query()->exists()) {
            $excursions = Excursion::where('show', 1)->get();
            View::share('excursions', $excursions);
        }

        // if (Partner::query()->exists()) {
        //     $partners = Partner::paginate(10);
        //     View::share('partners', $partners);
        // }

        // if (Review::query()->where('status', 'confirmed')->exists()) {

        //     $reviews = Review::get();

        //     View::share('reviews', $reviews);
        // }

        if (OwnerData::query()->exists()) {
            $ownerdata = OwnerData::first();
            View::share('ownerdata', $ownerdata);
        }

        if (Page::query()->exists()) {
            $pages = Page::orderBy('order')->get();
            View::share('pages', $pages);
        }

        if (Content::query()->exists()) {
            $contents = Content::where('show', 1)->get();
            View::share('contents', $contents);
        }
    }
}
