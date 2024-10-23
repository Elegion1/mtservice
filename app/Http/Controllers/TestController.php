<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function test()
    {
        $bookings = $this->bookings();
        return view('dashboard.test', compact('bookings'));
    }

    private function bookings()
    {
        return [
            [
                'id' => 12,
                'name' => 'Vance Joyce',
                'surname' => 'Albert',
                'email' => 'zove@mailinator.com',
                'phone' => '+1 (301) 928-4494',
                'body' => 'Qui at nihil consequ',
                'bookingData' => '{
                "type": "escursione", 
                "price": "150", 
                "date_dep": "2024-11-03T11:09", 
                "duration": "1", 
                "passengers": 1, 
                "departure_id": "2", 
                "date_departure": "Sun 03 November 2024", 
                "departure_name": "Marsala", 
                "original_price": "150", 
                "time_departure": "11:09"
                }',
                'created_at' => '2024-10-10 11:09:27',
                'updated_at' => '2024-10-17 14:58:52',
                'status' => 'confirmed',
                'code' => 'DQSSDA',
                'locale' => 'it'
            ],
            [
                'id' => 14,
                'name' => 'Quamar Parrish',
                'surname' => 'Conrad',
                'email' => 'devupa@mailinator.com',
                'phone' => '+1 (914) 824-3353',
                'body' => 'Inventore non deseru',
                'bookingData' => '
                {
                "type": "noleggio",
                 "price": 1400,
                 "car_id": "2",
                 "car_name": "Volkswagen Up",
                 "date_end": "2024-11-02T12:45",
                 "quantity": 1,
                 "date_start": "2024-10-19T12:45",
                 "original_price": 1400,
                 "car_description": "1.0 60cv"
                 }',
                'created_at' => '2024-10-10 12:45:30',
                'updated_at' => '2024-10-17 14:58:52',
                'status' => 'confirmed',
                'code' => 'AJXQLA',
                'locale' => 'it'
            ],
            [
                'id' => 15,
                'name' => 'Nathaniel Simon',
                'surname' => 'Delgado',
                'email' => 'gesohuruda@mailinator.com',
                'phone' => '+1 (329) 652-4358',
                'body' => 'Id sed eiusmod deser',
                'bookingData' => '
                {
                "type": "transfer", 
                "price": 1546,
                 "date_dep": "2024-10-31T18:04",
                 "date_ret": "2024-11-10T18:04",
                 "duration": "26",
                 "arrival_id": "7",
                 "passengers": 1,
                 "date_return": "Sun 10 November 2024",
                 "sola_andata": false,
                 "time_return": "18:04",
                 "arrival_name": "San Vito Lo Capo",
                 "departure_id": "1",
                 "date_departure": "Thu 31 October 2024",
                 "departure_name": "Aeroporto Trapani Birgi V.Florio",
                 "original_price": 1546,
                 "time_departure": "18:04"
                 }',
                'created_at' => '2024-10-14 18:05:06',
                'updated_at' => '2024-10-17 14:58:52',
                'status' => 'confirmed',
                'code' => 'BDUO5P',
                'locale' => 'it'
            ]
        ];
    }

    public function pdf($bookingId, $lang)
    {
        // Recupera la prenotazione dall'array o dal database (se utilizzi un database)
        $bookings = $this->bookings();
        $booking = collect($bookings)->firstWhere('id', $bookingId);

        if (!$booking) {
            abort(404, 'Booking not found');
        }

        // Decodifica 'bookingData' da JSON a array associativo
        $booking['bookingData'] = json_decode($booking['bookingData'], true);



        // Imposta le opzioni per Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Roboto');

        // Inizializza Dompdf
        $dompdf = new Dompdf($options);

        // Carica la vista con i dati della prenotazione e la lingua selezionata
        $dompdf->loadHtml(view('pdf.booking-summary-pdf_' . $lang, ['booking' => $booking])->render());

        // Imposta il formato della carta
        $dompdf->setPaper('A4', 'portrait');

        // Genera il PDF
        $dompdf->render();

        // Restituisci il PDF come download
        $output = $dompdf->output();

        return response()->streamDownload(
            fn() => print($output),
            "booking-summary.pdf"
        );
    }

    public function emailPreview($mailType, $bookingId = null, $lang)
    {
        Log::info("Mail type: " . $mailType);
        Log::info("Booking ID: " . $bookingId);
        Log::info("Language: " . $lang);
        
        App::setLocale($lang);

        $bookings = $this->bookings();
        $booking = (object) collect($bookings)->firstWhere('id', $bookingId);
        Log::info('Booking data:', (array) $booking);
        if (strpos($mailType, 'contattaci') !== false) {
            $contatto = (object) [
                'nome' => 'Mario',
                'cognome' => 'Rossi',
                'servizio' => 'Transfer',
                'messaggio' => 'Vorrei sapere maggiori informazioni sui vostri servizi di transfer.',
                'telefono' => '+39 123 456 7890',
                'email' => 'mario.rossi@example.com'
            ];

            Log::info("Loading view: mail." . $mailType);

            return view('mail.' . $mailType, compact('contatto')); // Vista con lingua
        }

        if (!$booking) {
            Log::error("Booking not found for ID: " . $bookingId);
            abort(404, 'Booking not found');
        }

        Log::info("Loading view: mail." . $mailType);

        return view('mail.' . $mailType, compact('booking'));
    }
}
