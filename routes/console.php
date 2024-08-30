<?php

use App\Models\Post;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Foundation\Inspiring;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('sitemap:generate', function () {

    // Percorso della sitemap
    $sitemapPath = public_path('sitemap.xml');

    // Verifica se il file della sitemap esiste e lo elimina
    if (file_exists($sitemapPath)) {
        unlink($sitemapPath);
        $this->comment('Vecchia sitemap cancellata.');
    }

    // Genera la nuova sitemap
    $sitemap = SitemapGenerator::create('https://tranchidatransfer.it');

    // Scrive la nuova sitemap nel file
    $sitemap->writeToFile($sitemapPath);

    $this->comment('Sitemap generata con successo.');
});

Artisan::command('schedule:run', function () {
    // Esegui la generazione della sitemap ogni giorno
    $this->call('sitemap:generate');
})->describe('Genera la sitemap ogni giorno.');
