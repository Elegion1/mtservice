<?php

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('sitemap:generate', function () {
    $sitemap = Sitemap::create();

    // Aggiungi le tue URL qui
    $sitemap->add(Url::create('/home'));
    $sitemap->add(Url::create('/about'));
    // Aggiungi altre URL dinamiche come i post del blog o prodotti

    $sitemap->writeToFile(public_path('sitemap.xml'));

    $this->comment('Sitemap generata con successo.');
});

Artisan::command('schedule:run', function () {
    // Esegui la generazione della sitemap ogni giorno
    $this->call('sitemap:generate');
})->describe('Genera la sitemap ogni giorno.');
