<!-- resources/views/components/user-navigation-menu.blade.php -->
<ul class="navbar-nav d-flex align-items-center justify-content-center flex-wrap mx-auto mb-2 mb-lg-0">
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.route') }}">Tratte</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.destination') }}">Destinazioni</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.car') }}">Auto</a>
    </li>
    <li class="p-1 text-center">
        <a class="text-decoration-none" href="{{ route('dashboard.bookingList') }}">Prenotazioni</a>
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
        <a class="text-decoration-none" href="{{ route('dashboard.ownerData') }}">Dati azienda</a>
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