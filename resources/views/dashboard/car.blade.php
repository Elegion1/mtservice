<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Auto</h1>

        <button id="carCreateBtn" class="btn btn-success">Crea Auto</button>

        <form class="d-none" id="carFormCreate" action="{{ route('cars.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="mb-3 col-lg-3 col-12">
                    <label for="name" class="form-label">Nome Auto</label>
                    <input type="text" class="form-control form_input_focused" id="name" name="name"
                        required>
                </div>
                <div class="mb-3 col-lg-3 col-12">
                    <label for="description" class="form-label">Descrizione</label>
                    <input type="text" class="form-control form_input_focused" id="description" name="description"
                        required>
                </div>
                <div class="mb-3 col-lg-3 col-12">
                    <label for="price" class="form-label">Prezzo</label>
                    <input type="number" class="form-control form_input_focused" id="price" name="price"
                        required>
                </div>
                <div class="mb-3 col-lg-3 col-12">
                    <label for="images" class="form-label">Immagini</label>
                    <input type="file" class="form-control form_input_focused" id="images" name="images[]"
                        multiple>
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
                                <img src="{{ Storage::url($image->path) }}" alt="{{ $car->name }}" width="50px">
                            @endforeach
                        </td>
                        <td>{{ $car->price }} €</td>
                        <td>{{ $car->show ? 'Si' : 'No' }}</td>
                        <td>
                            <x-edit-button :id="'Car'" :data="$car" />
                            <x-delete-button :route="'cars.destroy'" :model="$car" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

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

    <!-- JavaScript per gestire il modale di modifica -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var carCreateBtn = document.getElementById('carCreateBtn');
            var carFormCreate = document.getElementById('carFormCreate');

            carCreateBtn.addEventListener('click', function() {
                carFormCreate.classList.toggle('d-none');
                carCreateBtn.innerHTML = carFormCreate.classList.contains('d-none') ? 'Crea Auto' :
                    'Nascondi';
            });

        });
    </script>

</x-dashboard-layout>
