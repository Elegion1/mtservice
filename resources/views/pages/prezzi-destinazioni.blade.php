<x-layout>
    <div class="row">
        <div class="col-12">
            <div class="container bg-white rounded border_custom shadow">
                <livewire:prenotazione />
            </div>
            <div class="container bg-white rounded p-3 mb-3">
                <x-show-content :pagine="$pagine" />
            </div>
        </div>
        <div class="col-12">
            <div class="container bg-white rounded shadow p-3">
                <x-lista-tratte />
            </div>
            <div class="container my-5">
                <x-contact-link />
            </div>
        </div>
        <div class="col-12 mt-5">
            <h2 class="text-center">{{ __('ui.title2') }}</h2>
            <x-services />
        </div>
        <div class="col-12 mt-5">
            <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
            <x-excursions />
        </div>
    </div>


</x-layout>
