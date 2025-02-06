<x-dashboard-layout>
    <h1>Modifica Servizio</h1>
    <p>Servizio: <span class="text-primary">{{ $service->title_it }}</span></p>
    <button class="btn text-white bg-success" id="btn-it">IT</button>
    <button class="btn text-white bg-danger" id="btn-en">EN</button>

    <div class="container">
        <form id="editServiceForm" action="{{ route('services.update', $service->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-between align-items-center w-25">
                <!-- Checkbox: Show -->
                <div class="form-check mb-3">
                    <input type="hidden" name="show" value="0">
                    <input class="form-check-input" type="checkbox" id="show" name="show" value="1"
                        {{ $service->show ? 'checked' : '' }}>
                    <label class="form-check-label" for="show">
                        Mostra
                    </label>
                </div>

                <!-- Checkbox: Flag -->
                <div class="form-check mb-3">
                    <input type="hidden" name="flag" value="0">
                    <input class="form-check-input" type="checkbox" id="flag" name="flag" value="1"
                        {{ $service->flag ? 'checked' : '' }}>
                    <label class="form-check-label" for="flag">
                        Mostra sugli highlights
                    </label>
                </div>
            </div>

            <!-- Title -->
            <div class="mb-3">
                <label for="edit-title" class="form-label">Titolo *</label>
                <input type="text" class="form-control form_input_focused" id="edit-title" name="title">

            </div>

            <!-- Subtitle -->
            <div class="mb-3">
                <label for="edit-subtitle" class="form-label">Sottotitolo *</label>
                <input type="text" class="form-control form_input_focused" id="edit-subtitle" name="subtitle">

            </div>

            <!-- Second Subtitle -->
            <div class="mb-3">
                <label for="edit-subtitleSec" class="form-label">Sottotitolo Secondario</label>
                <input type="text" class="form-control form_input_focused" id="edit-subtitleSec" name="subtitleSec">

            </div>

            <!-- Abstract -->
            <div class="mb-3">
                <label for="edit-abstract" class="form-label">Abstract</label>
                <input type="text" class="form-control form_input_focused" id="edit-abstract" name="abstract">

            </div>

            <!-- Body -->
            <div class="mb-3">
                <label for="edit-body" class="form-label">Body *</label>
                <textarea class="form-control form_input_focused" id="edit-body" name="body">{{ $service->body }}</textarea>

            </div>

            <!-- Links -->
            <div class="mb-3">
                <label for="edit-links" class="form-label">Links</label>
                <input type="text" class="form-control form_input_focused" id="edit-links" name="links"
                    value="{{ $service->links }}">
            </div>

            <!-- Conditions -->
            <div class="mb-3">
                <label for="edit-condition" class="form-label">Condizioni</label>
                <textarea type="text" class="form-control form_input_focused" id="edit-condition" name="condition">{{ $service->condition }}</textarea>
            </div>

            <!-- Images -->
            <x-edit-images :images="$service->images" />

            <button type="submit" class="btn btn-primary">Salva Modifiche</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            tinymce.init({
                selector: '#edit-body',
                plugins: 'autolink lists link charmap preview anchor pagebreak',
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
                selector: '#edit-condition',
                plugins: 'autolink lists link charmap preview anchor pagebreak',
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
                const subtitleSecField = document.getElementById('edit-subtitleSec');
                const abstractField = document.getElementById('edit-abstract');
                const bodyField = document.getElementById('edit-body');
                const conditionField = document.getElementById('edit-condition');

                if (lang === 'it') {
                    btnIt.classList.add('border-5');
                    btnEn.classList.remove('border-5');
                    titleField.value = "{{ $service->title_it }}";
                    subtitleField.value = "{{ $service->subtitle_it }}";
                    subtitleSecField.value = "{{ $service->subtitleSec_it }}";
                    abstractField.value = "{{ $service->abstract_it }}";
                    titleField.name = "title_it";
                    subtitleField.name = "subtitle_it";
                    subtitleSecField.name = "subtitleSec_it";
                    abstractField.name = "abstract_it";
                    bodyField.name = "body_it";
                    conditionField.name = "condition_it";
                    if (tinymce.get('edit-body')) {
                        tinymce.get('edit-body').setContent(`{!! addslashes($service->body_it) !!}`);
                    }
                    if (tinymce.get('edit-condition')) {
                        tinymce.get('edit-condition').setContent(`{!! addslashes($service->condition_it) !!}`);
                    }
                } else if (lang === 'en') {
                    btnIt.classList.remove('border-5');
                    btnEn.classList.add('border-5');
                    titleField.value = "{{ $service->title_en }}";
                    subtitleField.value = "{{ $service->subtitle_en }}";
                    subtitleSecField.value = "{{ $service->subtitleSec_en }}";
                    abstractField.value = "{{ $service->abstract_en }}";
                    titleField.name = "title_en";
                    subtitleField.name = "subtitle_en";
                    subtitleSecField.name = "subtitleSec_en";
                    abstractField.name = "abstract_en";
                    bodyField.name = "body_en";
                    conditionField.name = "condition_en";
                    if (tinymce.get('edit-body')) {
                        tinymce.get('edit-body').setContent(`{!! addslashes($service->body_en) !!}`);
                    }
                    if (tinymce.get('edit-condition')) {
                        tinymce.get('edit-condition').setContent(`{!! addslashes($service->condition_en) !!}`);
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
