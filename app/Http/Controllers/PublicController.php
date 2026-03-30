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
use App\Traits\HasSeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    use HasSeoMeta;
    public function seoMap()
    {
        $defaultSeoMap = [
            'home' => [
                'title' => 'Taxi Trapani H24 | Transfer Aeroporto Palermo e Trapani',
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

        return cache()->remember('seo_map_data', 60 * 24, function () use ($defaultSeoMap) {
            $seoRows = \App\Models\SeoMeta::all(['page_key', 'title', 'description']);
            if ($seoRows->isEmpty()) {
                logger()->warning('SeoMeta table is empty. Using default SEO map.');
                return $defaultSeoMap;
            }

            $seoMap = [];
            foreach ($seoRows as $row) {
                $pageKey = $row->page_key;
                if (! $pageKey) {
                    continue;
                }

                $seoMap[$pageKey] = [
                    'title' => $row->title ?: ($defaultSeoMap[$pageKey]['title'] ?? null),
                    'description' => $row->description ?: ($defaultSeoMap[$pageKey]['description'] ?? null),
                ];
            }

            // Keep all default keys with fallback values and preserve any custom rows
            $final = $defaultSeoMap;
            foreach ($seoMap as $key => $value) {
                $final[$key] = array_merge($defaultSeoMap[$key] ?? ['title' => null, 'description' => null], $value);
            }

            // Keep any extra custom keys that aren't in defaults.
            foreach ($seoMap as $key => $value) {
                if (! array_key_exists($key, $defaultSeoMap)) {
                    $final[$key] = $value;
                }
            }

            return $final;
        });
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
        $ids = Route::visible()
            ->where('featured', 1)
            ->selectRaw('MIN(id) as id')
            ->groupBy(DB::raw('LEAST(departure_id, arrival_id), GREATEST(departure_id, arrival_id)'))
            ->orderBy('id')
            ->limit(5)
            ->pluck('id');

        $tratte = Route::with(['departure', 'arrival'])
            ->whereIn('id', $ids)
            ->get();

        return $this->viewWithSeo('welcome', 'home', ['tratte' => $tratte]);
    }

    public function noleggio()
    {
        $cars = Car::visible()->with('images')->get();
        return $this->viewWithSeo('pages.noleggio-auto', 'noleggio', ['cars' => $cars]);
    }

    public function transfer()
    {
        return $this->viewWithSeo('pages.transfer', 'transfer');
    }

    public function escursioni()
    {
        $excursionsP = Excursion::visible()->with('images')->orderBy('name_it', 'asc')->paginate(4);
        return $this->viewWithSeo('pages.escursioni', 'escursioni', ['excursionsP' => $excursionsP]);
    }

    public function prezziDestinazioni()
    {
        $tratte = Route::visible()->with(['departure', 'arrival'])->get();
        return $this->viewWithSeo('pages.prezzi-destinazioni', 'prezziDestinazioni', ['tratte' => $tratte]);
    }

    public function diconoDiNoi()
    {
        $reviewsP = Review::where('status', 'confirmed')->paginate(6);
        return $this->viewWithSeo('pages.dicono-di-noi', 'diconoDiNoi', ['reviewsP' => $reviewsP]);
    }

    public function contattaci()
    {
        return $this->viewWithSeo('pages.contattaci', 'contattaci');
    }

    public function partners()
    {
        $partners = Partner::orderBy('name', 'asc')->paginate(9);
        return $this->viewWithSeo('pages.partners', 'partners', ['partners' => $partners]);
    }

    public function faq()
    {
        return $this->viewWithSeo('pages.faq', 'faq');
    }

    public function privacy()
    {
        $view = session('locale', config('app.locale')) === 'en'
            ? 'pages.privacy-terms_en'
            : 'pages.privacy-terms_it';

        return $this->viewWithSeo($view, 'privacy');
    }

    public function servizi()
    {
        $services = Service::where('show', true)->get();
        return $this->viewWithSeo('pages.services', 'servizi', ['services' => $services]);
    }

    public function dashboard()
    {
        $allowedTypes = getAllowedBookingTypes();

        if (empty($allowedTypes)) {
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
