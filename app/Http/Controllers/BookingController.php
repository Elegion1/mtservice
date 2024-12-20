<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusNotification;

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
        $bookings = Booking::all();

        // Collezione per le prenotazioni elaborate
        $processedBookings = collect();

        foreach ($bookings as $booking) {
            $bookingData = $booking->bookingData; // Ottieni i dati della prenotazione
            if ($booking->status == 'confirmed') {
                if ($bookingData['type'] == 'transfer' || $bookingData['type'] == 'escursione') {
                    $processedBookings->push((object) [
                        'id' => $booking->id,
                        'status' => $booking->status,
                        'code' => $booking->code,
                        'name' => $booking->name,
                        'surname' => $booking->surname,
                        'email' => $booking->email,
                        'phone' => $booking->phone,
                        'body' => $booking->body,
                        'bookingData' => $booking->bookingData,
                        'start_date' => $bookingData['date_dep'],
                        'end_date' => null, // non applicabile per il viaggio di partenza
                    ]);

                    if (isset($bookingData['date_ret'])) {
                        $processedBookings->push((object) [
                            'id' => $booking->id,
                            'status' => $booking->status,
                            'code' => $booking->code,
                            'name' => $booking->name,
                            'surname' => $booking->surname,
                            'email' => $booking->email,
                            'phone' => $booking->phone,
                            'body' => $booking->body,
                            'bookingData' => $booking->bookingData,
                            'start_date' => null,
                            'end_date' => $bookingData['date_ret'],
                        ]);
                    }
                } elseif ($bookingData['type'] == 'noleggio') {
                    $processedBookings->push((object) [
                        'id' => $booking->id,
                        'status' => $booking->status,
                        'code' => $booking->code,
                        'name' => $booking->name,
                        'surname' => $booking->surname,
                        'email' => $booking->email,
                        'phone' => $booking->phone,
                        'body' => $booking->body,
                        'bookingData' => $booking->bookingData,
                        'start_date' => $bookingData['date_start'],
                        'end_date' => null,
                    ]);

                    if (isset($bookingData['date_end'])) {
                        $processedBookings->push((object) [
                            'id' => $booking->id,
                            'status' => $booking->status,
                            'code' => $booking->code,
                            'name' => $booking->name,
                            'surname' => $booking->surname,
                            'email' => $booking->email,
                            'phone' => $booking->phone,
                            'body' => $booking->body,
                            'bookingData' => $booking->bookingData,
                            'start_date' => null,
                            'end_date' => $bookingData['date_end'],
                        ]);
                    }
                }
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
            'groupedByMonth' => $groupedByMonth
        ]);
    }

    public function bookingToDo()
    {
        $bookings = Booking::all();
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
        $request->validate([
            'status' => 'required|in:confirmed,pending,rejected',
        ]);

        $booking->status = $request->status;
        $booking->save();

        // Imposta il locale temporaneamente alla lingua del cliente
        $previousLocale = App::getLocale();  // Salva il locale attuale
        App::setLocale($booking->locale);    // Imposta il locale della prenotazione

        // Invia la mail nella lingua del cliente
        Mail::to($booking->email)->send(new BookingStatusNotification($booking));

        // Reimposta il locale originale
        App::setLocale($previousLocale);

        return redirect()->back()->with('success', 'Stato della prenotazione aggiornato con successo.');
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
