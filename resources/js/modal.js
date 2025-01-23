window.showModal = function (id, item) {
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
                input.type !== "file"
            ) {
                const propertyName = inputId.replace("edit_", ""); // Estrai il nome della proprietà
                if (item[propertyName] !== undefined) {
                    if (input.type === "checkbox") {
                        input.checked = !!item[propertyName]; // Imposta il valore del checkbox
                    } else {
                        input.value = item[propertyName] || "";
                    }
                }
            }

            // Gestisce il tipo di input dinamico
            if (
                inputId &&
                inputId.startsWith("edit_") &&
                inputId === "edit_value"
            ) {
                // Gestisce i vari tipi di input
                if (item.type === "time") {
                    input.type = "time";
                    input.value = item.value || "";
                } else if (item.type === "url") {
                    input.type = "url";
                    input.value = item.value || "";
                } else if (item.type === "bool") {
                    input.type = "number";
                    input.max = 1;
                    input.min = 0;
                } else {
                    input.type = item.type; // Imposta il tipo generico
                    input.value = item.value || "";
                }
            }
        });
    } else {
        console.error(`Form con ID edit${id}Form non trovato!`);
    }
    
    if (item.images) {
        // Mostra le immagini esistenti (se la funzione è definita)
        if (typeof window.showCurrentImages === "function") {
            window.showCurrentImages(item.images || []);
        } else {
            console.warn("La funzione showCurrentImages non è definita.");
        }
    }
};
