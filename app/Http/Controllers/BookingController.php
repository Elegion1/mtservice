<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Jobs\SendReviewRequestJob;
use App\Mail\BookingAdmin;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Mail\BookingStatusNotification;
use App\Models\OwnerData;

use function PHPUnit\Framework\isEmpty;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::all();
        return view('dashboard.booking', compact('bookings'));
    }

    public function list()
    {
        App::setLocale('it');

        $allowedTypes = getAllowedBookingTypes();

        // Creazione della query per le prenotazioni confermate
        if (isEmpty($allowedTypes)) {
            $bookings = Booking::where('status', 'confirmed')->get();
            $pendingBookings = Booking::where('status', 'pending')->get();
            $rejectedBookings = Booking::where('status', 'rejected')->get();
        } else {
            $bookings = Booking::whereIn('bookingData->type', $allowedTypes)
                ->where('status', 'confirmed')
                ->get();

            $pendingBookings = Booking::whereIn('bookingData->type', $allowedTypes)
                ->where('status', 'pending')
                ->get();

            $rejectedBookings = Booking::whereIn('bookingData->type', $allowedTypes)
                ->where('status', 'rejected')
                ->get();
        }

        // Collezione per le prenotazioni elaborate
        $processedBookings = collect();

        foreach ($bookings as $booking) {

            $bookingData = $booking->bookingData;
            $type = $bookingData['type'];
            $startDateKey = $type === 'noleggio' ? 'date_start' : 'date_dep';
            $endDateKey = $type === 'noleggio' ? 'date_end' : 'date_ret';

            // Funzione per aggiungere la prenotazione alla lista
            $addBooking = function ($startDate, $endDate) use ($booking, $bookingData, &$processedBookings) {
                $processedBookings->push((object) [
                    'id' => $booking->id,
                    'status' => $booking->status,
                    'payment_status' => $booking->payment_status,
                    'code' => $booking->code,
                    'name' => $booking->name,
                    'surname' => $booking->surname,
                    'email' => $booking->email,
                    'dial_code' => $booking->dial_code,
                    'phone' => $booking->phone,
                    'body' => $booking->body,
                    'info' => $booking->info,
                    'bookingData' => $bookingData,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);
            };

            // Aggiungi la prenotazione con la data di inizio
            if (isset($bookingData[$startDateKey])) {
                $addBooking($bookingData[$startDateKey], null);
            }

            // Aggiungi la prenotazione con la data di fine (se presente)
            if (isset($bookingData[$endDateKey])) {
                $addBooking(null, $bookingData[$endDateKey]);
            }
        }

        // Ordina per 'start_date', se null usa 'end_date'
        $bookings = $processedBookings->sortBy(function ($booking) {
            return \Carbon\Carbon::parse($booking->start_date ?? $booking->end_date);
        });

        // Raggruppa per giorno
        $groupedByDay = $bookings->groupBy(function ($booking) {
            return \Carbon\Carbon::parse($booking->start_date ?? $booking->end_date)->format('Y-m-d');
        });

        // Raggruppa per mese, e all'interno del mese, raggruppa per giorno
        $groupedByMonth = $bookings->groupBy(function ($booking) {
            return \Carbon\Carbon::parse($booking->start_date ?? $booking->end_date)->format('Y-m');
        })->map(function ($monthBookings) {
            return $monthBookings->groupBy(function ($booking) {
                return \Carbon\Carbon::parse($booking->start_date ?? $booking->end_date)->format('Y-m-d');
            });
        });

        return view('dashboard.bookingList', [
            'groupedByDay' => $groupedByDay,
            'groupedByMonth' => $groupedByMonth,
            'pendingBookings' => $pendingBookings,
            'rejectedBookings' => $rejectedBookings,
        ]);
    }

    public function bookingToDo()
    {
        $allowedTypes = getAllowedBookingTypes();

        if (isEmpty($allowedTypes)) {
            $bookings = Booking::where('status', 'pending')->get();
        } else {
            $bookings = Booking::whereIn('bookingData->type', $allowedTypes)
                ->where('status', 'pending')
                ->get();
        }

        return view('dashboard.bookingsToDo', compact('bookings'));
    }

    public function bookingRejected()
    {
        $allowedTypes = getAllowedBookingTypes();

        if (isEmpty($allowedTypes)) {
            $bookings = Booking::where('status', 'rejected')->get();
        } else {
            $bookings = Booking::whereIn('bookingData->type', $allowedTypes)
                ->where('status', 'rejected')
                ->get();
        }

        return view('dashboard.bookingsRejected', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function getBookingCode()
    {
        $code = generateUniqueCode();
        Log::info('Codice generato per la prenotazione: ' . $code . response()->json(['code' => $code]));

        return response()->json(['code' => $code]);
    }

    public function getBookingData(Request $request)
    {
        // Recupera il parametro encryptedData dalla query string
        $encryptedData = $request->query('encryptedData');

        // Decodifica i dati da Base64
        $decodedData = base64_decode($encryptedData);

        // Converti la stringa JSON in un array associativo
        $bookingData = json_decode($decodedData, true);

        Log::info('Richiesta di prenotazione ricevuta.', $bookingData);

        $data = $this->adaptBookingData($bookingData);
        Log::info('Dati adattati per la prenotazione.', $data);
        $booking = Booking::create($data);

        $adminMail = OwnerData::value('email');
        
        //invia mail di notifica all'admin
        sendEmail(
            $adminMail,
            new BookingAdmin($booking, generatePDF($booking, 'it')),
            'Errore nell\'invio dell\'email di notifica',
            'it'
        );

        Log::info('Email di notifica inviata a ' . $adminMail);

        return response()->json(['success' => true, 'data' => $bookingData, 'booking' => $booking], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    private function adaptBookingData($data)
    {
        try {
            // Controlla se i dati obbligatori sono presenti
            if (!isset($data['name']) || !isset($data['surname']) || !isset($data['price'])) {
                Log::error('Dati obbligatori mancanti: name, surname o price');
                throw new \Exception('Dati obbligatori mancanti');
            }

            if ($data['type'] == 'transfer') {
                // Adatta i dati
                $adaptedData = [
                    'name' => $data['name'],
                    'surname' => $data['surname'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'dial_code' => null,
                    'body' => $data['message'] ?? '',
                    'info' => [
                        'flight' => [
                            'flightNumber' => $data['flightNumber'] ?? null,
                            'departureAirport' => $data['departureAirport'] ?? null,
                            'departureTime' => $data['departureTime'] ?? null,
                            'arrivalAirport' => $data['arrivalAirport'] ?? null,
                            'arrivalTime' => $data['arrivalTime'] ?? null,
                        ],
                    ],
                    'bookingData' => [
                        'type' => $data['type'],
                        'price' => $data['price'],
                        'original_price' => $data['price'],
                        'date_dep' => $data['dateStart'] . 'T' . $data['timeStart'],
                        'date_ret' => isset($data['dateReturn']) && isset($data['timeReturn']) ? $data['dateReturn'] . 'T' . $data['timeReturn'] : null,
                        'sola_andata' => isset($data['dateReturn']) && isset($data['timeReturn']) ? false : true,
                        'duration' => $data['duration'] ?? 1,
                        'passengers' => $data['passengers'],
                        'departure_id' => null,
                        'departure_name' => explode(' - ', $data['route'])[0],
                        'arrival_name' => explode(' - ', $data['route'])[1],
                        'sito_favignana' => true,
                        'transferType' => $data['transferType'] ?? null,
                    ],
                    'code' => $data['code'],
                    'service_date' => $data['dateStart'],
                    'status' => 'confirmed',
                    'payment_status' => $data['paymentStatus'] == 'COMPLETED' ? 'paid' : 'pending',
                    'locale' => $data['locale'] ?? 'it',
                ];
            }

            if ($data['type'] == 'escursione') {
                $adaptedData = [
                    'name' => $data['name'],
                    'surname' => $data['surname'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'dial_code' => null,
                    'body' => $data['message'] ?? '',
                    'info' => [
                        'flight' => [
                            'flightNumber' => $data['flightNumber'] ?? null,
                            'departureAirport' => $data['departureAirport'] ?? null,
                            'departureTime' => $data['departureTime'] ?? null,
                            'arrivalAirport' => $data['arrivalAirport'] ?? null,
                            'arrivalTime' => $data['arrivalTime'] ?? null,
                        ],
                    ],
                    'bookingData' => [
                        'type' => $data['type'],
                        'price' => $data['price'],
                        'original_price' => $data['price'],
                        'date_dep' => $data['dateStart'] . 'T' . $data['timeStart'],
                        'passengers' => $data['passengers'],
                        'sito_favignana' => true,
                        'departure_name' => $data['excursion'],
                        'departure_location' => $data['departureLocation'] ?? null,               
                    ],
                    'code' => $data['code'],
                    'service_date' => $data['dateStart'],
                    'status' => 'confirmed',
                    'payment_status' => $data['paymentStatus'] == 'COMPLETED' ? 'paid' : 'pending',
                    'locale' => $data['locale'] ?? 'it',
                ];
            }

            return $adaptedData;
        } catch (\Exception $e) {
            // Logga eventuali errori
            Log::error('Errore durante l\'adattamento dei dati: ' . $e->getMessage());
            return null;  // O gestisci come preferisci
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Booking $booking)
    {
        // Log per tracciare l'inizio della richiesta
        Log::info("Inizio aggiornamento della prenotazione: {$booking->code}");

        // Memorizza lo stato originale prima dell'aggiornamento
        $originalStatus = $booking->status;
        Log::info("Stato originale della prenotazione: {$originalStatus}");

        // Validazione unica per entrambi i campi
        $validated = $request->validate([
            'status' => 'nullable|in:confirmed,pending,rejected',
            'payment_status' => 'nullable|in:pending,paid,deposit_paid',
        ]);
        Log::info("Validazione riuscita. Dati ricevuti: " . json_encode($validated));

        $updates = [];

        // Aggiornamento stato della prenotazione
        if ($request->filled('status') && $booking->status !== $request->status) {
            $updates['status'] = $request->status;
            Log::info("Stato della prenotazione aggiornato da {$originalStatus} a {$updates['status']}");
        } else {
            Log::info("Nessun cambiamento nello stato della prenotazione.");
        }

        // Aggiornamento stato del pagamento
        if ($request->filled('payment_status') && $booking->payment_status !== $request->payment_status) {
            $updates['payment_status'] = $request->payment_status;
            Log::info("Stato del pagamento aggiornato da {$booking->payment_status} a {$updates['payment_status']}");
        } else {
            Log::info("Nessun cambiamento nello stato del pagamento.");
        }

        // **Cancella il job solo se lo stato passa da "confirmed" a "rejected"**
        if (
            array_key_exists('status', $updates) &&
            $originalStatus === 'confirmed' &&
            ($updates['status'] === 'rejected' || $updates['status'] === 'pending')
        ) {
            Log::info("Tentativo di cancellazione del job per la prenotazione: {$booking->code}");

            $jobToDelete = getJobs($booking);

            if ($jobToDelete) {
                Log::info("Job trovato per la prenotazione {$booking->code}. ID Job: {$jobToDelete->id}. Eliminazione in corso...");
                DB::table('jobs')->where('id', $jobToDelete->id)->delete();
                Log::info("Job {$jobToDelete->id} eliminato con successo.");
            } else {
                Log::warning("Nessun job trovato per la prenotazione {$booking->code}. Nessuna eliminazione eseguita.");
            }
        }

        // Se ci sono modifiche, salva
        if (!empty($updates)) {
            $booking->update($updates);
            Log::info("Prenotazione aggiornata con i seguenti dati: " . json_encode($updates));
        } else {
            Log::info("Nessuna modifica effettuata.");
            return redirect()->back()->with('error', 'Nessuna modifica effettuata.');
        }

        // Se la prenotazione è confermata, programma l'invio della richiesta di recensione
        // if (isset($updates['status']) && $updates['status'] === 'confirmed') {
        //     $defaultTime = getSetting('review_request_default_time');
        //     $delayDays = getSetting('review_request_delay_days');

        //     $serviceDate = Carbon::parse($booking->service_date . ' ' . $defaultTime);
        //     $delay = $serviceDate->addDays((int) $delayDays);

        //     Log::info("Configurazione invio recensione: data servizio - {$booking->service_date}, tempo predefinito - {$defaultTime}, giorni di ritardo - {$delayDays}, data ritardo calcolata - {$delay->toDateTimeString()}");

        //     // Controlla se esistono già jobs per la prenotazione
        //     $findJob = getJobs($booking);

        //     if ($findJob) {
        //         Log::info("Job già esistente per la prenotazione {$booking->code}. ID Job: {$findJob->id}. Annullo creazione del Job.");
        //         return redirect()->back()->with('message', 'Job già presente');
        //     } else {
        //         $appLocale = App::getLocale();
        //         App::setLocale($booking->locale);
        //         SendReviewRequestJob::dispatch($booking)->delay($delay);
        //         App::setLocale($appLocale);
        //         Log::info("Job per la richiesta di recensione creato per la prenotazione: {$booking->code}, con invio previsto per: {$delay->toDateTimeString()}");
        //         return redirect()->back()->with('message', 'Job creato con successo');
        //     }
        // }

        $notification = getSetting('email_notification');
        Log::info("Impostazione notifiche via email: " . ($notification ? 'Abilitata' : 'Disabilitata'));

        if ($notification) {
            // Invia email di notifica solo se lo stato è cambiato
            if (isset($updates['status'])) {
                Log::info("Invio email di notifica per lo stato della prenotazione.");
                sendEmail(
                    $booking->email,
                    new BookingStatusNotification($booking),
                    'Errore nell\'invio dell\'email di notifica',
                    $booking->locale
                );
            }
        }

        Log::info("Prenotazione aggiornata con successo. Codice: {$booking->code}");

        return redirect()->back()->with('success', 'Prenotazione aggiornata con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->back()->with('success', 'Prenotazione eliminata con successo!');
    }
}
