<x-layout>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="container rounded bg-white border_custom shadow">
                <div class="container p-3">
                    <livewire:escursioni-form />
                </div>
            </div>

            <div class="container rounded p-3 mt-3">
                <x-contact-link />
                <x-show-content :pagine="$pagine" />
            </div>
        </div>

        <div class="col-12 col-md-6 ">
            <div id="escursioni"
                class="container d-flex justify-content-center align-items-center flex-column rounded bg-white">
                <h2 class="my-3">{{ __('ui.excursionPageTitle') }}</h2>

                @foreach ($excursionsP as $excursion)
                    <div class="card border-0 mb-3" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex justify-content-center align-items-center">
                                @if ($excursion->images->isNotEmpty())
                                    <img src="{{ Storage::url($excursion->images->first()->path) }}"
                                        class="img-fluid rounded-start" alt="...">
                                @else
                                    <img src="https://picsum.photos/100{{ $excursion->id }}"
                                        class="img-fluid rounded-start" alt="immagine non disponibile">
                                @endif
                            </div>

                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title text-d">{{ $excursion->{'name_' . app()->getLocale()} }}</h5>
                                    <small>{{ __('ui.duration') }} {{ $excursion->duration }}
                                        @if ($excursion->duration == 1)
                                            {{ __('ui.hour') }}
                                        @else
                                            {{ __('ui.hours') }}
                                        @endif
                                        {{ __('ui.approx') }}
                                    </small>
                                    <p class="card-text">{!! $excursion->{'abstract_' . app()->getLocale()} !!}
                                    </p>
                                    {{-- <p class="card-text text-truncate" style="max-height: 80px">{!! $excursion->{'description_' . app()->getLocale()} !!}
                                    </p> --}}
                                    {{-- <div class="row mb-1" style="">
                                        <div class="col-10 text-truncate">
                                            {!! $excursion->{'description_' . app()->getLocale()} !!}
                                        </div>
                                    </div> --}}

                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="m-0">
                                            <small class="text-black">{{ __('ui.priceStartingFrom') }}</small>
                                            <strong class="fs-4 text-a">{{ $excursion->price }} €</strong>
                                        </p>
                                        <div class="d-flex justify-content-around align-items-center">
                                            <a class="btn rounded-4 bg-a text-white btn-sm me-1"
                                                href="{{ route('excursion.show', ['name_it' => $excursion->name_it, 'id' => $excursion->id]) }}">{{ __('ui.details') }}</a>
                                            <button class="btn rounded-4 bg-a text-white btn-sm"
                                                data-escursione-id="{{ $excursion->id }}"
                                                onclick="selezionaEscursione(this)">
                                                Prenota
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Controlli di paginazione -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    {{ $excursionsP->links('vendor.pagination.bootstrap-5') }}
                </div>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.selezionaEscursione = function(button) {
                let escursioneId = button.getAttribute('data-escursione-id');

                // Trova il componente Livewire
                let component = document.querySelector('#excursionForm')
                if (component) {
                    // Trova gli elementi select all'interno del componente Livewire
                    let excursionSelect = component.querySelector('#excursionSelect');
                    

                    if (excursionSelect) {
                        // Funzione per selezionare un'opzione dopo che le opzioni sono state caricate
                        function selectOption(select, value) {
                            let option = Array.from(select.options).find(opt => opt.value === value);
                            if (option) {
                                select.value = option.value;
                                select.dispatchEvent(new Event('change'));
                            } else {
                                console.error(`Opzione ${value} non trovata.`);
                            }
                        }

                        // Simula il clic per caricare le opzioni del menu a discesa e poi seleziona l'opzione desiderata
                        function clickAndSelect(select, value, callback) {
                            select.click();
                            setTimeout(function() {
                                selectOption(select, value);
                                if (callback) callback();
                            }, 500); // Tempo di attesa per assicurarsi che le opzioni siano caricate
                        }

                        // Seleziona prima la partenza
                        clickAndSelect(excursionSelect, escursioneId, function() {
                            // Dopo aver selezionato la partenza, seleziona il ritorno
                            
                        });
                    } else {
                        console.error('Gli elementi select non sono stati trovati.');
                    }
                } else {
                    console.error('Componente Livewire non trovato.');
                }
            };
        });
    </script>
</x-layout>
