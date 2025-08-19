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

            $car = Car::find($carId);
            if (!$car) {
                $this->warn("L'auto con ID {$carId} non trovata. Salto...");
                $skippedCars[] = $carId;
                continue;
            }

            // Recupera tutte le prenotazioni per l'auto una sola volta per ottimizzare
            $allBookingsForCar = Booking::where('bookingData->car_id', (int)$carId)->get();

            // Trova i periodi liberi consecutivi
            $freeSlots = $this->findFreeSlots($startDateTime, $endDateTime, $allBookingsForCar);
            $bookingsCreatedForThisCar = 0;

            foreach ($freeSlots as $slot) {
                $this->createDummyBookingForSlot($car, $slot['start'], $slot['end'], $createdBookings);
                $bookingsCreatedForThisCar++;
            }

            if ($bookingsCreatedForThisCar === 0) {
                $this->warn("L'auto con ID {$carId} è completamente prenotata nel periodo richiesto.");
                $skippedCars[] = $carId;
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
            $this->warn("\nAttenzione: Le seguenti auto sono state saltate: " . implode(', ', $skippedCars));
        }

        return 0;
    }

    private function findFreeSlots(Carbon $startDateTime, Carbon $endDateTime, $allBookingsForCar)
    {
        $freeSlots = [];
        $currentDate = $startDateTime->copy()->startOfDay();
        $endDate = $endDateTime->copy()->startOfDay();

        // Ordina le prenotazioni per data di inizio
        $sortedBookings = $allBookingsForCar->sortBy(function ($booking) {
            return Carbon::parse($booking->bookingData['date_start'])->startOfDay();
        });

        foreach ($sortedBookings as $booking) {
            $bookingStart = Carbon::parse($booking->bookingData['date_start'])->startOfDay();
            $bookingEnd = Carbon::parse($booking->bookingData['date_end'])->startOfDay();

            // Se c'è uno spazio libero prima di questa prenotazione
            if ($currentDate->lt($bookingStart)) {
                $freeSlots[] = [
                    'start' => $currentDate->copy(),
                    'end' => $bookingStart->copy()->subDay()
                ];
            }

            // Avanza la data corrente alla fine di questa prenotazione
            if ($bookingEnd->gte($currentDate)) {
                $currentDate = $bookingEnd->copy()->addDay();
            }
        }

        // Se c'è ancora spazio libero dopo l'ultima prenotazione
        if ($currentDate->lte($endDate)) {
            $freeSlots[] = [
                'start' => $currentDate->copy(),
                'end' => $endDate->copy()
            ];
        }

        return $freeSlots;
    }

    private function createDummyBookingForSlot(Car $car, Carbon $slotStart, Carbon $slotEnd, array &$createdBookings)
    {
        $code = 'D' . strtoupper(substr(uniqid(), -5));
        $days = $slotStart->diffInDays($slotEnd) + 1;
        $price = $car->price * $days;

        $bookingData = [
            'type' => 'noleggio',
            'price' => $price,
            'car_ID' => (string)$car->id,
            'car_id' => (int)$car->id,
            'pickup' => 'Sede principale',
            'car_name' => $car->name,
            'date_end' => $slotEnd->format('Y-m-d\\T23:59'),
            'delivery' => 'Sede principale',
            'quantity' => 1,
            'date_start' => $slotStart->format('Y-m-d\\T00:00'),
            'kasko_enabled' => false,
            'original_price' => $price,
            'car_description' => $car->description,
            'is_dummy' => true,
        ];

        $info = [
            'driver' => ['driverName' => 'Prenotazione Fittizia'],
            'flight' => ['flightNumber' => null],
            'is_dummy_booking' => true,
            'created_via_command' => true,
        ];

        $booking = Booking::create([
            'name' => 'Sistema',
            'surname' => 'Prenotazione Fittizia',
            'email' => 'sistema@dummy.local',
            'phone' => '0000000000',
            'dial_code' => '+39',
            'body' => 'Prenotazione creata da sistema per bloccare il veicolo.',
            'info' => $info,
            'bookingData' => $bookingData,
            'status' => 'confirmed',
            'code' => $code,
            'locale' => 'it',
            'service_date' => $slotStart->format('Y-m-d'),
            'payment_status' => 'paid',
        ]);

        $createdBookings[] = [
            'id' => $booking->id,
            'code' => $booking->code,
            'car_id' => $car->id,
            'start_date' => $slotStart->format('d/m/Y'),
            'end_date' => $slotEnd->format('d/m/Y'),
            'status' => $booking->status
        ];
    }
}
