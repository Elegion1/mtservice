<div>
    <div class="container ">
        <form wire:submit.prevent="submitBookingTransfer" id="transferForm">
            <h2 class="text-uppercase text-danger"><strong>Prenota Transfer</strong></h2>

            <div class="row">
                <div class="col-12">
                    <select wire:model.live="departure" id="departureSelect" class="form-select"
                        aria-label="Default select example">
                        <option selected>Seleziona partenza</option>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination->id }}">
                                {{ $destination->name }}</option>
                        @endforeach
                    </select>
                    <div class="error-message">
                        @error('departure')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <select wire:model.live="return" wire:change="calculatePrice" id="returnSelect" class="form-select"
                        aria-label="Default select example">
                        <option selected>Seleziona destinazione</option>
                        @foreach ($routes as $route)
                            @if ($route->departure->id == $departure)
                                <option value="{{ $route->arrival->id }}">{{ $route->arrival->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <div class="error-message">
                        @error('return')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label for="transferPassengers" class="form-label">Passeggeri</label>
                    <input wire:model.live="transferPassengers" type="number" class="form-control"
                        id="transferPassengers" min="1" max="16" value="1">
                    <div class="error-message">
                        @error('transferPassengers')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-6 d-flex flex-column align-items-start justify-content-end ">
                    <div class="form-check">
                        <input wire:click="setSolaAndata" class="form-check-input" type="radio" name="tripType"
                            id="solaAndata" {{ $solaAndata ? 'checked' : '' }}>
                        <label class="form-check-label" for="solaAndata">
                            Andata
                        </label>
                    </div>
                    <div class="form-check">
                        <input wire:click="setAndataRitorno" class="form-check-input" type="radio" name="tripType"
                            id="andataRitorno" {{ $andataRitorno ? 'checked' : '' }}>
                        <label class="form-check-label" for="andataRitorno">
                            Andata e Ritorno
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="dateDeparture">Andata</label>
                    <input wire:model.live="dateDeparture" type="datetime-local" class="form-control"
                        id="dateDeparture">
                    <div class="error-message"> @error('dateDeparture')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="dateReturn">Ritorno</label>
                    <input wire:model.live="dateReturn" type="datetime-local" class="form-control" id="dateReturn"
                        {{ $solaAndata ? 'disabled' : '' }}>
                    <div class="error-message"> @error('dateReturn')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3 align-items-end">
                <div class="col-6">
                    <label for="transferPrice" class="form-label">Totale</label>
                    <input wire:model.live="transferPrice" readonly type="text" class="form-control"
                        id="transferPrice" value="€">
                </div>
                <div class="col-6 d-grid">
                    <button type="submit" class=" btn bg-a text-white">Prenota</button>
                </div>
            </div>
        </form>
    </div>
</div>
