<div>
    <div class="container w-50 mx-auto my-3 p-5 border rounded shadow">
        <form wire:submit="submitBookingExcursion"  >
            <h1>Prenota Escursione</h1>

            <div class="mb-3 row">
                <div class="col-12">
                    <select wire:model.live="excursionSelect" wire:change="calculatePriceExcursion" id="excursionSelect" class="form-select">
                        <option selected>Seleziona Escursione</option>
                        @foreach ($excursions as $excursion)
                            <option value="{{ $excursion->id }}" data-price="{{ $excursion->price }}">
                                {{ $excursion->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-6">
                    <label for="excursionPassengers" class="form-label">Passeggeri</label>
                    <input wire:model.live="excursionPassengers" type="number" class="form-control" id="excursionPassengers" min="1" max="16" value="1">
                </div>
                <div class="col-6">
                    <label class="form-label" for="dateExcursion">Andata</label>
                    <input wire:model.live="excursionDate" type="datetime-local" class="form-control" id="dateExcursion">
                </div>

            </div>
            <div class="row mb-3 align-items-end">
                <div class="col-6">
                    <label for="excursionPrice" class="form-label">Totale</label>
                    <input wire:model.live="excursionPrice" readonly type="text" class="form-control" id="excursionPrice" value="">
                </div>
                <div class="col-6 d-grid">
                    <button type="submit" class="btn bg-a text-white">Prenota</button>
                </div>
            </div>
        </form>
    </div>

 
</div>