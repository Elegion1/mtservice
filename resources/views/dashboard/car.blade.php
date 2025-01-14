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
                            <button class="btn btn-warning btn-sm edit-open-details-modal" data-bs-toggle="modal"
                                data-bs-target="#editCarModal" data-car="{{ json_encode($car) }}">Modifica</button>
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
                            <input type="hidden" name="show" value="0">
                            <label for="edit_show">Mostra</label>
                            <input type="checkbox" class="form-check-input" id="edit_show" name="show" value="1">
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nome Auto</label>
                            <input type="text" class="form-control form_input_focused" id="edit_name" name="name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Descrizione</label>
                            <input type="text" class="form-control form_input_focused" id="edit_description"
                                name="description" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Prezzo</label>
                            <input type="number" class="form-control form_input_focused" id="edit_price" name="price"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_images" class="form-label">Aggiungi nuove immagini</label>
                            <input type="file" class="form-control form_input_focused" id="edit_images"
                                name="images[]" multiple>
                        </div>
                        <div class="mb-3">
                            <label for="edit_current_images" class="form-label">Immagini Caricate</label>
                            <div id="edit-current-images">
                                <!-- Anteprime delle immagini esistenti verranno aggiunte qui -->
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
            // Funzione per mostrare le anteprime delle immagini esistenti nel modale di modifica
            function showCurrentImages(images) {
                const currentImagesDiv = document.getElementById('edit-current-images');
                currentImagesDiv.innerHTML = '';

                images.forEach(image => {
                    const imgDiv = document.createElement('div');
                    imgDiv.classList.add('current-image');
                    imgDiv.innerHTML = `
                        <img src="{{ asset('storage') }}/${image.path}" alt="Immagine" width="100" class="img-thumbnail m-1">
                        <button type="button" class="btn btn-danger btn-sm remove-image" data-image-id="${image.id}">Elimina</button>
                    `;
                    currentImagesDiv.appendChild(imgDiv);
                });
            }

            // Aggiungi evento al click dei pulsanti "Modifica" per popolare il modale
            document.querySelectorAll('.edit-open-details-modal').forEach(button => {
                button.addEventListener('click', () => {
                    const car = JSON.parse(button.getAttribute('data-car'));

                    // Popola il form nel modale di modifica con i dati del car selezionato
                    const form = document.getElementById('editCarForm');
                    form.action = `/dashboard/cars/${car.id}`;
                    form.querySelector('#edit_name').value = car.name;
                    form.querySelector('#edit_description').value = car.description;
                    form.querySelector('#edit_price').value = car.price;
                    form.querySelector('#edit_show').checked = car.show;

                    // Mostra le immagini esistenti nel modale di modifica
                    showCurrentImages(car.images);

                    // Mostra il modale di modifica
                    const modal = new bootstrap.Modal(document.getElementById('editCarModal'));
                    modal.show();
                });
            });

            // Gestione del click sul pulsante "Elimina" immagine nel modale di modifica
            document.getElementById('edit-current-images').addEventListener('click', (event) => {
                if (event.target.classList.contains('remove-image')) {
                    const imageId = event.target.getAttribute('data-image-id');

                    // Invia una richiesta DELETE al server per eliminare l'immagine
                    fetch(`/dashboard/images/${imageId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Rimuovi visivamente l'anteprima dell'immagine eliminata
                                event.target.closest('.current-image').remove();
                            } else {
                                // Gestisci errori o situazioni in cui l'eliminazione non è riuscita
                                console.error(data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Errore durante l\'eliminazione dell\'immagine:', error);
                        });
                }
            });
        });
    </script>

</x-dashboard-layout>
