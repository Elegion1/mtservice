<x-dashboard-layout>

    <div class="row">
        <div class="col-md-6 justify-content-start align-items-center">
            <h1>Gestione Prenotazioni</h1>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-center">
            <button id="toggleActionsBtn" class="btn btn-sm btn-warning">Azioni</button>
        </div>
    </div>
    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Codice</th>
                <th>Info</th>
                <th>Tipo</th>
                <th>Totale</th>
                <th>Creazione</th>
                <th>Stato</th>
                <th class="action-column" style="display: none;">Modifica</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->name }} {{ $booking->surname }}</td>
                    <td>{{ $booking->email }}</td>
                    <td>
                        {{ $booking->code }}
                        <a href="{{ route('reviews.create', ['locale' => 'it', 'booking_code' => $booking->code]) }}">
                            <i class="bi bi-star-half"></i>
                        </a>
                    </td>
                    <td>
                        <button class="btn text-primary btn-sm open-details-modal" data-bs-toggle="modal"
                            data-bs-target="#bookingDetailsModal" data-booking-data="{{ json_encode($booking) }}">
                            <i class="bi bi-info-circle"></i>
                        </button>
                    </td>
                    <td>{{ ucfirst($booking->bookingData['type']) }}</td>
                    <td>{{ $booking->bookingData['price'] }} €</td>
                    <td>{{ $booking->created_at }}</td>
                    <td>
                        <x-status :status="$booking->status" />
                    </td>
                    <td class="action-column" style="display: none;">

                        @if ($booking->status != 'confirmed')
                            <form action="{{ route('bookings.update', $booking) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="confirmed">
                                <button title="Accetta prenotazione" type="submit" class="btn btn-sm">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </button>
                            </form>
                        @endif

                        @if ($booking->status != 'rejected')
                            <form action="{{ route('bookings.update', $booking) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button title="Rifiuta prenotazione" type="submit" class="btn btn-sm">
                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                </button>
                            </form>
                        @endif

                        @if ($booking->status != 'pending')
                            <form action="{{ route('bookings.update', $booking) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="pending">
                                <button title="Sposta in lavorazione" type="submit" class="btn btn-sm">
                                    <i class="bi bi-exclamation-circle-fill text-warning"></i>
                                </button>
                            </form>
                        @endif

                        <x-delete-button :route="'bookings'" :model="$booking" :label="true" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <!-- Modale per visualizzare le informazioni sulla prenotazione -->
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

    <!-- JavaScript per il modale e per la gestione della visibilità delle colonne -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Gestore per mostrare/nascondere le colonne "Modifica stato" e "Azione"
            const toggleButton = document.getElementById('toggleActionsBtn');
            const actionColumns = document.querySelectorAll('.action-column');

            toggleButton.addEventListener('click', function() {
                actionColumns.forEach(column => {
                    if (column.style.display === 'none') {
                        column.style.display = '';
                    } else {
                        column.style.display = 'none';
                    }
                });
            });

            // Aggiungi un gestore di eventi per il clic sui pulsanti "Apri info"
            document.querySelectorAll('.open-details-modal').forEach(button => {
                button.addEventListener('click', () => {
                    const booking = JSON.parse(button.getAttribute('data-booking-data'));
                    showBookingDetailsModal(booking);
                });
            });

            // Funzione per mostrare i dettagli della prenotazione nel modale
            function showBookingDetailsModal(booking) {
                const bookingData = booking.bookingData;
                const modalContent = document.getElementById('bookingDetailsContent');
                modalContent.innerHTML = ''; // Pulisci il contenuto del modale

                // Crea il contenuto del modale con i dettagli della prenotazione
                const bookingType = bookingData.type;
                modalContent.innerHTML += `
                <p>Codice prenotazione: <span class="text-primary">${booking.code}</span></p>
                <p>Nome: <span class="text-primary">${booking.name} ${booking.surname}</span></p>
                <p>Email: <a href="mailto:${booking.email}">${booking.email}</a> Telefono: <a href="tel:${booking.phone}">${booking.phone}</a></p>
                <p>Tipologia: <span class="text-primary">${bookingData.type}</span></p>
                <p>Prezzo: <span class="text-primary">${bookingData.price} €</span></p>
                `;

                if (bookingType === 'transfer') {
                    modalContent.innerHTML += `
                    <p>Data di partenza: <span class="text-primary">${bookingData.date_dep}</span></p>
                    <p>Data di ritorno: <span class="text-primary">${bookingData.date_ret || 'N/A'}</span></p>
                    <p>Partenza: <span class="text-primary">${bookingData.departure_name}</span></p>
                    <p>Arrivo: <span class="text-primary">${bookingData.arrival_name}</span></p>
                    <p>Passeggeri: <span class="text-primary">${bookingData.passengers}</span></p>
                    <p>Durata: <span class="text-primary">${bookingData.duration} minuti circa</span></p>
                    `;
                } else if (bookingType === 'escursione') {
                    modalContent.innerHTML += `
                    <p>Data di partenza: <span class="text-primary">${bookingData.date_dep}</span></p>
                    <p>Partenza: <span class="text-primary">${bookingData.departure_name}</span></p>
                    <p>Passeggeri: <span class="text-primary">${bookingData.passengers}</span></p>
                    `;
                } else if (bookingType === 'noleggio') {
                    modalContent.innerHTML += `
                    <p>Data di inizio noleggio: <span class="text-primary">${bookingData.date_start}</span></p>
                    <p>Data di fine noleggio: <span class="text-primary">${bookingData.date_end}</span></p>
                    <p>Auto noleggiata: <span class="text-primary">${bookingData.car_name}</span></p>
                    <p>Descrizione auto: <span class="text-primary">${bookingData.car_description}</span></p>
                    <p>Quantità: <span class="text-primary">${bookingData.quantity}</span></p>
                    `;
                }
            }
        });
    </script>
</x-dashboard-layout>
