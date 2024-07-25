<x-layout>
    <div class="container bg-white shadow rounded p-3 border_custom">
        <x-show-content :pagine="$pagine" />
        <h2>{{ucfirst(__('ui.contactUs'))}} </h2>
        <form action="{{ route('inviaForm') }}" method="POST">
            @csrf <!-- Includi il token CSRF qui -->
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <label for="nome">{{ucfirst(__('ui.name'))}}:</label>
                    <input type="text" class="form-control form_input_focused" id="nome" name="nome" required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="cognome">{{ucfirst(__('ui.surname'))}}:</label>
                    <input type="text" class="form-control form_input_focused" id="cognome" name="cognome"
                        required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control form_input_focused" id="email" name="email"
                        required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="telefono">{{ucfirst(__('ui.phone'))}}:</label>
                    <input type="tel" class="form-control form_input_focused" id="telefono" name="telefono"
                        required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="servizio">{{__('ui.typeOfService')}}:</label>
                    <select class="form-control form_input_focused" id="servizio" name="servizio" required>
                        <option value="transfer">{{ucfirst(__('ui.transfer'))}}</option>
                        <option value="escursione">{{ucfirst(__('ui.excursion'))}}</option>
                        <option value="noleggio auto">{{ucfirst(__('ui.carRent'))}}</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label for="messaggio">Messaggio:</label>
                    <textarea class="form-control form_input_focused" id="messaggio" name="messaggio" rows="5" required></textarea>
                </div>
            </div>
            <button type="submit" class="btn bg-a text-white">Invia</button>
        </form>
    </div>

</x-layout>
