<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Partner</h1>

        <button id="partnerCreateBtn" class="btn btn-success">Crea partner</button>

        <form id="partnerCreateForm" class="d-none" action="{{ route('partners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="mb-3 col-4">
                    <label for="name" class="form-label">Nome Partner</label>
                    <input type="text" class="form-control form_input_focused" id="name" name="name" required>
                </div>
                <div class="mb-3 col-4">
                    <label for="link" class="form-label">Link</label>
                    <input type="text" class="form-control form_input_focused" id="link" name="link"
                        required>
                </div>
                <div class="mb-3 col-4">
                    <label for="images" class="form-label">Immagini</label>
                    <input type="file" class="form-control form_input_focused" id="images" name="images[]"
                        multiple>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi Partner</button>
        </form>
        <hr>
        <h2>Tutti i Partner</h2>
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Link</th>
                    <th>Immagini</th>
                    <th>Data di aggiunta</th>
                    <th>Data di modifica</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($partners as $partner)
                    <tr>
                        <td>{{ $partner->id }}</td>
                        <td>{{ $partner->name }}</td>
                        <td><a href="{{ $partner->link }}" target="_blank">{{ $partner->link }}</a></td>
                        <td>
                            @if ($partner->images->count() > 0)
                                <small>{{ $partner->images->count() }} immagini caricate</small>
                            @else
                                <small>Nessuna immagine</small>
                            @endif
                        </td>
                        <td>{{ $partner->created_at }}</td>
                        <td>{{ $partner->updated_at }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-open-details-modal" data-bs-toggle="modal"
                                data-bs-target="#editPartnerModal"
                                data-partner="{{ json_encode($partner) }}">Modifica</button>
                            <form action="{{ route('partners.destroy', $partner) }}" method="POST"
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

    <!-- Modale per Modifica Partner -->
    <div class="modal fade" id="editPartnerModal" tabindex="-1" aria-labelledby="editPartnerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPartnerModalLabel">Modifica Partner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPartnerForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nome Partner</label>
                            <input type="text" class="form-control form_input_focused" id="edit_name" name="name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_link" class="form-label">Link</label>
                            <input type="text" class="form-control form_input_focused" id="edit_link" name="link"
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
            var partnerCreateBtn = document.getElementById('partnerCreateBtn');
            var partnerCreateForm = document.getElementById('partnerCreateForm');
            partnerCreateBtn.addEventListener('click', () => {
                partnerCreateForm.classList.toggle('d-none');
                partnerCreateBtn.innerHTML = partnerCreateForm.classList.contains('d-none') ? 'Crea partner' : 'Nascondi';
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
                    const partner = JSON.parse(button.getAttribute('data-partner'));

                    // Popola il form nel modale di modifica con i dati del partner selezionato
                    const form = document.getElementById('editPartnerForm');
                    form.action = `/dashboard/partners/${partner.id}`;
                    form.querySelector('#edit_name').value = partner.name;
                    form.querySelector('#edit_link').value = partner.link;

                    // Mostra le immagini esistenti nel modale di modifica
                    showCurrentImages(partner.images);

                    // Mostra il modale di modifica
                    const modal = new bootstrap.Modal(document.getElementById('editPartnerModal'));
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
