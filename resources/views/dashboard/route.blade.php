<x-dashboard-layout>

    <div class="container mt-5">
        <h1>Gestione Tratte</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('routes.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="mb-3 col-4">
                    <label for="departure_id" class="form-label">Partenza</label>
                    <select class="form-select" id="departure_id" name="departure_id" required>
                        <option value="" disabled selected>Seleziona partenza</option>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-4">
                    <label for="arrival_id" class="form-label">Arrivo</label>
                    <select class="form-select" id="arrival_id" name="arrival_id" required>
                        <option value="" disabled selected>Seleziona arrivo</option>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-4">
                    <label for="distance" class="form-label">Distanza (km)</label>
                    <input type="number" class="form-control" id="distance" name="distance" required>
                </div>
                <div class="mb-3 col-4">
                    <label for="price" class="form-label">Prezzo (€)</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>
                <div class="mb-3 col-4">
                    <label for="duration" class="form-label">Tempo di Percorrenza (Minuti)</label>
                    <input type="number" class="form-control" id="duration" name="duration" required>
                </div>
                <div class="mb-3 col-4">
                    <label for="price_increment" class="form-label">Incremento di prezzo per passeggero</label>
                    <input type="number" class="form-control" id="price_increment" name="price_increment">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi tratta</button>
        </form>

        <hr>

        <h2>Tutte le Rotte</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Partenza</th>
                    <th>Arrivo</th>
                    <th>Distanza</th>
                    <th>Prezzo</th>
                    <th>Incremento di prezzo</th>
                    <th>Tempo di Percorrenza</th>
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
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editRouteModal" 
                                data-id="{{ $route->id }}"
                                data-distance="{{ $route->distance }}" 
                                data-price="{{ $route->price }}"
                                data-duration="{{ $route->duration }}" 
                                data-price_increment="{{$route->price_increment}}"
                                data-name="{{ $route->departure->name }} - {{ $route->arrival->name }}">Modifica</button>
                            <form action="{{ route('routes.destroy', $route) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Elimina</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modale per Modifica Rotta -->
    <div class="modal fade" id="editRouteModal" tabindex="-1" aria-labelledby="editRouteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRouteModalLabel">Modifica Rotta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRouteForm" action="" method="POST">
                        <p id="route_name"></p>
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_distance" class="form-label">Distanza (km)</label>
                            <input type="number" class="form-control" id="edit_distance" name="distance" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Prezzo (€)</label>
                            <input type="number" class="form-control" id="edit_price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price_increment" class="form-label">Incremento di prezzo per passeggero</label>
                            <input type="text" class="form-control" id="edit_price_increment" name="price_increment" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_duration" class="form-label">Tempo di Percorrenza (Minuti)</label>
                            <input type="text" class="form-control" id="edit_duration" name="duration" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript per precompilare il modale di modifica con i dati della rotta selezionata
        document.addEventListener('DOMContentLoaded', function() {
            var editRouteModal = document.getElementById('editRouteModal');
            editRouteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var distance = button.getAttribute('data-distance');
                var price = button.getAttribute('data-price');
                var price_increment = button.getAttribute('data-price_increment');
                var duration = button.getAttribute('data-duration');
                var name = button.getAttribute('data-name');

                var modal = this;
                modal.querySelector('#edit_distance').value = distance;
                modal.querySelector('#edit_price').value = price;
                modal.querySelector('#edit_price_increment').value = price_increment;
                modal.querySelector('#edit_duration').value = duration;
                modal.querySelector('#edit_duration').value = duration;
                modal.querySelector('#route_name').innerHTML = name;

                var form = modal.querySelector('#editRouteForm');
                form.action = '/routes/' + id;
            });
        });
    </script>
</x-dashboard-layout>
