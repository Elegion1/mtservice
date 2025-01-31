<x-dashboard-layout>
    <h1 class="text-center">Dashboard</h1>
    <div class="container-fluid mt-5 d-flex justify-content-between align-items-center flex-wrap">
        <x-dashboard-button route="dashboard.bookingList" label="Prenotazioni" :count="$bookings->count()" />
        <x-dashboard-button route="dashboard.route" label="Tratte" />
        <x-dashboard-button route="dashboard.review" label="Recensioni" :count="$reviews->where('status', 'pending')->count()" />
        <x-dashboard-button route="dashboard.customer" label="Clienti" />
        <x-dashboard-button route="dashboard.contact" label="Messaggi" :count="$contacts->where('read', 0)->count()" />
        <x-dashboard-button route="dashboard.car" label="Auto" />
    </div>
    <div class="container mt-5 d-flex justify-content-end">
        <x-dashboard-button route="home" :params="['locale' => 'it']" label="Torna al sito" />
    </div>
</x-dashboard-layout>