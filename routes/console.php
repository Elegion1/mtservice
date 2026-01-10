<?php

use App\Models\Post;
use Spatie\Crawler\Crawler;
use Illuminate\Support\Facades\DB;
use App\Jobs\ExpirePendingBookings;
use Illuminate\Foundation\Inspiring;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Artisan::command('logs:clear', function () {
    // Svuota il contenuto dei file di log nella cartella storage/logs
    $logFiles = glob(storage_path('logs/*.log'));

    foreach ($logFiles as $logFile) {
        file_put_contents($logFile, '');  // Svuota il contenuto del file
    }

    // Svuota eventuali file di log nella root del progetto
    $rootLogFiles = glob(base_path('*.log'));
    foreach ($rootLogFiles as $logFile) {
        file_put_contents($logFile, '');  // Svuota il contenuto del file
    }

    $this->comment('Logs have been cleared!');
})->describe('Clear log files content');

// Esegui il Job ogni ora
// Definizione del comando
Artisan::command('expire:bookings', function () {
    echo "Checking expired bookings...\n";
    dispatch(new ExpirePendingBookings());
    echo "Done!\n";
})->describe('Mark pending bookings as rejected if older than 24 hours');

// Pianifica l'esecuzione automatica
Schedule::command('expire:bookings')->hourly()->withoutOverlapping();
Schedule::command('logs:clear')->weekly();
