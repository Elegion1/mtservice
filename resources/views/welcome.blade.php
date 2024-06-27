<x-layout>

    <div class="row">
        <div class="col-12 p-0">
            <div class="container bg-white rounded p-md-3">
                <livewire:prenotazione />
            </div>
            <div class="container my-3">
                <x-contact-link />
            </div>
            <div class="container-fluid bg-white bg-secondary-subtle rounded p-3">
                <p class="h2 text-center">Le tratte più frequenti</p>
                <x-lista-tratte />
            </div>
            <div class="container-fluid p-3">
                <h1 class="text-center ">I nostri servizi</h1>
                <x-services />
            </div>
            <div class="container-fluid bg-secondary-subtle p-3 mt-3">
                <h1 class="text-center ">Le nostre escursioni</h1>
                <x-excursions />
            </div>
        </div>
    </div>
</x-layout>
