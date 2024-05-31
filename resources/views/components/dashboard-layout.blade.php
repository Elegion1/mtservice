<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <nav>
        <div class="container px-5 py-1">
            <ul class="d-flex align-items-center justify-content-evenly list-unstyled">
                <li><a class="btn btn-danger" href="{{ route('dashboard.route') }}">Rotte</a></li>
                <li><a class="btn btn-danger" href="{{ route('dashboard.destination') }}">Destinazioni</a></li>
                <li><a class="btn btn-danger" href="{{ route('dashboard.excursion') }}">Escursioni</a></li>
                <li><a class="btn btn-danger" href="">link</a></li>
            </ul>
        </div>

    </nav>
    {{ $slot }}
</body>

</html>
