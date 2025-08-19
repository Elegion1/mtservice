<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CreateDummyBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:create-dummy 
                            {--car-id=* : ID dell\'auto da bloccare (puoi usarlo più volte: --car-id=1 --car-id=2)}
                            {--start-date= : Data di inizio (YYYY-MM-DD)}
                            {--end-date= : Data di fine (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea una prenotazione fittizia per bloccare un\'auto in caso di prenotazioni esterne';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $carIds = $this->option('car-id');
        $startDate = $this->option('start-date');
        $endDate = $this->option('end-date');

        // Validazione input
        if (empty($carIds)) {
            // Mostra la lista delle auto disponibili solo in modalità interattiva
            $this->line('Lista delle auto disponibili:');
            $cars = Car::all(['id', 'name']);
            $this->table(
                ['ID', 'Nome Auto'],
                $cars->map(fn($car) => [$car->id, $car->name])->toArray()
            );

            $carIdInput = $this->ask('Inserisci gli ID delle auto da bloccare, separati da virgola (es. 1,2,3)');
            $carIds = array_filter(array_map('trim', explode(',', $carIdInput)));
        }

        if (empty($carIds)) {
            $this->error('Nessun ID auto specificato.');
            return 1;
        }

        if (!$startDate) {
            $startDate = $this->ask('Inserisci la data di inizio (YYYY-MM-DD)', Carbon::today()->format('Y-m-d'));
        }

        if (!$endDate) {
            $endDate = $this->ask('Inserisci la data di fine (YYYY-MM-DD)', Carbon::today()->addDay()->format('Y-m-d'));
        }

        // Validazione formato date
        try {
            $startDateTime = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $endDateTime = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
        } catch (\Exception $e) {
            $this->error('Formato data non valido. Usa il formato YYYY-MM-DD.');
            return 1;
        }

        if ($endDateTime <= $startDateTime) {
            $this->error('La data di fine deve essere successiva alla data di inizio.');
            return 1;
        }

        $createdBookings = [];
        $skippedCars = [];

        foreach ($carIds as $carId) {
            $this->line("Processing Car ID: {$carId}...");

            // 1. Trova prenotazioni esistenti per questa auto nel periodo richiesto
            $existingBookings = Booking::where('bookingData->car_id', (int)$carId)
                ->where(function ($query) use ($startDateTime, $endDateTime) {
                    $query->where('bookingData->date_start', '<=', $endDateTime->format('Y-m-d'))
                          ->where('bookingData->date_end', '>=', $startDateTime->format('Y-m-d'));
                })
                ->orderBy('bookingData->date_start')
                ->get();

            // 2. Calcola gli slot liberi
            $freeSlots = [];
            $currentDate = $startDateTime->copy();

            foreach ($existingBookings as $booking) {
                $bookingStart = Carbon::createFromFormat('Y-m-d', $booking->bookingData['date_start'])->startOfDay();
                $bookingEnd = Carbon::createFromFormat('Y-m-d', $booking->bookingData['date_end'])->endOfDay();

                if ($currentDate->lt($bookingStart)) {
                    $freeSlots[] = ['start' => $currentDate->copy(), 'end' => $bookingStart->copy()->subDay()->endOfDay()];
                }
                $currentDate = $bookingEnd->copy()->addDay()->startOfDay();
            }

            if ($currentDate->lessThanOrEqualTo($endDateTime)) {
                $freeSlots[] = ['start' => $currentDate->copy(), 'end' => $endDateTime->copy()];
            }
            
            if (empty($freeSlots)) {
                $this->warn("L'auto con ID {$carId} è completamente prenotata nel periodo specificato. Nessun blocco creato.");
                $skippedCars[] = $carId;
                continue;
            }

            // 3. Crea prenotazioni fittizie per ogni slot libero
            foreach ($freeSlots as $slot) {
                $slotStart = $slot['start'];
                $slotEnd = $slot['end'];

                if ($slotEnd->lt($slotStart)) continue; // Salta slot non validi

                $code = 'D' . strtoupper(substr(uniqid(), -5));

                $bookingData = [
                    'type' => 'noleggio',
                    'car_id' => (int)$carId,
                    'date_start' => $slotStart->format('Y-m-d'),
                    'time_start' => '00:00',
                    'date_end' => $slotEnd->format('Y-m-d'),
                    'time_end' => '23:59',
                    'pickup_location' => 'Sede principale',
                    'return_location' => 'Sede principale',
                    'is_dummy' => true,
                    'dummy_reason' => 'Prenotazione esterna',
                    'created_by_command' => true
                ];

                $booking = Booking::create([
                    'name' => 'Sistema',
                    'surname' => 'Prenotazione Esterna',
                    'email' => 'sistema@dummy.local',
                    'dial_code' => '+39',
                    'phone' => '0000000000',
                    'body' => 'Prenotazione esterna',
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'code' => $code,
                    'locale' => 'it',
                    'service_date' => $slotStart->format('Y-m-d'),
                    'bookingData' => $bookingData,
                    'info' => [
                        'is_dummy_booking' => true,
                        'created_via_command' => true,
                        'reason' => 'Prenotazione esterna',
                        'created_at_timestamp' => now()->timestamp
                    ]
                ]);

                $createdBookings[] = [
                    'id' => $booking->id,
                    'code' => $booking->code,
                    'car_id' => $carId,
                    'start_date' => $slotStart->format('d/m/Y'),
                    'end_date' => $slotEnd->format('d/m/Y'),
                    'status' => $booking->status
                ];
            }
        }

        if (!empty($createdBookings)) {
            $this->info("\n✅ Creazione prenotazioni fittizie completata!");
            $this->table(
                ['ID Prenotazione', 'Codice', 'Auto ID', 'Data Inizio', 'Data Fine', 'Status'],
                collect($createdBookings)->map(fn($b) => array_values($b))->toArray()
            );
        } else {
            $this->info("\nNessuna prenotazione fittizia creata.");
        }

        if (!empty($skippedCars)) {
            $this->warn("\nAttenzione: Le seguenti auto non sono state bloccate perché completamente prenotate nel periodo richiesto: " . implode(', ', $skippedCars));
        }

        return 0;
    }
}
