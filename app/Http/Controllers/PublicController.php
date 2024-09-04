<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Page;
use App\Models\Image;
use App\Models\Service;
use Illuminate\Support\Facades\Route;
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
        return view('dashboard.index');
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



    public function setLanguage($lang)
    {
        // Imposta la lingua nella sessione
        session()->put('locale', $lang);

        // Reindirizza alla pagina precedente
        return redirect()->back();
    }

    // funzione per testare i pdf

    public function pdf()
    {
        $booking = [
            'id' => 1,
            'name' => 'Mario',
            'surname' => 'Rossi',
            'email' => 'mario.rossi@example.com',
            'phone' => '+39 123 456 7890',
            'body' => 'Queste sono alcune note per la prenotazione.',
            'bookingData' => [
                'type' => 'transfer', // Può essere 'transfer', 'escursione' o 'noleggio'
                'departure_name' => 'Roma',
                'arrival_name' => 'Milano',
                'date_departure' => '2024-07-01',
                'time_departure' => '10:00',
                'date_return' => '2024-07-02',
                'time_return' => '18:00',
                'duration' => 60,
                'passengers' => 4,
                'price' => 150.00,
                'original_price' => 200.00,
                'car_name' => 'Fiat 500',
                'car_description' => 'Auto compatta, perfetta per la città',
                'date_start' => '2024-07-01',
                'date_end' => '2024-07-07',
                'quantity' => 1
            ]
        ];

        $data = compact('booking');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Roboto');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('pdf.booking-summary-pdf_it', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        return response()->streamDownload(
            fn() => print($output),
            "booking-summary.pdf"
        );
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
