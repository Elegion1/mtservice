<?php

namespace App\Http\Middleware;

use alert;
use Closure;
use Illuminate\Support\Str;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Ottieni il prefisso della lingua dall'URL
        $locale = $request->segment(1);
        // Log::info("Lingua estratta dal segmento dell'URL: {$locale}");

        // Definisci i prefissi da escludere dal redirect
        $excludedPrefixes = ['dashboard', 'livewire', 'login', 'logout'];

        // Controlla se l'URL inizia con uno dei prefissi esclusi
        foreach ($excludedPrefixes as $prefix) {
            // Controlla sia con che senza slash finale
            if (Str::startsWith($request->path(), $prefix)) {
                // Log::info("Il percorso inizia con il prefisso escluso: {$prefix}");
                return $next($request); // Permetti l'accesso senza redirect
            }
        }

        // Verifica se la lingua è supportata
        if (in_array($locale, config('app.available_locales'))) {
            App::setLocale($locale);
            URL::defaults(['locale' => $locale]);
        } else {
            // Reindirizza alla lingua di default se il prefisso non è valido
            // Log::info("Reindirizzamento alla lingua di fallback: " . config('app.fallback_locale'));
            return redirect('/' . config('app.fallback_locale'));
        }
        // Log::info("Percorso dopo il controllo della lingua: " . $request->path());

        return $next($request);
    }
}
