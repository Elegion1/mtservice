<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Contact;
use App\Models\Excursion;
use App\Models\Image;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Review;
use App\Models\Route;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class PublicController extends Controller
{
    public function seoMap()
    {
        return [
            'home' => [
                'title' => 'Tranchida Transfer | Transfer, Taxi, Noleggio Auto ed Escursioni a Trapani',
                'description' => 'Servizi di transfer, taxi, noleggio auto ed escursioni in Sicilia occidentale. Prenota online Tranchida Transfer Trapani per Aeroporto Palermo e Trapani',
            ],
            'noleggio' => [
                'title' => 'Noleggio Auto a Trapani | Consegna in Aeroporto e in Città',
                'description' => 'Noleggia un’auto a Trapani con consegna in aeroporto o hotel. Prezzi competitivi e prenotazione semplice online',
            ],
            'transfer' => [
                'title' => 'Transfer e Taxi Trapani | Aeroporto Palermo & Aeroporto Trapani',
                'description' => 'Servizio di taxi e transfer privati da/per Trapani, Palermo e gli aeroporti. Puntualità e comfort assicurati',
            ],
            'servizi' => [
                'title' => 'I nostri servizi | Transfer, Noleggio Auto ed Escursioni a Trapani',
                'description' => 'Servizio di taxi e transfer privati da/per Trapani, Palermo e gli aeroporti. Puntualità e comfort assicurati',
            ],
            'escursioni' => [
                'title' => 'Escursioni Trapani | Tour alle Egadi, Erice e San Vito Lo Capo',
                'description' => 'Scopri le migliori escursioni da Trapani: Favignana, Levanzo, Erice e San Vito Lo Capo. Esperienze autentiche e guide locali',
            ],
            'prezziDestinazioni' => [
                'title' => 'Prezzi e Destinazioni | Transfer, Taxi ed Escursioni da Trapani',
                'description' => 'Consulta la lista completa di prezzi e destinazioni per i nostri servizi transfer, taxi ed escursioni da Trapani',
            ],
            'diconoDiNoi' => [
                'title' => 'Recensioni | Cosa dicono di noi i clienti Tranchida Transfer Trapani',
                'description' => 'Leggi le recensioni dei clienti che hanno scelto Tranchida Transfer Trapani per i loro spostamenti in Sicilia occidentale',
            ],
            'contattaci' => [
                'title' => 'Contatti | Prenota il tuo Transfer o Noleggio a Trapani',
                'description' => 'Contatta Tranchida Transfer Trapani per informazioni, preventivi o prenotazioni di transfer, taxi ed escursioni',
            ],
            'partners' => [
                'title' => 'Partners | Collaborazioni con Tranchida Transfer Trapani',
                'description' => 'Scopri i nostri partner e collaboratori nel settore turismo e trasporti in Sicilia occidentale',
            ],
            'faq' => [
                'title' => 'FAQ | Domande Frequenti su Tranchida Transfer Trapani',
                'description' => 'Consulta le risposte alle domande più frequenti su prenotazioni, pagamenti e servizi offerti da Tranchida Transfer Trapani',
            ],
            'privacy' => [
                'title' => 'Privacy e Termini | Tranchida Transfer Trapani',
                'description' => 'Consulta la nostra informativa sulla privacy, i termini e le condizioni del servizio Tranchida Transfer Trapani',
            ],
        ];

    }

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
        $ids = DB::table('routes')
            ->selectRaw('MIN(id) as id')
            ->where('show', 1)
            ->where('featured', 1)
            ->groupBy(DB::raw('LEAST(departure_id, arrival_id), GREATEST(departure_id, arrival_id)'))
            ->orderBy('id')
            ->limit(5)
            ->pluck('id');

        $tratte = Route::with(['departure', 'arrival'])
            ->whereIn('id', $ids)
            ->get();

        $data = $this->getPageData('home', ['tratte' => $tratte]);

        // Metadati SEO
        $seo = $this->seoMap();
        $data['seoTitle'] = $seo['home']['title'] ?? null;
        $data['seoDescription'] = $seo['home']['description'] ?? null;

        return view('welcome', $data);
    }

    public function noleggio()
    {
        $cars = Car::where('show', 1)->get();

        $data = $this->getPageData('noleggio', ['cars' => $cars]);

        $seo = $this->seoMap();
        $data['seoTitle'] = $seo['noleggio']['title'] ?? null;
        $data['seoDescription'] = $seo['noleggio']['description'] ?? null;

        return view('pages.noleggio-auto', $data);
    }

    public function transfer()
    {
        $data = $this->getPageData('transfer');
        $seo = $this->seoMap();
        $data['seoTitle'] = $seo['transfer']['title'] ?? null;
        $data['seoDescription'] = $seo['transfer']['description'] ?? null;

        return view('pages.transfer', $data);
    }

    public function escursioni()
    {
        $excursionsP = Excursion::where('show', 1)->orderBy('name_it', 'asc')->paginate(4);
        $data = $this->getPageData('escursioni', ['excursionsP' => $excursionsP]);
        $seo = $this->seoMap();
        $data['seoTitle'] = $seo['escursioni']['title'] ?? null;
        $data['seoDescription'] = $seo['escursioni']['description'] ?? null;

        return view('pages.escursioni', $data);
    }

    public function prezziDestinazioni()
    {
        $tratte = Route::where('show', 1)->get();
        $data = $this->getPageData('prezziDestinazioni', ['tratte' => $tratte]);
        $seo = $this->seoMap();
        $data['seoTitle'] = $seo['prezziDestinazioni']['title'] ?? null;
        $data['seoDescription'] = $seo['prezziDestinazioni']['description'] ?? null;

        return view('pages.prezzi-destinazioni', $data);
    }

    public function diconoDiNoi()
    {
        $reviewsP = Review::where('status', 'confirmed')->paginate(6);
        $data = $this->getPageData('diconoDiNoi', ['reviewsP' => $reviewsP]);
        $seo = $this->seoMap();
        $data['seoTitle'] = $seo['diconoDiNoi']['title'] ?? null;
        $data['seoDescription'] = $seo['diconoDiNoi']['description'] ?? null;

        return view('pages.dicono-di-noi', $data);
    }

    public function contattaci()
    {
        $data = $this->getPageData('contattaci');
        $seo = $this->seoMap();
        $data['seoTitle'] = $seo['contattaci']['title'] ?? null;
        $data['seoDescription'] = $seo['contattaci']['description'] ?? null;

        return view('pages.contattaci', $data);
    }

    public function partners()
    {
        $partners = Partner::orderBy('name', 'asc')->paginate(9);
        $data = $this->getPageData('partners', ['partners' => $partners]);
        $seo = $this->seoMap();
        $data['seoTitle'] = $seo['partners']['title'] ?? null;
        $data['seoDescription'] = $seo['partners']['description'] ?? null;

        return view('pages.partners', $data);
    }

    public function faq()
    {
        $data = $this->getPageData('faq');
        $seo = $this->seoMap();
        $data['seoTitle'] = $seo['faq']['title'] ?? null;
        $data['seoDescription'] = $seo['faq']['description'] ?? null;

        return view('pages.faq', $data);
    }

    public function privacy()
    {
        $data = [];
        $lang = session('locale', config('app.locale'));
        $seo = $this->seoMap();
        $data['seoTitle'] = $seo['privacy']['title'] ?? null;
        $data['seoDescription'] = $seo['privacy']['description'] ?? null;
        // Restituisce la vista corretta in base alla lingua
        if ($lang === 'en') {

            return view('pages.privacy-terms_en', compact('data'));
        }

        // Imposta la vista italiana come predefinita
        return view('pages.privacy-terms_it', compact('data'));
    }

    public function servizi()
    {
        $services = Service::where('show', true)->get();
        $data = $this->getPageData('servizi', ['services' => $services]);
        $seo = $this->seoMap();
        $data['seoTitle'] = $seo['servizi']['title'] ?? null;
        $data['seoDescription'] = $seo['servizi']['description'] ?? null;
        
        return view('pages.services', $data);
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

        if (! $booking) {
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
            'code' => 'required|string|size:6',
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

    // public function confirmBooking(Booking $booking)
    // {
    //     if ($booking->status === 'confirmed') {
    //         return redirect()->back()->withErrors(['message' => 'Prenotazione già confermata.']);
    //     }

    //     $booking->status = 'confirmed'; // or whatever status you want to set
    //     $booking->save();

    //     $delayDays = getSetting('review_request_delay_days');

    //     $defaultTime = getSetting('review_request_default_time');
    //     // Unisci la data del servizio con l'orario di default
    //     $serviceDate = Carbon::parse($booking->service_date . ' ' . $defaultTime);

    //     // Aggiungi i giorni di ritardo
    //     $delay = $serviceDate->addDays((int) $delayDays);  // (int) per essere sicuro che sia un numero intero

    //     Log::info([
    //         'service_date' => $booking->service_date,
    //         'default_time' => $defaultTime,
    //         'delay_days' => $delayDays,
    //         'calculated_delay' => $delay,
    //     ]);

    //     // Invia la mail nella lingua del cliente
    //     sendEmail(
    //         $booking->email, // Destinatario
    //         new BookingStatusNotification($booking), // Mailable
    //         'Errore nell\'invio dell\'email di stato prenotazione', // Messaggio di errore
    //         $booking->locale // Locale della prenotazione
    //     );

    //     // Controlla se esistono già jobs per la prenotazione
    //     $findJob = getJobs($booking);

    //     if ($findJob) {
    //         Log::info("Job trovato per la prenotazione {$booking->code}. ID Job: {$findJob->id}. Annullo creazione del Job");
    //         return redirect()->back()->withErrors(['message' => 'Job già presente']);
    //     } else {
    //         // Dispatcha il job per inviare la richiesta di recensione
    //         $appLocale = App::getLocale();
    //         App::setLocale($booking->locale);
    //         SendReviewRequestJob::dispatch($booking)->delay($delay);
    //         App::setLocale($appLocale);
    //         Log::info("Job per la richiesta di recensione creato per la prenotazione: {$booking->code}, con invio previsto per: {$delay->toDateTimeString()}");
    //     }

    //     return redirect()->back()->with('message', 'Prenotazione confermata con successo.');
    // }

    // public function rejectBooking(Booking $booking)
    // {
    //     if ($booking->status === 'rejected') {
    //         return redirect()->back()->withErrors(['message' => 'Prenotazione già rifiutata.']);
    //     }

    //     if ($booking->status === 'confirmed') {
    //         // **Cancella il job programmato per la richiesta di recensione**
    //         Log::info("Tentativo di cancellazione del job per la prenotazione: {$booking->code}");

    //         $jobToDelete = getJobs($booking);

    //         if ($jobToDelete) {
    //             Log::info("Job trovato per la prenotazione {$booking->code}. ID Job: {$jobToDelete->id}. Eliminazione in corso...");
    //             DB::table('jobs')->where('id', $jobToDelete->id)->delete();
    //             Log::info("Job {$jobToDelete->id} eliminato con successo.");
    //         } else {
    //             Log::warning("Nessun job trovato per la prenotazione {$booking->code}. Nessuna eliminazione eseguita.");
    //         }
    //     }

    //     $booking->status = 'rejected'; // or whatever status you want to set
    //     $booking->save();

    //     // Invia la mail nella lingua del cliente
    //     sendEmail(
    //         $booking->email, // Destinatario
    //         new BookingStatusNotification($booking), // Mailable
    //         'Errore nell\'invio dell\'email di stato prenotazione', // Messaggio di errore
    //         $booking->locale // Locale della prenotazione
    //     );

    //     return redirect()->back()->with('message', 'Prenotazione rifiutata con successo.');
    // }

    // funzione per eliminare le immagini
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
