<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckLogErrors extends Command
{
    protected $signature = 'app:check-log-errors {email?}';

    protected $description = 'Controlla i log della giornata e invia un report via email se trova errori.';

    public function handle()
    {
        $logPath = storage_path('logs/laravel.log');

        if (! File::exists($logPath)) {
            $this->info('Nessun file di log trovato.');

            return 0;
        }

        $today = date('Y-m-d');

        // Legge prima l'argomento da CLI, poi la variabile .env, infine una mail di fallback se .env è vuoto
        // Cerca prima l'argomento da CLI, poi la variabile .env, infine l'indirizzo 'from' di Laravel
        $recipient = $this->argument('email')
            ?? env('LOG_ALERT_EMAIL')
            ?? config('mail.from.address');

        // Se non è configurata alcuna email, blocca l'esecuzione e avvisa nei log/console
        if (! $recipient) {
            $this->error('Nessun indirizzo email configurato per l\'invio del report.');

            return 1;
        }

        $errors = [];
        $file = fopen($logPath, 'r');

        while (($line = fgets($file)) !== false) {
            if (Str::startsWith($line, "[$today]") && Str::contains($line, '.ERROR:')) {
                $errors[] = trim($line);
            }
        }
        fclose($file);

        $count = count($errors);
        $body = "Trovati {$count} errori nei log in data {$today}:\n\n".implode("\n\n", $errors);

        if ($count > 0) {
            Mail::raw($body, function ($message) use ($recipient, $count, $today) {
                $message->to($recipient)
                    ->subject("[Alert MTService] {$count} Errori nei log ($today)");
            });
        }

        $this->info("Report di {$count} errori inviato a {$recipient}.");

        return 0;
    }
}
