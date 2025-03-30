<x-dashboard-layout>

    <h2 class="text-nowrap">Prenotazioni confermate</h2>
    <div class="row">
        <div class="col-4 d-grid">
            <a id="pendingBookingsBtn"
                class="btn btn-sm btn-secondary text-small d-flex justify-content-around align-items-center"
                href="{{ route('booking.todo') }}">In attesa
                @if ($pendingBookings->count() > 0)
                    <span style="width: 20px; height:20px;"
                        class="d-flex justify-content-center align-items-center p-1 rounded-circle text-white bg-warning text-small">
                        {{ $pendingBookings->count() }}
                    </span>
                @endif
            </a>
        </div>
        <div class="col-8 px-4">
            <div id="buttons-container" class="row"></div>
        </div>
    </div>

    <div class="row d-flex justify-content-between align-items-center mt-1">
        <div class="col-4 d-grid">
            <button id="prevBtn" class="btn bg-secondary-subtle btn-sm text-small mb-1">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button id="nextBtn" class="btn bg-secondary-subtle btn-sm text-small">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        <div class="col-8 d-grid">
            <button id="groupBySelectorMonth" class="btn btn-sm text-small mb-1">Agenda
                Mese</button>
            <button id="groupBySelectorDay" class="btn btn-sm text-small">Agenda Giorno</button>
        </div>

    </div>

    <div id="dayGroup">
        <h2 id="dayTitle" class="text-center my-1 text-uppercase"></h2>
        <div class="overflow-auto overflow-x-hidden scroller-height">
            @foreach ($groupedByDay as $date => $dayBookings)
                <x-dayBookingsShow :dayBookings="$dayBookings" :date="$date" :dayGroup="'true'" />
            @endforeach
        </div>
    </div>

    <div id="monthGroup" class="d-none">
        @foreach ($groupedByMonth as $month => $dayBookingsInMonth)
            <div class="month-bookings" data-month="{{ $month }}">
                <h2 class="text-center my-1">
                    {{ strtoupper(\Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y')) }}
                </h2>
                <div class="overflow-auto overflow-x-hidden scroller-height">
                    @foreach ($dayBookingsInMonth as $date => $dayBookings)
                        <x-dayBookingsShow :dayBookings="$dayBookings" :date="$date" />
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <p class="text-center mt-3 fw-bold">
        {{ \Carbon\Carbon::now()->translatedFormat('l j F Y H:i') }}
    </p>

    <!-- Modal per i dettagli -->
    <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingDetailsModalLabel">Dettagli della Prenotazione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bookingDetailsContent">
                    <!-- Contenuto dinamico -->
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            //filtra le prenotazioni in base al tipo
            const buttons = document.querySelectorAll('.btn-filter');

            const buttonsData = [{
                    label: "Tutti",
                    type: "all",
                    bgClass: "bg-secondary col-12",
                    textColor: "text-white"
                },
                {
                    label: "Noleggio",
                    type: "noleggio",
                    bgClass: "bg-warning",
                    textColor: "text-black"
                },
                {
                    label: "Escursioni",
                    type: "escursione",
                    bgClass: "bg-success",
                    textColor: "text-black"
                },
                {
                    label: "Transfer",
                    type: "transfer",
                    bgClass: "bg-danger",
                    textColor: "text-black"
                },
                {
                    label: "Sito Favignana",
                    type: "sito_favignana",
                    bgClass: "bg-info",
                    textColor: "text-black"
                }
            ];

            generateButtons("buttons-container");

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const selectedType = this.getAttribute('data-type');

                    document.querySelectorAll('.booking-item').forEach(item => {
                        const itemType = item.getAttribute('data-type');
                        const isFavignana = item.getAttribute('data-favignana') === 'true';

                        if (
                            selectedType === 'all' ||
                            (selectedType === 'transfer' && itemType === 'transfer' && !
                                isFavignana) ||
                            (selectedType === 'sito_favignana' && isFavignana) ||
                            (selectedType !== 'transfer' && selectedType !==
                                'sito_favignana' && itemType === selectedType)
                        ) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });

            function generateButtons(containerId) {
                const container = document.getElementById(containerId);
                if (!container) return;

                container.innerHTML = ""; // Pulisce il contenitore prima di aggiungere i pulsanti

                buttonsData.forEach(({
                    label,
                    type,
                    bgClass,
                    textColor
                }) => {
                    const button = document.createElement("button");
                    button.className =
                        `col-6 btn btn-sm btn-filter ${bgClass} p-1 border rounded ${textColor}`;
                    button.dataset.type = type;
                    button.textContent = label;

                    container.appendChild(button);
                });
            }

            // Restituisce l'indice del mese corrente tra quelli disponibili
            const findCurrentMonthIndex = () => {
                const currentMonth = new Date().toISOString().slice(0,
                    7); // Ottiene il mese corrente nel formato "YYYY-MM"
                const monthItems = Array.from(document.querySelectorAll('#monthGroup .month-bookings'));

                return monthItems.findIndex(item => item.getAttribute('data-month') === currentMonth);
            };

            const groupBySelectorMonth = document.getElementById('groupBySelectorMonth');
            const groupBySelectorDay = document.getElementById('groupBySelectorDay');
            const dayGroup = document.getElementById('dayGroup');
            const monthGroup = document.getElementById('monthGroup');

            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            let currentIndex = 0;
            let currentView = 'month'; // Vista predefinita

            // Cerca il mese corrente solo se la vista è mensile
            const currentMonthIndex = findCurrentMonthIndex();
            if (currentMonthIndex !== -1) {
                currentIndex = currentMonthIndex; // Imposta l'indice al mese corrente
            }

            groupBySelectorDay.addEventListener('click', function() {
                currentView = 'day';
                currentIndex = 0; // Reset dell'indice quando si cambia vista
                updateView();

            });

            groupBySelectorMonth.addEventListener('click', function() {
                currentView = 'month';
                currentIndex = 0; // Reset dell'indice quando si cambia vista
                updateView();
            });

            // Gestione del pulsante precedente
            prevBtn.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    showCurrentItem();
                }
            });

            // Gestione del pulsante successivo
            nextBtn.addEventListener('click', function() {
                if (currentIndex < getCurrentItems().length - 1) {
                    currentIndex++;
                    showCurrentItem();
                }
            });

            // Aggiorna la visualizzazione in base al raggruppamento selezionato
            const updateView = () => {
                const isDayView = currentView === 'day';

                dayGroup.classList.toggle('d-none', !isDayView);
                monthGroup.classList.toggle('d-none', isDayView);

                groupBySelectorDay.classList.toggle('bg-secondary', isDayView);
                groupBySelectorDay.classList.toggle('text-white', isDayView);
                groupBySelectorDay.classList.toggle('bg-secondary-subtle', !isDayView);

                groupBySelectorMonth.classList.toggle('bg-secondary', !isDayView);
                groupBySelectorMonth.classList.toggle('text-white', !isDayView);
                groupBySelectorMonth.classList.toggle('bg-secondary-subtle', isDayView);

                showCurrentItem();
            };

            // Restituisce l'elenco degli elementi (giorni o mesi) da visualizzare
            const getCurrentItems = () => {
                return currentView === 'day' ?
                    Array.from(document.querySelectorAll('#dayGroup .day-bookings')) :
                    Array.from(document.querySelectorAll('#monthGroup .month-bookings'));
            };

            // Mostra l'elemento corrente (giorno o mese) in base all'indice corrente
            const showCurrentItem = () => {
                const dayTitle = document.getElementById('dayTitle');
                const items = getCurrentItems();

                items.forEach((item, index) => {
                    item.style.display = index === currentIndex ? 'block' : 'none';
                    if (item.style.display === 'block') {
                        const rawDate = item.getAttribute('data-date'); // Es. "2025-01-30"
                        const formattedDate = new Date(rawDate).toLocaleDateString('it-IT', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                        dayTitle.innerHTML = formattedDate; // Es. "30 gennaio 2025"
                    }
                });
            };
            // Inizializza la visualizzazione mostrando il primo gruppo
            updateView();
        });
    </script>
</x-dashboard-layout>
