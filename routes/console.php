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

Artisan::command('logs:clear', function() {
    exec('rm -f ' . storage_path('logs/*.log'));
    exec('rm -f ' . base_path('*.log'));
    $this->comment('Logs have been cleared!');
})->describe('Clear log files');

// Esegui il Job ogni ora
// Definizione del comando
Artisan::command('expire:bookings', function () {
    echo "Checking expired bookings...\n";
    dispatch(new ExpirePendingBookings())->handle();
    echo "Done!\n";
})->describe('Mark pending bookings as rejected if older than 24 hours');

// Pianifica l'esecuzione automatica
Schedule::command('expire:bookings')->hourly();
Schedule::command('logs:clear')->weekly();