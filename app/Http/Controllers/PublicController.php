<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Page;
use App\Models\Image;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Request;
use Sabberworm\CSS\Settings;
use App\Jobs\SendReviewRequestJob;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
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
        return view('welcome', compact('pagine'));
    }

    public function dashboard()
    {
        $bookings = Booking::where('status', 'pending')->get();
        $contacts = Contact::get();
        return view('dashboard.index', compact('bookings', 'contacts'));
    }

    public function noleggio()
    {
        $pagine = Page::where('link', 'noleggio')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        return view('pages.noleggio-auto', compact('pagine'));
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
        return view('pages.escursioni', compact('pagine'));
    }

    public function prezziDestinazioni()
    {
        $pagine = Page::where('link', 'prezziDestinazioni')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        return view('pages.prezzi-destinazioni', compact('pagine'));
    }

    public function diconoDiNoi()
    {
        $pagine = Page::where('link', 'diconoDiNoi')->with(['contents' => function ($query) {
            $query->where('order', '!=', 0);
        }])->get();
        return view('pages.dicono-di-noi', compact('pagine'));
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
        return view('pages.partners', compact('pagine'));
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
        
        SendReviewRequestJob::dispatch($booking)->delay($delay);
        // Imposta il locale temporaneamente alla lingua del cliente
        $previousLocale = App::getLocale();  // Salva il locale attuale
        App::setLocale($booking->locale);    // Imposta il locale della prenotazione

        // Invia la mail nella lingua del cliente
        Mail::to($booking->email)->send(new BookingStatusNotification($booking));

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
