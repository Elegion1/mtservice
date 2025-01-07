<x-dashboard-layout>
    <h1 class="text-center">Dashboard di gestione</h1>
    <div class="container mt-5 d-flex justify-content-between align-items-center flex-wrap">
        <a class="text-decoration-none border rounded p-3 bg-secondary-subtle m-3"
            href="{{ route('dashboard.bookingList') }}">Prenotazioni @if ($bookings->count() > 0)
                <span class="p-1 rounded-circle text-white bg-warning">{{ $bookings->count() }}</span>
            @endif
        </a>
        <a class="text-decoration-none border rounded p-3 bg-secondary-subtle m-3"
            href="{{ route('dashboard.route') }}">Tratte</a>
        <a class="text-decoration-none border rounded p-3 bg-secondary-subtle m-3"
            href="{{ route('dashboard.review') }}">Recensioni @if ($reviews->where('status', 'pending')->count() > 0)
                <span
                    class="p-1 rounded-circle text-white bg-warning">{{ $reviews->where('status', 'pending')->count() }}</span>
            @endif
        </a>
        <a class="text-decoration-none border rounded p-3 bg-secondary-subtle m-3"
            href="{{ route('dashboard.customer') }}">Clienti</a>
        <a class="text-decoration-none border rounded p-3 bg-secondary-subtle m-3"
            href="{{ route('dashboard.contact') }}">Messaggi @if ($contacts->count() > 0)
                <span
                    class="p-1 rounded-circle text-white bg-warning">{{ $contacts->count() }}</span>
            @endif
        </a>
        <a class="text-decoration-none border rounded p-3 bg-secondary-subtle m-3"
            href="{{ route('dashboard.car') }}">Auto</a>

    </div>
    <div class="w-100 mt-5 d-flex justify-content-end">
        <a class="text-decoration-none border rounded p-3 bg-secondary-subtle m-5" target="_blank"
            href="{{ route('home', ['locale' => 'it']) }}">Torna al
            sito</a>
    </div>

</x-dashboard-layout>
