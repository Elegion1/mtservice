<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Escursioni</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form per aggiungere una nuova escursione -->
        <form action="{{ route('excursions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="mb-3 col-8">
                    <label for="name" class="form-label">Nome Escursione</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3 col-1">
                    <label for="price" class="form-label">Prezzo</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
                <div class="mb-3 col-2">
                    <label for="price_increment" class="form-label">Incremento Prezzo</label>
                    <input type="number" step="0.01" class="form-control" id="price_increment"
                        name="price_increment" required>
                </div>
                <div class="mb-3 col-1">
                    <label for="duration" class="form-label">Durata</label>
                    <input type="text" step="0.01" class="form-control" id="duration" name="duration" required>
                </div>
                <div class="mb-3 col-12">
                    <label for="abstract" class="form-label">Abstract</label>
                    <input type="text" step="0.01" class="form-control" id="abstract" name="abstract" required>
                </div>
                <div class="mb-3 col-12">
                    <label for="description" class="form-label">Descrizione</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                </div>
            </div>
            <div class="mb-3">
                <label for="images" class="form-label">Immagini</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi Escursione</button>
        </form>

        <hr>

        <!-- Tabella con tutte le escursioni -->
        <h2>Tutte le Escursioni</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Prezzo</th>
                    <th>Incremento Prezzo</th>
                    <th>Abstract</th>
                    <th>Descrizione</th>
                    <th>Durata</th>
                    <th>Data di aggiunta</th>
                    <th>Data di modifica</th>
                    <th>Immagini</th>
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
                        <td>{{ $excursion->abstract }}</td>
                        <td>{{ $excursion->description }}</td>
                        <td>{{ $excursion->duration }} h</td>
                        <td>{{ $excursion->created_at }}</td>
                        <td>{{ $excursion->updated_at }}</td>
                        <td>
                            @if ($excursion->images->count() > 0)
                                <small>{{ $excursion->images->count() }} immagini caricate</small>
                            @else
                                <small>Nessuna immagine</small>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-open-details-modal" data-bs-toggle="modal"
                                data-bs-target="#editExcursionModal"
                                data-excursion="{{ json_encode($excursion) }}">Modifica</button>
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
                    <form id="editExcursionForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nome Escursione</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Prezzo</label>
                            <input type="number" step="0.01" class="form-control" id="edit_price" name="price"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price_increment" class="form-label">Incremento Prezzo</label>
                            <input type="number" step="0.01" class="form-control" id="edit_price_increment"
                                name="price_increment" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_duration" class="form-label">Durata</label>
                            <input type="text" class="form-control" id="edit_duration" name="duration" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_abstract" class="form-label">Abstract</label>
                            <input type="text" class="form-control" id="edit_abstract" name="abstract" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Descrizione</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_images" class="form-label">Aggiungi nuove immagini</label>
                            <input type="file" class="form-control" id="edit_images" name="images[]" multiple>
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
            // Funzione per mostrare le anteprime delle immagini esistenti nel modale di modifica
            function showCurrentImages(images) {
                const currentImagesDiv = document.getElementById('edit-current-images');
                currentImagesDiv.innerHTML = '';

                images.forEach(image => {
                    const imgDiv = document.createElement('div');
                    imgDiv.classList.add('current-image');
                    imgDiv.innerHTML = `
                        <img src="{{ asset('storage') }}/${image.path}" alt="Immagine" width="100">
                        <button type="button" class="btn btn-danger btn-sm remove-image" data-image-id="${image.id}">Elimina</button>
                    `;
                    currentImagesDiv.appendChild(imgDiv);
                });
            }

            // Aggiungi evento al click dei pulsanti "Modifica" per popolare il modale
            document.querySelectorAll('.edit-open-details-modal').forEach(button => {
                button.addEventListener('click', () => {
                    const excursion = JSON.parse(button.getAttribute('data-excursion'));

                    // Popola il form nel modale di modifica con i dati dell'escursione selezionata
                    const form = document.getElementById('editExcursionForm');
                    form.action = `/dashboard/excursions/${excursion.id}`;
                    form.querySelector('#edit_name').value = excursion.name;
                    form.querySelector('#edit_price').value = excursion.price;
                    form.querySelector('#edit_price_increment').value = excursion.price_increment;
                    form.querySelector('#edit_abstract').value = excursion.abstract;
                    form.querySelector('#edit_description').value = excursion.description;
                    form.querySelector('#edit_duration').value = excursion.duration;

                    // Mostra le immagini esistenti nel modale di modifica
                    showCurrentImages(excursion.images);

                    // Mostra il modale di modifica
                    const modal = new bootstrap.Modal(document.getElementById(
                        'editExcursionModal'));
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
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Assicurati di includere il token CSRF
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Rimuovi visivamente l'anteprima dell'immagine eliminata
                                event.target.closest('.current-image').remove();
                                // Aggiungi qui eventuali feedback visivi o logica aggiuntiva dopo l'eliminazione
                            } else {
                                // Gestisci errori o situazioni in cui l'eliminazione non è riuscita
                                console.error(data.error);
                            }
                        })
                        .catch(error => {
                            console.error(
                                'Si è verificato un errore durante l\'eliminazione dell\'immagine:',
                                error);
                        });
                }
            });

        });
    </script>
</x-dashboard-layout>
