<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-head.tinymce-config />
</head>

<body>
    @auth
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-1 position-fixed">
                    <nav>
                        <p>
                            Benvenuto: {{ Auth::user()->email }}</p>
                        <div class=" mt-3">
                            <ul
                                class="d-flex align-items-start list-unstyled flex-md-column justify-content-center justify-content-md-evenly">
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none" href="{{ route('dashboard.route') }}">Tratte</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.destination') }}">Destinazioni</a>

                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.excursion') }}">Escursioni</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none" href="{{ route('dashboard.car') }}">Auto</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.review') }}">Recensioni</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.booking') }}">Prenotazioni</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.contact') }}">Messaggi</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none" href="{{ route('dashboard.service') }}">Servizi</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.partner') }}">Partners</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.content') }}">Contenuto</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none" href="{{ route('dashboard.page') }}">Pagine</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none" href="{{ route('dashboard.ownerData') }}">Dati
                                        azienda</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none" target="_blank" href="{{ route('pdf') }}">Vista
                                        PDF</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <a class="my-3 text-decoration-none" target="_blank" href="{{ route('home') }}">Torna
                                        al sito</a>
                                </li>
                                <li class="border rounded py-2 mb-1 text-center w-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="btn text-primary" type="submit"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                    </nav>
                </div>
                <div class="col-1">

                </div>
                <div class="col-11">
                    <x-display-error />
                    <x-display-message />
                    <x-display-success />
                    {{ $slot }}
                </div>
            </div>
        </div>
    @endauth
    @guest
        {{ $slot }}
    @endguest
</body>

</html>
