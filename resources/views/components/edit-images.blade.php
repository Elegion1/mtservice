<div class="mb-3">
    <label for="edit_images" class="form-label">Aggiungi nuove immagini</label>
    <input type="file" class="form-control form_input_focused" id="edit_images" name="images[]" multiple>
</div>
<div class="mb-3">
    <label for="edit_current_images" class="form-label">Immagini Caricate</label>
    <div id="edit-current-images" @if (isset($images)) data-images="{{ json_encode($images) }}" @endif>
        <!-- Anteprime delle immagini esistenti verranno aggiunte qui -->
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const currentImagesDiv = document.getElementById('edit-current-images');

        if (currentImagesDiv) {
            const dataImages = currentImagesDiv.getAttribute('data-images');

            if (dataImages) {
                try {
                    const images = JSON.parse(dataImages);

                    // Mostra le immagini esistenti (se la funzione è definita)
                    if (typeof window.showCurrentImages === "function") {
                        window.showCurrentImages(images || []);
                    } else {
                        console.warn("La funzione showCurrentImages non è definita.");
                    }
                } catch (error) {
                    console.error("Errore nel parsing delle immagini:", error);
                }
            }
        }
    });
</script>
