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
            data-origin="{{ isset($booking->bookingData['sito_favignana']) ? 'favignana' : 'tranchida' }}">
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
                        <span class="text-uppercase text-small text-white text-center originShow"></span>

                        <span class="col-12 text-primary">
                            {{ $booking->code }}
                        </span>
                    </div>
                </div>

                <div class="col-9 p-0">
                    <button
                        class="btn open-details-modal ps-1 h-100 booking-card 
                        {{-- @if ($booking->bookingData['type'] == 'noleggio') bg-warning
                        @elseif ($booking->bookingData['type'] == 'escursione')
                            @if (!empty($booking->bookingData['sito_favignana']))
                                bg-info 
                            @else
                                bg-success
                            @endif
                        @elseif ($booking->bookingData['type'] == 'transfer')
                            @if (!empty($booking->bookingData['sito_favignana']))
                                bg-info 
                            @else
                                bg-danger 
                            @endif 
                        @endif --}}
                        "
                        data-bs-toggle="modal" data-bs-target="#bookingDetailsModal"
                        data-booking="{{ json_encode($booking) }}">
                        <p class="text-black text-decoration-underline text-start text-small mb-0 text-wrap">

                            {{ strtoupper($booking->name) }} {{ strtoupper($booking->surname) }} >>
                            {{ ucfirst($booking->bookingData['type']) }}
                            @if ($booking->bookingData['type'] == 'noleggio')
                                <strong>{{ $booking->bookingData['car_name'] }}</strong>
                                @if ($booking->start_date)
                                    >> Ritiro
                                @elseif ($booking->end_date)
                                    >> Consegna
                                @endif
                                @if ($booking->bookingData['kasko_enabled'] && $booking->bookingData['kasko_enabled'] == true)
                                    >> KASKO
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
    function originPrint() {
        const bookingItems = document.querySelectorAll('.booking-item');

        bookingItems.forEach(item => {
            const origin = item.getAttribute('data-origin');
            const originEl = item.querySelector('.originShow');
            
            if (originEl) {
                if (origin === 'favignana') {
                    originEl.innerHTML = 'FAVIGNANA';
                    originEl.classList.add('bg-favignana');
                    originEl.classList.remove('bg-tranchida');
                } else {
                    originEl.innerHTML = 'TRANCHIDA';
                    originEl.classList.add('bg-tranchida');
                    originEl.classList.remove('bg-favignana');
                }
            }
        });
    }

    originPrint();

    document.querySelectorAll('.open-details-modal').forEach(button => {
        button.addEventListener('click', () => {

            const booking = JSON.parse(button.getAttribute('data-booking'));
            const bookingData = booking.bookingData;
            const info = booking.info;

            showBookingDetailsModal(bookingData, booking, info);
        });
    });

    function toggleDetails(key) {
        const detailsElement = document.getElementById(`details-${key}`);
        const iconElement = document.getElementById(`icon-${key}`);

        // Controlla se i dettagli sono visibili
        if (detailsElement.style.display === 'none') {
            detailsElement.style.display = 'block';
            iconElement.classList.remove('bi-chevron-down');
            iconElement.classList.add('bi-chevron-up');
        } else {
            detailsElement.style.display = 'none';
            iconElement.classList.remove('bi-chevron-up');
            iconElement.classList.add('bi-chevron-down');
        }
    }

    // Funzione per mostrare i dettagli della prenotazione nel modale
    function showBookingDetailsModal(bookingData, booking, info) {
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

        const translations = {
            flight: {
                flightNumber: 'Numero volo',
                departureAirport: 'Aeroporto partenza',
                departureTime: 'Ora partenza',
                arrivalAirport: 'Aeroporto arrivo',
                arrivalTime: 'Ora arrivo'
            },
            driver: {
                driverName: 'Nome conducente',
                driverBirthDate: 'Data nascita',
                driverBirthPlace: 'Luogo nascita',
                driverAddress: 'Indirizzo',
                driverCity: 'Città',
                driverPostalCode: 'CAP',
                driverCountry: 'Paese',
                driverLicenseNumber: 'Numero patente',
                driverLicenseType: 'Tipo patente',
                driverLicenseIssueDate: 'Rilascio patente',
                driverLicenseExpirationDate: 'Scadenza patente',
                driverLicenseCountry: 'Paese rilascio patente',
                driverLicenseProvince: 'Provincia rilascio patente'
            }
        };

        let modalInnerHTML = `
        <p>
            <span class="text-primary text-uppercase">${bookingData.type}</span>
        </p>
        <p>    
            <span class="text-primary text-uppercase">${name} ${surname}</span>
        </p>
        <p>Tel: <a href="tel:${dial_code}${phone}">${phone}</a> Mail: <a href="mailto:${email}">${email}</a></p>
        <p>Note: <br/> <span class="text-primary">${formattedBody}</span></p>`;

        // Verifica che info sia un oggetto
        if (typeof info === 'object' && info !== null) {
            // Itera sulle chiavi di info (come 'flight' e 'driver')
            Object.entries(info).forEach(([key, value]) => {
                // Aggiungi un'intestazione per ogni gruppo di dati (come 'Flight' o 'Driver')
                modalInnerHTML += `
                <p class="text-uppercase text-primary border rounded p-1 m-0" style="cursor: pointer;" onclick="toggleDetails('${key}')">
                Info ${key === 'flight' ? 'Volo' : key === 'driver' ? 'Guidatore' : key} <i id="icon-${key}" class="bi bi-chevron-down"></i>
                </p>`;

                // Aggiungi una sezione che contiene i dettagli da nascondere inizialmente
                modalInnerHTML +=
                    `<div id="details-${key}" class="border rounded p-1 mb-1" style="display: none;">`;

                // Se la proprietà è un oggetto, itera sui suoi campi
                if (typeof value === 'object' && value !== null) {
                    Object.entries(value).forEach(([subKey, subValue]) => {
                        if (subKey === 'flightNumber') {
                            subValue =
                                `<a target="_blank" href="https://www.flightradar24.com/data/flights/${subValue}">${subValue}</a>`;
                        }
                        if (subKey && subValue) {
                            // Recupera la traduzione della subkey (se esiste)
                            const translatedSubKey = translations[key] && translations[key][subKey] ?
                                translations[key][subKey] : subKey;

                            modalInnerHTML += `
                        <p>${translatedSubKey}: <span class="text-primary text-uppercase">${subValue}</span></p>
                    `;
                        }
                    });
                }

                // Chiudi il contenitore dei dettagli
                modalInnerHTML += `</div> <div class="mb-3"></div>`;
            });
        } else {
            console.warn("La variabile 'info' non è un oggetto:", info);
        }

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
        <p>Luogo ritiro: <span class="text-primary">${bookingData.pickup}</span></p>
        <p>Consegna: <span class="text-primary">${dateEnd}</span></p>
        <p>Luogo consegna: <span class="text-primary">${bookingData.delivery}</span></p>
        <p>KASKO: <span class="text-primary">${bookingData.kasko_enabled ? 'SI' : 'NO'}</span></p>`;
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
