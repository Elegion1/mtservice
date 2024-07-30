<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Booking;
use Illuminate\Http\Request;

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
        $now = Carbon::now();
        $oneWeekAgo = $now->subWeek();

        // Filtra le prenotazioni non scadute e prenotazioni fino a una settimana prima
        $bookings = Booking::all()->filter(function ($booking) use ($oneWeekAgo) {
            return Carbon::parse($booking->start_date)->greaterThanOrEqualTo($oneWeekAgo);
        })->sortBy(function ($booking) {
            return $booking->start_date;
        });

        return view('dashboard.bookingList', compact('bookings'));
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
    public function show(Booking $booking)
    {
    }

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('dashboard.booking')->with('success', 'Prenotazione eliminata con successo!');
    }
}
