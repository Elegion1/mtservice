<x-dashboard-layout>
    <h1>Modifica Escursione</h1>
    <p>Escursione: <span class="text-primary">{{ $excursion->name_it }}</span></p>
    <button class="btn text-white bg-success" id="btn-it">IT</button>
    <button class="btn text-white bg-danger" id="btn-en">EN</button>

    <div class="container">
        <form id="editExcursionForm" action="{{ route('excursions.update', $excursion->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Nome Escursione -->
            <div class="mb-3">
                <label for="edit-name" class="form-label">Nome Escursione *</label>
                <input type="text" class="form-control form_input_focused" id="edit-name" name="name"
                    value="{{ $excursion->name }}"  >
            </div>

            <!-- Prezzo -->
            <div class="mb-3">
                <label for="price" class="form-label">Prezzo *</label>
                <input type="number" step="0.01" class="form-control form_input_focused" id="price"
                    name="price" value="{{ $excursion->price }}"  >
            </div>

            <!-- Incremento Prezzo -->
            <div class="mb-3">
                <label for="price_increment" class="form-label">Incremento Prezzo *</label>
                <input type="number" step="0.01" class="form-control form_input_focused" id="price_increment"
                    name="price_increment" value="{{ $excursion->price_increment }}"  >
            </div>

            <!-- Durata -->
            <div class="mb-3">
                <label for="duration" class="form-label">Durata *</label>
                <input type="number" class="form-control form_input_focused" id="duration" name="duration"
                    value="{{ $excursion->duration }}"  >
            </div>

            <!-- Abstract -->
            <div class="mb-3">
                <label for="edit-abstract" class="form-label">Abstract</label>
                <input type="text" class="form-control form_input_focused" id="edit-abstract" name="abstract"
                    value="{{ $excursion->abstract }}">
            </div>

            <!-- Descrizione -->
            <div class="mb-3">
                <label for="edit-description" class="form-label">Descrizione *</label>
                <textarea class="form-control form_input_focused" id="edit-description" name="description"  >{{ $excursion->description }}</textarea>
            </div>

            <!-- Immagini -->
            <div class="mb-3">
                <label for="edit_images" class="form-label">Aggiungi nuove immagini</label>
                <input type="file" class="form-control form_input_focused" id="edit_images" name="images[]" multiple>
            </div>
            <div class="mb-3">
                <label for="edit_current_images" class="form-label">Immagini Caricate</label>
                <div id="edit-current-images">
                    @foreach ($excursion->images as $image)
                        <div class="current-image">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="Immagine" width="100">
                            <button type="button" class="btn btn-danger btn-sm remove-image"
                                data-image-id="{{ $image->id }}">Elimina</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Salva Modifiche</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            tinymce.init({
                selector: '#edit-description',
                plugins: 'autolink lists link image charmap preview anchor pagebreak',
                menubar: false,
                tinycomments_mode: 'embedded',
                tinycomments_author: 'Author name',
                height: 300,
                license_key: 'gpl',
                setup: function(editor) {
                    editor.on('init', function() {
                        switchLanguage('it'); // Default to Italian on init
                    });
                }
            });

            const btnIt = document.getElementById('btn-it');
            const btnEn = document.getElementById('btn-en');

            // Funzione per cambiare la lingua attiva
            function switchLanguage(lang) {
                const nameField = document.getElementById('edit-name');
                const abstractField = document.getElementById('edit-abstract');
                const descriptionField = document.getElementById('edit-description');

                if (lang === 'it') {
                    btnIt.classList.add('border-5');
                    btnEn.classList.remove('border-5');
                    nameField.value = "{{ $excursion->name_it }}";
                    abstractField.value = "{{ $excursion->abstract_it }}";
                    nameField.name = "name_it";
                    abstractField.name = "abstract_it";
                    descriptionField.name = "description_it";
                    if (tinymce.get('edit-description')) {
                        tinymce.get('edit-description').setContent(`{!! addslashes($excursion->description_it) !!}`);
                    }
                } else if (lang === 'en') {
                    btnIt.classList.remove('border-5');
                    btnEn.classList.add('border-5');
                    nameField.value = "{{ $excursion->name_en }}";
                    abstractField.value = "{{ $excursion->abstract_en }}";
                    nameField.name = "name_en";
                    abstractField.name = "abstract_en";
                    descriptionField.name = "description_en";
                    if (tinymce.get('edit-description')) {
                        tinymce.get('edit-description').setContent(`{!! addslashes($excursion->description_en) !!}`);
                    }
                }
            }

            // Gestione del click sui pulsanti IT/EN
            btnIt.addEventListener('click', function() {
                switchLanguage('it');
            });

            btnEn.addEventListener('click', function() {
                switchLanguage('en');
            });


            // Gestione del click sul pulsante "Elimina" immagine
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
