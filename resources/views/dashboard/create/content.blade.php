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
            <div class="row mb-3">
                <div class="col-6 text-start">
                    <small class="text-primary">Contenuto con ordine 0 mostra il titolo e sottotitolo
                        nell'header</small>
                </div>
                <div class="col-6 text-end">
                    <small class="text-primary">Se la pagina non è selezionata e l'ordine è 0
                        viene mostrato nell'header di
                        tutte le pagine</small>
                </div>


                <div class="col-4">
                    <label for="create-order" class="form-label">Ordine</label> <br>
                    <input type="number" class="form-control form_input_focused" id="create-order" name="order">
                </div>

                {{-- Mostra --}}
                <div class="col-4">
                    <label for="show" class="form-label">Mostra</label>
                    <select name="show" id="show" class="form-select" aria-label="Default select example">
                        <option value="1">Si</option>
                        <option value="0">No</option>
                    </select>
                </div>

                {{-- pagina --}}
                <div class="col-4">
                    <label for="page" class="form-label">Pagina</label>
                    <select id="page" name="page_id" class="form-select form_input_focused"
                        aria-label="Default select example">
                        <option value="" selected>Seleziona una pagina</option>
                        @foreach ($pages as $page)
                            <option value="{{ $page->id }}">{{ $page->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>


            <div class="row mb-3">
                <div class="col-6">
                    <label for="start_date">Data di inizio</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control">
                </div>
                <div class="col-6">
                    <label for="end_date">Data di fine</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control">
                </div>
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

        });
    </script>
</x-dashboard-layout>
