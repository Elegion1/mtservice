<?php

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Route;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

if (!function_exists('updateLocaleInUrl')) {

    function updateLocaleInUrl($newLocale)
    {
        $currentUrl = request()->url(); // Get the current URL without query parameters
        $segments = request()->segments(); // Get all URL segments

        // Check if the first segment is a locale and replace it
        if (in_array($segments[0], config('app.available_locales'))) {
            $segments[0] = $newLocale; // Update the locale segment
        } else {
            array_unshift($segments, $newLocale); // Add the new locale as the first segment
        }

        return url(implode('/', $segments)); // Rebuild the URL and return
    }
}

if (!function_exists('sendEmail')) {

    function sendEmail($recipient, $mailable, $errorMessage, $language)
    {
        $currentLanguage = App::getLocale();
        Log::info('[MailHelper] Current language: ' . $currentLanguage);
        // Cambia la lingua solo se necessario
        if ($language !== $currentLanguage) {
            App::setLocale($language);
            Log::info('[MailHelper] Language changed to: ' . App::getLocale());
        }

        Log::info('[MailHelper] Sending email to: ' . $recipient . ' Language: ' . $language);
        try {
            // Invia l'email al destinatario
            Mail::to($recipient)->send($mailable);
            Log::info('[MailHelper] Email sent to: ' . $recipient . ' Language: ' . $language);
        } catch (\Exception $e) {
            Log::error('[MailHelper] ' . $errorMessage . ': ' . $e->getMessage());
        } finally {
            // Ripristina la lingua originale solo se è stata cambiata
            if ($language !== $currentLanguage) {
                App::setLocale($currentLanguage);
                Log::info('[MailHelper] Language restored to: ' . App::getLocale());
            }
        }
    }
}

function getSetting($name)
{
    $setting = Setting::where('name', $name)->first();
    return $setting ? $setting->value : null;
}

function getJobs($booking)
{
    return DB::table('jobs')
        ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(payload, '$.data.command.booking.code')) = ?", [$booking->code])
        ->first();
}

function getAllowedBookingTypes()
{
    $user = Auth::user();

    // Ottieni gli utenti autorizzati per ogni tipo di prenotazione
    $showTransferFor = explode(', ', getSetting('show_transfer'));
    $showEscursioniFor = explode(', ', getSetting('show_escursioni'));
    $showNoleggioFor = explode(', ', getSetting('show_noleggio'));

    // Inizializza un array per i tipi visibili
    $allowedTypes = [];

    if (in_array($user->name, $showTransferFor)) {
        $allowedTypes[] = 'transfer';
    }
    if (in_array($user->name, $showEscursioniFor)) {
        $allowedTypes[] = 'escursione';
    }
    if (in_array($user->name, $showNoleggioFor)) {
        $allowedTypes[] = 'noleggio';
    }

    return $allowedTypes;
}

function sendWPMessage($booking, $templateName, $languageCode = 'it')
{
    $phoneNumber = ltrim($booking->dial_code, "+") . $booking->phone;

    $accessToken = getSetting('whatsapp_access_token');

    if (!$accessToken) {
        Log::error('Access token WhatsApp non configurato');
        return response()->json(['error' => 'Access token WhatsApp non configurato'], 500);
    }

    // Log della richiesta in uscita
    Log::info('Invio messaggio WhatsApp', [
        'to' => $phoneNumber,
        'template' => $templateName,
        'language' => $languageCode
    ]);

    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post('https://graph.facebook.com/v22.0/499713656568876/messages', [
            'messaging_product' => 'whatsapp',
            'to' => $phoneNumber,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'parameter_name' => 'name',  // Aggiungi il nome del parametro nel template
                                'text' => $booking->name  // Nome del cliente
                            ],
                            [
                                'type' => 'text',
                                'parameter_name' => 'bookingcode',  // Aggiungi il nome del parametro nel template
                                'text' => $booking->code  // Codice prenotazione
                            ],
                            [
                                'type' => 'text',
                                'parameter_name' => 'price',  // Aggiungi il nome del parametro nel template
                                'text' => (string)$booking->bookingData['price']  // Prezzo della prenotazione
                            ],
                            [
                                'type' => 'text',
                                'parameter_name' => 'link',  // Aggiungi il nome del parametro nel template
                                'text' => 'https://revolut.me/atranchida'  // Link di pagamento
                            ],
                            [
                                'type' => 'text',
                                'parameter_name' => 'bookingstatus',  // Aggiungi il nome del parametro nel template
                                'text' => 'https://tranchidatransfer.it/' . $booking->locale . '/booking/status?code=' . $booking->code . '&email=' . $booking->email  // Link stato prenotazione
                            ]
                        ]
                    ]
                ]
            ],
        ]);
        Log::info('Richiesta: ' . $response);

        // Log della risposta
        Log::info('Risposta WhatsApp API', [
            'status' => $response->status(),
            'body' => $response->json()
        ]);
        return $response;
    } catch (\Exception $e) {
        // Log dell'errore
        Log::error('Errore nell\'invio del messaggio WhatsApp', [
            'error' => $e->getMessage()
        ]);

        return response()->json(['error' => 'Errore nell\'invio del messaggio'], 500);
    }
}

function combineDateAndTime($date, $time)
{
    if ($date && $time) {
        return "{$date}T{$time}";
    }
    return null;
}

function generateUniqueCode()
{
    do {
        $code = strtoupper(Str::random(6));
    } while (Booking::where('code', $code)->exists());

    return $code;
}

function generatePDF($booking, $language)
{
    $data = compact('booking');

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'Roboto');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml(view('pdf.booking-summary-pdf_' . $language, $data)->render());
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return $dompdf->output();
}

function calculateBasePrice($basePrice, $incrementPrice, $passengers, $minPassengers)
{
    $vehicleCapacity = getSetting('vehicle_capacity') ?: 8;
    
    if ($passengers <= $minPassengers) return $basePrice;

    $extraPassengers = max(0, $passengers - $minPassengers);

    if ($passengers <= $vehicleCapacity) {
        return $basePrice + ($incrementPrice * $extraPassengers);
    }

    // Calcolo dei veicoli necessari
    $firstVehicleExtra = max(0, $vehicleCapacity - $minPassengers);
    $firstVehiclePrice = $basePrice + ($incrementPrice * $firstVehicleExtra);

    $remainingPassengers = $passengers - $vehicleCapacity;
    $vehiclesNeeded = ceil($remainingPassengers / $vehicleCapacity);

    $totalPrice = $firstVehiclePrice;

    for ($i = 0; $i < $vehiclesNeeded; $i++) {
        $passengersThisVehicle = min($vehicleCapacity, $remainingPassengers);
        $extraThisVehicle = max(0, $passengersThisVehicle - $minPassengers);
        $totalPrice += $basePrice + ($incrementPrice * $extraThisVehicle);
        $remainingPassengers -= $passengersThisVehicle;
    }

    return $totalPrice;
}

function passengerNumber($change, &$passengerProperty, $priceCallback)
{
    $newCount = $passengerProperty + $change;

    if ($newCount >= 1 && $newCount <= 16) {
        $passengerProperty = $newCount;
        $priceCallback();
    }
}

function goToStep($step, &$currentStep)
{
    $currentStep = $step;
}
