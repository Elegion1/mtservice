<header class="position-relative">
    <div class="masthead">
        @php
            use Carbon\Carbon;
            $now = Carbon::now();
            $currentRoute = Route::currentRouteName();
            $defaultContent = null;
            $displayedContent = null;

            // Cerca il contenuto specifico per la pagina o quello predefinito
            // @dd($contents);
            foreach ($contents as $content) {
                if ($content->order == 0 && $content->show) {
                    if (
                        !empty($content->{'title_' . app()->getLocale()}) ||
                        !empty($content->{'subtitle_' . app()->getLocale()})
                    ) {
                        // Controllo validità della data
                        $isValidDate =
                            (!$content->start_date || $content->start_date <= $now) &&
                            (!$content->end_date || $content->end_date >= $now);

                        // @dd($content->start_date, $content->end_date, $isValidDate);
                        // Contenuto specifico per la pagina
                        if ($content->page && $content->page->link == $currentRoute && $isValidDate) {
                            $displayedContent = $content;
                            break; // Contenuto specifico trovato, interrompi il ciclo
                        }

                        // Contenuto predefinito (senza pagina associata)
                        if (!$content->page && !$defaultContent) {
                            $defaultContent = $content;
                        }
                    }
                }
            }

            // Usa il contenuto predefinito se nessun altro contenuto è stato trovato
            if (!$displayedContent) {
                $displayedContent = $defaultContent;
            }
        @endphp

        <div class="container-fluid position-absolute mt-5 pos_masthead">
            <div class="row">
                <div id="headerContainer" class="col-12 col-lg-6">
                    @if ($displayedContent)
                        <div id="headerText" class="text-wrap my-5 mx-3 mx-md-0 mt-md-0">
                            <h1 class="text-b text-shadow text-responsive">
                                {!! strtoupper($displayedContent->{'title_' . app()->getLocale()}) !!}
                            </h1>
                            <h2 class="text-shadow text-c btn_font_size">
                                {{ $displayedContent->{'subtitle_' . app()->getLocale()} }}
                            </h2>
                        </div>
                    @endif
                    <x-display-error />
                    <x-display-message />
                </div>
                <div id="headerBooking" class="col-12 col-lg-6 p-0 d-flex justify-content-center align-items-start">
                    <livewire:prenotazione />
                </div>
            </div>
        </div>

        {{-- Gradiente e immagine --}}
        <div class="gradient-overlay"></div>
        @if ($displayedContent && $displayedContent->images->isNotEmpty())
            <img class="img_car" src="{{ Storage::url($displayedContent->images->first()->path) }}" alt="HEADER-IMG">
        @else
            <img class="img_car"
                src="https://tranchidatransfer.it/storage/images/XLwFNr204aSLbrGfbAQc3wJ5eq8emjznHq1X4ucK.jpg"
                alt="HEADER-IMG-Default">
        @endif
    </div>

</header>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const headerContainer = document.querySelector('#headerContainer');
        const headerText = document.querySelector('#headerText');
        const headerBooking = document.querySelector('#headerBooking');

        // Funzione per applicare le classi in base alla dimensione dello schermo
        const applyClasses = () => {
            if (window.innerWidth >= 992) {
                headerContainer.classList.add('position-relative');
                headerText.classList.add('position-absolute', 'header_text_position');
                headerText.classList.remove('text-center');

            } else {
                headerContainer.classList.remove('position-relative');
                headerText.classList.remove('position-absolute', 'header_text_position');
                headerText.classList.add('text-center');

            }
        };

        // Applica le classi al caricamento della pagina
        applyClasses();

        // Riassegna le classi quando la finestra viene ridimensionata
        window.addEventListener('resize', applyClasses);
    });
</script>
