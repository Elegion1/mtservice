<x-dashboard-layout>

    <h1>Gestione Tratte</h1>

    <button id="routeCreateBtn" class="btn btn-success">Crea tratta</button>

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
                <label for="price" class="form-label">Prezzo (€)</label>
                <input type="number" class="form-control form_input_focused" id="price" name="price" required>
            </div>
            <div class="mb-3 col-lg-4 col-12">
                <label for="price_increment" class="form-label">Incremento di prezzo per passeggero</label>
                <input type="number" class="form-control form_input_focused" id="price_increment"
                    name="price_increment">
            </div>
            <div class="mb-3 col-lg-4 col-12">
                <label for="duration" class="form-label">Tempo di Percorrenza (Minuti)</label>
                <input type="number" class="form-control form_input_focused" id="duration" name="duration" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Aggiungi tratta</button>
    </form>

    <hr>

    <h2>Tutte le Tratte</h2>
    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Partenza</th>
                <th>Arrivo</th>
                <th>Distanza</th>
                <th>Prezzo</th>
                <th>Incremento di prezzo</th>
                <th>Tempo di Percorrenza</th>
                <th>Mostra</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($routes as $route)
                <tr>
                    <td>{{ $route->id }}</td>
                    <td>{{ $route->departure->name }}</td>
                    <td>{{ $route->arrival->name }}</td>
                    <td>{{ $route->distance }} km</td>
                    <td>{{ $route->price }} €</td>
                    <td>{{ $route->price_increment }} €</td>
                    <td>{{ $route->duration }} Min</td>
                    <td>{{ $route->show ? 'Si' : 'No' }}</td>
                    <td>
                        <x-edit-button :id="'Route'" :data="$route" />
                        <x-delete-button :route="'routes'" :model="$route" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


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
            <label for="edit_price" class="form-label">Prezzo (€)</label>
            <input type="number" class="form-control form_input_focused" id="edit_price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="edit_price_increment" class="form-label">Incremento di prezzo per
                passeggero</label>
            <input type="text" class="form-control form_input_focused" id="edit_price_increment"
                name="price_increment" required>
        </div>
        <div class="mb-3">
            <label for="edit_duration" class="form-label">Tempo di Percorrenza (Minuti)</label>
            <input type="text" class="form-control form_input_focused" id="edit_duration" name="duration" required>
        </div>
    </x-modal>

    <script>
        // JavaScript per precompilare il modale di modifica con i dati della rotta selezionata
        document.addEventListener('DOMContentLoaded', function() {

            var createRouteBtn = document.getElementById('routeCreateBtn');
            var routeCreateForm = document.getElementById('routeCreateForm');

            createRouteBtn.addEventListener('click', function() {
                routeCreateForm.classList.toggle('d-none');
                createRouteBtn.innerHTML = routeCreateForm.classList.contains('d-none') ? 'Crea tratta' :
                    'Nascondi';
            });

        });
    </script>
</x-dashboard-layout>
