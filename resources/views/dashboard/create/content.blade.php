<x-dashboard-layout>
    <h1>Crea Contenuto</h1>
    <button class="btn text-white bg-success" id="btn-it">IT</button>
    <button class="btn text-white bg-danger" id="btn-en">EN</button>

    <div class="container">
        <form id="createContentForm" action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="create-title" class="form-label">Titolo</label>
                <input type="text" class="form-control form_input_focused" id="create-title" name="title">
            </div>

            <div class="mb-3">
                <label for="create-subtitle" class="form-label">Sottotitolo</label>
                <input type="text" class="form-control form_input_focused" id="create-subtitle" name="subtitle">
            </div>

            <div class="mb-3">
                <label for="create-body" class="form-label">Body</label>
                <textarea class="form-control form_input_focused" id="create-body" name="body"></textarea>
            </div>

            {{-- Links --}}
            <div class="mb-3">
                <label for="create-links" class="form-label">Links</label>
                <input type="text" class="form-control form_input_focused" id="create-links" name="links">
            </div>

            {{-- Ordine --}}
            <div class="mb-3">
                <label for="create-order" class="form-label">Ordine</label>
                <small class="text-primary">Contenuto con ordine 0 mostra il titolo e sottotitolo nell'header</small>
                <input type="number" class="form-control form_input_focused" id="create-order" name="order">
            </div>

            {{-- Mostra --}}
            <div class="mb-3">
                <label for="show" class="form-label">Mostra</label>
                <select name="show" id="show" class="form-select" aria-label="Default select example">
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
            </div>

            {{-- pagina --}}
            <div class="mb-3">
                <label for="page" class="form-label">Pagina</label>
                <select id="page" name="page_id" class="form-select form_input_focused"
                    aria-label="Default select example"  >
                    <option selected>Seleziona una pagina</option>
                    @foreach ($pages as $page)
                        <option value="{{ $page->id }}">{{ $page->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Immagini -->
            <div class="mb-3">
                <label for="images" class="form-label">Immagini</label>
                <input type="file" class="form-control form_input_focused" id="images" name="images[]" multiple>
            </div>

            <button type="submit" class="btn btn-primary">Salva</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            tinymce.init({
                selector: '#create-body',
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
                const titleField = document.getElementById('create-title');
                const subtitleField = document.getElementById('create-subtitle');
                const bodyField = document.getElementById('create-body');

                if (lang === 'it') {
                    btnIt.classList.add('border-5');
                    btnEn.classList.remove('border-5');
                    titleField.name = "title_it";
                    subtitleField.name = "subtitle_it";
                    bodyField.name = "body_it";
                } else if (lang === 'en') {
                    btnIt.classList.remove('border-5');
                    btnEn.classList.add('border-5');
                    titleField.name = "title_en";
                    subtitleField.name = "subtitle_en";
                    bodyField.name = "body_en";
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
            document.getElementById('create-current-images').addEventListener('click', (event) => {
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
