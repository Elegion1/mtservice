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

        // Utilizza config() al posto di env() per evitare problemi con la cache delle configurazioni
        $recipient = $this->argument('email')
            ?? config('logging.alert_email')
            ?? config('mail.from.address');

        if (! $recipient) {
            $this->error('Nessun indirizzo email configurato per l\'invio del report.');

            return 1;
        }

        $errors = [];
        $file = fopen($logPath, 'r');

        if ($file) {
            while (($line = fgets($file)) !== false) {
                if (Str::startsWith($line, "[$today]") && Str::contains($line, '.ERROR:')) {
                    $errors[] = trim($line);
                }
            }
            fclose($file);
        }

        $count = count($errors);

        if ($count > 0) {
            $body = "Trovati {$count} errori nei log in data {$today}:\n\n".implode("\n\n", $errors);

            try {
                Mail::raw($body, function ($message) use ($recipient, $count, $today) {
                    $message->to($recipient)
                        ->subject("[Alert MTService] {$count} Errori nei log ($today)");
                });
                $this->info("Report di {$count} errori inviato a {$recipient}.");
            } catch (\Throwable $e) {
                $this->error("Errore durante l'invio dell'email: ".$e->getMessage());

                return 1;
            }
        } else {
            $this->info("Nessun errore trovato nei log per la data di oggi ({$today}).");
        }

        return 0;
    }
}
