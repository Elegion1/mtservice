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
                <label for="name_it" class="form-label">Nome Escursione (IT)</label>
                <input type="text" class="form-control form_input_focused" id="name_it" name="name_it" required>
                <label for="name_en" class="form-label mt-3">Nome Escursione (EN)</label>
                <input type="text" class="form-control form_input_focused" id="name_en" name="name_en">
            </div>

            <!-- Prezzo -->
            <div class="mb-3">
                <label for="price" class="form-label">Prezzo</label>
                <input type="number" step="0.01" class="form-control form_input_focused" id="price"
                    name="price" required>
            </div>

            <!-- Incremento Prezzo -->
            <div class="mb-3">
                <label for="price_increment" class="form-label">Incremento Prezzo</label>
                <input type="number" step="0.01" class="form-control form_input_focused" id="price_increment"
                    name="price_increment" required>
            </div>

            <!-- Durata -->
            <div class="mb-3">
                <label for="duration" class="form-label">Durata</label>
                <input type="text" class="form-control form_input_focused" id="duration" name="duration" required>
            </div>

            <!-- Abstract -->
            <div class="mb-3">
                <label for="abstract_it" class="form-label">Abstract (IT)</label>
                <input type="text" class="form-control form_input_focused" id="abstract_it" name="abstract_it"
                    required>
                <label for="abstract_en" class="form-label mt-3">Abstract (EN)</label>
                <input type="text" class="form-control form_input_focused" id="abstract_en" name="abstract_en">
            </div>

            <!-- Descrizione -->
            <div class="mb-3">
                <label for="description_it" class="form-label">Descrizione (IT)</label>
                <textarea class="form-control form_input_focused" id="description_it" name="description_it" required></textarea>
                <label for="description_en" class="form-label mt-3">Descrizione (EN)</label>
                <textarea class="form-control form_input_focused" id="description_en" name="description_en"></textarea>
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
        });
    </script>
</x-dashboard-layout>
