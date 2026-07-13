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

        $calendarStartDate = Carbon::now()->startOfYear();

        $bookings = $this->getBookingsByStatus('confirmed', true);
        $pendingBookings = $this->filterBookingsForCurrentAndFutureYears(
            $this->getBookingsByStatus('pending', true),
            $calendarStartDate
        );
        $rejectedBookings = $this->filterBookingsForCurrentAndFutureYears(
            $this->getBookingsByStatus('rejected', true),
            $calendarStartDate
        );

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
        $bookings = $processedBookings
            ->sortBy(function ($booking) {
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

    private function filterBookingsForCurrentAndFutureYears($bookings, Carbon $calendarStartDate)
    {
        return $bookings->filter(function ($booking) use ($calendarStartDate) {
            $dates = $this->extractBookingCalendarDates($booking);

            foreach ($dates as $date) {
                if (empty($date)) {
                    continue;
                }

                try {
                    if (Carbon::parse($date)->greaterThanOrEqualTo($calendarStartDate)) {
                        return true;
                    }
                } catch (\Throwable $e) {
                    // Ignora date non valide e continua la verifica con le altre date disponibili.
                    continue;
                }
            }

            return false;
        })->values();
    }

    private function extractBookingCalendarDates(Booking $booking): array
    {
        $bookingData = $booking->bookingData ?? [];
        $type = $bookingData['type'] ?? null;

        if ($type === 'transfer') {
            return [
                $bookingData['date_dep'] ?? null,
                $bookingData['date_ret'] ?? null,
            ];
        }

        if ($type === 'noleggio') {
            return [
                $bookingData['date_start'] ?? null,
                $bookingData['date_end'] ?? null,
            ];
        }

        if ($type === 'escursione') {
            return [
                $bookingData['date_dep'] ?? null,
            ];
        }

        return [
            $bookingData['date_dep'] ?? null,
            $bookingData['date_start'] ?? null,
            $bookingData['date_end'] ?? null,
        ];
    }

    public function bookingToDo()
    {
        $bookings = $this->getBookingsByStatus('pending', true)
            ->sortByDesc('created_at')
            ->values();

        return view('dashboard.bookingsToDo', compact('bookings'));
    }

    public function bookingRejected()
    {
        $bookings = $this->getBookingsByStatus('rejected', true);
        return view('dashboard.bookingsRejected', compact('bookings'));
    }

    /**
     * Get bookings filtered by status and user permissions
     * 
     * @param string $status Booking status filter
     * @param bool $excludeHiddenFromCalendar Exclude bookings hidden from calendar
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getBookingsByStatus($status, bool $excludeHiddenFromCalendar = false)
    {
        $allowedTypes = getAllowedBookingTypes();

        $query = Booking::query()->where('status', $status);

        if ($excludeHiddenFromCalendar) {
            $query->where('hidden_in_calendar', false);
        }

        if (!empty($allowedTypes)) {
            $query->whereIn('bookingData->type', $allowedTypes);
        }

        $bookings = $query->get();

        // In calendario, le prenotazioni rifiutate scompaiono dopo 7 giorni dalla data di fine servizio.
        if ($excludeHiddenFromCalendar && $status === 'rejected') {
            $bookings = $bookings->filter(function ($booking) {
                $visibilityDeadline = $this->getRejectedCalendarVisibilityDeadline($booking);

                // Se non c'e una data valida, non nascondiamo automaticamente la prenotazione.
                if (!$visibilityDeadline) {
                    return true;
                }

                return Carbon::now()->lessThanOrEqualTo($visibilityDeadline);
            })->values();
        }

        return $bookings;
    }

    private function getRejectedCalendarVisibilityDeadline(Booking $booking): ?Carbon
    {
        $bookingData = $booking->bookingData ?? [];
        $type = $bookingData['type'] ?? null;

        $referenceDate = null;

        if ($type === 'transfer') {
            // Transfer A/R: usa il ritorno, altrimenti usa l'andata.
            $referenceDate = !empty($bookingData['date_ret']) ? $bookingData['date_ret'] : ($bookingData['date_dep'] ?? null);
        } elseif ($type === 'escursione') {
            // Escursioni: usa la data di partenza.
            $referenceDate = $bookingData['date_dep'] ?? null;
        } elseif ($type === 'noleggio') {
            // Noleggio: usa la data di consegna.
            $referenceDate = $bookingData['date_end'] ?? null;
        } else {
            // Fallback per altri tipi: usa la data principale disponibile.
            $referenceDate = $bookingData['date_dep'] ?? $bookingData['date_start'] ?? null;
        }

        if (empty($referenceDate)) {
            return null;
        }

        try {
            return Carbon::parse($referenceDate)->addWeek()->endOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function hideFromCalendar(Booking $booking)
    {
        if ($booking->hidden_in_calendar) {
            return redirect()->back()->with('success', 'Prenotazione gia nascosta nel calendario.');
        }

        $booking->update(['hidden_in_calendar' => true]);

        return redirect()->back()->with('success', 'Prenotazione nascosta dal calendario con successo.');
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
