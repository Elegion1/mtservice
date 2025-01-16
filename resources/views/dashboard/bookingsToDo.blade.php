<x-dashboard-layout>
    <div class="container-fluid">
        <h2>Prenotazioni in attesa</h2>
        <a class="btn btn-sm btn-secondary text-small" href="{{ route('dashboard.bookingList') }}">Torna a confermate</a>

        @foreach ($bookings as $booking)
            @if (!$booking)
                <p class="text-center">Nessuna prenotazione in attesa</p>
            @endif
            @if ($booking->status == 'pending')
                <x-bookingCard :booking='$booking' />
            @endif
        @endforeach
    </div>

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Gestore per mostrare/nascondere le colonne "Modifica stato" e "Azione"

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

                const dateDep = bookingData.date_dep ? new Date(bookingData.date_dep).toLocaleString('it-IT') : 'N/A';
                const dateRet = bookingData.date_ret ? new Date(bookingData.date_ret).toLocaleString('it-IT') : 'N/A';
                const dateStart = bookingData.date_start ? new Date(bookingData.date_start).toLocaleString(
                    'it-IT') : 'N/A';
                const dateEnd = bookingData.date_end ? new Date(bookingData.date_end).toLocaleString('it-IT') :
                    'N/A';

                modalContent.innerHTML += `
                <p>Nome: <span class="text-primary">${booking.name} ${booking.surname}</span></p>
                <p>Email: <a href="mailto:${booking.email}">${booking.email}</a> Telefono: <a href="tel:${booking.phone}">${booking.phone}</a></p>
                <p>Tipologia: <span class="text-primary">${bookingData.type}</span></p>
                <p>Prezzo: <span class="text-primary">${bookingData.price} €</span></p>
                `;

                if (bookingType === 'transfer') {
                    modalContent.innerHTML += `
                    <p>Data di partenza: <span class="text-primary">${dateDep}</span></p>
                    <p>Data di ritorno: <span class="text-primary">${dateRet || 'N/A'}</span></p>
                    <p>Partenza: <span class="text-primary">${bookingData.departure_name}</span></p>
                    <p>Arrivo: <span class="text-primary">${bookingData.arrival_name}</span></p>
                    <p>Passeggeri: <span class="text-primary">${bookingData.passengers}</span></p>
                    <p>Durata: <span class="text-primary">${bookingData.duration} minuti circa</span></p>
                    `;
                } else if (bookingType === 'escursione') {
                    modalContent.innerHTML += `
                    <p>Data di partenza: <span class="text-primary">${dateDep}</span></p>
                    <p>Partenza: <span class="text-primary">${bookingData.departure_name}</span></p>
                    <p>Passeggeri: <span class="text-primary">${bookingData.passengers}</span></p>
                    `;
                } else if (bookingType === 'noleggio') {
                    modalContent.innerHTML += `
                    <p>Data di inizio noleggio: <span class="text-primary">${dateStart}</span></p>
                    <p>Data di fine noleggio: <span class="text-primary">${dateEnd}</span></p>
                    <p>Auto noleggiata: <span class="text-primary">${bookingData.car_name}</span></p>
                    <p>Descrizione auto: <span class="text-primary">${bookingData.car_description}</span></p>
                    <p>Quantità: <span class="text-primary">${bookingData.quantity}</span></p>
                    `;
                }
            }
        });
    </script>
</x-dashboard-layout>
