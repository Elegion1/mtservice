<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo immagini, CSS, JS
        if ($request->is('storage/*') || $request->is('livewire/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000');
        }

        return $next($request);
    }
}
