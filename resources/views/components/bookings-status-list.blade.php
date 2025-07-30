@props(['bookings', 'title', 'status', 'emptyMessage'])

<x-dashboard-layout>
    <h2>{{ $title }}</h2>
    <a class="btn btn-sm btn-secondary text-small" href="{{ route('dashboard.bookingList') }}">Torna a confermate</a>

    @if ($bookings->count() == 0)
        <p class="text-center">{{ $emptyMessage }}</p>
    @else
        @foreach ($bookings as $booking)
            <x-bookingCard :booking='$booking' />
        @endforeach
    @endif

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
            const currentStatus = '{{ $status }}';
            
            // Configurazione badge status
            const statusConfig = {
                'pending': { label: 'In attesa', class: 'bg-warning' },
                'rejected': { label: 'Rifiutata', class: 'bg-danger' },
                'confirmed': { label: 'Confermata', class: 'bg-success' }
            };

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

                const bookingType = bookingData.type;
                let details = '';

                // Dettagli comuni
                details += `<div class="row mb-2">
                    <div class="col-sm-4"><strong>Nome:</strong></div>
                    <div class="col-sm-8">${booking.name} ${booking.surname}</div>
                </div>`;

                details += `<div class="row mb-2">
                    <div class="col-sm-4"><strong>Email:</strong></div>
                    <div class="col-sm-8"><a href="mailto:${booking.email}">${booking.email}</a></div>
                </div>`;

                details += `<div class="row mb-2">
                    <div class="col-sm-4"><strong>Telefono:</strong></div>
                    <div class="col-sm-8"><a href="tel:${booking.phone}">${booking.dial_code || ''} ${booking.phone || ''}</a></div>
                </div>`;

                details += `<div class="row mb-2">
                    <div class="col-sm-4"><strong>Codice:</strong></div>
                    <div class="col-sm-8">${booking.code || 'N/A'}</div>
                </div>`;

                details += `<div class="row mb-2">
                    <div class="col-sm-4"><strong>Stato:</strong></div>
                    <div class="col-sm-8">
                        <span class="badge ${statusConfig[currentStatus]?.class || 'bg-secondary'}">
                            ${statusConfig[currentStatus]?.label || 'Sconosciuto'}
                        </span>
                    </div>
                </div>`;

                details += `<div class="row mb-2">
                    <div class="col-sm-4"><strong>Tipologia:</strong></div>
                    <div class="col-sm-8">${bookingData.type}</div>
                </div>`;

                details += `<div class="row mb-2">
                    <div class="col-sm-4"><strong>Prezzo:</strong></div>
                    <div class="col-sm-8">${bookingData.price || 'N/A'} €</div>
                </div>`;

                // Dettagli specifici per tipo di prenotazione
                if (bookingType === 'transfer') {
                    const dateDep = bookingData.date_dep ? new Date(bookingData.date_dep).toLocaleString('it-IT') : 'N/A';
                    const dateRet = bookingData.date_ret ? new Date(bookingData.date_ret).toLocaleString('it-IT') : null;

                    details += `<div class="row mb-2">
                        <div class="col-sm-4"><strong>Partenza:</strong></div>
                        <div class="col-sm-8">${bookingData.departure_name || bookingData.dep || 'N/A'}</div>
                    </div>`;

                    details += `<div class="row mb-2">
                        <div class="col-sm-4"><strong>Destinazione:</strong></div>
                        <div class="col-sm-8">${bookingData.arrival_name || bookingData.dest || 'N/A'}</div>
                    </div>`;

                    details += `<div class="row mb-2">
                        <div class="col-sm-4"><strong>Data partenza:</strong></div>
                        <div class="col-sm-8">${dateDep}</div>
                    </div>`;

                    if (dateRet) {
                        details += `<div class="row mb-2">
                            <div class="col-sm-4"><strong>Data ritorno:</strong></div>
                            <div class="col-sm-8">${dateRet}</div>
                        </div>`;
                    }

                    if (bookingData.passengers) {
                        details += `<div class="row mb-2">
                            <div class="col-sm-4"><strong>Passeggeri:</strong></div>
                            <div class="col-sm-8">${bookingData.passengers}</div>
                        </div>`;
                    }

                    if (bookingData.duration) {
                        details += `<div class="row mb-2">
                            <div class="col-sm-4"><strong>Durata:</strong></div>
                            <div class="col-sm-8">${bookingData.duration} minuti circa</div>
                        </div>`;
                    }

                } else if (bookingType === 'noleggio') {
                    const dateStart = bookingData.date_start ? new Date(bookingData.date_start).toLocaleString('it-IT') : 'N/A';
                    const dateEnd = bookingData.date_end ? new Date(bookingData.date_end).toLocaleString('it-IT') : 'N/A';

                    details += `<div class="row mb-2">
                        <div class="col-sm-4"><strong>Auto:</strong></div>
                        <div class="col-sm-8">${bookingData.car_name || 'N/A'}</div>
                    </div>`;

                    if (bookingData.car_description) {
                        details += `<div class="row mb-2">
                            <div class="col-sm-4"><strong>Descrizione auto:</strong></div>
                            <div class="col-sm-8">${bookingData.car_description}</div>
                        </div>`;
                    }

                    details += `<div class="row mb-2">
                        <div class="col-sm-4"><strong>Data inizio:</strong></div>
                        <div class="col-sm-8">${dateStart}</div>
                    </div>`;

                    details += `<div class="row mb-2">
                        <div class="col-sm-4"><strong>Data fine:</strong></div>
                        <div class="col-sm-8">${dateEnd}</div>
                    </div>`;

                    if (bookingData.quantity) {
                        details += `<div class="row mb-2">
                            <div class="col-sm-4"><strong>Quantità:</strong></div>
                            <div class="col-sm-8">${bookingData.quantity}</div>
                        </div>`;
                    }

                } else if (bookingType === 'escursione') {
                    const dateDep = bookingData.date_dep ? new Date(bookingData.date_dep).toLocaleString('it-IT') : 'N/A';

                    details += `<div class="row mb-2">
                        <div class="col-sm-4"><strong>Escursione:</strong></div>
                        <div class="col-sm-8">${bookingData.excursion_name || 'N/A'}</div>
                    </div>`;

                    details += `<div class="row mb-2">
                        <div class="col-sm-4"><strong>Data:</strong></div>
                        <div class="col-sm-8">${bookingData.date || dateDep}</div>
                    </div>`;

                    if (bookingData.departure_name) {
                        details += `<div class="row mb-2">
                            <div class="col-sm-4"><strong>Partenza:</strong></div>
                            <div class="col-sm-8">${bookingData.departure_name}</div>
                        </div>`;
                    }

                    if (bookingData.passengers) {
                        details += `<div class="row mb-2">
                            <div class="col-sm-4"><strong>Passeggeri:</strong></div>
                            <div class="col-sm-8">${bookingData.passengers}</div>
                        </div>`;
                    }
                }

                if (booking.body) {
                    details += `<div class="row mb-2">
                        <div class="col-sm-4"><strong>Note:</strong></div>
                        <div class="col-sm-8">${booking.body}</div>
                    </div>`;
                }

                modalContent.innerHTML = details;

                // Mostra il modale
                const modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
                modal.show();
            }
        });
    </script>
</x-dashboard-layout>
