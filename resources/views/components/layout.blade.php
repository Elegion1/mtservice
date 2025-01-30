<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <!-- Charset at the top -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Single meta description -->
    @if (App::getLocale() == 'en')
        <meta name="description"
            content="Car rental, Excursions, and Taxi Transfers 24/7 for the province of Trapani. Taxi transfers to and from all airports and ports in Sicily.">
    @elseif (App::getLocale() == 'it')
        <meta name="description"
            content="Noleggio auto, escursioni e trasferimenti taxi 24/7 per la provincia di Trapani. Trasferimenti taxi da e per tutti gli aeroporti e porti della Sicilia.">
    @endif

    <!-- Google Tag -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-M5SQ98ZHWM"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-M5SQ98ZHWM');
    </script>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" sizes="32x32" href="https://tranchidatransfer.it/favicon.ico">

    <!-- Title: ensure it's within <head> -->
    <title>
        @php
            $title = '';
            $type = '';
            $currentRouteName = Route::currentRouteName();
        @endphp
        @foreach ($pages as $page)
            @if ($currentRouteName == $page->link)
                @php
                    $title = ucfirst(__('ui.' . $page->name));
                    break;
                @endphp
            @endif
        @endforeach
        @if (empty($title))
            @php
                $pathInfo = request()->getPathInfo();
                $segments = explode('/', $pathInfo);
                if (isset($segments[2])) {
                    $title = urldecode($segments[2]);
                    if (isset($segments[3])) {
                        $type = urldecode($segments[3]);
                    }
                }
            @endphp
        @endif
        @if ($title)
            {{ ucfirst($title) }} |
        @endif
        @if ($type)
            {{ ucfirst($type) }} |
        @endif
        Taxi Transfer & Car Rent Trapani | Taxi transfer Aeroporto Palermo e Aeroporto Trapani | Noleggio Auto |
        Escursioni
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('cookie-consent::index')
</head>

<body class="overflow-x-hidden">
    <x-whatsapp />
    <x-nav />
    <x-masthead />
    <div id="mainContent" data-currentRoute="{{ $currentRouteName }}" class="overflow-hidden mt-5">
        {{ $slot }}
    </div>

    <x-footer />




</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funzione per scrollare in alto
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Elemento principale del contenuto
        const mainContent = document.getElementById('mainContent');

        if (!mainContent) {
            console.error('Elemento #mainContent non trovato.');
            return;
        }

        // Recupera il valore di data-currentRoute
        const currentRoute = mainContent.getAttribute('data-currentRoute');

        // Logica di scrolling
        if (currentRoute === 'home') {
            scrollToTop();
        } else {
            mainContent.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
</script>

</html>
