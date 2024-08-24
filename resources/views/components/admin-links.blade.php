<!-- resources/views/components/admin-navigation-menu.blade.php -->
<ul class="navbar-nav d-flex align-items-center justify-content-center flex-wrap mx-1 mb-2 mb-lg-0">
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.route') }}">Tratte</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.destination') }}">Destinazioni</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.excursion') }}">Escursioni</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.car') }}">Auto</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.review') }}">Recensioni</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.booking') }}">Prenotazioni</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.bookingList') }}">Lista prenotazioni</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.customer') }}">Clienti</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.discount') }}">Sconti</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.contact') }}">Messaggi</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.service') }}">Servizi</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.partner') }}">Partners</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.content') }}">Contenuto</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.page') }}">Pagine</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.ownerData') }}">Dati azienda</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" target="_blank" href="{{ route('pdf') }}">Vista PDF</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" target="_blank" href="{{ route('home') }}">Torna al sito</a>
    </li>
    <li class="p-1 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn text-primary" type="submit">Logout</button>
        </form>
    </li>
</ul>
