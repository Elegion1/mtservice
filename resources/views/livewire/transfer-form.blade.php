<div>
    <div class="container w-50 mx-auto my-3 p-5 border rounded shadow">
        <form wire:submit.prevent="submit">
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
                    <select wire:model.live="return" wire:change="calculatePrice" 
                    {{-- wire:key="{{$departure}}"  --}}
                    id="returnSelect" class="form-select" aria-label="Default select example">
                        <option selected>Seleziona destinazione</option>
                        @foreach ($routes as $route)
                            @if ($route->departure->id == $departure)
                                <option  
                                {{-- data-price-transfer="{{$route->price}}" data-price-transfer-increment="{{$route->price_increment}}" --}}
                                value="{{ $route->arrival->id }}">{{ $route->arrival->name }}</option>
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
            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label" for="arrivalDate">Data di partenza</label>
                    <input type="datetime-local" class="form-control" id="arrivalDate">
                </div>
                <div class="col-6">
                    <label class="form-label" for="returnDate">Data di ritorno</label>
                    <input type="datetime-local" class="form-control" id="returnDate">
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

{{-- <script>
    document.getElementById('returnSelect').addEventListener('change', function() {
        calculatePriceTransfer();
    });

    document.getElementById('transferPassengers').addEventListener('input', function() {
        calculatePriceTransfer();
    });

    document.getElementById('solaAndata').addEventListener('change', function() {
        calculatePriceTransfer();
    });

    document.getElementById('andataRitorno').addEventListener('change', function() {
        calculatePriceTransfer();
    });

    function calculatePriceTransfer() {
        const returnSelect = document.getElementById('returnSelect');
        const selectedOptionT = returnSelect.options[returnSelect.selectedIndex];
        const basePriceT = parseFloat(selectedOptionT.getAttribute('data-price-transfer')) || 0;
        const incrementPrice = parseFloat(selectedOptionT.getAttribute('data-price-transfer-increment')) || 0;
        const transferPassengers = parseInt(document.getElementById('transferPassengers').value) || 0;
        let totalPriceT = basePriceT;

        if (transferPassengers > 4) {
            totalPriceT += incrementPrice * (transferPassengers - 4);
        }

        const priceElementT = document.getElementById('transferPrice');
        priceElementT.value = totalPriceT.toLocaleString('it-IT', {
            style: 'currency',
            currency: 'EUR'
        });
    }
</script> --}}
