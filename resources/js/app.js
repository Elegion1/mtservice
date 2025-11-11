import "./bootstrap";
import "bootstrap";
import "./edit-images";
import "./modal";

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

document.querySelectorAll(".phone-click").forEach((link) => {
    console.log("Tasto rilevato:", link.dataset.number);

    link.addEventListener("click", async (event) => {
        const number = link.dataset.number;
        console.log("Clic su numero:", number);

        const payload = { number };
        console.log("Payload inviato:", payload);

        // Avvia la fetch senza bloccare il comportamento del link
        fetch("/dashboard/phone-click", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(payload),
        })
            .then((response) => {
                console.log(
                    "Risposta ricevuta:",
                    response.status,
                    response.statusText
                );
            })
            .catch((error) => console.error("Fetch fallita:", error));

        // Lascia un piccolo delay per permettere alla fetch di partire
        setTimeout(() => {
            window.location.href = `tel:${number}`;
        }, 150);
    });
});
