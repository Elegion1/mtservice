<?php

use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
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
        $currentLanguage = app()->getLocale();

        // Cambia la lingua solo se necessario
        if ($language !== $currentLanguage) {
            App::setLocale($language);
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
            }
        }
    }
}

function getSetting($name)
{
    $setting = Setting::where('name', $name)->first();
    return $setting ? $setting->value : null;
}
