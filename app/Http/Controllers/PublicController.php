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
use App\Models\Partner;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Excursion;
use Illuminate\Http\Request;
use Sabberworm\CSS\Settings;
use App\Jobs\SendReviewRequestJob;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusNotification;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    public function home()
    {
        $pagine = Page::where('link', 'home')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        $tratte = Route::where('show', 1)->take(5)->get();
        return view('welcome', compact('pagine', 'tratte'));
    }

    public function dashboard()
    {
        $bookings = Booking::where('status', 'pending')->get();
        $contacts = Contact::get();
        $reviews = Review::all();
        return view('dashboard.index', compact('bookings', 'contacts', 'reviews'));
    }

    public function noleggio()
    {
        $pagine = Page::where('link', 'noleggio')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        $cars = Car::where('show', 1)->get();
        return view('pages.noleggio-auto', compact('pagine', 'cars'));
    }

    public function transfer()
    {
        $pagine = Page::where('link', 'transfer')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        return view('pages.transfer', compact('pagine'));
    }

    public function escursioni()
    {
        $pagine = Page::where('link', 'escursioni')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        $excursionsP = Excursion::where('show', 1)->paginate(4);
        return view('pages.escursioni', compact('pagine', 'excursionsP'));
    }

    public function prezziDestinazioni()
    {
        $pagine = Page::where('link', 'prezziDestinazioni')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        $tratte = Route::where('show', 1)->get();
        return view('pages.prezzi-destinazioni', compact('pagine', 'tratte'));
    }

    public function diconoDiNoi()
    {
        $pagine = Page::where('link', 'diconoDiNoi')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        $reviewsP = Review::where('status', 'confirmed')->paginate(6);
        return view('pages.dicono-di-noi', compact('pagine', 'reviewsP'));
    }

    public function contattaci()
    {
        $pagine = Page::where('link', 'contattaci')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        return view('pages.contattaci', compact('pagine'));
    }

    public function partners()
    {
        $pagine = Page::where('link', 'partners')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        $partners = Partner::paginate(10);
        return view('pages.partners', compact('pagine', 'partners'));
    }

    public function faq()
    {
        $pagine = Page::where('link', 'faq')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        return view('pages.faq', compact('pagine'));
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
        $logFile = storage_path('logs/laravel.log');

        if (!File::exists($logFile)) {
            return view('dashboard.logs', ['logEntries' => []]);
        }

        $logs = File::get($logFile);

        // Estrai tutte le entries del log con il timestamp come delimitatore
        preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?(?=(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]|$))/s', $logs, $matches);

        $logEntries = $matches[0];

        // Recupera la lunghezza massima dai settings, con un valore predefinito di 1000
        $maxLength = Setting::where('name', 'log_max_character_length')->value('value') ?? 1000;

        // Filtra le entries per lunghezza e per esclusione di termini specifici
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

        // Ordina le entries per timestamp dal più recente al meno recente
        usort($filteredLogEntries, function ($a, $b) {
            preg_match('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]/', $a, $timestampA);
            preg_match('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]/', $b, $timestampB);

            $timeA = isset($timestampA[0]) ? strtotime(trim($timestampA[0], '[]')) : 0;
            $timeB = isset($timestampB[0]) ? strtotime(trim($timestampB[0], '[]')) : 0;

            return $timeB <=> $timeA;
        });

        return view('dashboard.logs', ['logEntries' => $filteredLogEntries]);
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
        $booking->status = 'confirmed'; // or whatever status you want to set
        $booking->save();

        $defaultTime = Setting::where('name', 'review_request_default_time')->value('value');
        $delayDays = Setting::where('name', 'review_request_delay_days')->value('value');
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

        // Imposta il locale temporaneamente alla lingua del cliente
        $previousLocale = App::getLocale();  // Salva il locale attuale
        App::setLocale($booking->locale);    // Imposta il locale della prenotazione

        // Invia la mail nella lingua del cliente
        Mail::to($booking->email)->send(new BookingStatusNotification($booking));

        SendReviewRequestJob::dispatch($booking)->delay($delay);
        // Reimposta il locale originale
        App::setLocale($previousLocale);
        return redirect()->back()->with('message', 'Prenotazione confermata con successo.');
    }

    public function rejectBooking(Booking $booking)
    {
        $booking->status = 'rejected'; // or whatever status you want to set
        $booking->save();

        // Imposta il locale temporaneamente alla lingua del cliente
        $previousLocale = App::getLocale();  // Salva il locale attuale
        App::setLocale($booking->locale);    // Imposta il locale della prenotazione

        // Invia la mail nella lingua del cliente
        Mail::to($booking->email)->send(new BookingStatusNotification($booking));

        // Reimposta il locale originale
        App::setLocale($previousLocale);

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
