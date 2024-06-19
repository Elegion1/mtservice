<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Auto</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="mb-3 col-3">
                    <label for="name" class="form-label">Nome Auto</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3 col-3">
                    <label for="description" class="form-label">Descrizione</label>
                    <input type="text" class="form-control" id="description" name="description" required>
                </div>
                <div class="mb-3 col-3">
                    <label for="img" class="form-label">Immagine</label>
                    <input type="file" class="form-control" id="img" name="img" required>
                </div>
                <div class="mb-3 col-3">
                    <label for="price" class="form-label">Prezzo</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi Auto</button>
        </form>
        <hr>
        <h2>Tutte le Auto</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Descrizione</th>
                    <th>Immagine</th>
                    <th>Prezzo</th>
                    <th>Data di aggiunta</th>
                    <th>Data di modifica</th>
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
                            <img src="{{ Storage::url($car->img) }}" alt="{{ $car->name }}" width="50">
                        </td>
                        <td>{{ $car->price }} €</td>
                        <td>{{ $car->created_at }}</td>
                        <td>{{ $car->updated_at }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCarModal"
                                data-id="{{ $car->id }}" data-name="{{ $car->name }}"
                                data-description="{{ $car->description }}"
                                data-price="{{ $car->price }}">Modifica</button>
                            <form action="{{ route('cars.destroy', $car) }}" method="POST"
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

    <!-- Modale per Modifica Auto -->
    <div class="modal fade" id="editCarModal" tabindex="-1" aria-labelledby="editCarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCarModalLabel">Modifica Auto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCarForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nome Auto</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Descrizione</label>
                            <input type="text" class="form-control" id="edit_description" name="description"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_img" class="form-label">Immagine</label>
                            <input type="file" class="form-control" id="edit_img" name="img">
                            <img id="current_img" src="" alt="" width="100" class="mt-2">
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Prezzo</label>
                            <input type="number" class="form-control" id="edit_price" name="price" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editCarModal = document.getElementById('editCarModal');
            editCarModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var description = button.getAttribute('data-description');
                var price = button.getAttribute('data-price');

                var modal = this;
                modal.querySelector('#edit_name').value = name;
                modal.querySelector('#edit_description').value = description;
                modal.querySelector('#edit_price').value = price;
                modal.querySelector('#current_img').src = button.getAttribute('data-img');

                var form = modal.querySelector('#editCarForm');
                form.action = '{{ url('dashboard/cars') }}/' + id;
            });
        });
    </script>
</x-dashboard-layout>
