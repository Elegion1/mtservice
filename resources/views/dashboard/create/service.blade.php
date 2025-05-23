<x-dashboard-layout>
    <h1>Crea Servizio</h1>
    <button class="btn text-white bg-success" id="btn-it">IT</button>
    <button class="btn text-white bg-danger" id="btn-en">EN</button>

    <div class="container">
        <form id="createServiceForm" action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="mb-3">
                <label for="create-title" class="form-label">Titolo *</label>
                <input type="text" class="form-control form_input_focused" id="create-title" name="title"  >
            </div>

            <!-- Subtitle -->
            <div class="mb-3">
                <label for="create-subtitle" class="form-label">Sottotitolo *</label>
                <input type="text" class="form-control form_input_focused" id="create-subtitle" name="subtitle"  >
            </div>

            <!-- Second Subtitle -->
            <div class="mb-3">
                <label for="create-subtitleSec" class="form-label">Sottotitolo Secondario</label>
                <input type="text" class="form-control form_input_focused" id="create-subtitleSec" name="subtitleSec">

            </div>

            <!-- Abstract -->
            <div class="mb-3">
                <label for="create-abstract" class="form-label">Abstract</label>
                <input type="text" class="form-control form_input_focused" id="create-abstract" name="abstract">

            </div>

            <!-- Body -->
            <div class="mb-3">
                <label for="create-body" class="form-label">Body *</label>
                <textarea class="form-control form_input_focused" id="create-body" name="body"></textarea>

            </div>

            <!-- Links -->
            <div class="mb-3">
                <label for="create-links" class="form-label">Links</label>
                <input type="text" class="form-control form_input_focused" id="create-links" name="links">
            </div>

            <!-- Conditions -->
            <div class="mb-3">
                <label for="create-condition" class="form-label">Condizioni</label>
                <textarea class="form-control form_input_focused" id="create-condition" name="condition"></textarea>

            </div>

            <!-- Images -->
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

            tinymce.init({
                selector: '#create-condition',
                plugins: 'autolink lists link image charmap preview anchor pagebreak',
                menubar: 'false',
                tinycomments_mode: 'embedded',
                tinycomments_author: 'Author name',
                height: 300,
                license_key: 'gpl',
            });


            const btnIt = document.getElementById('btn-it');
            const btnEn = document.getElementById('btn-en');

            // Funzione per cambiare la lingua attiva
            function switchLanguage(lang) {
                const titleField = document.getElementById('create-title');
                const subtitleField = document.getElementById('create-subtitle');
                const subtitleSecField = document.getElementById('create-subtitleSec');
                const abstractField = document.getElementById('create-abstract');
                const bodyField = document.getElementById('create-body');
                const conditionField = document.getElementById('create-condition');

                if (lang === 'it') {
                    btnIt.classList.add('border-5');
                    btnEn.classList.remove('border-5');
                    titleField.name = "title_it";
                    subtitleField.name = "subtitle_it";
                    subtitleSecField.name = "subtitleSec_it";
                    abstractField.name = "abstract_it";
                    bodyField.name = "body_it";
                    conditionField.name = "condition_it";
                } else if (lang === 'en') {
                    btnIt.classList.remove('border-5');
                    btnEn.classList.add('border-5');
                    titleField.name = "title_en";
                    subtitleField.name = "subtitle_en";
                    subtitleSecField.name = "subtitleSec_en";
                    abstractField.name = "abstract_en";
                    bodyField.name = "body_en";
                    conditionField.name = "condition_en";
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
