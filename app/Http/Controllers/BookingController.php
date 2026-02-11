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
use App\Services\BookingDataAdapter;

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

        $bookings = $this->getBookingsByStatus('confirmed');
        $pendingBookings = $this->getBookingsByStatus('pending');
        $rejectedBookings = $this->getBookingsByStatus('rejected');

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
        $bookings = $this->getBookingsByStatus('pending');
        return view('dashboard.bookingsToDo', compact('bookings'));
    }

    public function bookingRejected()
    {
        $bookings = $this->getBookingsByStatus('rejected');
        return view('dashboard.bookingsRejected', compact('bookings'));
    }

    /**
     * Get bookings filtered by status and user permissions
     * 
     * @param string $status Booking status filter
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getBookingsByStatus($status)
    {
        $allowedTypes = getAllowedBookingTypes();

        if (empty($allowedTypes)) {
            return Booking::where('status', $status)->get();
        }

        return Booking::whereIn('bookingData->type', $allowedTypes)
            ->where('status', $status)
            ->get();
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

        $data = BookingDataAdapter::adapt($bookingData);
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
        // Memorizza lo stato originale prima dell'aggiornamento
        $originalStatus = $booking->status;

        // Validazione unica per entrambi i campi
        $validated = $request->validate([
            'status' => 'nullable|in:confirmed,pending,rejected',
            'payment_status' => 'nullable|in:pending,paid,deposit_paid',
        ]);

        $updates = [];

        // Aggiornamento stato della prenotazione
        if ($request->filled('status') && $booking->status !== $request->status) {
            $updates['status'] = $request->status;
        }

        // Aggiornamento stato del pagamento
        if ($request->filled('payment_status') && $booking->payment_status !== $request->payment_status) {
            $updates['payment_status'] = $request->payment_status;
        }

        // **Cancella il job solo se lo stato passa da "confirmed" a "rejected"**
        if (
            array_key_exists('status', $updates) &&
            $originalStatus === 'confirmed' &&
            ($updates['status'] === 'rejected' || $updates['status'] === 'pending')
        ) {
            $jobToDelete = getJobs($booking);

            if ($jobToDelete) {
                DB::table('jobs')->where('id', $jobToDelete->id)->delete();
                Log::info("Job eliminato per prenotazione: {$booking->code}");
            } else {
                Log::warning("Nessun job trovato per prenotazione: {$booking->code}");
            }
        }

        // Se ci sono modifiche, salva
        if (!empty($updates)) {
            $booking->update($updates);
        } else {
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

        if ($notification && isset($updates['status'])) {
            sendEmail(
                $booking->email,
                new BookingStatusNotification($booking),
                'Errore nell\'invio dell\'email di notifica',
                $booking->locale
            );
            Log::info("Email inviata per prenotazione: {$booking->code}");
        }

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
