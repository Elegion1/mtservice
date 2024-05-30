<div>
    <div class="container w-50 mx-auto my-3 p-5 border rounded shadow">
        <form>
            <h1>Prenota Escursione</h1>

            <div class="mb-3 row">
                <div class="col-12">
                    <select id="excursionSelect" class="form-select">
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
                    <input type="number" class="form-control" id="excursionPassengers" min="1" max="16" value="1">
                </div>
                <div class="col-6">
                    <label class="form-label" for="dateExcursion">Andata</label>
                    <input type="datetime-local" class="form-control" id="dateExcursion">
                </div>

            </div>
            <div class="row mb-3 align-items-end">
                <div class="col-6">
                    <label for="excursionPrice" class="form-label">Totale</label>
                    <input readonly type="text" class="form-control" id="excursionPrice" value="">
                </div>
                <div class="col-6 d-grid">
                    <button type="submit" class="btn bg-a text-white">Prenota</button>
                </div>
                
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('excursionSelect').addEventListener('change', function() {
        calculatePriceExcursion();
    });

    document.getElementById('excursionPassengers').addEventListener('input', function() {
        calculatePriceExcursion();
    });

    function calculatePriceExcursion() {
        const excursionSelect = document.getElementById('excursionSelect');
        const selectedOption = excursionSelect.options[excursionSelect.selectedIndex];
        const basePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const excursionPassengers = parseInt(document.getElementById('excursionPassengers').value) || 0;
        let totalPrice = basePrice;

        if (excursionPassengers > 4) {
            totalPrice += 10 * (excursionPassengers - 4);
        }

        const priceElement = document.getElementById('excursionPrice');
        priceElement.value = totalPrice.toLocaleString('it-IT', {
            style: 'currency',
            currency: 'EUR'
        });
    }
</script>
