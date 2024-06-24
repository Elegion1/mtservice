<x-layout>

    <h2>Form di Contatto</h2>
    <form action="{{ route('inviaForm') }}" method="POST">
        @csrf <!-- Includi il token CSRF qui -->
        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="cognome">Cognome:</label>
                <input type="text" class="form-control" id="cognome" name="cognome" required>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="telefono">Telefono:</label>
                <input type="tel" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="servizio">Tipo di Servizio:</label>
                <select class="form-control" id="servizio" name="servizio" required>
                    <option value="transfer">Transfer</option>
                    <option value="escursione">Escursione</option>
                    <option value="noleggio auto">Noleggio Auto</option>
                </select>
            </div>
            <div class="col-12 mb-3">
                <label for="messaggio">Messaggio:</label>
                <textarea class="form-control" id="messaggio" name="messaggio" rows="5" required></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Invia</button>
    </form>

</x-layout>
