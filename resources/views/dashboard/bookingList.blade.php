<x-dashboard-layout>
    <div class="row">
        <div class="col-12">
            <h1>Lista Prenotazioni</h1>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-6 text-start">
            <button id="prevDayBtn" class="btn btn-secondary">Giorno Precedente</button>
        </div>
        <div class="col-6 text-end">
            <button id="nextDayBtn" class="btn btn-secondary">Giorno Successivo</button>
        </div>
    </div>

    <div id="bookingsContainer">
        @php
            // Raggruppa le prenotazioni per giorno, usando start_date se disponibile, altrimenti end_date
            $groupedBookings = $bookings->groupBy(function ($booking) {
                $date = $booking->start_date ?? $booking->end_date;
                return $date ? \Carbon\Carbon::parse($date)->format('Y-m-d') : 'unknown';
            });
        @endphp

        @foreach ($groupedBookings as $date => $dayBookings)
            <div class="day-bookings" data-date="{{ $date }}">
                <h3 class="text-center my-3">
                    @if ($date !== 'unknown')
                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                    @else
                        Data sconosciuta
                    @endif
                </h3>

                @foreach ($dayBookings->sortBy(function ($booking) {
        return \Carbon\Carbon::parse($booking->start_date ?? $booking->end_date);
    }) as $booking)
                    <div class="booking-item border p-3 mb-2">
                        <p><strong>Cliente:</strong> {{ $booking->name }} {{ $booking->surname }}</p>
                        <p><strong>Tipo:</strong> {{ ucfirst($booking->bookingData['type']) }}
                            @if ($booking->bookingData['type'] == 'noleggio')
                                <strong>{{ $booking->bookingData['car_name'] }}</strong>
                            @elseif ($booking->bookingData['type'] == 'escursione')
                                a <strong>{{ $booking->bookingData['departure_name'] }}</strong>
                            @endif
                        </p>
                        <p><strong>Ore:</strong>
                            @if ($booking->bookingData['type'] == 'transfer' || $booking->bookingData['type'] == 'escursione')
                                {{ \Carbon\Carbon::parse($booking->bookingData['date_dep'])->format('H:i') }}
                            @elseif ($booking->bookingData['type'] == 'noleggio')
                                {{ \Carbon\Carbon::parse($booking->bookingData['date_start'])->format('H:i') }}
                            @endif
                            @if ($booking->bookingData['type'] == 'transfer' || $booking->bookingData['type'] == 'escursione')
                                <strong>PAX:</strong> {{ $booking->bookingData['passengers'] }}
                            @endif
                        </p>
                        @if ($booking->start_date && $booking->bookingData['type'] == 'transfer')
                            Da: <strong>{{ $booking->bookingData['departure_name'] }}</strong> a:
                            <strong>{{ $booking->bookingData['arrival_name'] }}</strong>
                            @if ($booking->bookingData['date_ret'])
                                Ritorno il:
                                {{ \Carbon\Carbon::parse($booking->bookingData['date_ret'])->format('d/m/Y') }} ore:
                                {{ \Carbon\Carbon::parse($booking->bookingData['date_ret'])->format('H:i') }}
                            @endif
                        @endif
                        @if ($booking->end_date && $booking->bookingData['type'] == 'transfer')
                            Da: <strong>{{ $booking->bookingData['arrival_name'] }}</strong> a:
                            <strong>{{ $booking->bookingData['departure_name'] }}</strong> <br>
                        @endif

                        @if ($booking->bookingData['type'] == 'noleggio' && $booking->start_date)
                            @if (isset($booking->bookingData['date_end']))
                                <p>Fine noleggio il:
                                    {{ \Carbon\Carbon::parse($booking->bookingData['date_end'])->format('d/m/Y') }}</p>
                            @endif
                        @elseif ($booking->bookingData['type'] == 'noleggio' && $booking->end_date)
                            <p class="text-success">Consegna auto</p>
                            <p>Dal: {{ \Carbon\Carbon::parse($booking->bookingData['date_start'])->format('d/m/Y') }}
                                al: {{ \Carbon\Carbon::parse($booking->bookingData['date_end'])->format('d/m/Y') }}</p>
                        @endif
                        <p>Prezzo: {{ $booking->bookingData['price'] }} €</p>
                        <p><strong>Note:</strong> {{ $booking->body }}</p>
                        <button class="btn btn-primary btn-sm open-details-modal" data-bs-toggle="modal"
                            data-bs-target="#bookingDetailsModal"
                            data-booking-data="{{ json_encode($booking->bookingData) }}">
                            Apri info
                        </button>
                    </div>
                @endforeach
            </div>
        @endforeach
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
                    showBookingDetailsModal(bookingData);
                });
            });

            // Funzione per mostrare i dettagli della prenotazione nel modale
            function showBookingDetailsModal(bookingData) {
                const modal = document.getElementById('bookingDetailsModal');
                const modalContent = document.getElementById('bookingDetailsContent');
                modalContent.innerHTML = ''; // Pulisci il contenuto del modale

                // Crea il contenuto del modale con i dettagli della prenotazione
                const bookingType = bookingData.type;
                if (bookingType === 'transfer') {
                    modalContent.innerHTML = `
                    
                    <p>Tipologia: <span class="text-primary">${bookingData.type}</span></p>
                    <p>Prezzo: <span class="text-primary">${bookingData.price} €</span></p>
                    <p>Data di partenza: <span class="text-primary">${bookingData.date_dep}</span></p>
                    <p>Data di ritorno: <span class="text-primary">${bookingData.date_ret}</span></p>
                    <p>Partenza: <span class="text-primary">${bookingData.departure_name}</span></p>
                    <p>Arrivo: <span class="text-primary">${bookingData.arrival_name}</span></p>
                    <p>Passeggeri: <span class="text-primary">${bookingData.passengers}</span></p>
                    <p>Durata: <span class="text-primary">${bookingData.duration} minuti circa</span></p>
                `;
                } else if (bookingType === 'escursione') {
                    modalContent.innerHTML = `
                    
                    <p>Tipologia: <span class="text-primary">${bookingData.type}</span></p>
                    <p>Prezzo: <span class="text-primary">${bookingData.price} €</span></p>
                    <p>Data di partenza: <span class="text-primary">${bookingData.date_dep}</span></p>
                    <p>Partenza: <span class="text-primary">${bookingData.departure_name}</span></p>
                    <p>Passeggeri: <span class="text-primary">${bookingData.passengers}</span></p>
                `;
                } else if (bookingType === 'noleggio') {
                    modalContent.innerHTML = `
                    
                    <p>Tipologia: <span class="text-primary">${bookingData.type}</span></p>
                    <p>Prezzo: <span class="text-primary">${bookingData.price} €</span></p>
                    <p>Data di inizio noleggio: <span class="text-primary">${bookingData.date_start}</span></p>
                    <p>Data di fine noleggio: <span class="text-primary">${bookingData.date_end}</span></p>
                    <p>Auto noleggiata: <span class="text-primary">${bookingData.car_name}</span></p>
                    <p>Descrizione auto: <span class="text-primary">${bookingData.car_description}</span></p>
                    <p>Quantità: <span class="text-primary">${bookingData.quantity}</span></p>
                `;
                }

                // Apri il modale
                modal.style.display = 'block';
            }

            const days = Array.from(document.querySelectorAll('.day-bookings'));
            let currentDayIndex = days.findIndex(day => day.style.display !== 'none');

            const updateDayVisibility = () => {
                days.forEach((day, index) => {
                    day.style.display = index === currentDayIndex ? 'block' : 'none';
                });
            };

            document.getElementById('prevDayBtn').addEventListener('click', function() {
                if (currentDayIndex > 0) {
                    currentDayIndex--;
                    updateDayVisibility();
                }
            });

            document.getElementById('nextDayBtn').addEventListener('click', function() {
                if (currentDayIndex < days.length - 1) {
                    currentDayIndex++;
                    updateDayVisibility();
                }
            });

            updateDayVisibility(); // Mostra il giorno corrente inizialmente
        });
    </script>
</x-dashboard-layout>
