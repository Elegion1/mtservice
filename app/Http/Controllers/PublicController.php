<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Image;
use App\Models\Route;
use App\Models\Review;
use App\Models\OwnerData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    public function home()
    {
        $page = Page::where('link', 'home')->with('contents')->firstOrFail();
        return view('welcome', compact('page'));
    }

    public function dashboard()
    {
        return view('dashboard.index');
    }

    public function noleggio()
    {
        $page = Page::where('link', 'noleggio')->with('contents')->firstOrFail();
        return view('pages.noleggio-auto', compact('page'));
    }

    public function transfer()
    {
        $page = Page::where('link', 'transfer')->with('contents')->firstOrFail();
        return view('pages.transfer', compact('page'));
    }

    public function escursioni()
    {
        $page = Page::where('link', 'escursioni')->with('contents')->firstOrFail();
        return view('pages.escursioni', compact('page'));
    }

    public function prezziDestinazioni()
    {
        $page = Page::where('link', 'prezziDestinazioni')->with('contents')->firstOrFail();
        return view('pages.prezzi-destinazioni', compact('page'), compact('page'));
    }

    public function diconoDiNoi()
    {
        $page = Page::where('link', 'diconoDiNoi')->with('contents')->firstOrFail();
        return view('pages.dicono-di-noi', compact('page'));
    }

    public function contattaci()
    {
        $page = Page::where('link', 'contattaci')->with('contents')->firstOrFail();
        return view('pages.contattaci', compact('page'));
    }

    public function partners()
    {
        $page = Page::where('link', 'partners')->with('contents')->firstOrFail();
        return view('pages.partners', compact('page'));
    }

    public function faq()
    {
        $page = Page::where('link', 'faq')->with('contents')->firstOrFail();
        return view('pages.faq', compact('page'));
    }

    public function privacy()
    {   
        return view('pages.privacy-terms');
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
                'car_name' => 'Fiat 500',
                'car_description' => 'Auto compatta, perfetta per la città',
                'date_start' => '2024-07-01',
                'date_end' => '2024-07-07',
                'quantity' => 1
            ]
        ];
        return view('pdf.booking-summary-pdf', compact('booking'));
    }

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
