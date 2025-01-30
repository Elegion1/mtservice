window.showModal = function (id, item) {
    const modal = document.getElementById(`edit${id}Modal`);

    // Configura il form
    const form = document.getElementById(`edit${id}Form`);

    if (form) {
        form.action = `/dashboard/${id.toLowerCase()}s/${item.id}`;

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
                        input.checked = !!item[propertyName];
                    } else if (input.type === "date") {
                        input.value =
                            item[propertyName]?.substring(0, 10) || "";
                    } else if (input.type === "datetime-local") {
                        if (item[propertyName]) {
                            const date = new Date(item[propertyName] + "Z");
                            input.value = !isNaN(date)
                                ? date.toISOString().slice(0, 16)
                                : "";
                        } else {
                            input.value = "";
                        }
                    } else {
                        input.value = item[propertyName] || "";
                    }
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
