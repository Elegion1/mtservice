<div>
    <div class="container">
        <div class="container-fluid m-0 p-0">
            @if ($bookingData['type'] == 'transfer')
                <h1>Riepilogo Prenotazione</h1>

                <p>Tipologia:
                    <span class="text-primary"> {{ ucfirst($bookingData['type']) }} </span>
                </p>

                <p>
                    Da:
                    <span class="text-primary"> {{ $bookingData['departure_name'] ?? 'N/A' }} </span>
                    A: <span class="text-primary"> {{ $bookingData['arrival_name'] ?? 'N/A' }} </span>
                </p>

                <p>
                    Data:
                    <span class="text-primary"> {{ $bookingData['date_departure'] ?? 'N/A' }} </span>
                    ore: <span class="text-primary"> {{ $bookingData['time_departure'] ?? 'N/A' }} </span>
                </p>
                <p>
                    Durata:
                    <span class="text-primary"> {{ $bookingData['duration'] ?? 'N/A' }} </span> Minuti circa
                </p>

                @if (!empty($bookingData['date_ret']))
                    <p>
                        Ritorno:
                        <span class="text-primary"> {{ $bookingData['date_return'] }} </span>
                        ore <span class="text-primary"> {{ $bookingData['time_return'] }} </span>
                    </p>
                @endif
                <p>
                    Passeggeri:
                    <span class="text-primary"> {{ $bookingData['passengers'] ?? 'N/A' }} </span>
                </p>
                <p>
                    Prezzo Totale:
                    <span class="text-primary"> {{ $bookingData['price'] ?? 'N/A' }} </span> €
                </p>


            @endif
            @if ($bookingData['type'] == 'escursione')
                <h1>Riepilogo Prenotazione</h1>

                <p>Tipologia:
                    <span class="text-primary"> {{ ucfirst($bookingData['type']) }} </span>
                    a
                    <span class="text-primary"> {{ $bookingData['departure_name'] ?? 'N/A' }} </span>
                </p>

                <p>
                    Data:
                    <span class="text-primary"> {{ $bookingData['date_departure'] ?? 'N/A' }} </span>
                    ore:
                    <span class="text-primary"> {{ $bookingData['time_departure'] ?? 'N/A' }} </span>
                </p>

                <p>Passeggeri:
                    <span class="text-primary"> {{ $bookingData['passengers'] ?? 'N/A' }} </span>
                </p>
                <p>
                    Prezzo Totale:
                    <span class="text-primary"> {{ $bookingData['price'] ?? 'N/A' }} €</span>
                </p>
            @endif
            @if ($bookingData['type'] == 'noleggio')
                <h1>Riepilogo Prenotazione</h1>

                <p>Tipologia:
                    <span class="text-primary"> {{ ucfirst($bookingData['type']) }} </span>

                    <span class="text-primary"> {{ $bookingData['car_name'] ?? 'N/A' }}
                        {{ $bookingData['car_description'] ?? 'N/A' }} </span>
                </p>

                <p>
                    Data di ritiro:
                    <span class="text-primary"> {{ $bookingData['date_start'] ?? 'N/A' }} </span>
                    data di consegna:
                    <span class="text-primary"> {{ $bookingData['date_end'] ?? 'N/A' }} </span>
                </p>

                <p>Quantità:
                    <span class="text-primary"> {{ $bookingData['quantity'] ?? 'N/A' }} </span>
                </p>
                <p>
                    Prezzo Totale:
                    <span class="text-primary"> {{ $bookingData['price'] ?? 'N/A' }} €</span>
                </p>
            @endif
        </div>
        <form wire:submit.prevent="confirmBooking">
            <h6 class="text-primary">DATI PERSONALI</h6>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" wire:model="name" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="surname" class="form-label">Cognome</label>
                        <input type="text" class="form-control" id="surname" wire:model="surname" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" wire:model="email" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefono</label>
                        <input type="tel" class="form-control" id="phone" wire:model="phone" required>
                    </div>
                </div>
            </div>


            <div class="mb-3">
                <textarea class="form-control" id="body" wire:model="body">Inserisci delle note</textarea>
            </div>
            <div class="container-fluid m-0 p-0 d-flex justify-content-center align-items-center">
                <button type="submit" class="btn bg-a text-white">Conferma Prenotazione</button>

            </div>
        </form>
    </div>
</div>
