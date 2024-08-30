<?php

use App\Models\Post;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Foundation\Inspiring;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('sitemap:generate', function () {
    
    $sitemap = SitemapGenerator::create('https://tranchidatransfer.it');

    $sitemap->writeToFile(public_path('sitemap.xml'));

    $this->comment('Sitemap generata con successo.');
});

Artisan::command('schedule:run', function () {
    // Esegui la generazione della sitemap ogni giorno
    $this->call('sitemap:generate');
})->describe('Genera la sitemap ogni giorno.');
