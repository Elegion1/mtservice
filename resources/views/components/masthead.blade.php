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

        <div class="container-fluid pos_masthead">
            <div class="row align-items-center">
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
                <div id="headerBooking"
                    class="col-12 col-lg-6 d-none d-lg-flex justify-content-center align-items-start">
                    <button id="resumeBookingBtn"
                        class="btn btn-primary btn-lg shadow d-none animate__animated animate__fadeIn"
                        onclick="bookingModuleAppend(true)">
                        {{ __('ui.resumeBooking') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="d-lg-none fixed-bottom p-2" id="bookNowBtnContainer" style="z-index: 1050;">
            <button type="button" id="bookNowBtn" class="btn bg-a w-100 py-3 fw-bold text-uppercase shadow-lg text-light" data-bs-toggle="modal"
                data-bs-target="#bookingModal">{{ __('ui.bookNow') }}
            </button>
        </div>
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center align-items-center"></div>
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

    <div class="booking-module-storage visually-hidden">
        <div class="booking-module">
            <span class="visually-hidden">{{ $bookingModuleTitle }}</span>
            <span class="visually-hidden">{{ __('ui.bookNow') }}</span>
            <p class="visually-hidden">
                {{ $bookingModuleDesc }}
            </p>
            <livewire:prenotazione />
        </div>
    </div>

</header>


<script>
    // Variabile per tenere traccia dello step globale
    let currentBookingStep = 1;

    const applyClasses = () => {
        const headerContainer = document.querySelector('#headerContainer');
        const headerText = document.querySelector('#headerText');
        if (!headerContainer || !headerText) return;

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

    function toggleResumeButton() {
        const resumeBtn = document.querySelector('#resumeBookingBtn');
        const modalElement = document.getElementById('bookingModal');
        if (!resumeBtn || !modalElement) return;

        const isModalOpen = modalElement.classList.contains('show');

        // Mostra il tasto solo su desktop, se lo step > 1 e il modal è CHIUSO
        if (window.innerWidth >= 992 && currentBookingStep > 1 && !isModalOpen) {
            resumeBtn.classList.remove('d-none');
        } else {
            resumeBtn.classList.add('d-none');
        }
    }

    function bookingModuleAppend(shouldOpenModal = false) {
        const bookingModule = document.querySelector('.booking-module');
        const headerBooking = document.querySelector('#headerBooking');
        const bookingModalBody = document.querySelector('#bookingModal .modal-body');

        if (!bookingModule) return;

        if (window.innerWidth < 992 || shouldOpenModal || currentBookingStep > 1) {
            if (bookingModalBody && !bookingModalBody.contains(bookingModule)) {
                bookingModalBody.appendChild(bookingModule);
            }
        } else {
            if (headerBooking && !headerBooking.contains(bookingModule)) {
                headerBooking.appendChild(bookingModule);
            }
        }

        if (shouldOpenModal) {
            const modalElement = document.getElementById('bookingModal');
            if (modalElement) {
                const bootstrapLib = window.bootstrap || bootstrap;
                if (bootstrapLib) {
                    const modalInstance = bootstrapLib.Modal.getOrCreateInstance(modalElement);
                    setTimeout(() => {
                        modalInstance.show();
                    }, 50);
                }
            }
        }

        // Controlla se mostrare il tasto resume dopo lo spostamento
        toggleResumeButton();
    }

    document.addEventListener('DOMContentLoaded', () => {
        applyClasses();
        bookingModuleAppend();

        // Ascolta quando il modal viene chiuso per mostrare il tasto resume
        const modalElement = document.getElementById('bookingModal');
        const bookNowBtn = document.getElementById('bookNowBtn');
        if (modalElement) {
            // Inizializza inert quando la pagina carica (il modal è nascosto)
            modalElement.setAttribute('inert', '');
            
            modalElement.addEventListener('hidden.bs.modal', () => {
                console.log('[bookingModal] hidden');

                // Aggiungi inert quando il modal è chiuso
                modalElement.setAttribute('inert', '');
                // Mostra il tasto "Prenota Ora" quando il modale è chiuso
                if (bookNowBtn) {
                    bookNowBtn.classList.remove('d-none');
                }
                toggleResumeButton();
            });
            modalElement.addEventListener('shown.bs.modal', () => {
                console.log('[bookingModal] shown');

                // Rimuovi inert quando il modal è aperto
                modalElement.removeAttribute('inert');
                // Nascondi il tasto "Prenota Ora" quando il modale si apre
                if (bookNowBtn) {
                    bookNowBtn.classList.add('d-none');
                }
                toggleResumeButton();
            });
        }
    });

    window.addEventListener('resize', () => {
        const modalElement = document.getElementById('bookingModal');
        const isModalOpen = modalElement && modalElement.classList.contains('show');

        applyClasses();
        if (!isModalOpen) {
            bookingModuleAppend();
        }
        toggleResumeButton();
    });

    window.addEventListener('prenotazione-state', e => {
        // Gestione compatibilità Livewire 3 (array vs oggetto)
        const detail = Array.isArray(e.detail) ? e.detail[0] : e.detail;

        currentBookingStep = detail.currentStep;
        const currentForm = detail.currentForm;

        // Se siamo oltre lo step 1 OPPURE se il form visualizzato è il riepilogo (bookingSummary)
        if (currentBookingStep > 1 || currentForm === 'bookingSummary') {

            // Forza lo spostamento nel modal e l'apertura
            bookingModuleAppend(true);

            // Scroll in alto per mostrare l'inizio del riepilogo/form
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

        } else {
            // Se si torna all'inizio (Step 1 e non summary), riporta il modulo nell'header (su Desktop)
            bookingModuleAppend(false);
        }
    });
</script>
