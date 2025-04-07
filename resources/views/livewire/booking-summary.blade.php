<div>
    <div class="row">
        @if ($currentStep == 1)
            <x-booking-summary-title :isRequired="true"> bookingSummaryTitle </x-booking-summary-title>

            <div class="bg-c p-3 pb-0 rounded m-0 ">

                <p class="text-uppercase fw-light">{{ ucfirst(__('ui.' . $bookingData['type'])) }}</p>

                @if ($bookingData['type'] == 'transfer')
                    <p>
                        <span class="text_col">{{ $bookingData['departure_name'] ?? 'N/A' }}</span>
                        <span class="mx-2">
                            @if (empty($bookingData['date_ret']))
                                <i class="bi bi-arrow-right"></i>
                            @endif
                            @if ($bookingData['date_ret'])
                                <i class="bi bi-arrow-left-right"></i>
                            @endif
                        </span>
                        <span class="text_col">{{ $bookingData['arrival_name'] ?? 'N/A' }}</span>
                    </p>
                    <p>
                        {{ ucfirst(__('ui.outward')) }}: <span class="text_col">
                            {{ \Carbon\Carbon::parse($bookingData['date_dep'])->translatedFormat('d/m/Y') ?? 'N/A' }}
                        </span>
                        {{ __('ui.time') }}: <span class="text_col">
                            {{ \Carbon\Carbon::parse($bookingData['date_dep'])->translatedFormat('H:i') ?? 'N/A' }}
                        </span>
                    </p>
                    @if (!empty($bookingData['date_ret']))
                        <p>
                            {{ ucfirst(__('ui.return')) }}: <span class="text_col">
                                {{ \Carbon\Carbon::parse($bookingData['date_ret'])->translatedFormat('d/m/Y') }}
                            </span>
                            {{ __('ui.time') }}: <span class="text_col">
                                {{ \Carbon\Carbon::parse($bookingData['date_ret'])->translatedFormat('H:i') }}
                            </span>
                        </p>
                    @endif
                    <p>{{ __('ui.passengers') }}: <span
                            class="text_col">{{ $bookingData['passengers'] ?? 'N/A' }}</span></p>
                    <p>
                        {{ __('ui.duration') }}: <span class="text_col">{{ $bookingData['duration'] ?? 'N/A' }}</span>
                        {{ __('ui.minutes') }} {{ __('ui.approx') }}
                    </p>
                @elseif ($bookingData['type'] == 'escursione')
                    <p>
                        <span class="text_col">{{ $bookingData['departure_name'] ?? 'N/A' }}</span>
                    </p>
                    <p>
                        {{ __('ui.date') }}: <span class="text_col">
                            {{ \Carbon\Carbon::parse($bookingData['date_dep'])->translatedFormat('d/m/Y') ?? 'N/A' }}
                        </span>

                        {{ __('ui.time') }}: <span class="text_col">
                            {{ \Carbon\Carbon::parse($bookingData['date_dep'])->translatedFormat('H:i') ?? 'N/A' }}
                        </span>
                    </p>
                    <p>{{ __('ui.passengers') }}: <span
                            class="text_col">{{ $bookingData['passengers'] ?? 'N/A' }}</span></p>
                    <p>
                        {{ __('ui.duration') }}: <span class="text_col">{{ $bookingData['duration'] ?? 'N/A' }}</span>
                        {{ __('ui.hours') }} {{ __('ui.approx') }}
                    </p>
                @elseif ($bookingData['type'] == 'noleggio')
                    <p>
                        <span class="text_col">{{ $bookingData['car_name'] ?? 'N/A' }}
                            {{ $bookingData['car_description'] ?? 'N/A' }}</span>
                    </p>
                    <p class="text-capitalize">
                        {{ __('ui.pickup') }}: <span class="text_col">
                            {{ \Carbon\Carbon::parse($bookingData['date_start'])->translatedFormat('d/m/Y') ?? 'N/A' }}
                        </span>
                        {{ __('ui.time') }}: <span class="text_col">
                            {{ \Carbon\Carbon::parse($bookingData['date_start'])->translatedFormat('H:i') ?? 'N/A' }}
                        </span> 
                        <br>
                        <span class="text-capitalize">{{ __('ui.address') }}: {{ $bookingData['pickup'] }}</span>
                    </p>
                    <p class="text-capitalize">
                        {{ __('ui.dropoff') }}: <span class="text_col">
                            {{ \Carbon\Carbon::parse($bookingData['date_end'])->translatedFormat('d/m/Y') ?? 'N/A' }}
                        </span>
                        {{ __('ui.time') }}: <span class="text_col">
                            {{ \Carbon\Carbon::parse($bookingData['date_end'])->translatedFormat('H:i') ?? 'N/A' }}
                        </span>
                        <br>
                        <span class="text-capitalize">{{ __('ui.address') }}: {{ $bookingData['delivery'] }}</span>
                    </p>
                    @if ($bookingData['quantity'] > 1)
                        <p>{{ __('ui.quantity') }}: <span
                                class="text_col">{{ $bookingData['quantity'] ?? 'N/A' }}</span></p>
                    @endif
                @endif

                <p wire:model.live="originalPrice">
                    {{ ucfirst(__('ui.price')) }}:
                    <span class="text_col">€ {{ number_format($originalPrice, 2) }}</span>
                </p>

                @if ($originalPrice != $discountedPrice)
                    <p wire:model.live="discountedPrice">
                        <strong>{!! ucfirst(${'discountType_' . app()->getLocale()}) !!} {{ $discountPercentage }}
                            %</strong>
                    </p>
                    <p>{{ __('ui.totalPrice') }}:
                        <span class="text_col">€ {{ number_format($discountedPrice, 2) }}</span>
                    </p>
                @endif

            </div>

            <p class="fw-light small my-3 ">
                {{ __('ui.seatBoosterMsg') }}
            </p>

            <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                <button wire:click="goBack" type="button" onclick="scrollToTop()"
                    class="btn w-custom input_size bg-dark rounded px-2 text-light me-3 text-uppercase">{{ __('ui.back') }}</button>
                <button type="button" wire:click="goToStep(1.5)" onclick="scrollToTop()"
                    class="btn w-custom input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.next') }}</button>
            </div>
        @endif

        @if ($currentStep == 1.5)
            <x-booking-summary-title :isRequired="true"> flightInfoAndNotes </x-booking-summary-title>
            <!-- Messaggio -->
            <div class="col-12 p-0">
                <x-flight-info />
                <label for="body" class="form-label">{{ __('ui.notesMsg') }} <x-required-field /></label>
                <textarea class="form-control form_input" id="body" wire:model="body"
                    placeholder="{{ __('ui.bookingConfBodyMsg') }}" rows="5"></textarea>
                <x-error-message field='body' />
            </div>
            <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                <button type="button" wire:click="goToStep(1)" onclick="scrollToTop()"
                    class="btn w-custom input_size bg-dark rounded px-2 text-light me-3 text-uppercase">{{ __('ui.back') }}</button>
                <button
                    @if ($bookingData['type'] == 'noleggio') wire:click="goToStep(1.7)"
                    @else 
                        wire:click="submitMessage()" @endif
                    type="button"
                    class="btn w-custom input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.next') }}</button>
            </div>
        @endif

        @if ($currentStep == 1.7)
            <x-booking-summary-title :isRequired="true"> driverInfo </x-booking-summary-title>
            <div class="col-12 p-0">
                <x-driver-info />
            </div>
            <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                <button type="button" wire:click="goToStep(1.5)" onclick="scrollToTop()"
                    class="btn w-custom input_size bg-dark rounded px-2 text-light me-3 text-uppercase">{{ __('ui.back') }}</button>
                <button wire:click="goToStep(1.8)" type="button"
                    class="btn w-custom input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.next') }}</button>
            </div>
        @endif
        @if ($currentStep == 1.8)
            <x-booking-summary-title :isRequired="true"> licenseInfo </x-booking-summary-title>
            <div class="col-12 p-0">
                <x-license-info :bookingData="$bookingData" />
            </div>
            <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                <button type="button" wire:click="goToStep(1.7)" onclick="scrollToTop()"
                    class="btn w-custom input_size bg-dark rounded px-2 text-light me-3 text-uppercase">{{ __('ui.back') }}</button>
                <button wire:click="submitMessage()" type="button"
                    class="btn w-custom input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.next') }}</button>
            </div>
        @endif
    </div>


    @if ($currentStep == 2)

        <form wire:submit.prevent="confirmBooking">
            <div class="row">
                <x-booking-summary-title :isRequired="true"> personalData </x-booking-summary-title>


                <!-- Nome -->
                <div class="col-12 p-0">
                    <label for="name" class="form-label">{{ __('ui.name') }} <x-required-field /></label>
                    <input type="text" class="form-control form_input input_size" id="name"
                        wire:model.live="name" placeholder="Mario">
                    <x-error-message field='name' />
                </div>

                <!-- Cognome -->
                <div class="col-12 p-0">
                    <label for="surname" class="form-label">{{ __('ui.surname') }} <x-required-field /></label>
                    <input type="text" class="form-control form_input input_size" id="surname"
                        wire:model.live="surname" placeholder="Rossi">
                    <x-error-message field='surname' />
                </div>

                <!-- Email -->
                <div class="col-12 p-0">
                    <label for="email" class="form-label">{{ __('ui.email') }} <x-required-field /></label>
                    <input type="email" class="form-control form_input input_size" id="email"
                        wire:model.live="email" placeholder="mario.rossi@mail.com">
                    <x-error-message field='email' />
                </div>

                <!-- Telefono -->
                <div class="col-12 p-0">
                    <label for="phone" class="form-label d-flex align-items-center">
                        {{ __('ui.phone') }} <x-required-field />
                        <img width="30px" class="ms-2 border" src="{{ $dialFlag['png'] }}"
                            alt="{{ $dialFlag['alt'] }}">
                    </label>
                    <div class="d-flex justify-content-between">
                        <div class="me-3 w-custom">
                            <select class="form-select form_input input_size" id="dialCode"
                                wire:model.live="dialCode">
                                @foreach ($dialCodes as $dial)
                                    @if ($dial['code'] > 0)
                                        <option value="{{ $dial['code'] }}">{{ $dial['name'] }} {{ $dial['code'] }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <x-error-message field='dialCode' />
                        </div>
                        <div class="w-custom">
                            <input type="text" class="form-control form_input input_size" id="phone"
                                wire:model.live="phone" minlength="8" maxlength="15" placeholder="3491234567">
                            <x-error-message field='phone' />
                        </div>
                    </div>

                </div>

                <div class="col-12 d-flex align-items-center justify-content-between p-0">
                    <!-- Privacy Policy and Terms and Conditions Checkbox -->
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="accept_policy"
                            wire:model="accept_policy">
                        <label for="accept_policy" class="form-check-label small">
                            {{ __('ui.acceptPolicy') }}
                            <a href="{{ route('privacy') }}#privacy"
                                target="_blank">{{ __('ui.privacyPolicy') }}</a> {{ __('ui.and') }}
                            <a href="{{ route('privacy') }}#terms"
                                target="_blank">{{ __('ui.termsConditions') }}</a>.
                            <x-required-field />
                        </label>
                        <x-error-message field='accept_policy' />
                    </div>
                </div>

                @if ($originalPrice != $discountedPrice)
                    <p wire:model.live="discountedPrice">
                        <strong>{!! ucfirst(${'discountType_' . app()->getLocale()}) !!} {{ $discountPercentage }}
                            %</strong>
                    </p>
                    <p>{{ __('ui.totalPrice') }}:
                        <span class="text_col">€ {{ number_format($discountedPrice, 2) }}</span>
                    </p>
                @endif

                <div class="container text-center p-0">
                    <p class="fs-6 text-d small">{{ __('ui.paymentMessage') }}</p>
                </div>

                <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                    {{-- Pulsante Back --}}
                    <button
                        @if ($bookingData['type'] == 'noleggio') wire:click="goToStep(1.8)" 
                        @else 
                            wire:click="goToStep(1.5)" @endif
                        type="button" onclick="scrollToTop()"
                        class="btn w-custom input_size bg-dark rounded px-2 text-light me-3 text-uppercase">{{ __('ui.back') }}</button>
                    <!-- Pulsante Submit -->
                    <button type="submit"
                        class="btn w-custom input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.submit') }}</button>
                </div>
                <div class="loader-wrapper" wire:loading wire:target="confirmBooking">
                    <span class="loader"></span>
                </div>

            </div>
        </form>

    @endif

</div>
