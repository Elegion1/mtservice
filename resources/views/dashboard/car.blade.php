<x-dashboard-layout>

    <h1>Gestione Auto</h1>
    <div class="d-flex justify-content-around align-items-center">
        <button id="carCreateBtn" class="btn btn-sm btn-success">Crea Auto</button>
        <button id="periodCreateBtn" class="btn btn-sm btn-primary">Crea Periodo</button>
        <button id="carPriceAssociateBtn" class="btn btn-sm btn-info">Associa Periodo</button>
    </div>

    <form action="{{ route('cars.store') }}" method="POST" class="d-none mt-3" id="carFormCreate"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Nome Auto -->
            <div class="mb-3 col-lg-3 col-12">
                <label for="name" class="form-label">Nome Auto</label>
                <input type="text" class="form-control form_input_focused" id="name" name="name" required>
            </div>

            <!-- Descrizione -->
            <div class="mb-3 col-lg-3 col-12">
                <label for="description" class="form-label">Descrizione</label>
                <input type="text" class="form-control form_input_focused" id="description" name="description"
                    required>
            </div>

            <!-- Prezzo base -->
            <div class="mb-3 col-lg-3 col-12">
                <label for="price" class="form-label">Prezzo base</label>
                <input type="number" class="form-control form_input_focused" id="price" name="price" required>
            </div>

            <!-- Immagini -->
            <div class="mb-3 col-lg-3 col-12">
                <label for="images" class="form-label">Immagini</label>
                <input type="file" class="form-control form_input_focused" id="images" name="images[]" multiple>
            </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary">Aggiungi Auto</button>
    </form>

    <form action="{{ route('timeperiods.store') }}" method="POST" class="d-none mt-3" id="periodFormCreate"
        enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-6">
                <label for="start" class="form-label">Data di inizio</label>
                <input type="datetime-local" class="form-control" id="start" name="start"
                    placeholder="Inizio periodo" required>
            </div>
            <div class="col-6">
                <label for="end" class="form-label">Data di fine</label>
                <input type="datetime-local" class="form-control" id="end" name="end"
                    placeholder="Fine periodo" required>
            </div>
        </div>

        <!-- Prezzo base per tutte le auto -->
        <div class="row mb-3">
            <div class="col-12 col-md-6">
                <label for="base_price" class="form-label">Prezzo base (per tutte le auto)</label>
                <input type="number" class="form-control" id="base_price" name="base_price" 
                    placeholder="0,00" step="0.01" required>
            </div>
        </div>

        <!-- Selezione Auto -->
        <div class="mb-3">
            <label class="form-label">Seleziona le auto da associare</label>
            <div style="border: 1px solid #ddd; border-radius: 4px; padding: 15px; max-height: 300px; overflow-y: auto;">
                @forelse ($cars as $car)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="cars[]" value="{{ $car->id }}" id="car_{{ $car->id }}">
                        <label class="form-check-label" for="car_{{ $car->id }}">
                            <strong>{{ $car->name }}</strong> - {{ $car->description }}
                        </label>
                    </div>
                @empty
                    <p class="text-muted">Nessuna auto disponibile</p>
                @endforelse
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Aggiungi periodo e prezzi</button>
    </form>

    <form action="{{ route('carprices.sync') }}" method="POST" class="d-none mt-3" id="carPriceAssociateForm"
        enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-12 col-md-8">
                <label for="sync_time_period_id" class="form-label">Seleziona Periodo</label>
                <select name="time_period_id" id="sync_time_period_id" class="form-control" required>
                    <option value="" selected>Scegli un periodo</option>
                    @foreach ($timePeriods as $period)
                        <option value="{{ $period->id }}">
                            {{ $period->formatted_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label for="sync_price" class="form-label">Prezzo per questo periodo</label>
                <input type="number" class="form-control" id="sync_price" name="price" 
                    placeholder="0,00" step="0.01" required>
            </div>
        </div>

        <!-- Selezione Auto -->
        <div class="mb-3">
            <label class="form-label">Seleziona le auto per questo periodo</label>
            <div style="border: 1px solid #ddd; border-radius: 4px; padding: 15px; max-height: 300px; overflow-y: auto;">
                @forelse ($cars as $car)
                    <div class="form-check mb-2">
                        <input class="form-check-input car-checkbox" type="checkbox" name="cars[]" 
                            value="{{ $car->id }}" id="sync_car_{{ $car->id }}">
                        <label class="form-check-label" for="sync_car_{{ $car->id }}">
                            <strong>{{ $car->name }}</strong> - {{ $car->description }}
                        </label>
                    </div>
                @empty
                    <p class="text-muted">Nessuna auto disponibile</p>
                @endforelse
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salva Associazioni</button>
    </form>

    <hr>

    <h2>Tutte le Auto</h2>
    @if (request()->header('User-Agent') && preg_match('/Mobile|Android|iPhone/i', request()->header('User-Agent')))
        <div class="overflow-y-auto border-bottom rounded" style="height: 65vh">
            @foreach ($cars as $car)
                <div class="card shadow-sm mb-3 overflow-hidden">
                    <div class="card-body row">
                        <div class="col-6">
                            <h5 class="card-title">{{ $car->name }}</h5>
                            <p class="card-text">{{ $car->description }}</p>
                            <p class="card-text">Prezzo: {{ $car->price }} €</p>
                            <p class="card-text">Mostra: {{ $car->show ? 'Si' : 'No' }}</p>
                        </div>
                        <div class="col-6 d-flex justify-content-center align-items-center">
                            @foreach ($car->images as $image)
                                <img loading="lazy" src="{{ Storage::url($image->path) }}" alt="{{ $car->name }}"
                                    width="200px">
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-end">

                            <x-edit-button :id="'Car'" :data="$car" />
                            <x-delete-button :route="'cars'" :model="$car" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Descrizione</th>
                    <th>Immagine</th>
                    <th>Prezzo</th>
                    <th>Mostra</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cars as $car)
                    <tr>
                        <td>{{ $car->id }}</td>
                        <td>{{ $car->name }}</td>
                        <td>{{ $car->description }}</td>
                        <td>
                            @foreach ($car->images as $image)
                                <img loading="lazy" src="{{ Storage::url($image->path) }}" alt="{{ $car->name }}"
                                    width="50px">
                            @endforeach
                        </td>
                        <td>{{ $car->price }} €</td>
                        <td>{{ $car->show ? 'Si' : 'No' }}</td>
                        <td>
                            <x-edit-button :id="'Car'" :data="$car" />
                            <x-delete-button :route="'cars'" :model="$car" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>

    {{-- <h2>Periodi</h2>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Inizio</th>
                <th>Fine</th>
                <th>Auto associate - Prezzo</th>

                <th>Azioni periodi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($timePeriods as $timePeriod)
                <tr>
                    <td>{{ $timePeriod->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($timePeriod->start)->format('d/m/Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($timePeriod->end)->format('d/m/Y H:i') }}</td>
                    <td>
                        @foreach ($carPrices as $carPrice)
                            @if ($carPrice->time_period_id == $timePeriod->id)
                                <div class="row p-0 m-0">
                                    <div class="col-6 m-0 p-0">
                                        {{ $carPrice->car->name }}
                                    </div>
                                    <div class="col-6 m-0 p-0">
                                        {{ $carPrice->price }} €
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <x-edit-button :id="'TimePeriod'" :data="$timePeriod" />
                        <x-delete-button :route="'timeperiods'" :model="$timePeriod" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}

    <h2>Prezzi e periodi</h2>
    @php
        $groupedCarPrices = $carPrices->groupBy('time_period_id');
    @endphp
    @if (request()->header('User-Agent') && preg_match('/Mobile|Android|iPhone/i', request()->header('User-Agent')))
        <div class="row">
            @foreach ($groupedCarPrices as $timePeriodId => $group)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <p class="card-title">
                                <strong>{{ $group->first()->timePeriod->formatted_name }}</strong>
                            </p>

                            @foreach ($group as $carPrice)
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-1">{{ $carPrice->car->name }}</p>
                                    <div class="d-flex justify-content-between align-items-center ">
                                        <span class="me-3">{{ $carPrice->price }} €</span>
                                        <x-edit-button :id="'CarPrice'" :data="$carPrice" :label="true" />
                                        <x-delete-button :route="'carprices'" :model="$carPrice" :label="true" />
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between mt-2">
                                <div>
                                    <x-edit-button :id="'TimePeriod'" :data="$group->first()->timePeriod" />
                                    <x-delete-button :route="'timeperiods'" :model="$group->first()->timePeriod" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Periodo</th>
                    <th>Auto e Prezzi</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedCarPrices as $timePeriodId => $group)
                    <tr>
                        <td>
                            <strong>{{ $group->first()->timePeriod->formatted_name }}</strong>
                        </td>
                        <td>
                            @foreach ($group as $carPrice)
                                <div class="mb-2">
                                    <span>{{ $carPrice->car->name }}: <strong>{{ $carPrice->price }} €</strong></span>
                                    <div>
                                        <x-edit-button :id="'CarPrice'" :data="$carPrice" :label="true" />
                                        <x-delete-button :route="'carprices'" :model="$carPrice" :label="true" />
                                    </div>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <x-edit-button :id="'TimePeriod'" :data="$group->first()->timePeriod" />
                            <x-delete-button :route="'timeperiods'" :model="$group->first()->timePeriod" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <!-- Modale per Modifica Auto -->
    <x-modal :id="'Car'" :title="'Modifica auto'">

        <div class="mb-3">
            <input type="hidden" name="show" value="0">
            <label for="edit_show">Mostra</label>
            <input type="checkbox" class="form-check-input" id="edit_show" name="show" value="1">
        </div>
        <div class="mb-3">
            <label for="edit_name" class="form-label">Nome Auto</label>
            <input type="text" class="form-control form_input_focused" id="edit_name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="edit_description" class="form-label">Descrizione</label>
            <input type="text" class="form-control form_input_focused" id="edit_description" name="description"
                required>
        </div>
        <div class="mb-3">
            <label for="edit_price" class="form-label">Prezzo</label>
            <input type="number" class="form-control form_input_focused" id="edit_price" name="price" required>
        </div>

        <x-edit-images />

    </x-modal>

    <x-modal :id="'TimePeriod'" :title="'Modifica periodo'">

        <div class="alert alert-info">
            Nome periodo: <strong id="period_name_display"></strong>
        </div>

        <div class="mb-3">
            <label for="edit_start" class="form-label">Data di inizio</label>
            <input type="datetime-local" class="form-control" id="edit_start" name="start"
                placeholder="Inizio periodo" required>
        </div>
        <div class="mb-3">
            <label for="edit_end" class="form-label">Data di fine</label>
            <input type="datetime-local" class="form-control" id="edit_end" name="end"
                placeholder="Fine periodo" required>
        </div>
        <div class="mb-3">
            <label for="edit_base_price" class="form-label">Prezzo (aggiorna per tutte le auto)</label>
            <input type="number" class="form-control" id="edit_base_price" name="base_price"
                placeholder="0,00" step="0.01" required>
            <small class="text-muted">Modifica questo prezzo per aggiornare automaticamente il prezzo di TUTTE le auto associate a questo periodo</small>
        </div>

    </x-modal>

    <x-modal :id="'CarPrice'" :title="'Modifica prezzo auto'">

        <div class="mb-3">
            <label for="edit_car_id" class="form-label">Auto</label>
            <select name="car_id" id="edit_car_id" class="form-control">
                <option value="" selected>Seleziona auto</option>
                @foreach ($cars as $car)
                    <option value="{{ $car->id }}"
                        {{ isset($selectedCar) && $selectedCar->id == $car->id ? 'selected' : '' }}>
                        {{ $car->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="edit_time_period_id" class="form-label">Periodi</label>
            <select name="time_period_id" id="edit_time_period_id" class="form-control">
                @foreach ($timePeriods as $period)
                    <option value="{{ $period->id }}">
                        Da: {{ \Carbon\Carbon::parse($period->start)->format('d/m/Y H:i') }} a:
                        {{ \Carbon\Carbon::parse($period->end)->format('d/m/Y H:i') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="edit_price" class="form-label">Prezzo</label>
            <input type="number" class="form-control form_input_focused" id="edit_price" name="price" required>
        </div>

    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carCreateBtn = document.getElementById('carCreateBtn');
            const carFormCreate = document.getElementById('carFormCreate');
            const periodCreateBtn = document.getElementById('periodCreateBtn');
            const periodFormCreate = document.getElementById('periodFormCreate');
            const carPriceAssociateBtn = document.getElementById('carPriceAssociateBtn');
            const carPriceAssociateForm = document.getElementById('carPriceAssociateForm');

            // Toggle del form di creazione auto
            carCreateBtn.addEventListener('click', function() {
                carFormCreate.classList.toggle('d-none');
                carCreateBtn.innerHTML = carFormCreate.classList.contains('d-none') ? 'Crea Auto' :
                    'Nascondi';
            });

            periodCreateBtn.addEventListener('click', function() {
                periodFormCreate.classList.toggle('d-none');
                periodCreateBtn.innerHTML = periodFormCreate.classList.contains('d-none') ? 'Crea Periodo' :
                    'Nascondi';
            });

            carPriceAssociateBtn.addEventListener('click', function() {
                carPriceAssociateForm.classList.toggle('d-none');
                carPriceAssociateBtn.innerHTML = carPriceAssociateForm.classList.contains('d-none') ?
                    'Associa Periodo' :
                    'Nascondi';
            });

            // Aggiorna il nome del periodo quando cambiano le date nel modale
            const editStart = document.getElementById('edit_start');
            const editEnd = document.getElementById('edit_end');
            const periodNameDisplay = document.getElementById('period_name_display');

            function updatePeriodName() {
                if (editStart.value && editEnd.value) {
                    const startDate = new Date(editStart.value);
                    const endDate = new Date(editEnd.value);

                    const options = { day: 'numeric', month: 'long' };
                    const locale = document.documentElement.lang || 'it-IT';
                    
                    const startStr = startDate.toLocaleDateString(locale, options);
                    const endStr = endDate.toLocaleDateString(locale, options);

                    periodNameDisplay.textContent = startStr + ' - ' + endStr;
                }
            }

            editStart.addEventListener('change', updatePeriodName);
            editEnd.addEventListener('change', updatePeriodName);

            // Popola i checkbox quando cambia il periodo selezionato
            const syncTimePeriodSelect = document.getElementById('sync_time_period_id');
            const syncPriceInput = document.getElementById('sync_price');
            const carCheckboxes = document.querySelectorAll('.car-checkbox');

            syncTimePeriodSelect.addEventListener('change', function() {
                const timePeriodId = this.value;

                // Reset tutti i checkbox
                carCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                syncPriceInput.value = '';

                if (!timePeriodId) return;

                // Carica i dati del periodo via AJAX
                fetch(`/dashboard/carprices/period/${timePeriodId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Popola il prezzo se tutte le auto hanno lo stesso prezzo
                    if (data.cars.length > 0) {
                        const firstPrice = data.cars[0].pivot.price;
                        const allSamePrice = data.cars.every(car => car.pivot.price === firstPrice);
                        if (allSamePrice) {
                            syncPriceInput.value = firstPrice;
                        }
                    }

                    // Seleziona i checkbox delle auto già associate
                    data.cars.forEach(car => {
                        const checkbox = document.getElementById(`sync_car_${car.id}`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });
                })
                .catch(error => console.error('Errore:', error));
            });
        });

    </script>

</x-dashboard-layout>
