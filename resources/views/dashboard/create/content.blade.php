<x-dashboard-layout>
    <h1>Crea Contenuto</h1>
    <button class="btn text-white bg-success" id="btn-it">IT</button>
    <button class="btn text-white bg-danger" id="btn-en">EN</button>

    <div class="container">
        <form id="createContentForm" action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Titolo -->
            <div class="mb-3">
                <label for="title_it" class="form-label">Titolo (IT)</label>
                <input type="text" class="form-control form_input_focused" id="title_it" name="title_it" required>
                <label for="title_en" class="form-label mt-3">Titolo (EN)</label>
                <input type="text" class="form-control form_input_focused" id="title_en" name="title_en">
            </div>

            <!-- Sottotitolo -->
            <div class="mb-3">
                <label for="subtitle_it" class="form-label">Sottotitolo (IT)</label>
                <input type="text" class="form-control form_input_focused" id="subtitle_it" name="subtitle_it"
                    required>
                <label for="subtitle_en" class="form-label mt-3">Sottotitolo (EN)</label>
                <input type="text" class="form-control form_input_focused" id="subtitle_en" name="subtitle_en">
            </div>

            <!-- Corpo -->
            <div class="mb-3">
                <label for="body_it" class="form-label">Corpo (IT)</label>
                <textarea class="form-control form_input_focused" id="body_it" name="body_it" required></textarea>
                <label for="body_en" class="form-label mt-3">Corpo (EN)</label>
                <textarea class="form-control form_input_focused" id="body_en" name="body_en"></textarea>
            </div>

            {{-- Links --}}
            <div class="mb-3">
                <label for="links" class="form-label">Links</label>
                <input type="text" class="form-control form_input_focused" id="edit-links" name="links">
            </div>

            {{-- Ordine --}}
            <div class="mb-3">
                <label for="order" class="form-label">Ordine</label>
                <input type="number" class="form-control form_input_focused" id="edit-order" name="order">
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
                    aria-label="Default select example" required>
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
