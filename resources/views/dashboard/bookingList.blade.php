<x-dashboard-layout>
    <div class="container-fluid">

        <h2 class="text-wrap">Prenotazioni confermate</h2>
        <a class="btn btn-sm btn-secondary text-small" href="{{ route('booking.todo') }}">Da confermare</a>

        <div class="row d-flex justify-content-center align-items-center mt-1">
            <div class="col-4 text-start">
                <button id="prevBtn" class="btn btn-secondary btn-sm text-small">Precedente</button>
            </div>
            <div class="col-4">
                <select id="groupBySelector" class="form-select form-select-sm">
                    <option value="month" selected>Mese</option>
                    <option value="day">Giorno</option>
                </select>
            </div>
            <div class="col-4 text-end">
                <button id="nextBtn" class="btn btn-secondary btn-sm text-small">Successivo</button>
            </div>
        </div>

        <div id="dayGroup">
            @foreach ($groupedByDay as $date => $dayBookings)
                <div class="day-bookings" data-date="{{ $date }}">
                    <h3 class="text-center my-3 bg-secondary-subtle">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l d/m/Y') }}
                    </h3>

                    <x-dayBookingsShow :dayBookings="$dayBookings" />
                </div>
            @endforeach
        </div>

        <div id="monthGroup" class="d-none">
            @foreach ($groupedByMonth as $month => $dayBookingsInMonth)
                <div class="month-bookings" data-month="{{ $month }}">
                    <h2 class="text-center my-3 bg-primary-subtle">
                        {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</h2>

                    @foreach ($dayBookingsInMonth as $date => $dayBookings)
                        <div class="day-bookings" data-date="{{ $date }}">
                            <h3 class="text-center my-3 bg-secondary-subtle">
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('l d/m/Y') }}
                            </h3>

                            <x-dayBookingsShow :dayBookings="$dayBookings" />
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
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
                    var id = button.getAttribute('data-booking-id') || 'N/A';

                    console.log(start_date, bookingData.date_dep);
                    console.log(end_date, bookingData.date_ret);

                    showBookingDetailsModal(bookingData, start_date, end_date, name, surname, phone,
                        email, id);
                });
            });

            // Funzione per mostrare i dettagli della prenotazione nel modale
            function showBookingDetailsModal(bookingData, start_date, end_date, name, surname, phone, email, id) {
                const modal = document.getElementById('bookingDetailsModal');
                const modalContent = document.getElementById('bookingDetailsContent');
                modalContent.innerHTML = ''; // Pulisci il contenuto del modale

                // Crea il contenuto del modale con i dettagli della prenotazione
                const bookingType = bookingData.type;

                const dateDep = bookingData.date_dep ? new Date(bookingData.date_dep).toLocaleString('it-IT') : 'N/A';
                const dateRet = bookingData.date_ret ? new Date(bookingData.date_ret).toLocaleString('it-IT') : 'N/A';
                const dateStart = bookingData.date_start ? new Date(bookingData.date_start).toLocaleString(
                    'it-IT') : 'N/A';
                const dateEnd = bookingData.date_end ? new Date(bookingData.date_end).toLocaleString('it-IT') :
                    'N/A';

                let modalInnerHTML = `
        <p><span class="text-primary">${name} ${surname}</span></p>
        <p><a href="tel:${phone}">${phone}</a> <a href="mailto:${email}">${email}</a></p>
        <p>Tipologia: <span class="text-primary">${bookingData.type}</span></p>`;

                if (bookingType === 'transfer' && start_date !== 'N/A') {
                    modalInnerHTML += `
        <p>Data di partenza: <span class="text-primary">${dateDep}</span></p>
        <p>Data di ritorno: <span class="text-primary">${dateRet}</span></p>
        <p>Transfer da: <span class="text-primary">${bookingData.departure_name}</span></p>
        <p>Transfer a: <span class="text-primary">${bookingData.arrival_name}</span></p>`;
                } else if (bookingType === 'transfer' && end_date !== 'N/A') {
                    modalInnerHTML += `
        <p>Data di ritorno: <span class="text-primary">${dateRet}</span></p>
        <p>Transfer da: <span class="text-primary">${bookingData.arrival_name}</span></p>
        <p>Transfer a: <span class="text-primary">${bookingData.departure_name}</span></p>`;
                } else if (bookingType === 'escursione') {
                    modalInnerHTML += `
        <p>Data di partenza: <span class="text-primary">${dateDep}</span></p>
        <p>A: <span class="text-primary">${bookingData.departure_name}</span></p>`;
                } else if (bookingType === 'noleggio') {
                    modalInnerHTML += `
        <p>Data di inizio noleggio: <span class="text-primary">${dateStart}</span></p>
        <p>Data di fine noleggio: <span class="text-primary">${dateEnd}</span></p>
        <p>Auto noleggiata: <span class="text-primary">${bookingData.car_name}</span></p>
        <p>Descrizione auto: <span class="text-primary">${bookingData.car_description}</span></p>`;
                }

                modalInnerHTML += `<p>Prezzo: <span class="text-primary">${bookingData.price} €</span></p>`;

                modalInnerHTML += `<form action="/dashboard/bookings/${id}/update-status"
                    method="POST" style="display:inline-block;">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="btn btn-danger">
                        Rifiuta prenotazione
                    </button>
                </form>`;

                modalContent.innerHTML = modalInnerHTML;

                // Apri il modale
                modal.style.display = 'block';
            }

            // Restituisce l'indice del mese corrente tra quelli disponibili
            const findCurrentMonthIndex = () => {
                const currentMonth = new Date().toISOString().slice(0,
                    7); // Ottiene il mese corrente nel formato "YYYY-MM"
                const monthItems = Array.from(document.querySelectorAll('#monthGroup .month-bookings'));

                return monthItems.findIndex(item => item.getAttribute('data-month') === currentMonth);
            };

            const groupBySelector = document.getElementById('groupBySelector');
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
