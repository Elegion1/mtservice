<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Servizi</h1>
         

        <!-- Form per aggiungere un nuovo servizio -->
        <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titolo</label>
                        <input type="text" class="form-control form_input_focused" id="title" name="title" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Sottotitolo</label>
                        <input type="text" class="form-control form_input_focused" id="subtitle" name="subtitle" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="subtitleSec" class="form-label">Sottotitolo Secondario</label>
                        <input type="text" class="form-control form_input_focused" id="subtitleSec" name="subtitleSec" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="abstract" class="form-label">Abstract</label>
                        <input type="text" class="form-control form_input_focused" id="abstract" name="abstract" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="body" class="form-label">Body</label>
                <textarea class="form-control form_input_focused" id="body" name="body" required></textarea>
            </div>
            <div class="mb-3">
                <label for="condition" class="form-label">Condizioni</label>
                <textarea class="form-control form_input_focused" id="condition" name="condition" required></textarea>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="images" class="form-label">Immagini</label>
                        <input type="file" class="form-control form_input_focused" id="images" name="images[]" multiple>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="links" class="form-label">Links</label>
                        <input type="text" class="form-control form_input_focused" id="links" name="links" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Aggiungi Servizio</button>
        </form>

        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titolo</th>
                    <th>Sottotitolo</th>
                    <th>Sottotitolo Secondario</th>
                    <th>Abstract</th>
                    <th>Body</th>
                    <th>Links</th>
                    <th>Condizioni</th>
                    <th>Immagini</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                    <tr>
                        <td>{{ $service->id }}</td>
                        <td>{{ $service->title }}</td>
                        <td>{{ $service->subtitle }}</td>
                        <td>{{ $service->subtitleSec }}</td>
                        <td>{{ $service->abstract }}</td>
                        <td>{{ $service->body }}</td>
                        <td>{{ $service->links }}</td>
                        <td>{{ $service->condition }}</td>
                        <td>
                            {{-- @foreach ($service->images as $image)
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Immagine" width="100">
                            @endforeach --}}
                            @if ($service->images->count() > 0)
                                <small>
                                    {{ $service->images->count() }} immagini caricate
                                </small>
                            @else
                                <small>Nessuna immagine</small>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm open-details-modal" data-bs-toggle="modal"
                                data-bs-target="#serviceDetailsModal" data-service="{{ json_encode($service) }}">
                                Modifica
                            </button>
                            <form action="{{ route('services.destroy', $service) }}" method="POST"
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

    <!-- Modale per modificare i dettagli del servizio -->
    <div class="modal fade" id="serviceDetailsModal" tabindex="-1" aria-labelledby="serviceDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceDetailsModalLabel">Modifica Servizio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="serviceDetailsForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">Titolo</label>
                            <input type="text" class="form-control form_input_focused" id="edit-title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Sottotitolo</label>
                            <input type="text" class="form-control form_input_focused" id="edit-subtitle" name="subtitle">
                        </div>
                        <div class="mb-3">
                            <label for="subtitleSec" class="form-label">Sottotitolo Secondario</label>
                            <input type="text" class="form-control form_input_focused" id="edit-subtitleSec" name="subtitleSec">
                        </div>
                        <div class="mb-3">
                            <label for="abstract" class="form-label">Abstract</label>
                            <input type="text" class="form-control form_input_focused" id="edit-abstract" name="abstract">
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Body</label>
                            <textarea class="form-control form_input_focused" id="edit-body" name="body"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="links" class="form-label">Links</label>
                            <input type="text" class="form-control form_input_focused" id="edit-links" name="links">
                        </div>
                        <div class="mb-3">
                            <label for="condition" class="form-label">Condizioni</label>
                            <textarea class="form-control form_input_focused" id="edit-condition" name="condition"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">Immagini</label>
                            <input type="file" class="form-control form_input_focused" id="edit-images" name="images[]" multiple>
                            <div id="current-images" class="mt-3">
                                <!-- Anteprime delle immagini esistenti verranno aggiunte qui -->
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Aggiorna Servizio</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript per il modale -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Funzione per mostrare le anteprime delle immagini
            function showImagePreviews(files) {
                const previewContainer = document.getElementById('current-images');
                if (!previewContainer) {
                    console.error('Preview container not found.');
                    return;
                }
                previewContainer.innerHTML = '';
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgDiv = document.createElement('div');
                        imgDiv.classList.add('current-image');
                        imgDiv.innerHTML = `
                    <img src="${e.target.result}" alt="Immagine" width="100">
                    <button type="button" class="btn btn-danger btn-sm remove-image-temp">Elimina</button>
                `;
                        previewContainer.appendChild(imgDiv);
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Funzione per gestire la rimozione delle immagini
            function handleRemoveImage(imageId) {
                const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenElement) {
                    console.error('Elemento CSRF Token non trovato nel documento.');
                    return;
                }

                const csrfToken = csrfTokenElement.getAttribute('content');
                fetch(`/dashboard/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                }).then(response => {
                    if (!response.ok) {
                        throw new Error('Errore nella richiesta di eliminazione: ' + response.status);
                    }
                    return response.json();
                }).then(data => {
                    if (data.success) {
                        // Rimuovi l'elemento visuale dell'immagine
                        const imageElement = document.querySelector(
                            `.remove-image[data-image-id="${imageId}"]`);
                        if (imageElement) {
                            imageElement.parentElement.remove();
                        }
                    } else {
                        console.error('Errore nella risposta di eliminazione:', data.error);
                    }
                }).catch(error => {
                    console.error('Errore nella richiesta di eliminazione:', error);
                });

            }


            // Aggiungi evento al cambio di input per mostrare le anteprime delle immagini
            document.getElementById('images').addEventListener('change', function() {
                showImagePreviews(this.files);
            });

            // Gestisci il click sui pulsanti "Elimina Immagine"
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-image')) {
                    const imageId = event.target.getAttribute('data-image-id');
                    if (imageId) {
                        handleRemoveImage(imageId);
                    }
                }
            });

            // Aggiungi evento al modale dei dettagli del servizio per gestire le immagini esistenti
            document.querySelectorAll('.open-details-modal').forEach(button => {
                button.addEventListener('click', () => {
                    const service = JSON.parse(button.getAttribute('data-service'));
                    const form = document.getElementById('serviceDetailsForm');
                    if (!form) {
                        console.error('Form element not found.');
                        return;
                    }
                    form.action = `/dashboard/services/${service.id}`;
                    form.querySelector('#edit-title').value = service.title;
                    form.querySelector('#edit-subtitle').value = service.subtitle;
                    form.querySelector('#edit-subtitleSec').value = service.subtitleSec;
                    form.querySelector('#edit-abstract').value = service.abstract;
                    form.querySelector('#edit-body').value = service.body;
                    form.querySelector('#edit-links').value = service.links;
                    form.querySelector('#edit-condition').value = service.condition;

                    tinymce.get('edit-body').setContent(service.body);
                    tinymce.get('edit-condition').setContent(service.condition);
                    // Mostra le immagini esistenti nel modale
                    const currentImagesDiv = document.getElementById('current-images');
                    if (!currentImagesDiv) {
                        console.error('Current images container not found.');
                        return;
                    }
                    currentImagesDiv.innerHTML = '';
                    service.images.forEach(image => {
                        const imgDiv = document.createElement('div');
                        imgDiv.classList.add('current-image');
                        imgDiv.innerHTML = `
                    <img src="/storage/${image.path}" alt="Immagine" width="100">
                    <button type="button" class="btn btn-danger btn-sm remove-image" data-image-id="${image.id}">Elimina</button>
                `;
                        currentImagesDiv.appendChild(imgDiv);
                    });
                });
            });
        });
    </script>





</x-dashboard-layout>
