<!DOCTYPE html>
<html lang="en">

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
        Taxi Transfer & Car Rent Trapani | Taxi transfer Aeroporto Palermo e Aeroporto Trapani | Noleggio Auto |
        Escursioni
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('cookie-consent::index')
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
            <div class="d-flex justify-content-center align-items-center">
                {{ Breadcrumbs::render() }}
            </div>
        </div>
        <div class="border_footer p-0 m-0 bg-linear-gradient">
            <x-footer />
        </div>
    </div>


</body>

</html>
