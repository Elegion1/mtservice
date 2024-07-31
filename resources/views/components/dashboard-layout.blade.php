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
                @if (Auth::user()->name == 'Admin')
                    <div class=" col-1 position-fixed">
                        <nav>
                            <p>
                                Benvenuto: {{ Auth::user()->email }}</p>
                            <div>
                                <ul
                                    class="d-flex align-items-start list-unstyled flex-column justify-content-center">
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.route') }}"><small>Tratte</small></a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.destination') }}"><small>Destinazioni</small></a>

                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.excursion') }}"><small>Escursioni</small></a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.car') }}"><small>Auto</small></a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.review') }}"><small>Recensioni</small></a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.booking') }}"><small>Prenotazioni</small></a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.contact') }}"><small>Messaggi</small></a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.service') }}"><small>Servizi</small></a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.partner') }}"><small>Partners</small></a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.content') }}"><small>Contenuto</small></a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.page') }}"><small>Pagine</small></a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none"
                                            href="{{ route('dashboard.ownerData') }}"><small>Dati</small>
                                            azienda</a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none" target="_blank"
                                            href="{{ route('pdf') }}"><small>Vista</small>
                                            PDF</a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <a class="my-3 text-decoration-none" target="_blank"
                                            href="{{ route('home') }}"><small>Torna</small>
                                            al sito</a>
                                    </li>
                                    <li class="border py-1 text-center w-100">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button class="btn text-primary" type="submit"></i><small>Logout</small>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <div class="col-1">
                    @else
                        <ul
                            class="d-flex align-items-start list-unstyled justify-content-between text-small flex-wrap">
                            <li class="border p-1 text-center">
                                <a class="my-3 text-decoration-none"
                                    href="{{ route('dashboard.route') }}"><small>Tratte</small></a>
                            </li>
                            <li class="border p-1 text-center">
                                <a class="my-3 text-decoration-none"
                                    href="{{ route('dashboard.destination') }}"><small>Destinazioni</small></a>

                            </li>
                            <li class="border p-1 text-center">
                                <a class="my-3 text-decoration-none"
                                    href="{{ route('dashboard.car') }}"><small>Auto</small></a>
                            </li>
                            <li class="border p-1 text-center">
                                <a class="my-3 text-decoration-none"
                                    href="{{ route('dashboard.bookingList') }}"><small>Prenotazioni</small></a>
                            </li>
                            <li class="border p-1 text-center">
                                <a class="my-3 text-decoration-none"
                                    href="{{ route('dashboard.contact') }}"><small>Messaggi</small></a>
                            </li>
                            <li class="border p-1 text-center">
                                <a class="my-3 text-decoration-none"
                                    href="{{ route('dashboard.ownerData') }}"><small>Dati</small>
                                    azienda</a>
                            </li>
                            <li class="border p-1 text-center">
                                <a class="my-3 text-decoration-none" target="_blank"
                                    href="{{ route('home') }}"><small>Torna</small>
                                    al sito</a>
                            </li>
                            <li class="border p-1 text-center">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="btn text-primary p-0 text-small" type="submit"></i><small>Logout</small>
                                    </button>
                                </form>
                            </li>
                        </ul>
                @endif
            </div>
            <div class="col-lg-11 col-12">
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
