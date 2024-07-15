<div>
    <div class="container">
        <div class="container-fluid m-0 p-0">
            <h1>{{__('ui.bookingSummaryTitle')}}</h1>

            @if ($bookingData['type'] == 'transfer')
                <p>{{__('ui.bookingType')}}: <span class="text_col">{{ ucfirst($bookingData['type']) }}</span></p>
                <p>{{__('ui.from')}}: <span class="text_col">{{ $bookingData['departure_name'] ?? 'N/A' }}</span> {{__('ui.to')}}: <span
                        class="text_col">{{ $bookingData['arrival_name'] ?? 'N/A' }}</span></p>
                <p>{{__('ui.date')}}: <span class="text_col">{{ $bookingData['date_departure'] ?? 'N/A' }}</span> {{__('ui.time')}}: <span
                        class="text_col">{{ $bookingData['time_departure'] ?? 'N/A' }}</span></p>
                <p>{{__('ui.duration')}}: <span class="text_col">{{ $bookingData['duration'] ?? 'N/A' }}</span> {{__('ui.minutes')}} {{__('ui.approx')}}</p>
                @if (!empty($bookingData['date_ret']))
                    <p>{{__('ui.return')}}: <span class="text_col">{{ $bookingData['date_return'] }}</span> {{__('ui.time')}} <span
                            class="text_col">{{ $bookingData['time_return'] }}</span></p>
                @endif
                <p>{{_('ui.passengers')}}: <span class="text_col">{{ $bookingData['passengers'] ?? 'N/A' }}</span></p>
                <p>{{ucfirst(__('ui.price'))}} {{ucfirst(__('ui.totalPrice'))}}: <span class="text_col">{{ $bookingData['price'] ?? 'N/A' }}</span> €</p>
            @elseif ($bookingData['type'] == 'escursione')
                <p>{{__('ui.bookingType')}}: <span class="text_col">{{ ucfirst($bookingData['type']) }}</span> a <span
                        class="text_col">{{ $bookingData['departure_name'] ?? 'N/A' }}</span></p>
                <p>{{__('ui.date')}}: <span class="text_col">{{ $bookingData['date_departure'] ?? 'N/A' }}</span></p>
                <p>{{__('ui.time')}}: <span class="text_col">{{ $bookingData['time_departure'] ?? 'N/A' }}</span></p>
                <p>{{__('ui.duration')}}: <span class="text_col">{{ $bookingData['duration'] ?? 'N/A' }}</span></p>
                <p>{{__('ui.passengers')}}: <span class="text_col">{{ $bookingData['passengers'] ?? 'N/A' }}</span></p>
                <p>{{ucfirst(__('ui.price'))}} {{ucfirst(__('ui.totalPrice'))}}: <span class="text_col">{{ $bookingData['price'] ?? 'N/A' }} €</span></p>
            @elseif ($bookingData['type'] == 'noleggio')
                <p>{{__('ui.bookingType')}}: <span class="text_col">{{ ucfirst($bookingData['type']) }}</span> <span
                        class="text_col">{{ $bookingData['car_name'] ?? 'N/A' }}
                        {{ $bookingData['car_description'] ?? 'N/A' }}</span></p>
                <p>{{__('ui.collectionDate')}}: <span class="text_col">{{ $bookingData['date_start'] ?? 'N/A' }}</span> {{__('ui.returnDate')}}: <span class="text_col">{{ $bookingData['date_end'] ?? 'N/A' }}</span></p>
                <p>{{__('ui.quantity')}}: <span class="text_col">{{ $bookingData['quantity'] ?? 'N/A' }}</span> {{ucfirst(__('ui.price'))}} {{ucfirst(__('ui.totalPrice'))}}:
                    <span class="text_col">{{ $bookingData['price'] ?? 'N/A' }} €</span>
                </p>
            @endif
            <p class="text-a">
                @foreach ($contents as $content)
                    @if ($content->title_it == 'messaggio seggiolini')
                        {{ $content->{'subtitle_' . app()->getLocale()} }}
                    @endif
                @endforeach
            </p>
        </div>
        <form wire:submit.prevent="confirmBooking">
            <h6 class="text-black">{{__('ui.personalData')}}</h6>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{__('ui.name')}}</label>
                        <input type="text" class="form-control form_input_focused" id="name" wire:model="name">
                        <div class="error-message">
                            @error('name')
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="surname" class="form-label">{{__('ui.surname')}}</label>
                        <input type="text" class="form-control form_input_focused" id="surname"
                            wire:model="surname">
                        <div class="error-message">
                            @error('surname')
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control form_input_focused" id="email" wire:model="email">
                        <div class="error-message">
                            @error('email')
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">{{__('ui.phone')}}</label>
                        <input type="text" class="form-control form_input_focused" id="phone" wire:model="phone"
                            minlength="8" maxlength="15">
                        <div class="error-message">
                            @error('phone')
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <textarea class="form-control form_input_focused" id="body" wire:model="body" placeholder="Inserisci delle note"></textarea>
                <div class="error-message">
                    @error('body')
                        <span class="text-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

             <!-- Privacy Policy Checkbox -->
             <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="privacy_policy" wire:model="privacy_policy">
                <label for="privacy_policy" class="form-check-label">{{__('ui.acceptPrivacy')}} <a href="{{route('privacy')}}#privacy" target="_blank">{{__('ui.privacyPolicy')}}</a></label>
                <div class="error-message">
                    @error('privacy_policy')
                        <span class="text-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Terms and Conditions Checkbox -->
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="terms_conditions" wire:model="terms_conditions">
                <label for="terms_conditions" class="form-check-label">{{__('ui.acceptTerms')}} <a href="{{route('privacy')}}#terms" target="_blank">{{__('ui.termsConditions')}}</a></label>
                <div class="error-message">
                    @error('terms_conditions')
                        <span class="text-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="container-fluid m-0 p-0 d-flex justify-content-center align-items-center">
                <button type="submit" class=" btn bg-a text-white" wire:loading.attr="disabled">{{__('ui.confirmBooking')}}</button>
            </div>
        </form>

        <!-- Messaggio di caricamento -->
        <div wire:loading wire:target="confirmBooking text-center">
            <p class="h3 text-success">{{__('ui.loading')}}</p>
        </div>
    </div>
</div>
