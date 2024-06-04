<div>
    <div class="container w-50 mx-auto my-3 p-5 border rounded shadow">
        <form wire:submit="submitBookingTransfer" >
            <h1>Prenota Transfer</h1>

            <div class="mb-3 row">
                <div class="col-12 mb-3">
                    <select wire:model.live="departure" id="departureSelect" class="form-select" aria-label="Default select example">
                        <option selected>Seleziona partenza</option>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination->id }}">
                                {{ $destination->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <select wire:model.live="return" wire:change="calculatePrice" id="returnSelect" class="form-select" aria-label="Default select example">
                        <option selected>Seleziona destinazione</option>
                        @foreach ($routes as $route)
                            @if ($route->departure->id == $departure)
                                <option value="{{ $route->arrival->id }}">{{ $route->arrival->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-6">
                    <label for="transferPassengers" class="form-label">Passeggeri</label>
                    <input wire:model.live="transferPassengers" type="number" class="form-control" id="transferPassengers" min="1" max="16" value="1">
                </div>
                <div class="col-6 d-flex flex-column align-items-start justify-content-end ">
                    <div class="form-check">
                        <input wire:click="setSolaAndata" class="form-check-input" type="radio" name="tripType" id="solaAndata" {{ $solaAndata ? 'checked' : '' }}>
                        <label class="form-check-label" for="solaAndata">
                            Andata
                        </label>
                    </div>
                    <div class="form-check">
                        <input wire:click="setAndataRitorno" class="form-check-input" type="radio" name="tripType" id="andataRitorno" {{ $andataRitorno ? 'checked' : '' }}>
                        <label class="form-check-label" for="andataRitorno">
                            Andata e Ritorno
                        </label>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-6">
                    <label class="form-label" for="dateDeparture">Andata</label>
                    <input wire:model.live="dateDeparture" type="datetime-local" class="form-control" id="dateDeparture">
                </div>
                <div class="col-6">
                    <label class="form-label" for="dateReturn">Ritorno</label>
                    <input wire:model.live="dateReturn" type="datetime-local" class="form-control" id="dateReturn" {{ $solaAndata ? 'disabled' : '' }}>
                </div>
            </div>
            <div class="row mb-3 align-items-end">
                <div class="col-6">
                    <label for="transferPrice" class="form-label">Totale</label>
                    <input wire:model.live="transferPrice" readonly type="text" class="form-control" id="transferPrice" value="">
                </div>
                <div class="col-6 d-grid">
                    <button type="submit" class="btn bg-a text-white">Prenota</button>
                </div>
            </div>
        </form>
    </div>

</div>
