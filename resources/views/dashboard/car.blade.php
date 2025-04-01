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
        <div class="row mb-2 ">
            <div class="col-6">
                <label for="start" class="form-label">Data di inizio</label>
                <input type="datetime-local" class="form-control" id="start" name="start"
                    placeholder="Inizio periodo">
            </div>
            <div class="col-6">
                <label for="end" class="form-label">Data di fine</label>
                <input type="datetime-local" class="form-control" id="end" name="end"
                    placeholder="Fine periodo">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Aggiungi periodo</button>
    </form>

    <form action="{{ route('carprices.store') }}" method="POST" class="d-none mt-3" id="carPriceAssociateForm"
        enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-12 col-md-4">
                <label for="car_id" class="form-label">Auto</label>
                <select name="car_id" id="car_id" class="form-control">
                    <option value="" selected>Seleziona auto</option>
                    @foreach ($cars as $car)
                        <option value="{{ $car->id }}"
                            {{ isset($selectedCar) && $selectedCar->id == $car->id ? 'selected' : '' }}>
                            {{ $car->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-5">
                <label for="timeperiods" class="form-label">Periodi</label>
                <select name="time_period_id" id="timeperiods" class="form-control">
                    @foreach ($timePeriods as $period)
                        <option value="{{ $period->id }}">
                            Da: {{ \Carbon\Carbon::parse($period->start)->format('d/m/Y H:i') }} a:
                            {{ \Carbon\Carbon::parse($period->end)->format('d/m/Y H:i') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="price" class="form-label">Prezzo</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Prezzo">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Associa Periodo</button>
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
                                <img src="{{ Storage::url($image->path) }}" alt="{{ $car->name }}"
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
                                <img src="{{ Storage::url($image->path) }}" alt="{{ $car->name }}"
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
                            <p class="card-title">Periodo: <br>
                                Da
                                {{ \Carbon\Carbon::parse($group->first()->timePeriod->start)->translatedFormat('d F Y H:i') }}
                                <br>
                                a
                                {{ \Carbon\Carbon::parse($group->first()->timePeriod->end)->translatedFormat('d F Y H:i') }}
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
                    <th>#</th>
                    <th>Inizio</th>
                    <th>Fine</th>
                    <th>Auto</th>
                    <th>Prezzo</th>
                    <th>CarPrice</th>
                    <th>Periodo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedCarPrices as $timePeriodId => $group)
                    <tr>
                        <td>{{ $group->first()->id }}</td>
                        <td>{{ $group->first()->timePeriod->start }}</td>
                        <td>{{ $group->first()->timePeriod->end }}</td>
                        <td>
                            @foreach ($group as $carPrice)
                                {{ $carPrice->car->name }} <br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($group as $carPrice)
                                {{ $carPrice->price }} € <br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($group as $carPrice)
                                <div class=" mb-1">
                                    <x-edit-button :id="'CarPrice'" :data="$carPrice" />
                                    <x-delete-button :route="'carprices'" :model="$carPrice" /> <br>
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

        <div class="mb-3">
            <label for="edit_start" class="form-label">Data di inizio</label>
            <input type="datetime-local" class="form-control" id="edit_start" name="start"
                placeholder="Inizio periodo">
        </div>
        <div class="mb-3">
            <label for="edit_end" class="form-label">Data di inizio</label>
            <input type="datetime-local" class="form-control" id="edit_end" name="end"
                placeholder="Fine periodo">
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
        });
    </script>

</x-dashboard-layout>
