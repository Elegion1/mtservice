<div>
    <div class="container">
        <div class="container-fluid m-0 p-0">
            <h1>Riepilogo Prenotazione</h1>
            <p>Tipologia: <span class="text-primary">{{ ucfirst($bookingData['type']) }}</span></p>

            @if ($bookingData['type'] == 'transfer')
                <p>Da: <span class="text-primary">{{ $bookingData['departure_name'] ?? 'N/A' }}</span></p>
                <p>A: <span class="text-primary">{{ $bookingData['arrival_name'] ?? 'N/A' }}</span></p>
                <p>Data: <span class="text-primary">{{ $bookingData['date_departure'] ?? 'N/A' }}</span></p>
                <p>Ore: <span class="text-primary">{{ $bookingData['time_departure'] ?? 'N/A' }}</span></p>
                <p>Durata: <span class="text-primary">{{ $bookingData['duration'] ?? 'N/A' }}</span> Minuti circa</p>
                @if (!empty($bookingData['date_ret']))
                    <p>Ritorno: <span class="text-primary">{{ $bookingData['date_return'] }}</span> ore <span
                            class="text-primary">{{ $bookingData['time_return'] }}</span></p>
                @endif
                <p>Passeggeri: <span class="text-primary">{{ $bookingData['passengers'] ?? 'N/A' }}</span></p>
                <p>Prezzo Totale: <span class="text-primary">{{ $bookingData['price'] ?? 'N/A' }}</span> €</p>
            @elseif ($bookingData['type'] == 'escursione')
                <p>Tipologia: <span class="text-primary">{{ ucfirst($bookingData['type']) }}</span> a <span
                        class="text-primary">{{ $bookingData['departure_name'] ?? 'N/A' }}</span></p>
                <p>Data: <span class="text-primary">{{ $bookingData['date_departure'] ?? 'N/A' }}</span></p>
                <p>Ore: <span class="text-primary">{{ $bookingData['time_departure'] ?? 'N/A' }}</span></p>
                <p>Durata: <span class="text-primary">{{ $bookingData['duration'] ?? 'N/A' }}</span></p>
                <p>Passeggeri: <span class="text-primary">{{ $bookingData['passengers'] ?? 'N/A' }}</span></p>
                <p>Prezzo Totale: <span class="text-primary">{{ $bookingData['price'] ?? 'N/A' }} €</span></p>
            @elseif ($bookingData['type'] == 'noleggio')
                <p>Tipologia: <span class="text-primary">{{ ucfirst($bookingData['type']) }}</span> <span
                        class="text-primary">{{ $bookingData['car_name'] ?? 'N/A' }}
                        {{ $bookingData['car_description'] ?? 'N/A' }}</span></p>
                <p>Data di ritiro: <span class="text-primary">{{ $bookingData['date_start'] ?? 'N/A' }}</span> data di
                    consegna: <span class="text-primary">{{ $bookingData['date_end'] ?? 'N/A' }}</span></p>
                <p>Quantità: <span class="text-primary">{{ $bookingData['quantity'] ?? 'N/A' }}</span> Prezzo Totale:
                    <span class="text-primary">{{ $bookingData['price'] ?? 'N/A' }} €</span>
                </p>
            @endif
        </div>
        <form wire:submit.prevent="confirmBooking">
            <h6 class="text-primary">DATI PERSONALI</h6>
            <div class="row">
                <div class="col-12">
                    <p class="text-danger">Siamo dotati di seggiolini e alzatine per trasporto bimbi da 1 mese a 10
                        anni. Aggiungi la tua richiesta durante la prenotazione.</p>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" wire:model="name">
                        <div class="error-message">
                            @error('name')
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="surname" class="form-label">Cognome</label>
                        <input type="text" class="form-control" id="surname" wire:model="surname">
                        <div class="error-message">
                            @error('surname')
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" wire:model="email">
                        <div class="error-message">
                            @error('email')
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefono</label>
                        <input type="text" class="form-control" id="phone" wire:model="phone" minlength="8"
                            maxlength="15">
                        <div class="error-message">
                            @error('phone')
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <textarea class="form-control" id="body" wire:model="body" placeholder="Inserisci delle note"></textarea>
                <div class="error-message">
                    @error('body')
                        <span class="text-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="container-fluid m-0 p-0 d-flex justify-content-center align-items-center">
                <button type="submit" class=" btn bg-a text-white" wire:loading.attr="disabled">Conferma
                    Prenotazione</button>
            </div>
        </form>

        <!-- Messaggio di caricamento -->
        <div wire:loading wire:target="confirmBooking text-center">
            <p class="h3 text-success">Caricamento in corso... Si prega di attendere.</p>
        </div>
    </div>
</div>
