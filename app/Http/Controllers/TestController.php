<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class TestController extends Controller
{
    public function test()
    {
        $bookings = $this->bookings();
        return view('dashboard.test', compact('bookings'));
    }

    private function bookings()
    {
        return collect([
            (object) [
                'id' => 12,
                'name' => 'Vance Joyce',
                'surname' => 'Albert',
                'email' => 'zove@mailinator.com',
                'phone' => '+1 (301) 928-4494',
                'body' => 'Qui at nihil consequ',
                'bookingData' => json_decode('{
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
                }', true),
                'created_at' => '2024-10-10 11:09:27',
                'updated_at' => '2024-10-17 14:58:52',
                'status' => 'confirmed',
                'code' => 'DQSSDA',
                'locale' => 'it',
                'service_date' => '2024-11-03',
            ],
            (object) [
                'id' => 14,
                'name' => 'Quamar Parrish',
                'surname' => 'Conrad',
                'email' => 'devupa@mailinator.com',
                'phone' => '+1 (914) 824-3353',
                'body' => 'Inventore non deseru',
                'info' => json_decode('{
                "flight":{
                    "flightNumber":"fr12345",
                    "departureAirport":"palermo",
                    "departureTime":"12:34",
                    "arrivalAirport":"erice",
                    "arrivalTime":"12:34"
                    },
                "driver":{
                    "driverName":"Giovanni Sugamiele", 
                    "driverBirthDate":"2007-03-26",
                    "driverBirthPlace":"frfdsfds",
                    "driverAddress":"Via regina Margherita, 87",
                    "driverCity":"Paceco",
                    "driverPostalCode":"91027",
                    "driverCountry":"Italia",
                    "driverLicenseNumber":"ewf3rdfs",
                    "driverLicenseType":"b",
                    "driverLicenseIssueDate":"2025-03-31",
                    "driverLicenseExpirationDate":"2025-06-09",
                    "driverLicenseCountry":"italia"
                    ,"driverLicenseProvince":"erice"
                    }
                }', true),
                'bookingData' => json_decode('{
                    "type": "noleggio",
                    "price": 1400,
                    "car_id": "2",
                    "car_name": "Volkswagen Up",
                    "date_end": "2024-11-02T12:45",
                    "quantity": 1,
                    "date_start": "2024-10-19T12:45",
                    "original_price": 1400,
                    "car_description": "1.0 60cv"
                }', true),
                'created_at' => '2024-10-10 12:45:30',
                'updated_at' => '2024-10-17 14:58:52',
                'status' => 'confirmed',
                'code' => 'AJXQLA',
                'locale' => 'it',
                'service_date' => '2024-11-02',
            ],
            (object) [
                'id' => 15,
                'name' => 'Nathaniel Simon',
                'surname' => 'Delgado',
                'email' => 'gesohuruda@mailinator.com',
                'phone' => '+1 (329) 652-4358',
                'body' => 'Id sed eiusmod deser',
                'bookingData' => json_decode('{
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
                }', true),

                'created_at' => '2024-10-14 18:05:06',
                'updated_at' => '2024-10-17 14:58:52',
                'status' => 'confirmed',
                'code' => 'BDUO5P',
                'locale' => 'it',
                'service_date' => '2024-11-03',
            ]
        ]);
    }

    public function pdf($bookingId, $lang)
    {
        // Recupera la prenotazione dall'array o dal database (se utilizzi un database)
        $bookings = $this->bookings();
        $bookingJson = collect($bookings)->firstWhere('id', $bookingId);

        $booking = (array) $bookingJson;


        if (!$booking) {
            abort(404, 'Booking not found');
        }

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

    public function emailPreview($mailType, $lang, $bookingId = null)
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

    public function jobsIndex()
    {
        // Recupera i job in coda (dove 'reserved_at' è null)
        $jobs = DB::table('jobs')
            ->whereNull('reserved_at') // I job non ancora in esecuzione
            ->orderBy('created_at', 'desc')
            ->get();

        // Recupera i job falliti
        $failedJobs = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->get();
        return view('dashboard.jobs', compact('jobs', 'failedJobs'));
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
}
