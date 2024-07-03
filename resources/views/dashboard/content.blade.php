<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Contenuti</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form per aggiungere un nuovo contenuto -->
        <form action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titolo</label>
                        <input type="text" class="form-control form_input_focused" id="title" name="title" >
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Sottotitolo</label>
                        <input type="text" class="form-control form_input_focused" id="subtitle" name="subtitle" >
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="body" class="form-label">Corpo</label>
                        <textarea class="form-control form_input_focused" id="body" name="body" ></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-3">
                    <div class="mb-3">
                        <label for="images" class="form-label">Immagini</label>
                        <input type="file" class="form-control form_input_focused" id="images" name="images[]" multiple>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="page" class="form-label">Pagina</label>
                        <select id="page" name="page_id" class="form-select form_input_focused" aria-label="Default select example"
                            required>
                            <option selected >Seleziona una pagina</option>
                            @foreach ($pages as $page)
                                <option value="{{ $page->id }}">{{ $page->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label for="links" class="form-label">Links</label>
                        <input type="text" class="form-control form_input_focused" id="links" name="links">
                    </div>
                </div>
                <div class="col-2">
                    <div class="mb-3">
                        <label for="order" class="form-label">Ordine</label>
                        <input type="number" class="form-control form_input_focused" id="order" name="order">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Aggiungi Contenuto</button>
        </form>

        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pagina</th>
                    <th>Titolo</th>
                    <th>Sottotitolo</th>
                    <th>Corpo</th>
                    <th>Links</th>
                    <th>Ordine</th>
                    <th>Immagini</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contents as $content)
                    <tr>
                        <td>{{ $content->id }}</td>
                        <td>{{ $content->page->name }}</td>
                        <td>{{ $content->title }}</td>
                        <td>{{ $content->subtitle }}</td>
                        <td>{{ $content->body }}</td>
                        <td>{{ $content->links }}</td>
                        <td>{{ $content->order }}</td>
                        <td>
                            {{-- @foreach ($content->images as $image)
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Immagine" width="100">
                            @endforeach --}}
                            @if ($content->images->count() > 0)
                                <small>
                                    {{ $content->images->count() }} immagini caricate
                                </small>
                            @else
                                <small>Nessuna immagine</small>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm open-details-modal" data-bs-toggle="modal"
                                data-bs-target="#contentDetailsModal" data-content="{{ json_encode($content) }}">
                                Modifica
                            </button>
                            <form action="{{ route('contents.destroy', $content) }}" method="POST"
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

    <!-- Modale per modificare i dettagli del Contenuto -->
    <div class="modal fade" id="contentDetailsModal" tabindex="-1" aria-labelledby="contentDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contentDetailsModalLabel">Modifica Contenuto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="contentDetailsForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">Titolo</label>
                            <input type="text" class="form-control form_input_focused" id="title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Sottotitolo</label>
                            <input type="text" class="form-control form_input_focused" id="subtitle" name="subtitle">
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Body</label>
                            <textarea class="form-control form_input_focused" id="body" name="body"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="links" class="form-label">Links</label>
                            <input type="text" class="form-control form_input_focused" id="links" name="links">
                        </div>
                        <div class="mb-3">
                            <label for="order" class="form-label">Ordine</label>
                            <input type="number" class="form-control form_input_focused" id="order" name="order">
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">Immagini</label>
                            <input type="file" class="form-control form_input_focused" id="images" name="images[]" multiple>
                            <div id="current-images" class="mt-3">
                                <!-- Anteprime delle immagini esistenti verranno aggiunte qui -->
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Aggiorna Contenuto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript per il modale -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Aggiungi evento al cambio di selezione della pagina
            document.getElementById('page').addEventListener('change', function() {
                const selectedPageId = this.value;
                document.getElementById('page_id').value = selectedPageId;
            });
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

            // Aggiungi evento al modale dei dettagli del Contenuto per gestire le immagini esistenti
            document.querySelectorAll('.open-details-modal').forEach(button => {
                button.addEventListener('click', () => {
                    const content = JSON.parse(button.getAttribute('data-content'));
                    const form = document.getElementById('contentDetailsForm');
                    if (!form) {
                        console.error('Form element not found.');
                        return;
                    }
                    form.action = `/dashboard/contents/${content.id}`;
                    form.querySelector('#title').value = content.title;
                    form.querySelector('#subtitle').value = content.subtitle;
                    form.querySelector('#body').value = content.body;
                    form.querySelector('#links').value = content.links;
                    form.querySelector('#order').value = content.order;

                    // Mostra le immagini esistenti nel modale
                    const currentImagesDiv = document.getElementById('current-images');
                    if (!currentImagesDiv) {
                        console.error('Current images container not found.');
                        return;
                    }
                    currentImagesDiv.innerHTML = '';
                    content.images.forEach(image => {
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
