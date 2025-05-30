<x-dashboard-layout>

    <h1>pagina di test</h1>
    <div class="row">
        <div class="col-6">
            <select name="booking" id="bookingSelect" class="form-select w-25 m-3">
                @foreach ($bookings as $booking)
                    <option value="{{ $booking->id }}">{{ $booking->bookingData['type'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-6">
            <label for="dateInput">Seleziona una data:</label>
            <input type="text" id="dateInput" readonly>
        </div>

        <div class="col-6">
            <div class="container rounded-5 border">
                <h3 class="m-4">Mail</h3>
                <div class="row m-3">
                    <div class="col-12 p-1">
                        <button id="emailBookingAdmin" class="btn btn-sm btn-danger">Booking admin</button>
                        <button id="emailContattaciAdmin" class="btn btn-sm btn-danger">Contattaci admin</button>
                    </div>
                    <div class="col-12 p-1">
                        <button id="emailBookingConfirmationIt" class="btn btn-sm btn-success">Booking confirmation
                            IT</button>
                        <button id="emailBookingConfirmationEn" class="btn btn-sm btn-primary">Booking confirmation
                            EN</button>
                    </div>
                    <div class="col-12 p-1">
                        <button id="emailBookingStatusNotificationIt" class="btn btn-sm btn-success">Booking status
                            notification IT</button>
                        <button id="emailBookingStatusNotificationEn" class="btn btn-sm btn-primary">Booking status
                            notification EN</button>
                    </div>
                    <div class="col-12 p-1">
                        <button id="emailContattaciIt" class="btn btn-sm btn-success">Contattaci IT</button>
                        <button id="emailContattaciEn" class="btn btn-sm btn-primary">Contattaci EN</button>
                    </div>
                    <div class="col-12 p-1">
                        <button id="emailRecensioneIt" class="btn btn-sm btn-success">Recensione IT</button>
                        <button id="emailRecensioneEn" class="btn btn-sm btn-primary">Recensione EN</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="container rounded-5 border">
                <h3 class="m-4">PDF</h3>
                <div class="row m-3">
                    <div class="col-12">
                        <a id="pdfIt" href="#" class="btn btn-sm btn-success">PDF IT</a>
                        <a id="pdfEn" href="#" class="btn btn-sm btn-primary">PDF EN</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sezione per mostrare il contenuto delle email -->
    <div class="row mt-4">
        <div class="col-12">
            <h3>Anteprima Mail</h3>
            <div id="emailPreview" class="border rounded-5 p-3" style="min-height: 300px;">
                <!-- Contenuto della mail verrà caricato qui -->
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingSelect = document.getElementById('bookingSelect');
            const pdfItLink = document.getElementById('pdfIt');
            const pdfEnLink = document.getElementById('pdfEn');
            const emailPreview = document.getElementById('emailPreview');

            var dateInput = document.getElementById("datePicker");

            if (dateInput) {
                dateInput.addEventListener("click", function() {
                    new AnyPicker({
                        inputElement: dateInput,
                        mode: "date",
                        dateTimeFormat: "DD-MM-YYYY",
                        locale: "it",
                        onChange: function(selectedDate) {
                            dateInput.value = selectedDate.format("DD-MM-YYYY");
                        }
                    }).show();
                });
            }

            function loadEmailPreview(route) {
                // console.log('Caricamento email per il percorso:', route); // Log del percorso chiamato

                fetch(route)
                    .then(response => response.text())
                    .then(html => {
                        // console.log('Contenuto HTML caricato:', html); // Log del contenuto HTML ricevuto
                        emailPreview.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Errore durante il caricamento della mail:', error); // Log dell'errore
                        emailPreview.innerHTML =
                            '<p class="text-danger">Errore nel caricamento dell\'email.</p>';
                    });
            }

            // Funzione per aggiornare i link dei PDF
            bookingSelect.addEventListener('change', function() {
                const selectedBookingId = this.value;

                // Aggiorna i link dei pulsanti PDF con la lingua corretta
                pdfItLink.href = "{{ url('dashboard/generate/pdf') }}/" + selectedBookingId + "/it";
                pdfEnLink.href = "{{ url('dashboard/generate/pdf') }}/" + selectedBookingId + "/en";
            });

            // Attivare il cambio iniziale per settare i link quando la pagina è caricata
            bookingSelect.dispatchEvent(new Event('change'));

            // Event listeners per i pulsanti email con lingua fissa basata sui bottoni
            document.getElementById('emailBookingAdmin').addEventListener('click', function() {
                const selectedBookingId = bookingSelect.value;
                loadEmailPreview(`/dashboard/email/preview/booking-admin/it/${selectedBookingId}`);
            });

            document.getElementById('emailContattaciAdmin').addEventListener('click', function() {
                loadEmailPreview(`/dashboard/email/preview/contattaci-admin/it`);
            });

            document.getElementById('emailBookingConfirmationIt').addEventListener('click', function() {
                const selectedBookingId = bookingSelect.value;
                loadEmailPreview(`/dashboard/email/preview/booking-confirmation/it/${selectedBookingId}`);
            });

            document.getElementById('emailBookingConfirmationEn').addEventListener('click', function() {
                const selectedBookingId = bookingSelect.value;
                loadEmailPreview(`/dashboard/email/preview/booking-confirmation/en/${selectedBookingId}`);
            });

            document.getElementById('emailBookingStatusNotificationIt').addEventListener('click', function() {
                const selectedBookingId = bookingSelect.value;
                loadEmailPreview(
                    `/dashboard/email/preview/booking-status-notification/it/${selectedBookingId}`);
            });

            document.getElementById('emailBookingStatusNotificationEn').addEventListener('click', function() {
                const selectedBookingId = bookingSelect.value;
                loadEmailPreview(
                    `/dashboard/email/preview/booking-status-notification/en/${selectedBookingId}`);
            });

            document.getElementById('emailContattaciIt').addEventListener('click', function() {
                loadEmailPreview(`/dashboard/email/preview/contattaci/it`);
            });
            document.getElementById('emailContattaciEn').addEventListener('click', function() {
                loadEmailPreview(`/dashboard/email/preview/contattaci/en`);
            });

            document.getElementById('emailRecensioneIt').addEventListener('click', function() {
                const selectedBookingId = bookingSelect.value;
                loadEmailPreview(`/dashboard/email/preview/review-request/it/${selectedBookingId}`);
            });
            document.getElementById('emailRecensioneEn').addEventListener('click', function() {
                const selectedBookingId = bookingSelect.value;
                loadEmailPreview(`/dashboard/email/preview/review-request/en/${selectedBookingId}`);
            });
        });
    </script>
</x-dashboard-layout>
