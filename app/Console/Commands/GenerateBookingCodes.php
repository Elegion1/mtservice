<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class GenerateBookingCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:generate-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate unique alphanumeric codes for existing bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bookings = Booking::all(); // Recupera tutte le prenotazioni

        foreach ($bookings as $booking) {
            // Genera un codice alfanumerico di 6 caratteri maiuscoli
            $code = strtoupper(Str::random(6));

            // Assicurati che il codice sia unico
            while (Booking::where('code', $code)->exists()) {
                $code = strtoupper(Str::random(6));
            }

            // Salva il codice nella prenotazione
            $booking->code = $code;
            $booking->save();

            $this->info("Generated code for booking ID {$booking->id}: {$code}");
        }

        $this->info('All codes generated successfully!');
    

    }
}
