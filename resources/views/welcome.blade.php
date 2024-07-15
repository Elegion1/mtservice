<x-layout>

    <div class="row">

        <div class="col-12 p-0">
            <div class="container bg-white rounded p-md-3 border_custom">
                <livewire:prenotazione />
            </div>
            <div class="container my-3">
                <x-contact-link />
            </div>
            <div class="container-fluid bg-c rounded p-3">
                <p class="h2 text-center">{{ __('ui.title1') }}</p>
                <x-lista-tratte />
            </div>
            <div class="container-fluid p-3">
                <h1 class="text-center ">{{ __('ui.title2') }}</h1>
                <x-services />
            </div>
            <div class="container-fluid bg-c p-3 mt-3">
                <h1 class="text-center ">{{ __('ui.title3') }}</h1>
                <x-excursions />
            </div>
            <div class="container-fluid p-3 d-flex justify-content-center align-items-center flex-column">
                <x-show-content :pagine="$pagine" />
            </div>
        </div>
    </div>
</x-layout>
