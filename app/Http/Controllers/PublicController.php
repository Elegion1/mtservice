<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Car;
use Dompdf\Options;
use App\Models\Page;
use App\Models\Image;
use App\Models\Route;
use App\Models\Review;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Content;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Excursion;
use Illuminate\Http\Request;
use Sabberworm\CSS\Settings;
use App\Jobs\SendReviewRequestJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use App\Mail\BookingStatusNotification;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class PublicController extends Controller
{
    public function getPageData($link, $extraData = [])
    {
        $pagine = Page::where('link', $link)
            ->with(['contents' => function ($query) {
                $query->where('order', '!=', 0);
            }])->get();

        return array_merge(['pagine' => $pagine], $extraData);
    }

    public function home()
    {
        $tratte = Route::where('show', 1)->take(5)->get();
        $data = $this->getPageData('home', ['tratte' => $tratte]); // Ottieni i dati della pagina
        return view('welcome', $data);
    }

    public function noleggio()
    {
        $cars = Car::where('show', 1)->get();
        $data = $this->getPageData('noleggio', ['cars' => $cars]);
        return view('pages.noleggio-auto', $data);
    }

    public function transfer()
    {
        $data = $this->getPageData('transfer');
        return view('pages.transfer', $data);
    }

    public function escursioni()
    {
        $excursionsP = Excursion::where('show', 1)->paginate(4);
        $data = $this->getPageData('escursioni', ['excursionsP' => $excursionsP]);
        return view('pages.escursioni', $data);
    }

    public function prezziDestinazioni()
    {
        $tratte = Route::where('show', 1)->get();
        $data = $this->getPageData('prezziDestinazioni', ['tratte' => $tratte]);
        return view('pages.prezzi-destinazioni', $data);
    }

    public function diconoDiNoi()
    {
        $reviewsP = Review::where('status', 'confirmed')->paginate(6);
        $data = $this->getPageData('diconoDiNoi', ['reviewsP' => $reviewsP]);
        return view('pages.dicono-di-noi', $data);
    }

    public function contattaci()
    {
        $data = $this->getPageData('contattaci');
        return view('pages.contattaci', $data);
    }

    public function partners()
    {
        $partners = Partner::paginate(9);
        $data = $this->getPageData('partners', ['partners' => $partners]);
        return view('pages.partners', $data);
    }

    public function faq()
    {
        $data = $this->getPageData('faq');
        return view('pages.faq', $data);
    }

    public function privacy()
    {
        $lang = session('locale', config('app.locale'));
        // Restituisce la vista corretta in base alla lingua
        if ($lang === 'en') {

            return view('pages.privacy-terms_en');
        }
        // Imposta la vista italiana come predefinita
        return view('pages.privacy-terms_it');
    }

    public function servizi()
    {
        $services = Service::all();
        return view('pages.services', compact('services'));
    }

    public function dashboard()
    {
        $allowedTypes = getAllowedBookingTypes();

        if (isEmpty($allowedTypes)) {
            $bookings = Booking::where('status', 'pending')->get();
        } else {
            $bookings = Booking::whereIn('bookingData->type', $allowedTypes)
                ->where('status', 'pending')
                ->get();
        }

        $contacts = Contact::all();
        $reviews = Review::all();
        return view('dashboard.index', compact('bookings', 'contacts', 'reviews'));
    }

    public function bookingStatus()
    {        // Recupera i dati della prenotazione dalla sessione
        $booking = session('booking');

        if (!$booking) {
            session(['verified' => false]);
        }

        return view('pages.booking-status', ['booking' => $booking]);
    }

    public function showLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');

            // Se il file non esiste o non è leggibile, mostra una pagina vuota
            if (!File::exists($logFile) || !is_readable($logFile)) {
                Log::warning("Log file non trovato o non leggibile: {$logFile}");
                return view('dashboard.logs', ['logEntries' => []]);
            }

            $logs = File::get($logFile);

            // Estrai le entries dei log usando i timestamp come delimitatori
            preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?(?=(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]|$))/s', $logs, $matches);
            $logEntries = $matches[0] ?? [];

            // Recupera la lunghezza massima dai settings, con valore predefinito
            $maxLength = getSetting('log_max_character_length') ?? 1000;

            // Filtra le entries
            $filteredLogEntries = array_filter($logEntries, function ($entry) use ($maxLength) {
                $excludePatterns = ['syntax error', 'SQLSTATE'];
                $isTooLong = strlen($entry) > $maxLength;

                foreach ($excludePatterns as $pattern) {
                    if (stripos($entry, $pattern) !== false) {
                        return false; // Escludi se contiene uno dei pattern
                    }
                }

                return !$isTooLong;
            });

            // Ordina per timestamp decrescente
            usort($filteredLogEntries, function ($a, $b) {
                preg_match('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]/', $a, $timestampA);
                preg_match('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]/', $b, $timestampB);

                $timeA = isset($timestampA[0]) ? strtotime(trim($timestampA[0], '[]')) : 0;
                $timeB = isset($timestampB[0]) ? strtotime(trim($timestampB[0], '[]')) : 0;

                return $timeB <=> $timeA;
            });

            return view('dashboard.logs', ['logEntries' => $filteredLogEntries]);
        } catch (\Exception $e) {
            Log::error("Errore durante la lettura dei log: " . $e->getMessage());
            return view('dashboard.logs', ['logEntries' => []]);
        }
    }
    
    // Check the email and show the booking status if verified
    public function bookingStatusCheck(Request $request)
    {
        // Valida i dati in ingresso
        $validated = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6'
        ]);

        $code = strtoupper($validated['code']);
        // Cerca la prenotazione corrispondente all'ID e all'email
        $booking = Booking::where('code', $code)
            ->where('email', $validated['email'])
            ->first();

        session(['verified' => false]);

        if ($booking) {
            // Email verificata correttamente
            session(['verified' => true]); // Imposta la variabile di sessione
            return view('pages.booking-status', ['booking' => $booking, 'verified' => true]);
        } else {
            // Email o ID non corretti
            session(['verified' => false]); // Imposta la variabile di sessione per email non valida
            return redirect()->route('booking.status', ['booking' => $validated['code']])->withErrors([
                'email' => __('ui.email_not_verified'),
                'code' => __('ui.code_not_verified'),
            ]);
        }
    }

    public function confirmBooking(Booking $booking)
    {
        if ($booking->status === 'confirmed') {
            return redirect()->back()->withErrors(['message' => 'Prenotazione già confermata.']);
        }

        $booking->status = 'confirmed'; // or whatever status you want to set
        $booking->save();

        $delayDays = getSetting('review_request_delay_days');

        $defaultTime = getSetting('review_request_default_time');
        // Unisci la data del servizio con l'orario di default
        $serviceDate = Carbon::parse($booking->service_date . ' ' . $defaultTime);

        // Aggiungi i giorni di ritardo
        $delay = $serviceDate->addDays((int) $delayDays);  // (int) per essere sicuro che sia un numero intero

        Log::info([
            'service_date' => $booking->service_date,
            'default_time' => $defaultTime,
            'delay_days' => $delayDays,
            'calculated_delay' => $delay,
        ]);

        // Invia la mail nella lingua del cliente
        sendEmail(
            $booking->email, // Destinatario
            new BookingStatusNotification($booking), // Mailable
            'Errore nell\'invio dell\'email di stato prenotazione', // Messaggio di errore
            $booking->locale // Locale della prenotazione
        );

        // Controlla se esistono già jobs per la prenotazione
        $findJob = getJobs($booking);

        if ($findJob) {
            Log::info("Job trovato per la prenotazione {$booking->code}. ID Job: {$findJob->id}. Annullo creazione del Job");
            return redirect()->back()->withErrors(['message' => 'Job già presente']);
        } else {
            // Dispatcha il job per inviare la richiesta di recensione
            $appLocale = App::getLocale();
            App::setLocale($booking->locale);
            SendReviewRequestJob::dispatch($booking)->delay($delay);
            App::setLocale($appLocale);
            Log::info("Job per la richiesta di recensione creato per la prenotazione: {$booking->code}, con invio previsto per: {$delay->toDateTimeString()}");
        }


        return redirect()->back()->with('message', 'Prenotazione confermata con successo.');
    }

    public function rejectBooking(Booking $booking)
    {
        if ($booking->status === 'rejected') {
            return redirect()->back()->withErrors(['message' => 'Prenotazione già rifiutata.']);
        }

        if ($booking->status === 'confirmed') {
            // **Cancella il job programmato per la richiesta di recensione**
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

        $booking->status = 'rejected'; // or whatever status you want to set
        $booking->save();

        // Invia la mail nella lingua del cliente
        sendEmail(
            $booking->email, // Destinatario
            new BookingStatusNotification($booking), // Mailable
            'Errore nell\'invio dell\'email di stato prenotazione', // Messaggio di errore
            $booking->locale // Locale della prenotazione
        );

        return redirect()->back()->with('message', 'Prenotazione rifiutata con successo.');
    }

    //funzione per eliminare le immagini
    public function deleteImage($id)
    {
        $image = Image::find($id);

        if ($image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'error' => 'Immagine non trovata'], 404);
    }
}
