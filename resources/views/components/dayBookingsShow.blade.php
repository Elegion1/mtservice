<div class="day-bookings border" data-date="{{ $date }}">
    <div
        class="p-1 bg-secondary-subtle sticky-top d-flex @if (isset($dayGroup)) justify-content-center @else justify-content-between @endif">
        @if (isset($dayGroup))
            <strong>
                {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
            </strong>
        @else
            <strong>
                {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
            </strong>
            <strong>
                {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
            </strong>

        @endif
    </div>
    @foreach ($dayBookings as $booking)
        <div class="booking-item border-bottom border-1 container-fluid" data-type="{{ $booking->bookingData['type'] }}"
            data-favignana="{{ isset($booking->bookingData['sito_favignana']) ? 'true' : 'false' }}">
            <div class="row">
                <div class="col-3 p-0">
                    <div class="row gap-1">

                        <span class="col-6 fs-5">
                            {{ \Carbon\Carbon::parse($booking->start_date ?? $booking->end_date)->format('H:i') }}
                        </span>
                        <span class="col-5 fs-4">
                            @if ($booking->payment_status == 'pending')
                                <i class="bi bi-hourglass text-warning" title="Pagamento in attesa"></i>
                            @elseif ($booking->payment_status == 'deposit_paid')
                                <i class="bi bi-cash-coin text-primary" title="Acconto pagato"></i>
                            @elseif ($booking->payment_status == 'paid')
                                <i class="bi bi-check-circle-fill text-success" title="Pagato"></i>
                            @endif
                        </span>

                        <span class="col-12 text-primary">
                            {{ $booking->code }}
                        </span>
                    </div>
                </div>

                <div class="col-9 p-0">
                    <button
                        class="btn open-details-modal ps-1 h-100 
                    @if ($booking->bookingData['type'] == 'noleggio') bg-warning
                    @elseif ($booking->bookingData['type'] == 'escursione')
                            bg-success
                    @elseif ($booking->bookingData['type'] == 'transfer')

                        @if (!empty($booking->bookingData['sito_favignana']))
                            bg-info 
                        @else
                            bg-danger @endif 

                    @endif
                            "
                        data-bs-toggle="modal" data-bs-target="#bookingDetailsModal"
                        data-booking-data="{{ json_encode($booking->bookingData) }}"
                        data-booking="{{ json_encode($booking) }}">
                        <p class="text-black text-decoration-underline text-start text-small mb-0 text-wrap">

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
                                <strong>PAX</strong> {{ $booking->bookingData['passengers'] }}
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
            const booking = JSON.parse(button.getAttribute('data-booking'));
            console.log(booking);

            showBookingDetailsModal(bookingData, booking);
        });
    });

    // Funzione per mostrare i dettagli della prenotazione nel modale
    function showBookingDetailsModal(bookingData, booking) {
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

        const name = booking.name || 'N/A';
        const surname = booking.surname || 'N/A';
        const dial_code = booking.dial_code || 'N/A';
        const phone = booking.phone || 'N/A';
        const email = booking.email || 'N/A';
        const body = booking.body || 'N/A';
        const status = booking.status || 'N/A';
        const payment_status = booking.payment_status || 'N/A';
        const id = booking.id || 'N/A';
        const start_date = booking.start_date;
        const end_date = booking.end_date;

        const formattedBody = body.replace(/\n/g, '<br>');

        let modalInnerHTML = `
        <p><span class="text-primary">${name} ${surname}</span></p>
        <p><a href="tel:${dial_code}${phone}">${phone}</a> <a href="mailto:${email}">${email}</a></p>
        <p>Note: <span class="text-primary">${formattedBody}</span></p>
        <p>Tipologia: <span class="text-primary text-capitalize">${bookingData.type}</span></p>`;

        if (bookingType === 'transfer' && start_date !== 'N/A') {
            modalInnerHTML += `
        <p>Data di partenza: <span class="text-primary">${dateDep}</span></p>
        <p>Transfer da: <span class="text-primary">${bookingData.departure_name}</span></p>
        <p>Transfer a: <span class="text-primary">${bookingData.arrival_name}</span></p>`;
        } else if (bookingType === 'transfer' && end_date !== 'N/A') {
            modalInnerHTML += `
        <p>Data di ritorno: <span class="text-primary">${dateRet}</span></p>`;
        } else if (bookingType === 'escursione') {
            modalInnerHTML += `
        <p>A: <span class="text-primary">${bookingData.departure_name}</span></p>
        <p>Data di partenza: <span class="text-primary">${dateDep}</span></p>`;
        } else if (bookingType === 'noleggio') {
            modalInnerHTML += `
        <p>Auto: <span class="text-primary">${bookingData.car_name}</span></p>
        <p>Ritiro: <span class="text-primary">${dateStart}</span></p>
        <p>Consegna: <span class="text-primary">${dateEnd}</span></p>`;
        }

        let paymentStatus;
        if (payment_status === 'pending') {
            paymentStatus = 'In attesa';
        } else if (payment_status === 'deposit_paid') {
            paymentStatus = 'Acconto pagato';
        } else if (payment_status === 'paid') {
            paymentStatus = 'Pagato';
        }

        modalInnerHTML +=
            `<p>Prezzo: <span class="text-primary">${bookingData.price} €</span> | Acconto 30%: <span class="text-primary">${(bookingData.price * 0.3).toFixed(2)} €</span></p>
        <p>Pagamento: <span class="text-primary">${paymentStatus}</span></p>`;

        let newPaymentStatus;
        let buttonLabel;

        if (payment_status === 'pending') {
            newPaymentStatus = 'deposit_paid';
            buttonLabel = 'Acconto pagato';
        } else if (payment_status === 'deposit_paid') {
            newPaymentStatus = 'paid';
            buttonLabel = 'Pagato in totale';
        } else if (payment_status === 'paid') {
            newPaymentStatus = 'pending';
            buttonLabel = 'In lavorazione';
        }

        modalInnerHTML += `
    <div class="d-flex justify-content-between">
        <div class="border-end border-3 pe-2 border-black">
        <form action="/dashboard/bookings/${id}/update-status" method="POST"
                        style="display:inline-block;">
                        @csrf
                        <input type="hidden" name="status" value="pending">
                        <button title="Sposta in lavorazione" type="submit" class="btn btn-warning">
                            In attesa
                        </button>
                    </form>
        <form id="rejectForm_${id}" action="/dashboard/bookings/${id}/update-status"
                method="POST" style="display:inline-block;">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <button type="button" class="btn btn-danger" onclick="confermaRifiuto(${id})">
                    Rifiuta
                </button>
        </form>
        </div>
        <form action="/dashboard/bookings/${id}/update-status"
                method="POST" style="display:inline-block;">
                @csrf
                <input type="hidden" name="payment_status" value="${newPaymentStatus}">
                <button type="submit" class="btn btn-warning">
                    ${buttonLabel}
                </button>
        </form>
    </div>`;


        modalContent.innerHTML = modalInnerHTML;

        // Apri il modale
        modal.style.display = 'block';
    }

    function confermaRifiuto(id) {
        if (confirm("Sei sicuro di voler rifiutare questa prenotazione?")) {
            document.getElementById(`rejectForm_${id}`).submit();
        }
    }
</script>
