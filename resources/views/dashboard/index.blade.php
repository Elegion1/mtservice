<x-dashboard-layout>

    <h1 class="text-center">Dashboard</h1>
    <div class="container-fluid container-md mt-5 d-flex justify-content-between align-items-center flex-wrap">
        <x-dashboard-button route="dashboard.bookingList" label="Prenotazioni" :count="$bookings->count()" />
        <x-dashboard-button route="dashboard.route" label="Tratte" />
        <x-dashboard-button route="dashboard.review" label="Recensioni" :count="$reviews->where('status', 'pending')->count()" />
        <x-dashboard-button route="dashboard.excursion" label="Escursioni" />
        <x-dashboard-button route="dashboard.customer" label="Clienti" />
        <x-dashboard-button route="dashboard.car" label="Auto" />
        <x-dashboard-button route="dashboard.contact" label="Messaggi" :count="$contacts->where('read', 0)->count()" />
    </div>
    <div class="container mt-5 d-flex flex-column align-items-end">
        <x-dashboard-button route="home" :params="['locale' => 'it']" label="Torna al sito" />
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                class="btn text-primary border rounded p-3 bg-secondary-subtle mt-3 d-flex flex-column align-items-center"
                type="submit">Logout</button>
        </form>
    </div>

</x-dashboard-layout>
