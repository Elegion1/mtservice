<x-dashboard-layout>
    <h1>Modifica Contenuto</h1>
    <p>Contenuto: <span class="text-primary">{{ $content->title_it }}</span></p>
    <button class="btn text-white bg-success" id="btn-it">IT</button>
    <button class="btn text-white bg-danger" id="btn-en">EN</button>

    <div class="container">
        <form id="editContentForm" action="{{ route('contents.update', $content->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="edit-title" class="form-label">Titolo</label>
                <input type="text" class="form-control form_input_focused" id="edit-title" name="title"
                    value="{{ $content->title }}">
            </div>

            <div class="mb-3">
                <label for="edit-subtitle" class="form-label">Sottotitolo</label>
                <input type="text" class="form-control form_input_focused" id="edit-subtitle" name="subtitle"
                    value="{{ $content->subtitle }}">
            </div>

            <div class="mb-3">
                <label for="edit-body" class="form-label">Body</label>
                <textarea class="form-control form_input_focused" id="edit-body" name="body">{{ $content->body }}</textarea>
            </div>

            <div class="mb-3">
                <label for="links" class="form-label">Links</label>
                <input type="text" class="form-control form_input_focused" id="edit-links" name="links"
                    value="{{ $content->links }}">
            </div>

            <div class="mb-3">
                <label for="order" class="form-label">Ordine</label><br>
                <small class="text-primary">Immagine con ordine 0 mostra l'immagine nell'header</small>
                <input type="number" class="form-control form_input_focused" id="edit-order" name="order"
                    value="{{ $content->order }}">
            </div>


            <div class="mb-3">
                <label for="edit-show" class="form-label">Mostra</label>
                <select name="show" value="{{ $content->show }}" id="edit-show" class="form-select"
                    aria-label="Default select example">
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
            </div>

            <!-- Immagini -->
            <div class="mb-3">
                <label for="edit_images" class="form-label">Aggiungi nuove immagini</label>
                <input type="file" class="form-control form_input_focused" id="edit_images" name="images[]" multiple>
            </div>
            <div class="mb-3">
                <label for="edit_current_images" class="form-label">Immagini Caricate</label>
                <div id="edit-current-images">
                    @foreach ($content->images as $image)
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
                selector: '#edit-body',
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
                const titleField = document.getElementById('edit-title');
                const subtitleField = document.getElementById('edit-subtitle');
                const bodyField = document.getElementById('edit-body');

                if (lang === 'it') {
                    btnIt.classList.add('border-5');
                    btnEn.classList.remove('border-5');
                    titleField.value = "{{ $content->title_it }}";
                    subtitleField.value = "{{ $content->subtitle_it }}";
                    titleField.name = "title_it";
                    subtitleField.name = "subtitle_it";
                    bodyField.name = "body_it";
                    if (tinymce.get('edit-body')) {
                        tinymce.get('edit-body').setContent(`{!! addslashes($content->body_it) !!}`);
                    }
                } else if (lang === 'en') {
                    btnIt.classList.remove('border-5');
                    btnEn.classList.add('border-5');
                    titleField.value = "{{ $content->title_en }}";
                    subtitleField.value = "{{ $content->subtitle_en }}";
                    titleField.name = "title_en";
                    subtitleField.name = "subtitle_en";
                    bodyField.name = "body_en";
                    if (tinymce.get('edit-body')) {
                        tinymce.get('edit-body').setContent(`{!! addslashes($content->body_en) !!}`);
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
