window.showModal = function (id, item) {
    console.log("Dentro la funzione con ID:", id, "e dati:", item);
    const modal = document.getElementById(`edit${id}Modal`);
    
    // Configura il form
    const form = document.getElementById(`edit${id}Form`);

    if (form) {
        form.action = `/dashboard/${id.toLowerCase()}s/${item.id}`;

        // Trova tutti gli input che iniziano con "edit_" e non sono di tipo "file"
        Array.from(form.elements).forEach((input) => {
            const inputId = input.id;
            if (
                inputId && 
                inputId.startsWith("edit_") && 
                input.type !== "file" // Escludi i file
            ) {
                const propertyName = inputId.replace("edit_", ""); // Estrai il nome della proprietà
                if (item[propertyName] !== undefined) {
                    if (input.type === "checkbox") {
                        input.checked = !!item[propertyName];
                    } else {
                        input.value = item[propertyName] || "";
                    }
                }
            }
        });
    } else {
        console.error(`Form con ID edit${id}Form non trovato!`);
    }

    // Mostra le immagini esistenti (se la funzione è definita)
    if (typeof window.showCurrentImages === "function") {
        window.showCurrentImages(item.images || []);
    } else {
        console.warn("La funzione showCurrentImages non è definita.");
    }
};
