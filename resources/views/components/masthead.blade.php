<header class="position-relative">
    <div class="masthead">
        @php
            use Carbon\Carbon;
            use App\Models\Setting;

            $locale = App::getLocale();
            $now = Carbon::now();
            $currentRoute = Route::currentRouteName();
            $defaultContent = null;
            $displayedContent = null;
            $displayedImage = null;
            $defaultImageSetting = getSetting('default_header_image');
            $defaultImagePath = $defaultImageSetting ?: 'https://picsum.photos/1920/1080';

            // Cerca il contenuto specifico per la pagina o quello predefinito

            foreach ($contents as $content) {
                if ($content->order == 0) {
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
                    if ($content->images->isNotEmpty() && $content->page->link == $currentRoute) {
                        $displayedImage = $content->images->first()->path;
                    }
                }
            }

            // Usa il contenuto predefinito se nessun altro contenuto è stato trovato
            if (!$displayedContent) {
                $displayedContent = $defaultContent;
            }

            if ($locale == 'it') {
                $bookingModuleTitle = 'Prenotazione Transfer, Escursioni e Noleggi Auto';
                $bookingModuleDesc = "Compila il modulo per prenotare il tuo transfer privato, un'escursione o il noleggio auto a
                            Trapani.
                            I nostri servizi sono disponibili in tutta la Sicilia con prezzi trasparenti e conferma
                            immediata.";
            } else {
                $bookingModuleTitle = 'Book Transfers, Excursions and Car Rentals';
                $bookingModuleDesc = "Fill out the form to book your private transfer, an excursion, or a car rental in Trapani.  
        Our services are available throughout Sicily with transparent prices and instant confirmation.";
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
                            @if ($displayedContent->{'subtitle_' . app()->getLocale()})
                                <span class="text-shadow text-c btn_font_size">
                                    {{ $displayedContent->{'subtitle_' . app()->getLocale()} }}
                                </span>
                            @endif
                        </div>
                    @endif
                    <x-display-error />
                    <x-display-message />
                </div>
                <div id="headerBooking" class="col-12 col-lg-6 p-0 d-flex justify-content-center align-items-start">
                    <div class="booking-module">
                        <span class="visually-hidden">{{ $bookingModuleTitle }}</span>
                        <span class="visually-hidden">{{ __('ui.bookNow') }}</span>
                        <p class="visually-hidden">
                            {{ $bookingModuleDesc }}
                        </p>
                        <livewire:prenotazione />
                    </div>
                </div>
            </div>
        </div>

        {{-- Gradiente e immagine --}}
        <div class="gradient-overlay"></div>

        @if ($displayedImage)
            <x-responsive-image fetchpriority="high" image="{{ Storage::url($displayedImage) }}"
                alt="{{ $displayedContent ? $displayedContent->{'title_' . app()->getLocale()} : 'Transfer and excursions in Sicily' }}"
                class="img_car" />
        @else
            <x-responsive-image fetchpriority="high" image="{{ $defaultImagePath }}" alt="{{ $bookingModuleTitle }}"
                class="img_car" />
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
