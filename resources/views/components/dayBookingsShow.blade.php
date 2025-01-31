<div class="day-bookings overflow-auto overflow-x-hidden border" style="height: 55vh;" data-date="{{ $date }}">
    @if (isset($dayGroup))
        <div class="p-1 bg-secondary-subtle d-flex justify-content-start">
            <strong>
                {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
            </strong>
        </div>
        @else
        <div class="p-1 bg-secondary-subtle d-flex justify-content-between">
            <strong>
                {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
            </strong>
            <strong>
                {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
            </strong>
        </div>
    @endif
    @foreach ($dayBookings as $booking)
        <div
            class="booking-item border-bottom border-2
        @if ($booking->bookingData['type'] == 'noleggio') bg-warning-subtle
        @elseif ($booking->bookingData['type'] == 'escursione')
            bg-info-subtle
        @elseif ($booking->bookingData['type'] == 'transfer')
            bg-success-subtle @endif
        ">
            <div class="row p-1">
                <div class="col-3 text-small">
                    <div class="row">
                        <div class="col-12 ps-4">
                            <span>
                                {{ \Carbon\Carbon::parse($booking->start_date ?? $booking->end_date)->format('H:i') }}
                            </span>
                        </div>
                        <div class="col-12 ps-3">
                            <span class="text-primary">
                                {{ $booking->code }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <button class="btn open-details-modal p-0" data-bs-toggle="modal"
                        data-bs-target="#bookingDetailsModal"
                        data-booking-data="{{ json_encode($booking->bookingData) }}"
                        data-booking-id="{{ $booking->id }}" data-booking-start="{{ $booking->start_date }}"
                        data-booking-end="{{ $booking->end_date }}" data-booking-name="{{ $booking->name }}"
                        data-booking-surname="{{ $booking->surname }}" data-booking-phone="{{ $booking->phone }}"
                        data-booking-email="{{ $booking->email }}" data-booking-body="{{ $booking->body }}">
                        <p class="text-primary text-decoration-underline text-start mb-0 text-wrap text-small">
                            {{ $booking->name }} {{ $booking->surname }} >>
                            {{ ucfirst($booking->bookingData['type']) }}
                            @if ($booking->bookingData['type'] == 'noleggio')
                                <strong>{{ $booking->bookingData['car_name'] }}</strong>
                                @if ($booking->start_date)
                                    >> Ritiro
                                @elseif ($booking->end_date)
                                    >> Consegna
                                @endif
                            @elseif ($booking->bookingData['type'] == 'escursione')
                                >> <strong>{{ $booking->bookingData['departure_name'] }}</strong> >>
                            @endif
                            @if ($booking->start_date && $booking->bookingData['type'] == 'transfer')
                                >> <strong>{{ $booking->bookingData['departure_name'] }}</strong>
                                >>
                                <strong>{{ $booking->bookingData['arrival_name'] }}</strong> >>
                            @endif
                            @if ($booking->end_date && $booking->bookingData['type'] == 'transfer')
                                >> <strong>{{ $booking->bookingData['arrival_name'] }}</strong>
                                >>
                                <strong>{{ $booking->bookingData['departure_name'] }}</strong> >>
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

<script>
    document.querySelectorAll('.open-details-modal').forEach(button => {
        button.addEventListener('click', () => {
            const bookingData = JSON.parse(button.getAttribute('data-booking-data'));
            var start_date = button.getAttribute('data-booking-start') || 'N/A';
            var end_date = button.getAttribute('data-booking-end') || 'N/A';
            var name = button.getAttribute('data-booking-name') || 'N/A';
            var surname = button.getAttribute('data-booking-surname') || 'N/A';
            var phone = button.getAttribute('data-booking-phone') || 'N/A';
            var email = button.getAttribute('data-booking-email') || 'N/A';
            var body = button.getAttribute('data-booking-body') || 'N/A';
            var id = button.getAttribute('data-booking-id') || 'N/A';

            console.log(start_date, bookingData.date_dep);
            console.log(end_date, bookingData.date_ret);

            showBookingDetailsModal(bookingData, start_date, end_date, name, surname, phone,
                email, body, id);
        });
    });

    // Funzione per mostrare i dettagli della prenotazione nel modale
    function showBookingDetailsModal(bookingData, start_date, end_date, name, surname, phone, email, body, id) {
        const modal = document.getElementById('bookingDetailsModal');
        const modalContent = document.getElementById('bookingDetailsContent');
        modalContent.innerHTML = ''; // Pulisci il contenuto del modale

        // Crea il contenuto del modale con i dettagli della prenotazione
        const bookingType = bookingData.type;

        const dateDep = bookingData.date_dep ? new Date(bookingData.date_dep).toLocaleString('it-IT') :
            'N/A';
        const dateRet = bookingData.date_ret ? new Date(bookingData.date_ret).toLocaleString('it-IT') :
            'N/A';
        const dateStart = bookingData.date_start ? new Date(bookingData.date_start).toLocaleString(
            'it-IT') : 'N/A';
        const dateEnd = bookingData.date_end ? new Date(bookingData.date_end).toLocaleString('it-IT') :
            'N/A';

        let modalInnerHTML = `
        <p><span class="text-primary">${name} ${surname}</span></p>
        <p><a href="tel:${phone}">${phone}</a> <a href="mailto:${email}">${email}</a></p>
        <p>Messaggio: <span class="text-primary">${body}</span></p>
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
        <p>Ritiro: <span class="text-primary">${dateStart}</span></p>
        <p>Consegna: <span class="text-primary">${dateEnd}</span></p>
        <p>Auto: <span class="text-primary">${bookingData.car_name}</span></p>
        <p>Descrizione: <span class="text-primary">${bookingData.car_description}</span></p>`;
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
</script>
