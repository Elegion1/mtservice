<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Escludi la brochure
        if ($request->is('*brochure')) {
            return $next($request);
        }

        $firstSegment = $request->segment(1);

        // 2. Escludi Dashboard, Livewire, Auth e asset
        $excludedSegments = ['dashboard', 'livewire', 'login', 'logout', 'api', '_debugbar', 'assets'];
        if (in_array($firstSegment, $excludedSegments)) {
            return $next($request);
        }

        $availableLocales = config('app.available_locales', ['it', 'en']);
        $defaultLocale = config('app.locale', 'it');

        // 3. Se l'URL contiene un locale valido (/it, /en)
        if (in_array($firstSegment, $availableLocales)) {
            App::setLocale($firstSegment);
            URL::defaults(['locale' => $firstSegment]); // <--- Impostato per le rotte con prefisso

            return $next($request);
        }

        // 4. Se MANCA il locale (es. '/') o non è valido:
        // IMPOSIZIONE FONDAMENTALE: Imposta SEMPRE il default PRIMA di fare qualsiasi altra cosa
        App::setLocale($defaultLocale);
        URL::defaults(['locale' => $defaultLocale]);

        // Reindirizza mantenendo il resto del percorso (es. '/contatti' -> '/it/contatti')
        $path = $request->path() === '/' ? '' : '/'.$request->path();

        return redirect('/'.$defaultLocale.$path);
    }
}
