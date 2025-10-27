<x-layout>
    <div class="container-fluid">
        {{-- <h1> già incluso nel masthead --}}

        {{-- Sezione 1: servizi in evidenza --}}
        <h2 class="text-center text-uppercase mt-5 mt-md-3">{{ __('ui.serviceHighlightTitle') }}</h2>
        <div class="d-flex justify-content-center align-items-start flex-wrap flex-md-nowrap mt-3">
            <x-services-overview />
        </div>
    </div>

    <div class="container">
        <div class="row">

            {{-- Sezione 2: contenuto informativo --}}
            <div class="col-12 mt-5">
                <div class="text-center">
                    {{-- se x-show-content ha già heading, assicurati che siano h3 --}}
                    <x-show-content :pagine="$pagine" />
                </div>
                <div class="container my-3">
                    <x-contact-link />
                </div>
            </div>

            {{-- Sezione 3: tratte --}}
            <div class="col-12 col-lg-6">
                <div class="container-fluid p-3">
                    <h2 class="text-center text-uppercase">{{ __('ui.title1') }}</h2>
                    <x-lista-tratte :tratte="$tratte" />
                    <div class="d-flex justify-content-center align-items-center">
                        <a class="text-decoration-none border rounded p-2 bg-d text-white text-uppercase"
                            style="font-weight: 200"
                            href="{{ route('prezziDestinazioni') }}">{{ __('ui.allDestinations') }}</a>
                    </div>
                </div>
            </div>

            {{-- Sezione 4: servizi --}}
            <div class="col-12 col-lg-6">
                <div class="container-fluid p-3">
                    <a class="text-decoration-none text-reset" href="{{ route('services.index') }}">
                        <h2 class="text-center text-uppercase">{{ __('ui.title2') }}</h2>
                    </a>
                    <x-services />
                </div>
            </div>

            {{-- Sezione 5: escursioni --}}
            <div class="col-12">
                <div class="container-fluid p-3">
                    <a class="text-decoration-none text-reset" href="{{ route('escursioni') }}">
                        <h2 class="text-center text-uppercase">{{ __('ui.title3') }}</h2>
                    </a>
                    <x-excursions />
                </div>
            </div>

        </div>
    </div>
</x-layout>
