<x-dashboard-layout>
    <div class="row">
        <div class="col-8">
            <h1>Lista Prenotazioni</h1>
        </div>

        <div class="col-4 mb-3">
            <select id="groupBySelector" class="form-select">
                <option value="month" selected>Mese</option>
                <option value="day">Giorno</option>
            </select>
        </div>
        <div class="col-6 text-start">
            <button id="prevBtn" class="btn btn-secondary">Precedente</button>
        </div>
        <div class="col-6 text-end">
            <button id="nextBtn" class="btn btn-secondary">Successivo</button>
        </div>
    </div>

    <div id="dayGroup">
        @foreach ($groupedByDay as $date => $dayBookings)
            <div class="day-bookings" data-date="{{ $date }}">
                <h3 class="text-center my-3 bg-secondary-subtle">
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l d/m/Y') }}
                </h3>

                @foreach ($dayBookings->sortBy('start_date') as $booking)
                    <div class="booking-item border p-3">
                        <div class="row">
                            <div class="col-3">
                                <!-- Ora di partenza o di noleggio -->
                                {{ \Carbon\Carbon::parse($booking->start_date ?? $booking->end_date)->format('H:i') }}
                            </div>
                            <div class="col-9">
                                <button class="btn open-details-modal p-0" data-bs-toggle="modal"
                                    data-bs-target="#bookingDetailsModal"
                                    data-booking-data="{{ json_encode($booking->bookingData) }}">
                                    <p class="text-primary text-decoration-underline text-start m-0 text-wrap">
                                        {{ $booking->name }} {{ $booking->surname }} >>
                                        {{ ucfirst($booking->bookingData['type']) }}
                                        @if ($booking->bookingData['type'] == 'noleggio')
                                            <strong>{{ $booking->bookingData['car_name'] }}</strong>
                                        @elseif ($booking->bookingData['type'] == 'escursione')
                                            >> <strong>{{ $booking->bookingData['departure_name'] }}</strong> >>
                                        @endif
                                        @if ($booking->start_date && $booking->bookingData['type'] == 'transfer')
                                            >> <strong>{{ $booking->bookingData['departure_name'] }}</strong> >>
                                            <strong>{{ $booking->bookingData['arrival_name'] }}</strong> >>
                                        @endif
                                        @if ($booking->bookingData['type'] == 'transfer' || $booking->bookingData['type'] == 'escursione')
                                            {{ $booking->bookingData['passengers'] }} <strong>PAX</strong>
                                        @endif
                                        >> {{ $booking->bookingData['price'] }} €
                                    </p>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <div id="monthGroup" class="d-none">
        @foreach ($groupedByMonth as $month => $dayBookingsInMonth)
            <div class="month-bookings" data-month="{{ $month }}">
                <h2 class="text-center my-4 bg-primary-subtle">
                    {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</h2>

                @foreach ($dayBookingsInMonth as $date => $dayBookings)
                    <div class="day-bookings" data-date="{{ $date }}">
                        <h3 class="text-center my-3 bg-secondary-subtle">
                            {{ \Carbon\Carbon::parse($date)->translatedFormat('l d/m/Y') }}
                        </h3>

                        @foreach ($dayBookings->sortBy('start_date') as $booking)
                            <div class="booking-item border p-3">
                                <div class="row">
                                    <div class="col-3">
                                        <!-- Ora di partenza o di noleggio -->
                                        {{ \Carbon\Carbon::parse($booking->start_date ?? $booking->end_date)->format('H:i') }}
                                    </div>
                                    <div class="col-9">
                                        <button class="btn open-details-modal p-0" data-bs-toggle="modal"
                                            data-bs-target="#bookingDetailsModal"
                                            data-booking-data="{{ json_encode($booking->bookingData) }}"
                                            data-booking-start="{{ $booking->start_date }}"
                                            data-booking-end="{{ $booking->end_date }}"
                                            data-booking-name="{{ $booking->name }}"
                                            data-booking-surname="{{ $booking->surname }}"
                                            data-booking-phone="{{ $booking->phone }}"
                                            data-booking-email="{{ $booking->email }}">
                                            <p class="text-primary text-decoration-underline text-start mb-0 text-wrap">
                                                {{ $booking->name }} {{ $booking->surname }} >>
                                                {{ ucfirst($booking->bookingData['type']) }}
                                                @if ($booking->bookingData['type'] == 'noleggio')
                                                    <strong>{{ $booking->bookingData['car_name'] }}</strong>
                                                @elseif ($booking->bookingData['type'] == 'escursione')
                                                    >> <strong>{{ $booking->bookingData['departure_name'] }}</strong>>
                                                @endif
                                                @if ($booking->start_date && $booking->bookingData['type'] == 'transfer')
                                                    >> <strong>{{ $booking->bookingData['departure_name'] }}</strong>
                                                    >>
                                                    <strong>{{ $booking->bookingData['arrival_name'] }}</strong> >>
                                                @endif
                                                @if ($booking->bookingData['type'] == 'transfer' || $booking->bookingData['type'] == 'escursione')
                                                    {{ $booking->bookingData['passengers'] }} <strong>PAX</strong>
                                                @endif
                                                >> {{ $booking->bookingData['price'] }} €
                                            </p>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

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

    <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingDetailsModalLabel">Dettagli della Prenotazione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bookingDetailsContent">
                    <!-- Il contenuto della prenotazione verrà aggiunto qui tramite JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Aggiungi un gestore di eventi per il clic sui pulsanti "Apri info"
            document.querySelectorAll('.open-details-modal').forEach(button => {
                button.addEventListener('click', () => {
                    const bookingData = JSON.parse(button.getAttribute('data-booking-data'));
                    var start_date = button.getAttribute('data-booking-start') || 'N/A';
                    var end_date = button.getAttribute('data-booking-end') || 'N/A';
                    var name = button.getAttribute('data-booking-name') || 'N/A';
                    var surname = button.getAttribute('data-booking-surname') || 'N/A';
                    var phone = button.getAttribute('data-booking-phone') || 'N/A';
                    var email = button.getAttribute('data-booking-email') || 'N/A';

                    showBookingDetailsModal(bookingData, start_date, end_date, name, surname, phone,
                        email);
                });
            });

            // Funzione per mostrare i dettagli della prenotazione nel modale
            function showBookingDetailsModal(bookingData, start_date, end_date, name, surname, phone, email) {
                const modal = document.getElementById('bookingDetailsModal');
                const modalContent = document.getElementById('bookingDetailsContent');
                modalContent.innerHTML = ''; // Pulisci il contenuto del modale

                // Crea il contenuto del modale con i dettagli della prenotazione
                const bookingType = bookingData.type;

                let modalInnerHTML = `
        <p><span class="text-primary">${name} ${surname}</span></p>
        <p><a href="tel:${phone}">${phone}</a> <a href="mailto:${email}">${email}</a></p>
        <p>Tipologia: <span class="text-primary">${bookingData.type}</span></p>`;

                if (bookingType === 'transfer' && start_date !== 'N/A') {
                    modalInnerHTML += `
        <p>Data di partenza: <span class="text-primary">${bookingData.date_dep}</span></p>
        <p>Data di ritorno: <span class="text-primary">${bookingData.date_ret}</span></p>
        <p>Transfer da: <span class="text-primary">${bookingData.departure_name}</span></p>
        <p>Transfer a: <span class="text-primary">${bookingData.arrival_name}</span></p>`;
                } else if (bookingType === 'transfer' && end_date !== 'N/A') {
                    modalInnerHTML += `
        <p>Data di ritorno: <span class="text-primary">${bookingData.date_ret}</span></p>
        <p>Transfer da: <span class="text-primary">${bookingData.arrival_name}</span></p>
        <p>Transfer a: <span class="text-primary">${bookingData.departure_name}</span></p>`;
                } else if (bookingType === 'escursione') {
                    modalInnerHTML += `
        <p>Data di partenza: <span class="text-primary">${bookingData.date_dep}</span></p>
        <p>A: <span class="text-primary">${bookingData.departure_name}</span></p>`;
                } else if (bookingType === 'noleggio') {
                    modalInnerHTML += `
        <p>Data di inizio noleggio: <span class="text-primary">${start_date}</span></p>
        <p>Data di fine noleggio: <span class="text-primary">${end_date}</span></p>
        <p>Auto noleggiata: <span class="text-primary">${bookingData.car_name}</span></p>
        <p>Descrizione auto: <span class="text-primary">${bookingData.car_description}</span></p>`;
                }

                modalInnerHTML += `<p>Prezzo: <span class="text-primary">${bookingData.price} €</span></p>`;

                modalContent.innerHTML = modalInnerHTML;

                // Apri il modale
                modal.style.display = 'block';
            }

            const groupBySelector = document.getElementById('groupBySelector');
            const dayGroup = document.getElementById('dayGroup');
            const monthGroup = document.getElementById('monthGroup');

            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            let currentIndex = 0;
            let currentView = 'month'; // Vista predefinita

            // Cambia visualizzazione quando l'utente seleziona un'opzione diversa
            groupBySelector.addEventListener('change', function() {
                currentView = this.value;
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
                if (currentView === 'day') {
                    dayGroup.classList.remove('d-none');
                    monthGroup.classList.add('d-none');
                } else {
                    dayGroup.classList.add('d-none');
                    monthGroup.classList.remove('d-none');
                }
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
                const items = getCurrentItems();
                items.forEach((item, index) => {
                    item.style.display = index === currentIndex ? 'block' : 'none';
                });
            };

            // Inizializza la visualizzazione mostrando il primo gruppo
            updateView();
        });
    </script>
</x-dashboard-layout>
