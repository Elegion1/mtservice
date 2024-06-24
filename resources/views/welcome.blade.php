<x-layout>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="container bg-white rounded shadow p-3">
                <livewire:prenotazione />
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="container bg-white rounded shadow p-3">
                <x-services />
            </div>
        </div>
        <div class="col-12">
            <div class="container bg-white rounded shadow p-3 my-3">
                <p class="h4 text-center">Le tratte più frequenti</p>
                <x-lista-tratte />
            </div>
        </div>
        <div class="col-12 mt-5">
            <div class="container bg-white rounded shadow p-3">
                <h1 class="text-center mb-3">Le nostre escursioni</h1>
                <x-excursions />
            </div>
        </div>
    </div>

</x-layout>
