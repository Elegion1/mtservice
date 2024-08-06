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

        <nav class="navbar navbar-expand-lg bg_nav border_custom shadow z-3 d-flex flex-column mb-5">
            <div class="container">
                <p>Dashboard</p>
                <p>Benvenuto: {{ Auth::user()->email }}</p>
                <button class="navbar-toggler p-0 border-0 " type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="container d-block d-lg-none">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul
                            class="navbar-nav d-flex align-items-center justify-content-center flex-wrap mx-auto mb-2 mb-lg-0">
                            @if (Auth::user()->name == 'Admin')
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.route') }}">Tratte</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.destination') }}">Destinazioni</a>

                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.excursion') }}">Escursioni</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.car') }}">Auto</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.review') }}">Recensioni</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.booking') }}">Prenotazioni</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.bookingList') }}">Lista
                                            prenotazioni</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.contact') }}">Messaggi</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.service') }}">Servizi</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.partner') }}">Partners</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.content') }}">Contenuto</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.page') }}">Pagine</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.ownerData') }}">Dati
                                        azienda</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none" target="_blank"
                                        href="{{ route('pdf') }}">Vista PDF</a>
                                </li>
                                <li class="py-1 text-center">
                                    <a class="my-3 text-decoration-none" target="_blank"
                                        href="{{ route('home') }}">Torna
                                        al sito</a>
                                </li>
                                <li class="py-1 text-center">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="btn text-primary" type="submit"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li class="p-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.route') }}">Tratte</a>
                                </li>
                                <li class="p-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.destination') }}">Destinazioni</a>

                                </li>
                                <li class="p-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.car') }}">Auto</a>
                                </li>
                                <li class="p-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.bookingList') }}">Prenotazioni</a>
                                </li>
                                <li class="p-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.contact') }}">Messaggi</a>
                                </li>
                                <li class="p-1 text-center">
                                    <a class="my-3 text-decoration-none"
                                        href="{{ route('dashboard.ownerData') }}">Dati
                                        azienda</a>
                                </li>
                                <li class="p-1 text-center">
                                    <a class="my-3 text-decoration-none" target="_blank"
                                        href="{{ route('home') }}">Torna
                                        al sito</a>
                                </li>
                                <li class="p-1 text-center">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="btn text-primary p-0" type="submit"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container-fluid d-none d-lg-block">
                <ul
                    class="navbar-nav d-block d-flex align-items-center justify-content-center flex-wrap mx-auto mb-2 mb-lg-0">
                    @if (Auth::user()->name == 'Admin')
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.route') }}">Tratte</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.destination') }}">Destinazioni</a>

                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.excursion') }}">Escursioni</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.car') }}">Auto</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.review') }}">Recensioni</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.booking') }}">Prenotazioni</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none" href="{{ route('dashboard.bookingList') }}">Lista
                                    prenotazioni</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.contact') }}">Messaggi</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.service') }}">Servizi</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.partner') }}">Partners</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.content') }}">Contenuto</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.page') }}">Pagine</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.ownerData') }}">Dati
                                azienda</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none" target="_blank" href="{{ route('pdf') }}">Vista
                                    PDF</a>
                        </li>
                        <li class="border py-1 text-center">
                            <a class="my-3 text-decoration-none" target="_blank"
                                href="{{ route('home') }}">Torna
                                al sito</a>
                        </li>
                        <li class="border py-1 text-center">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn text-primary" type="submit">Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="p-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.route') }}">Tratte</a>
                        </li>
                        <li class="p-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.destination') }}">Destinazioni</a>

                        </li>
                        <li class="p-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.car') }}">Auto</a>
                        </li>
                        <li class="p-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.bookingList') }}">Prenotazioni</a>
                        </li>
                        <li class="p-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.contact') }}">Messaggi</a>
                        </li>
                        <li class="p-1 text-center">
                            <a class="my-3 text-decoration-none"
                                href="{{ route('dashboard.ownerData') }}">Dati
                                azienda</a>
                        </li>
                        <li class="p-1 text-center">
                            <a class="my-3 text-decoration-none" target="_blank"
                                href="{{ route('home') }}">Torna
                                al sito</a>
                        </li>
                        <li class="p-1 text-center">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn text-primary p-0" type="submit"></i>Logout
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        <div class="container-fluid">
            <x-display-error />
            <x-display-message />
            <x-display-success />
            {{ $slot }}
        </div>
    @endauth
    @guest
        {{ $slot }}
    @endguest
</body>

</html>
