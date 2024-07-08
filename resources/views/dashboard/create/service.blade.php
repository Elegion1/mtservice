<x-dashboard-layout>
    <h1>Crea Servizio</h1>
    <button class="btn text-white bg-success" id="btn-it">IT</button>
    <button class="btn text-white bg-danger" id="btn-en">EN</button>

    <div class="container">
        <form id="createServiceForm" action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="mb-3">
                <label for="title_it" class="form-label">Titolo (IT)</label>
                <input type="text" class="form-control form_input_focused" id="title_it" name="title_it" required>
                <label for="title_en" class="form-label mt-3">Titolo (EN)</label>
                <input type="text" class="form-control form_input_focused" id="title_en" name="title_en">
            </div>

            <!-- Subtitle -->
            <div class="mb-3">
                <label for="subtitle_it" class="form-label">Sottotitolo (IT)</label>
                <input type="text" class="form-control form_input_focused" id="subtitle_it" name="subtitle_it"
                    required>
                <label for="subtitle_en" class="form-label mt-3">Sottotitolo (EN)</label>
                <input type="text" class="form-control form_input_focused" id="subtitle_en" name="subtitle_en">
            </div>

            <!-- Second Subtitle -->
            <div class="mb-3">
                <label for="subtitleSec_it" class="form-label">Sottotitolo Secondario (IT)</label>
                <input type="text" class="form-control form_input_focused" id="subtitleSec_it" name="subtitleSec_it"
                    required>
                <label for="subtitleSec_en" class="form-label mt-3">Sottotitolo Secondario (EN)</label>
                <input type="text" class="form-control form_input_focused" id="subtitleSec_en" name="subtitleSec_en">
            </div>

            <!-- Abstract -->
            <div class="mb-3">
                <label for="abstract_it" class="form-label">Abstract (IT)</label>
                <input type="text" class="form-control form_input_focused" id="abstract_it" name="abstract_it"
                    required>
                <label for="abstract_en" class="form-label mt-3">Abstract (EN)</label>
                <input type="text" class="form-control form_input_focused" id="abstract_en" name="abstract_en">
            </div>

            <!-- Body -->
            <div class="mb-3">
                <label for="body_it" class="form-label">Body (IT)</label>
                <textarea class="form-control form_input_focused" id="body_it" name="body_it" required></textarea>
                <label for="body_en" class="form-label mt-3">Body (EN)</label>
                <textarea class="form-control form_input_focused" id="body_en" name="body_en"></textarea>
            </div>

            <!-- Links -->
            <div class="mb-3">
                <label for="links" class="form-label">Links</label>
                <input type="text" class="form-control form_input_focused" id="links" name="links" required>
            </div>

            <!-- Conditions -->
            <div class="mb-3">
                <label for="condition_it" class="form-label">Condizioni (IT)</label>
                <input type="text" class="form-control form_input_focused" id="condition_it" name="condition_it"
                    required>
                <label for="condition_en" class="form-label mt-3">Condizioni (EN)</label>
                <input type="text" class="form-control form_input_focused" id="condition_en" name="condition_en">
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
            const btnIt = document.getElementById('btn-it');
            const btnEn = document.getElementById('btn-en');

            const fieldsIt = document.querySelectorAll('[id$="_it"], [for$="_it"]');
            const fieldsEn = document.querySelectorAll('[id$="_en"], [for$="_en"]');

            btnIt.addEventListener('click', function() {
                fieldsIt.forEach(field => field.style.display = 'block');
                fieldsEn.forEach(field => field.style.display = 'none');
            });

            btnEn.addEventListener('click', function() {
                fieldsIt.forEach(field => field.style.display = 'none');
                fieldsEn.forEach(field => field.style.display = 'block');
            });

            // Default to showing Italian fields
            btnIt.click();

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
            // Ensure form submission is working as expected
            document.getElementById('createServiceForm').addEventListener('submit', (event) => {
                // Add additional validation or checks if needed

                // Optionally, prevent default form submission for testing
                // event.preventDefault();

                // Log form submission to console for debugging
                console.log('Form submitted');
            });
        });
    </script>
</x-dashboard-layout>
