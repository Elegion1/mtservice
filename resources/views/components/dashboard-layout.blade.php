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
    <div class="container-fluid">
        <div class="row">
            <div class="col-1">
                <nav>
                    <div class=" my-5">
                        <ul class="d-flex align-items-start list-unstyled flex-column">
                            <li>
                                <a class="btn btn-danger my-3" href="{{ route('dashboard.route') }}">Tratte</a>
                            </li>
                            <li>
                                <a class="btn btn-danger my-3" href="{{ route('dashboard.destination') }}">Destinazioni</a>

                            </li>
                            <li>
                                <a class="btn btn-danger my-3" href="{{ route('dashboard.excursion') }}">Escursioni</a>
                            </li>
                            <li>
                                <a class="btn btn-danger my-3" href="{{ route('dashboard.car') }}">Auto</a>
                            </li>
                            <li>
                                <a class="btn btn-danger my-3" href="{{route('home')}}">Torna al sito</a>
                            </li>
                        </ul>
                    </div>

                </nav>
            </div>
            <div class="col-11">
                {{ $slot }}
            </div>
        </div>
    </div>


</body>

</html>
