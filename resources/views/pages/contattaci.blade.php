<x-layout>
    <x-seo-data :seoTitle="$seoTitle" :seoDescription="$seoDescription" />
    <div class="container rounded p-3 mt-md-3">
        <x-show-content :pagine="$pagine" />
        <h2>{{ ucfirst(__('ui.contactUs')) }} </h2>

        @if ($errors->has('recaptcha'))
            <div class="alert alert-danger">
                {{ $errors->first('recaptcha') }}
            </div>
        @endif

        <form id="contact-form" action="{{ route('inviaForm') }}" method="POST">
            @csrf

            <!-- CAMPO HIDDEN PER IL TOKEN RECAPTCHA -->
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

            <!-- HONEYPOT ANTI-SPAMBOT (Invisibile per gli utenti) -->
            <div style="display:none;" aria-hidden="true">
                <input type="text" name="website_hp" id="website_hp" tabindex="-1" autocomplete="off">
            </div>

            <div class="row">
                <div class="col-6 col-md-4 mb-3">
                    <label for="nome">{{ ucfirst(__('ui.name')) }}:</label>
                    <input type="text" class="form-control form_input_focused" id="nome" name="nome"
                        placeholder="Mario" value="{{ old('nome') }}" required>
                </div>
                <div class="col-6 col-md-4 mb-3">
                    <label for="cognome">{{ ucfirst(__('ui.surname')) }}:</label>
                    <input type="text" class="form-control form_input_focused" id="cognome" name="cognome"
                        placeholder="Rossi" value="{{ old('cognome') }}" required>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control form_input_focused" id="email" name="email"
                        placeholder="mario.rossi@mail.com" value="{{ old('email') }}" required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="telefono">{{ ucfirst(__('ui.phone')) }}:</label>
                    <input type="tel" class="form-control form_input_focused" id="telefono" name="telefono"
                        placeholder="3471234567" value="{{ old('telefono') }}" required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="servizio">{{ __('ui.typeOfService') }}:</label>
                    <select class="form-control form_input_focused" id="servizio" name="servizio" required>
                        <option class="text-lowercase" value="">{{ __('ui.select') }}
                            {{ strtolower(__('ui.typeOfService')) }}</option>
                        <option value="transfer" {{ old('servizio') == 'transfer' ? 'selected' : '' }}>
                            {{ ucfirst(strtolower(__('ui.transfer'))) }}</option>
                        <option value="escursione" {{ old('servizio') == 'escursione' ? 'selected' : '' }}>
                            {{ ucfirst(strtolower(__('ui.excursions'))) }}</option>
                        <option value="noleggio auto" {{ old('servizio') == 'noleggio auto' ? 'selected' : '' }}>
                            {{ ucfirst(strtolower(__('ui.carRent'))) }}</option>
                        <option value="altro" {{ old('servizio') == 'altro' ? 'selected' : '' }}>
                            {{ ucfirst(strtolower(__('ui.other'))) }}</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label for="messaggio">{{ __('ui.message') }}:</label>
                    <textarea class="form-control form_input_focused" id="messaggio" name="messaggio"
                        placeholder="{{ __('ui.contactBody') }}" rows="5" required>{{ old('messaggio') }}</textarea>
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
                <button id="submit-btn" aria-label="Contattaci" type="submit"
                    class="btn bg-a text-white">{{ __('ui.send') }}</button>
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

    <!-- SCRIPT GOOGLE RECAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('contact-form');
            var submitBtn = document.getElementById('submit-btn');
            var isSubmitting = false;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                var now = new Date().toISOString();
                console.log('[' + now + '] [FRONTEND] Submit intercettato JS');

                if (isSubmitting) {
                    console.warn('[' + now +
                        '] [FRONTEND] ATTENZIONE: Inviato bloccato da flag isSubmitting!');
                    return false;
                }

                isSubmitting = true;
                submitBtn.disabled = true;

                grecaptcha.ready(function() {
                    console.log('[' + new Date().toISOString() +
                        '] [FRONTEND] Executing reCAPTCHA...');
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
                            action: 'contact_form'
                        })
                        .then(function(token) {
                            console.log('[' + new Date().toISOString() +
                                '] [FRONTEND] Token ottenuto. Invocazione form.submit()');
                            document.getElementById('g-recaptcha-response').value = token;
                            form.submit();
                        })
                        .catch(function(error) {
                            console.error('[' + new Date().toISOString() +
                                '] [FRONTEND] Errore reCAPTCHA:', error);
                            isSubmitting = false;
                            submitBtn.disabled = false;
                        });
                });
            });
        });
    </script>
</x-layout>
