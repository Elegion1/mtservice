<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TRANCHIDA Transfer & Rent</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <x-nav />
    <x-masthead />

    <div class="position-absolute masthead-position">
        <div class="container-fluid">
            <x-display-error />
            <x-display-message />
            {{ $slot }}
        </div>
    </div>
    <div class="altezza"></div>
    <x-footer />

</body>

</html>
