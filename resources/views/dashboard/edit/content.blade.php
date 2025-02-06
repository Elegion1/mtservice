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

            <div class="row mb-3">
                <small class="text-primary">Immagine con ordine 0 mostra l'immagine nell'header</small>
                <div class="col-6">
                    <label for="order" class="form-label">Ordine</label><br>
                    <input type="number" class="form-control form_input_focused" id="edit-order" name="order"
                        value="{{ $content->order }}">
                </div>
                <div class="col-6">
                    <label for="edit-show" class="form-label">Mostra</label>
                    <select name="show" value="{{ $content->show }}" id="edit-show" class="form-select"
                        aria-label="Default select example">
                        <option value="1">Si</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="start_date">Data di inizio</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                        value="{{ $content->start_date ?? '' }}">
                </div>
                <div class="col-6">
                    <label for="end_date">Data di fine</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control"
                        value="{{ $content->end_date ?? '' }}">
                </div>
            </div>

            <!-- Immagini -->
            <x-edit-images :images="$content->images" />
            
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

        });
    </script>
</x-dashboard-layout>
