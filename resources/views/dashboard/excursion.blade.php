<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Escursioni</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('excursions.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="mb-3 col-4">
                    <label for="name" class="form-label">Nome Escursione</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3 col-4">
                    <label for="price" class="form-label">Prezzo</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
                <div class="mb-3 col-4">
                    <label for="price_increment" class="form-label">Incremento Prezzo</label>
                    <input type="number" step="0.01" class="form-control" id="price_increment" name="price_increment" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi Escursione</button>
        </form>
        <hr>
        <h2>Tutte le Escursioni</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Prezzo</th>
                    <th>Incremento Prezzo</th>
                    <th>Data di aggiunta</th>
                    <th>Data di modifica</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($excursions as $excursion)
                    <tr>
                        <td>{{ $excursion->id }}</td>
                        <td>{{ $excursion->name }}</td>
                        <td>{{ $excursion->price }} €</td>
                        <td>{{ $excursion->price_increment }} €</td>
                        <td>{{ $excursion->created_at }}</td>
                        <td>{{ $excursion->updated_at }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editExcursionModal" data-id="{{ $excursion->id }}"
                                data-name="{{ $excursion->name }}" data-price="{{ $excursion->price }}"
                                data-price_increment="{{ $excursion->price_increment }}">Modifica</button>
                            <form action="{{ route('excursions.destroy', $excursion) }}" method="POST"
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

    <!-- Modale per Modifica Escursione -->
    <div class="modal fade" id="editExcursionModal" tabindex="-1" aria-labelledby="editExcursionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExcursionModalLabel">Modifica Escursione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editExcursionForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nome Escursione</label>
                            <textarea type="text" class="form-control" id="edit_name" name="name" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Prezzo</label>
                            <input type="number" step="0.01" class="form-control" id="edit_price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price_increment" class="form-label">Incremento Prezzo</label>
                            <input type="number" step="0.01" class="form-control" id="edit_price_increment" name="price_increment" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editExcursionModal = document.getElementById('editExcursionModal');
            editExcursionModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var price = button.getAttribute('data-price');
                var price_increment = button.getAttribute('data-price_increment');

                var modal = this;
                modal.querySelector('#edit_name').value = name;
                modal.querySelector('#edit_price').value = price;
                modal.querySelector('#edit_price_increment').value = price_increment;

                var form = modal.querySelector('#editExcursionForm');
                form.action = '{{ url("dashboard/excursions") }}/' + id;
            });
        });
    </script>
</x-dashboard-layout>
