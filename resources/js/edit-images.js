const currentImagesDiv = document.getElementById("edit-current-images");

// Funzione per mostrare le immagini esistenti
window.showCurrentImages = function (images) {
    currentImagesDiv.innerHTML = "";
    images.forEach((image) => {
        const imgDiv = document.createElement("div");
        imgDiv.classList.add(
            "current-image",
            "d-inline-block",
            "position-relative",
            "me-2"
        );

        // Usa innerHTML per inserire il contenuto HTML
        imgDiv.innerHTML = `
                <img src="/storage/${image.path}" alt="Immagine" width="100" class="img-thumbnail">
                <button type="button" class="btn btn-danger btn-sm remove-image position-absolute top-0 end-0 m-1" data-image-id="${image.id}">
                    ✖
                </button>
            `;

        currentImagesDiv.appendChild(imgDiv);
    });
};

if (currentImagesDiv) {
    // Gestione del click su "Elimina"
    currentImagesDiv.addEventListener("click", (event) => {
        if (event.target.classList.contains("remove-image")) {
            const imageId = event.target.getAttribute("data-image-id");

            // Conferma eliminazione
            if (!confirm("Sei sicuro di voler eliminare questa immagine?"))
                return;

            // Invia la richiesta DELETE al server
            fetch(`/dashboard/images/${imageId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => {
                    // console.log(response); // Aggiungi questo per vedere la risposta del server
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        event.target.closest(".current-image").remove();
                    } else {
                        alert("Errore durante l'eliminazione dell'immagine.");
                        console.error(data.error);
                    }
                })
                .catch((error) => {
                    alert("Si è verificato un errore durante l'eliminazione.");
                    console.error("Errore:", error);
                });
        }
    });
}