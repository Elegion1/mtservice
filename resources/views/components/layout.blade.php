<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
        {{ $title }} | @if ($type)
            {{ ucfirst($type) }} |
        @endif 
        TRANCHIDA Transfer & Rent
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
