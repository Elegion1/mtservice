<?php

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
    // Ottimizzare la query per cercare solo job relativi a una prenotazione
    return DB::table('jobs')
        ->get()
        ->first(function ($job) use ($booking) {
            $payload = json_decode($job->payload, true);

            if (isset($payload['data']['command'])) {
                $commandData = unserialize($payload['data']['command']);
                return isset($commandData->booking) && $commandData->booking->code === $booking->code;
            }

            return false;
        });
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
