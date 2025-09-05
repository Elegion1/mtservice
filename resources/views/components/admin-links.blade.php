<ul class="navbar-nav d-flex align-items-center justify-content-center flex-wrap mx-1 mb-2 mb-lg-0">
    <li>
        <div class="dropdown">
            <button class="btn text-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Transfer
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('dashboard.route') }}">Tratte</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.destination') }}">Destinazioni</a></li>
            </ul>
        </div>
    </li>
    <li>
        <div class="dropdown">
            <button class="btn text-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Escursioni
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('dashboard.excursion') }}">Escursioni</a></li>
            </ul>
        </div>
    </li>
    <li>
        <div class="dropdown">
            <button class="btn text-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Noleggio auto
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('dashboard.car') }}">Auto</a></li>
            </ul>
        </div>
    </li>
    <li>
        <div class="dropdown">
            <button class="btn text-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Prenotazioni
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('dashboard.booking') }}">Prenotazioni</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.bookingList') }}">Lista prenotazioni</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.customer') }}">Clienti</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.discount') }}">Sconti</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.contact') }}">Messaggi</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.review') }}">Recensioni</a></li>
            </ul>
        </div>
    </li>
    <li>
        <div class="dropdown">
            <button class="btn text-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Contenuti
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('dashboard.service') }}">Servizi</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.partner') }}">Partners</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.content') }}">Contenuto</a></li>
            </ul>
        </div>
    </li>
    <li>
        <div class="dropdown">
            <button class="btn text-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Gestione sito
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('dashboard.page') }}">Pagine</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.ownerData') }}">Dati azienda</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.users') }}">Utenti</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.settings') }}">Impostazioni</a></li>
                <li><a class="dropdown-item" href="{{ route('visits.dashboard') }}">Statistiche Visite</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.logs') }}">Logs</a></li>
            </ul>
        </div>
    </li>
    <li>
        <a class="text-decoration-none p-1" href="{{ route('dashboard.testing') }}">TEST</a>
    </li>


    <li class="p-1 text-center">
        <a class="text-decoration-none" target="_blank" href="{{ route('home', ['locale' => 'it']) }}">Torna al
            sito</a>
    </li>
    <li class="p-1 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn text-primary" type="submit">Logout</button>
        </form>
    </li>
</ul>
