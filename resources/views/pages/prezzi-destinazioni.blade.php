<x-layout>
    <div class="row p-0" id="prenotazionediv">
        <div class="col-12 col-lg-6">

            <div class="container my-5">
                <x-contact-link />
            </div>

            <div class="container sticky-top top_custom">
                <h2 class="text-center">{{ __('ui.title2') }}</h2>
                <x-services />
            </div>
        </div>
        <div class="col-12 col-lg-6">
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
                                    {{ __('ui.select') }}
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
            let transferButton = document.querySelector('#transfer-btn');
            const MAX_EXECUTION_TIME = 5000; // Tempo massimo in millisecondi (5 secondi)
            let executionTimedOut = false;

            window.selezionaTratta = function(button) {

                // Imposta un timeout globale
                let globalTimeout = setTimeout(() => {
                    executionTimedOut = true;
                    console.error('Tempo massimo di esecuzione superato.');
                }, MAX_EXECUTION_TIME);

                // Funzione per attendere la comparsa del componente nel DOM
                function waitForComponent(selector, callback) {
                    let interval = setInterval(() => {
                        if (executionTimedOut) {
                            clearInterval(interval);
                            return; // Interrompi se il timeout globale è stato raggiunto
                        }

                        let element = document.querySelector(selector);
                        if (element) {
                            clearInterval(interval);
                            callback(element);
                        }
                    }, 100); // Controlla ogni 100ms
                }

                // Simula il clic per mostrare il componente
                transferButton.click();

                // Attendi che il componente sia disponibile
                waitForComponent('#transferForm', function(component) {
                    if (executionTimedOut) return; // Interrompi se il timeout globale è stato raggiunto

                    // console.log('Componente trovato:', component);

                    let trattaId = button.getAttribute('data-tratta-id');
                    let departure = button.getAttribute('data-departure');
                    let arrival = button.getAttribute('data-arrival');

                    // Trova gli elementi select all'interno del componente Livewire
                    let departureSelect = component.querySelector('#departureSelect');
                    let arrivalSelect = component.querySelector('#returnSelect');

                    if (departureSelect && arrivalSelect) {
                        // Funzione per selezionare un'opzione
                        function selectOption(select, value) {
                            if (executionTimedOut)
                        return; // Interrompi se il timeout globale è stato raggiunto

                            let option = Array.from(select.options).find(opt => opt.value === value);
                            if (option) {
                                select.value = option.value;
                                select.dispatchEvent(new Event('change'));
                            } else {
                                console.error(`Opzione ${value} non trovata.`);
                            }
                        }

                        // Seleziona la partenza
                        selectOption(departureSelect, departure);

                        // Dopo un breve ritardo, seleziona l'arrivo
                        setTimeout(() => {
                            if (executionTimedOut)
                        return; // Interrompi se il timeout globale è stato raggiunto

                            selectOption(arrivalSelect, arrival);
                            // console.log('Partenza e arrivo selezionati:', departure, arrival);

                            // Scorri fino al componente
                            scrollToComponent('#transferForm');

                            // Cancella il timeout globale poiché l'operazione è completata
                            clearTimeout(globalTimeout);
                        }, 500); // Ritardo di 500ms
                    } else {
                        console.error('Gli elementi select non sono stati trovati.');
                    }
                });
            };

            function scrollToComponent(selector) {
                if (executionTimedOut) return; // Interrompi se il timeout globale è stato raggiunto

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
