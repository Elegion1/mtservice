<x-layout>
    <div class="row p-0" id="prenotazionediv">
        <div class="col-12 col-md-6">

            <div class="container my-5">
                <x-contact-link />
            </div>

            <div class="container sticky-top top_custom">
                <h2 class="text-center">{{ __('ui.title2') }}</h2>
                <x-services />
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="container bg-white rounded p-3">
                <x-show-content :pagine="$pagine" />
            </div>
            <div class="container-fluid bg-white p-1">
                @foreach ($dest as $tratta)
                    <div class="container text-start text-wrap">
                        <div class="row">
                            <div class="col-3 d-flex justify-content-end align-items-center">
                                <button class="btn btn-sm bg-a text-white" data-tratta-id="{{ $tratta->id }}"
                                    data-departure="{{ $tratta->departure->id }}"
                                    data-arrival="{{ $tratta->arrival->id }}" onclick="selezionaTratta(this)">
                                    Prenota
                                </button>
                            </div>
                            <div class="col-9">
                                <p class="h6">{{ __('ui.from') }}
                                    <span class="text_col">{{ $tratta->departure->name }}</span>
                                    <br class="d-block d-md-none">
                                    {{ __('ui.to') }}
                                    <span class="text_col">{{ $tratta->arrival->name }}</span>
                                </p>
                                <p>{{ __('ui.priceStartingFrom') }} <span class="text-d">€ {{ $tratta->price }}
                                    </span>{{ __('ui.perPerson') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
           
        </div>
        <div class="col-12 mt-5">
            <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
            <x-excursions />
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.selezionaTratta = function(button) {
                let trattaId = button.getAttribute('data-tratta-id');
                let departure = button.getAttribute('data-departure');
                let arrival = button.getAttribute('data-arrival');

                // Trova il componente Livewire
                let component = document.querySelector('#transferForm');

                if (component) {
                    // Trova gli elementi select all'interno del componente Livewire
                    let departureSelect = component.querySelector('#departureSelect');
                    let arrivalSelect = component.querySelector('#returnSelect');

                    if (departureSelect && arrivalSelect) {
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
                        clickAndSelect(departureSelect, departure, function() {
                            // Dopo aver selezionato la partenza, seleziona il ritorno
                            clickAndSelect(arrivalSelect, arrival, function() {
                                console.log('Partenza e ritorno selezionati.');
                            });
                            scrollToComponent('#prenotazionediv');
                        });
                    } else {
                        console.error('Gli elementi select non sono stati trovati.');
                    }
                } else {
                    console.error('Componente Livewire non trovato.');
                }
            };

            function scrollToComponent(selector) {
                let element = document.querySelector(selector);
                if (element) {
                    element.scrollIntoView({
                        behavior: 'smooth'
                    });
                } else {
                    console.error('Componente non trovato: ' + selector);
                }
            }
        });
    </script>
</x-layout>
