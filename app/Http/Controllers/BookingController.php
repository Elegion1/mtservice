<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Jobs\SendReviewRequestJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use App\Mail\BookingStatusNotification;

use function PHPUnit\Framework\isEmpty;

class BookingController extends Controller
{
    public function showPdf($id)
    {
        $booking = Booking::find($id);

        // Dati necessari per generare la vista del PDF
        $data = compact('booking');

        // Opzioni per Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        // Crea una nuova istanza di Dompdf
        $dompdf = new Dompdf($options);

        // Carica la vista del PDF
        $view = view('pdf.booking-summary-pdf', $data)->render();

        // Carica il contenuto HTML nel Dompdf
        $dompdf->loadHtml($view);

        // Imposta il formato del documento
        $dompdf->setPaper('A4', 'portrait');

        // Rendi il PDF
        $dompdf->render();

        // Restituisci il PDF al client
        return $dompdf->stream('booking-summary.pdf');
    }


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
        } else {
            $bookings = Booking::whereIn('bookingData->type', $allowedTypes)
                ->where('status', 'confirmed')
                ->get();

            $pendingBookings = Booking::whereIn('bookingData->type', $allowedTypes)
                ->where('status', 'pending')
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        } else {
            return redirect()->back()->with('error', 'Nessuna modifica effettuata.');
        }

        // Se la prenotazione è confermata, programma l'invio della richiesta di recensione
        if (isset($updates['status']) && $updates['status'] === 'confirmed') {
            $defaultTime = getSetting('review_request_default_time');
            $delayDays = getSetting('review_request_delay_days');

            $serviceDate = Carbon::parse($booking->service_date . ' ' . $defaultTime);
            $delay = $serviceDate->addDays((int) $delayDays);

            Log::info([
                'service_date' => $booking->service_date,
                'default_time' => $defaultTime,
                'delay_days' => $delayDays,
                'calculated_delay' => $delay,
                'booking_locale' => $booking->locale,
            ]);

            // Controlla se esistono già jobs per la prenotazione
            $findJob = getJobs($booking);

            if ($findJob) {
                Log::info("Job trovato per la prenotazione {$booking->code}. ID Job: {$findJob->id}. Annullo creazione del Job");
                return redirect()->back()->with('message', 'Job già presente');
            } else {
                $appLocale = App::getLocale();
                App::setLocale($booking->locale);
                SendReviewRequestJob::dispatch($booking)->delay($delay);
                App::setLocale($appLocale);
                Log::info("Job per la richiesta di recensione creato per la prenotazione: {$booking->code}, con invio previsto per: {$delay->toDateTimeString()}");
            }
        }

        $notification = getSetting('email_notification');

        if ($notification) {
            
            // Invia email di notifica solo se lo stato è cambiato
            if (isset($updates['status'])) {
                sendEmail(
                    $booking->email,
                    new BookingStatusNotification($booking),
                    'Errore nell\'invio dell\'email di notifica',
                    $booking->locale
                );
            }
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
