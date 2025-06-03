<x-dashboard-layout>
    <h1>Crea Escursione</h1>
    <button class="btn text-white bg-success" id="btn-it">IT</button>
    <button class="btn text-white bg-danger" id="btn-en">EN</button>

    <div class="container">
        <form id="createExcursionForm" action="{{ route('excursions.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <!-- Nome Escursione -->
            <div class="mb-3">
                <label for="create-name" class="form-label">Nome Escursione *</label>
                <input type="text" class="form-control form_input_focused" id="create-name" name="name"  >
            </div>

            <!-- Prezzo -->
            <div class="mb-3">
                <label for="price" class="form-label">Prezzo *</label>
                <input type="number" step="0.01" class="form-control form_input_focused" id="price"
                    name="price"  >
            </div>

            <!-- Incremento Prezzo -->
            <div class="mb-3">
                <label for="price_increment" class="form-label">Incremento Prezzo *</label>
                <input type="number" step="0.01" class="form-control form_input_focused" id="price_increment"
                    name="price_increment"  >
            </div>

             <!-- PAX Incremento -->
            <div class="mb-3">
                <label for="increment_passengers" class="form-label">PAX Incremento</label>
                <input type="number" step="1" class="form-control form_input_focused" id="increment_passengers"
                    name="increment_passengers">
            </div>

            <!-- Durata -->
            <div class="mb-3">
                <label for="duration" class="form-label">Durata *</label>
                <input type="number" class="form-control form_input_focused" id="duration" name="duration"  >
            </div>

            <!-- Abstract -->
            <div class="mb-3">
                <label for="create-abstract" class="form-label">Abstract</label>
                <input type="text" class="form-control form_input_focused" id="create-abstract" name="abstract">
            </div>

            <!-- Descrizione -->
            <div class="mb-3">
                <label for="create-description" class="form-label">Descrizione</label>
                <textarea class="form-control form_input_focused" id="create-description" name="description"></textarea>
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
                selector: '#create-description',
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

            function switchLanguage(lang) {
                const nameField = document.getElementById('create-name');
                const abstractField = document.getElementById('create-abstract');
                const descriptionField = document.getElementById('create-description');

                if (lang === 'it') {
                    btnIt.classList.add('border-5');
                    btnEn.classList.remove('border-5');
                    nameField.name = "name_it";
                    abstractField.name = "abstract_it";
                    descriptionField.name = "description_it";
                } else if (lang === 'en') {
                    btnIt.classList.remove('border-5');
                    btnEn.classList.add('border-5');
                    nameField.name = "name_en";
                    abstractField.name = "abstract_en";
                    descriptionField.name = "description_en";
                }
            }

            btnIt.addEventListener('click', function() {
                switchLanguage('it');
            });

            btnEn.addEventListener('click', function() {
                switchLanguage('en');
            });

        });
    </script>
</x-dashboard-layout>
