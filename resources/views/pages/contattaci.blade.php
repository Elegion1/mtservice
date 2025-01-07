<x-layout>
    <div class="container bg-white rounded p-3 ">
        <x-show-content :pagine="$pagine" />
        <h2>{{ ucfirst(__('ui.contactUs')) }} </h2>
        <form action="{{ route('inviaForm') }}" method="POST">
            @csrf <!-- Includi il token CSRF qui -->
            <div class="row">
                <div class="col-6 col-md-4 mb-3">
                    <label for="nome">{{ ucfirst(__('ui.name')) }}:</label>
                    <input type="text" class="form-control form_input_focused" id="nome" name="nome" placeholder="Mario" required>
                </div>
                <div class="col-6 col-md-4 mb-3">
                    <label for="cognome">{{ ucfirst(__('ui.surname')) }}:</label>
                    <input type="text" class="form-control form_input_focused" id="cognome" name="cognome" placeholder="Rossi"
                        required>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control form_input_focused" id="email" name="email" placeholder="mario.rossi@mail.com"
                        required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="telefono">{{ ucfirst(__('ui.phone')) }}:</label>
                    <input type="tel" class="form-control form_input_focused" id="telefono" name="telefono" placeholder="3471234567"
                        required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="servizio">{{ __('ui.typeOfService') }}:</label>
                    <select class="form-control form_input_focused" id="servizio" name="servizio" required>
                        <option value="transfer">{{ ucfirst(strtolower(__('ui.transfer'))) }}</option>
                        <option value="escursione">{{ ucfirst(strtolower(__('ui.excursions'))) }}</option>
                        <option value="noleggio auto">{{ ucfirst(strtolower(__('ui.carRent'))) }}</option>
                        <option value="altro">{{ ucfirst(strtolower(__('ui.other'))) }}</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label for="messaggio">{{ __('ui.message') }}:</label>
                    <textarea class="form-control form_input_focused" id="messaggio" name="messaggio" placeholder="{{__('ui.contactBody')}}" rows="5" required></textarea>
                </div>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="privacy_policy" required>
                <label for="privacy_policy" class="form-check-label">{{ __('ui.acceptPrivacy') }} <a
                        href="{{ route('privacy') }}#privacy" target="_blank">{{ __('ui.privacyPolicy') }}</a></label>
            </div>

            <!-- Terms and Conditions Checkbox -->
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="terms_conditions" required>
                <label for="terms_conditions" class="form-check-label">{{ __('ui.acceptTerms') }} <a
                        href="{{ route('privacy') }}#terms" target="_blank">{{ __('ui.termsConditions') }}</a></label>
            </div>

            <div class="container d-flex justify-content-center align-items-center">
                <button type="submit" class="btn bg-a text-white">{{ __('ui.send') }}</button>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <h2 class="text-center">{{ __('ui.title2') }}</h2>
            <x-services />
        </div>
        <div class="col-12 mt-5">
            <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
            <x-excursions />
        </div>
    </div>

</x-layout>
