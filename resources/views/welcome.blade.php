<x-layout>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="container bg-white rounded shadow p-3">
                <livewire:prenotazione />
            </div>
            <div class="container my-3">
                <x-contact-link />
            </div>
            <div class="container bg-white rounded shadow p-3">
                <h1 class="text-center mb-3">I nostri servizi</h1>
                <x-services />
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="container bg-white rounded shadow p-3">
                <p class="h2 text-center">Le tratte più frequenti</p>
                <x-lista-tratte />
            </div>
            <div class="container bg-white rounded shadow p-3 mt-3">
                <h1 class="text-center mb-3">Le nostre escursioni</h1>
                <x-excursions />
            </div>
        </div>
    </div>

</x-layout>
