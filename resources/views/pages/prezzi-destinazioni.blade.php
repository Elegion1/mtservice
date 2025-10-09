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
            <div class="p-3 pb-0">
                <x-show-content :pagine="$pagine" />
            </div>
            <div class="p-3 pt-0">

                @php
                    $tratteByDeparture = $tratte->groupBy('departure.name');
                @endphp

                <div class="accordion" id="tratteAccordion">
                    @foreach ($tratteByDeparture as $departure => $tratte)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ Str::slug($departure) }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ Str::slug($departure) }}" aria-expanded="false"
                                    aria-controls="collapse{{ Str::slug($departure) }}">
                                    {{ __('ui.from') }} {{ $departure }}
                                </button>
                            </h2>
                            <div id="collapse{{ Str::slug($departure) }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ Str::slug($departure) }}" data-bs-parent="#tratteAccordion">
                                <div class="accordion-body">
                                    @foreach ($tratte as $tratta)
                                        @php
                                            $locale = App::getLocale();
                                            $departureSlug = $tratta->departure->slug;
                                            $arrivalSlug = $tratta->arrival->slug;
                                        @endphp
                                        
                                        <div
                                            class="d-flex justify-content-between align-items-center border-bottom py-2">
                                            <a href="{{ route('transfer.show', ['locale' => $locale, 'departure' => $departureSlug, 'arrival' => $arrivalSlug]) }}"
                                                class="text-decoration-none text-dark d-block">
                                                <p class="m-0">
                                                    {{ __('ui.to') }} <span
                                                        class="fw-bold">{{ $tratta->arrival->name }}</span> <br>
                                                    {{ __('ui.from') }}
                                                    <span class="text-d">€ {{ $tratta->price }}</span>
                                                    {{ __('ui.perPerson') }}
                                                </p>
                                            </a>
                                            <button class="btn btn-sm bg-a text-white"
                                                data-tratta-id="{{ $tratta->id }}"
                                                data-departure="{{ $tratta->departure->id }}"
                                                data-arrival="{{ $tratta->arrival->id }}"
                                                onclick="selezionaTratta(this)">
                                                {{ __('ui.select') }}
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
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
