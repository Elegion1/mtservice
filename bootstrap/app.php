<?php

use App\Http\Middleware\CacheControl;
use App\Http\Middleware\TrackVisits;
use Illuminate\Foundation\Application;
use App\Http\Middleware\SetLocaleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SetLocaleMiddleware::class,
            TrackVisits::class,
            CacheControl::class
        ]);
        $middleware->alias([
            'locale' => SetLocaleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
