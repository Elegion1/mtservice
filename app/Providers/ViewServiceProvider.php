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
        if (Route::query()->exists()) {
            $tratte = Route::take(5)->get();
            $dest = Route::all();
            View::share('tratte',  $tratte);
            View::share('dest',  $dest);
        }


        if (Service::query()->exists()) {
            $services = Service::all();
            View::share('services', $services);
        }

        if (Excursion::query()->exists()) {
            $excursionsP = Excursion::paginate(4);
            $excursions = Excursion::all();
            View::share('excursionsP', $excursionsP);
            View::share('excursions', $excursions);
        }

        if (Partner::query()->exists()) {
            $partners = Partner::paginate(10);
            View::share('partners', $partners);
        }

        if (Review::query()->exists()) {
            $reviewsP = Review::paginate(6);
            $reviews = Review::all();
            View::share('reviewsP', $reviewsP);
            View::share('reviews', $reviews);
        }

        if (OwnerData::query()->exists()) {
            $ownerdata = OwnerData::first();
            View::share('ownerdata', $ownerdata);
        }

        if (Page::query()->exists()) {
            $pages = Page::orderBy('order')->get();
            View::share('pages', $pages);
        }

        if (Content::query()->exists()) {
            $contents = Content::all();
            View::share('contents', $contents);
        }
    }
}
