<x-dashboard-layout>
    <div class="container mt-5">
        <h2>Gestione Prenotazioni</h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>Messaggio</th>
                    <th>Dati prenotazione</th>
                    <th>Tipo servizio</th>
                    <th>Prezzo</th>
                    <th>Data di aggiunta</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->name }}</td>
                        <td>{{ $booking->surname }}</td>
                        <td><a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a></td>
                        <td><a href="tel:{{ $booking->phone }}">{{ $booking->phone }}</a></td>
                        <td>{{ $booking->body }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm open-details-modal" data-bs-toggle="modal" data-bs-target="#bookingDetailsModal"
                                data-booking-data="{{ json_encode($booking->bookingData) }}">
                                Apri info
                            </button>
                        </td>
                        <td>{{ ucfirst($booking->bookingData['type']) }}</td>
                        <td>{{ $booking->bookingData['price'] }} €</td>
                        <td>{{ $booking->created_at }}</td>
                        <td>
                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Elimina</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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

    <!-- JavaScript per il modale -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
        });
    </script>

</x-dashboard-layout>
