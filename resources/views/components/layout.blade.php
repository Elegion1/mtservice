<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <!-- Charset at the top -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- JSON-LD --}}
    <script type="application/ld+json">
        {!! json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
            '@type' => 'TravelAgency',
            'name' => 'Tranchida Transfer Trapani',
            'url' => url('/'),
            'logo' => asset('media/logo.png'),
            'telephone' => $ownerdata->phone ,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Trapani',
                'addressRegion' => 'TP',
                'addressCountry' => 'IT'
            ]
            ],
            [
            '@type' => 'Service',
            'serviceType' => 'Transfer Aeroporto Palermo - Trapani',
            'provider' => ['@type' => 'TravelAgency', 'name' => 'Trapani Transfer'],
            'areaServed' => ['@type' => 'Place', 'name' => 'Sicilia Occidentale']
            ]
        ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

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
    @php
        use Illuminate\Support\Str;

        $currentRoute = Route::currentRouteName();
        $seoTitle = null;
        $seoDescription = null;

        // Mappa SEO per le rotte principali
        $seoMap = [
            'home' => [
                'title' => 'Tranchida Transfer Trapani | Transfer, Taxi, Noleggio Auto ed Escursioni a Trapani',
                'description' =>
                    'Servizi di transfer, taxi, noleggio auto ed escursioni in Sicilia occidentale. Prenota online Tranchida Transfer Trapani per Aeroporto Palermo e Trapani',
            ],
            'noleggio' => [
                'title' => 'Noleggio Auto a Trapani | Consegna in Aeroporto e in Città',
                'description' =>
                    'Noleggia un’auto a Trapani con consegna in aeroporto o hotel. Prezzi competitivi e prenotazione semplice online',
            ],
            'transfer' => [
                'title' => 'Transfer e Taxi Trapani | Aeroporto Palermo & Aeroporto Trapani',
                'description' =>
                    'Servizio di taxi e transfer privati da/per Trapani, Palermo e gli aeroporti. Puntualità e comfort assicurati',
            ],
            'escursioni' => [
                'title' => 'Escursioni Trapani | Tour alle Egadi, Erice e San Vito Lo Capo',
                'description' =>
                    'Scopri le migliori escursioni da Trapani: Favignana, Levanzo, Erice e San Vito Lo Capo. Esperienze autentiche e guide locali',
            ],
            'prezziDestinazioni' => [
                'title' => 'Prezzi e Destinazioni | Transfer, Taxi ed Escursioni da Trapani',
                'description' =>
                    'Consulta la lista completa di prezzi e destinazioni per i nostri servizi transfer, taxi ed escursioni da Trapani',
            ],
            'diconoDiNoi' => [
                'title' => 'Recensioni | Cosa dicono di noi i clienti Tranchida Transfer Trapani',
                'description' =>
                    'Leggi le recensioni dei clienti che hanno scelto Tranchida Transfer Trapani per i loro spostamenti in Sicilia occidentale',
            ],
            'contattaci' => [
                'title' => 'Contatti | Prenota il tuo Transfer o Noleggio a Trapani',
                'description' =>
                    'Contatta Tranchida Transfer Trapani per informazioni, preventivi o prenotazioni di transfer, taxi ed escursioni',
            ],
            'partners' => [
                'title' => 'Partners | Collaborazioni con Tranchida Transfer Trapani',
                'description' =>
                    'Scopri i nostri partner e collaboratori nel settore turismo e trasporti in Sicilia occidentale',
            ],
            'faq' => [
                'title' => 'FAQ | Domande Frequenti su Tranchida Transfer Trapani',
                'description' =>
                    'Consulta le risposte alle domande più frequenti su prenotazioni, pagamenti e servizi offerti da Tranchida Transfer Trapani',
            ],
            'privacy' => [
                'title' => 'Privacy e Termini | Tranchida Transfer Trapani',
                'description' =>
                    'Consulta la nostra informativa sulla privacy, i termini e le condizioni del servizio Tranchida Transfer Trapani',
            ],
        ];

        // Se la route è definita nella mappa, usa quei valori
        if (isset($seoMap[$currentRoute])) {
            $seoTitle = $seoMap[$currentRoute]['title'];
            $seoDescription = $seoMap[$currentRoute]['description'];
        }

        // Altrimenti, prova a dedurlo da segmenti dinamici (es. servizi, escursioni singole)
        else {
            $pathInfo = request()->getPathInfo();
            $segments = array_values(array_filter(explode('/', $pathInfo)));

            if (isset($segments[1])) {
                $title = urldecode($segments[1]);
                $seoTitle = Str::title(str_replace('-', ' ', $title)) . ' | Tranchida Transfer Trapani';
            } else {
                $seoTitle = 'Tranchida Transfer Trapani| Servizi di Transfer, Taxi, Noleggio Auto ed Escursioni';
            }

            $seoDescription =
                'Scopri di più su ' .
                Str::title(str_replace('-', ' ', $title ?? 'Tranchida Transfer Trapani')) .
                ' a Trapani e prenota online i tuoi servizi di trasporto o escursione';
        }
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Tranchida Transfer Trapani">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('cookie-consent::index')
</head>

<body class="overflow-x-hidden bg-e">
    <x-whatsapp :lazy-loading />
    <x-nav lazy />
    <x-masthead />
    <div id="mainContent" data-currentRoute="{{ $currentRoute }}" class="overflow-hidden mt-5">
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
            console.error('Elemento #mainContent non trovato');
            return;
        }

        // // Recupera il valore di data-currentRoute
        // const currentRoute = mainContent.getAttribute('data-currentRoute');
        // const displayInfo = document.getElementById('display-info');
        // // Logica di scrolling
        // if (currentRoute === 'home' || displayInfo) {
        //     scrollToTop();
        // } else {
        //     mainContent.scrollIntoView({
        //         behavior: 'smooth',
        //         block: 'start'
        //     });
        // }
    });
</script>

</html>
