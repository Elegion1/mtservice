<x-dashboard-layout>

    <h1>Gestione Tratte</h1>

    <button id="routeCreateBtn" class="btn btn-success">Crea tratta</button>
    <button id="destinationCreateBtn" class="btn btn-success">Crea destinazione</button>
    <a href="{{ route('dashboard.destination') }}" class="btn btn-success">Mostra Destinazioni</a>

    <form class="d-none" id="destinationFormCreate" action="{{ route('destinations.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="mb-3 col-lg-4 col-12">
                <label for="name" class="form-label">Destinazione</label>
                <input type="text" class="form-control form_input_focused" id="name" name="name" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Aggiungi Destinazione</button>
    </form>

    <form class="d-none" id="routeCreateForm" action="{{ route('routes.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="mb-3 col-lg-4 col-12">
                <label for="departure_id" class="form-label">Partenza</label>
                <select class="form-select form_input_focused" id="departure_id" name="departure_id" required>
                    <option value="" disabled selected>Seleziona partenza</option>
                    @foreach ($destinations as $destination)
                        <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-lg-4 col-12">
                <label for="arrival_id" class="form-label">Arrivo</label>
                <select class="form-select form_input_focused" id="arrival_id" name="arrival_id" required>
                    <option value="" disabled selected>Seleziona arrivo</option>
                    @foreach ($destinations as $destination)
                        <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-lg-4 col-12">
                <label for="distance" class="form-label">Distanza (km)</label>
                <input type="number" class="form-control form_input_focused" id="distance" name="distance" required>
            </div>
            <div class="mb-3 col-lg-4 col-12">
                <label for="duration" class="form-label">Tempo di Percorrenza (Minuti)</label>
                <input type="number" class="form-control form_input_focused" id="duration" name="duration" required>
            </div>
            <div class="mb-3 col-lg-4 col-12">
                <label for="price" class="form-label">Prezzo (€)</label>
                <input type="number" class="form-control form_input_focused" id="price" name="price" required>
            </div>
            <div class="mb-3 col-lg-4 col-12">
                <label for="price_increment" class="form-label">Incremento di prezzo per passeggero</label>
                <input type="number" class="form-control form_input_focused" id="price_increment"
                    name="price_increment">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Aggiungi tratta</button>
    </form>

    <hr>

    <h2>Tutte le Tratte</h2>
    @if (request()->header('User-Agent') && preg_match('/Mobile|Android|iPhone/i', request()->header('User-Agent')))
        <div class="overflow-y-auto border-bottom rounded" style="height: 65vh">
            @foreach ($groupedRoutes as $group)
                <div class="mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <p class="card-text">
                                {{ $group['route']->departure->name }} ↔ {{ $group['route']->arrival->name }}
                            </p>
                            <p class="card-text">Distanza: {{ $group['route']->distance }} km</p>
                            <p class="card-text">Prezzo: {{ $group['route']->price }} €</p>
                            <p class="card-text">Incremento per passeggero: {{ $group['route']->price_increment }} €
                            </p>
                            <p class="card-text">Tempo di percorrenza: {{ $group['route']->duration }} min</p>
                            <p class="card-text">Mostra: {{ $group['route']->show ? 'Si' : 'No' }}</p>
                            <div class="d-flex justify-content-end">
                                <x-edit-button :id="'Route'" :data="$group['route']" />
                                <x-delete-button :route="'routes'" :model="$group['route']" />
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Rotta</th>
                    <th>Distanza</th>
                    <th>Prezzo</th>
                    <th>Incremento di prezzo</th>
                    <th>Tempo di Percorrenza</th>
                    <th>Mostra</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedRoutes as $group)
                    <tr>
                        <td>{{ $group['route']->id }} / {{ $group['reverseRoute']->id }}</td>
                        <td>{{ $group['route']->departure->name }} ↔ {{ $group['route']->arrival->name }}</td>
                        <td>{{ $group['route']->distance }} km</td>
                        <td>{{ $group['route']->price }} €</td>
                        <td>{{ $group['route']->price_increment }} €</td>
                        <td>{{ $group['route']->duration }} Min</td>
                        <td>{{ $group['route']->show ? 'Si' : 'No' }}</td>
                        <td>
                            <x-edit-button :id="'Route'" :data="$group['route']" />
                            <x-delete-button :route="'routes'" :model="$group['route']" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif


    <!-- Modale per Modifica Rotta -->
    <x-modal :id="'Route'" :title="'Modifica rotta'">

        <div class="mb-3">
            <input type="hidden" name="show" value="0">
            <label for="edit_show">Mostra</label>
            <input type="checkbox" class="form-check-input" id="edit_show" name="show" value="1">
        </div>
        <div class="mb-3">
            <label for="edit_distance" class="form-label">Distanza (km)</label>
            <input type="number" class="form-control form_input_focused" id="edit_distance" name="distance" required>
        </div>
        <div class="mb-3">
            <label for="edit_duration" class="form-label">Tempo di Percorrenza (Minuti)</label>
            <input type="text" class="form-control form_input_focused" id="edit_duration" name="duration"
                required>
        </div>
        <div class="mb-3">
            <label for="edit_price" class="form-label">Prezzo (€)</label>
            <input type="number" class="form-control form_input_focused" id="edit_price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="edit_price_increment" class="form-label">Incremento di prezzo per
                passeggero</label>
            <input type="text" class="form-control form_input_focused" id="edit_price_increment"
                name="price_increment" required>
        </div>
    </x-modal>

    <script>
        // JavaScript per precompilare il modale di modifica con i dati della rotta selezionata
        document.addEventListener('DOMContentLoaded', function() {

            var createRouteBtn = document.getElementById('routeCreateBtn');
            var routeCreateForm = document.getElementById('routeCreateForm');
            var destinationCreateBtn = document.getElementById('destinationCreateBtn');
            var destinationFormCreate = document.getElementById('destinationFormCreate');

            destinationCreateBtn.addEventListener('click', function() {
                destinationFormCreate.classList.toggle('d-none');
                destinationCreateBtn.innerText = destinationFormCreate.classList.contains('d-none') ?
                    'Crea Destinazione' : 'Nascondi';
            });

            createRouteBtn.addEventListener('click', function() {
                routeCreateForm.classList.toggle('d-none');
                createRouteBtn.innerHTML = routeCreateForm.classList.contains('d-none') ? 'Crea tratta' :
                    'Nascondi';
            });

        });
    </script>
</x-dashboard-layout>
