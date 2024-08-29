<!DOCTYPE html>
<html lang="it">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-M5SQ98ZHWM"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-M5SQ98ZHWM');
    </script>
    <meta name="description" lang="it"
        content="Noleggio auto, Escursioni e Transfer per la provincia di Trapani. Taxi Transfer da e per tutti gli aeroporti e porti della Sicilia. Aeroporto di Trapani, Aeroporto di Palermo, Aeroporto di Catania. Escursioni in tutta la Sicilia. Noleggio Auto per la tua vacanza. Taxi Favignana, Taxi Trapani, Taxi Marsala, Taxi Aeroporto.">
    <meta name="description" lang="en"
        content="Car rental, excursions, and transfers for the province of Trapani. Taxi transfers to and from all airports and ports in Sicily. Trapani Airport, Palermo Airport, Catania Airport. Excursions throughout Sicily. Car rental for your vacation. Taxi Favignana, Taxi Trapani, Taxi Marsala, Taxi Airport.">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" sizes="32x32" href="https://tranchidatransfer.it/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('cookie-consent::index')
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
                    break; // Exit the loop if match is found
                @endphp
            @endif
        @endforeach
        @if (empty($title))
            @php
                // Ottenere il valore di PATH_INFO
                $pathInfo = request()->getPathInfo();
                // Suddividere il percorso in parti
                $segments = explode('/', $pathInfo);
                // Estrarre la parte desiderata (in questo caso, la seconda parte)
                if (isset($segments[2])) {
                    $title = urldecode($segments[2]);
                    $type = urldecode($segments[1]);
                }
            @endphp
        @endif
        @if ($title)
            {{ $title }} |
        @endif
        @if ($type)
            {{ ucfirst($type) }} |
        @endif
        TRANCHIDA Transfer & Rent Trapani Taxi
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <x-whatsapp />
    <x-nav />
    <x-masthead />
    <div class="position-absolute masthead-position">
        <div class="container-fluid">
            <x-display-error />
            <x-display-message />
            {{ $slot }}
        </div>
        <div class="border_footer p-0 m-0 bg-linear-gradient">
            <x-footer />
        </div>
    </div>


</body>

</html>
