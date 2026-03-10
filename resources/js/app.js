import "./bootstrap";
import "bootstrap";
import "./edit-images";
import "./modal";

import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap; // Questo lo rende disponibile ovunque

// const csrfToken = document
//     .querySelector('meta[name="csrf-token"]')
//     .getAttribute("content");

// document.querySelectorAll(".phone-click").forEach((link) => {
//     console.log("Tasto rilevato:", link.dataset.number);

//     link.addEventListener("click", async (event) => {
//         const number = link.dataset.number;
//         console.log("Clic su numero:", number);

//         const payload = { number };
//         console.log("Payload inviato:", payload);

//         // Avvia la fetch senza bloccare il comportamento del link
//         fetch("/dashboard/phone-click", {
//             method: "POST",
//             headers: {
//                 "Content-Type": "application/json",
//                 "X-CSRF-TOKEN": csrfToken,
//             },
//             body: JSON.stringify(payload),
//         })
//             .then((response) => {
//                 console.log(
//                     "Risposta ricevuta:",
//                     response.status,
//                     response.statusText
//                 );
//             })
//             .catch((error) => console.error("Fetch fallita:", error));

//         // Lascia un piccolo delay per permettere alla fetch di partire
//         setTimeout(() => {
//             window.location.href = `tel:${number}`;
//         }, 150);
//     });
// });

/**
 * Funzione di Scroll Intelligente Unificata
 * @param {string|null} elementId - ID dell'elemento a cui scrollare.
 * @param {number} delay - Tempo di attesa per il rendering di Livewire (default 50ms).
 */
window.scrollToTarget = function (elementId = null, delay = 50) {
    setTimeout(() => {
        // Individua il target: ID specifico, oppure i default del progetto
        const target = elementId
            ? document.getElementById(elementId)
            : document.getElementById("form-top") ||
              document.getElementById("mainContent");

        if (target) {
            // Otteniamo la posizione dell'elemento rispetto alla visuale attuale (viewport)
            const rect = target.getBoundingClientRect();

            // Definiamo dei margini di tolleranza (offset)
            // Se la cima del form è troppo vicina al bordo superiore (< 100px)
            // o se la cima è finita sotto il bordo inferiore, allora scrolla.
            const isFullyVisible =
                rect.top >= 0 &&
                rect.top <=
                    (window.innerHeight ||
                        document.documentElement.clientHeight) -
                        100;

            // Scrolliamo SOLO se l'inizio dell'elemento NON è in una posizione comoda
            if (!isFullyVisible) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        } else {
            // Fallback: se non c'è un target e non siamo già in cima, torna su
            if (window.scrollY > 0) {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth",
                });
            }
        }
    }, delay);
};

/**
 * Listener per la navigazione Livewire (facoltativo)
 * Se usi Livewire 3 con persistenza, questo gestisce lo scroll al cambio pagina
 */
document.addEventListener("livewire:navigated", () => {
    const mainContent = document.getElementById("mainContent");
    if (mainContent) {
        // Se siamo in home o c'è un'info specifica, valutiamo lo scroll iniziale
        const currentRoute = mainContent.getAttribute("data-currentRoute");
        if (currentRoute === "home") {
            // Qui usiamo un delay 0 perché è al caricamento pagina
            window.scrollToTarget(null, 0);
        }
    }
});

