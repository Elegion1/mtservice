<x-layout>

    <div class="row">

        <div class="col-12 p-0">
            <div class="container-sm bg-white rounded p-md-3 shadow border_custom bg-c">
                <livewire:prenotazione />
            </div>
            <div class="container my-3">
                <x-contact-link />
            </div>
            {{-- 
            <div class="container-fluid bg-c p-3">
                <h2 class="text-center">{{ __('ui.title1') }}</h2>
                <x-lista-tratte />
            </div>
            <div class="container-fluid p-3">
                <h2 class="text-center">{{ __('ui.title2') }}</h2>
                <x-services />
            </div>
            <div class="container-fluid bg-c p-3 mt-3">
                <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
                <x-excursions />
            </div> --}}
            {{-- <div class="container-fluid p-3 d-flex justify-content-center align-items-center flex-column">
                <x-show-content :pagine="$pagine" />
            </div> --}}

        </div>

        <div class="col-12 col-lg-6">
            <div class="container-fluid p-3">
                <h2 class="text-center">{{ __('ui.title1') }}</h2>
                <x-lista-tratte />
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="container-fluid p-3">
                <h2 class="text-center">{{ __('ui.title2') }}</h2>
                <x-services />
            </div>
        </div>
        <div class="col-12">
            <div class="container-fluid p-3">
                <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
                <x-excursions />
            </div>
        </div>
        <div class="col-12">
            <div class="container-fluid p-3 d-flex justify-content-center align-items-center flex-column">
                <x-show-content :pagine="$pagine" />
            </div>
        </div>
    </div>
</x-layout>
