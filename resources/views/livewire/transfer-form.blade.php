<div>

    <form wire:submit.prevent="submitBookingTransfer" id="transferForm">

        <div class="row">
            @if ($currentStep == 1)
                {{-- <h4 class="text-uppercase text-a"><strong>{{ __('ui.transferTitle') }}</strong></h4> --}}
                <div class="col-12 p-0 m-0">

                    <span class="text-capitalize">{{ __('ui.departure') }}</span>

                    <select wire:model.live="departure" id="departureSelect" class="form-select form_input input_size"
                        aria-label="seleziona luogo di partenza">
                        <option value="">{{ __('ui.selectDeparture') }}</option>
                        @foreach ($routes->unique('departure_id') as $route)
                            <option value="{{ $route->departure->id }}">{{ $route->departure->name }}</option>
                        @endforeach
                    </select>

                    <x-error-message field='departure' />

                </div>

                <div class="col-12 p-0 m-0">

                    <span class="text-capitalize">{{ __('ui.destination') }}</span>

                    <select wire:model.live="return" wire:change="calculatePrice" id="returnSelect"
                        class="form-select form_input input_size" aria-label="seleziona luogo di ritorno">
                        <option value="">{{ __('ui.selectDestination') }}</option>
                        @foreach ($routes as $route)
                            @if ($route->departure_id == $departure)
                                <option value="{{ $route->arrival->id }}">{{ $route->arrival->name }}</option>
                            @endif
                        @endforeach
                    </select>

                    <x-error-message field='return' />
                </div>

                <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">

                    <div class="w-custom me-3 ">

                        <span class="text-capitalize">{{ __('ui.outward') }}</span>

                        <input wire:model.live="dateDeparture" type="date" min="{{ date('Y-m-d') }}"
                            class="form-control form_input input_size" id="dateDeparture">

                        <x-error-message field='dateDeparture' />
                    </div>

                    <div class="w-custom">

                        <span>{{ __('ui.time') }}</span>

                        <input wire:model.live="timeDeparture" type="time" min="{{ date('H:i') }}"
                            class="form-control form_input input_size" id="timeDeparture">

                        <x-error-message field='timeDeparture' />
                    </div>

                </div>

                <div
                    class="col-12 p-0 m-0 d-flex justify-content-between align-items-center position-relative {{ $solaAndata ? 'd-none' : '' }}">

                    <button type="button" title="{{ __('ui.oneWay') }}" wire:click="setSolaAndata"
                        class="position-absolute close_position bg-c border border-light rounded-circle text-black p-0 text-center">
                        <i class="bi bi-x-lg"></i></button>

                    <div class="w-custom me-3">
                        <span class="text-capitalize">{{ __('ui.return') }}</span>

                        <input wire:model.live="dateReturn" type="date" min="{{ date('Y-m-d') }}"
                            class="form-control form_input input_size" id="dateReturn">

                        <x-error-message field='dateReturn' />
                    </div>

                    <div class="w-custom">
                        <span>{{ __('ui.time') }}</span>

                        <input wire:model.live="timeReturn" type="time" min="{{ $minReturnTime }}"
                            class="form-control form_input input_size" id="timeReturn">

                        <x-error-message field='timeReturn' />

                    </div>

                </div>

                <button wire:click="setAndataRitorno"
                    class="btn py-2 input_size border border-3 mt-2 mb-3 {{ !$solaAndata ? 'd-none' : '' }}">
                    <small>{{ strtoupper(__('ui.addReturn')) }}</small>
                </button>
                <button wire:click="submitTransferSelection" type="button"
                    class="btn col-12 input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.next') }}</button>
            @endif

            @if ($currentStep == 2)

                @if ($dateDeparture && $timeDeparture && $departureName && $arrivalName)
                    <div class="col-12 m-0 bg-c rounded p-2">
                        <p>
                            <span class="text_col">{{ $departureName ?? 'N/A' }}</span>
                            <span class="mx-2">
                                @if (empty($dateReturn))
                                    <i class="bi bi-arrow-right"></i>
                                @endif
                                @if ($dateReturn)
                                    <i class="bi bi-arrow-left-right"></i>
                                @endif
                            </span>
                            <span class="text_col">{{ $arrivalName ?? 'N/A' }}</span>
                        </p>
                        <p>
                            {{ ucfirst(__('ui.outward')) }}: <span class="text_col">
                                {{ \Carbon\Carbon::parse($dateDeparture)->translatedFormat('d/m/Y') ?? 'N/A' }}
                            </span>
                            {{ __('ui.time') }}: <span class="text_col">
                                {{ \Carbon\Carbon::parse($timeDeparture)->translatedFormat('H:i') ?? 'N/A' }}
                            </span>
                        </p>
                        @if (!empty($dateReturn))
                            <p>
                                {{ ucfirst(__('ui.return')) }}: <span class="text_col">
                                    {{ \Carbon\Carbon::parse($dateReturn)->translatedFormat('d/m/Y') }}
                                </span>
                                {{ __('ui.time') }}: <span class="text_col">
                                    {{ \Carbon\Carbon::parse($timeReturn)->translatedFormat('H:i') }}
                                </span>
                            </p>
                        @endif
                        <p>
                            {{ __('ui.duration') }}: <span class="text_col">{{ $route->duration ?? 'N/A' }}</span>
                            {{ __('ui.minutes') }} {{ __('ui.approx') }}
                        </p>
                    </div>
                @endif

                <div class="col-12 p-0 m-0">
                    <span>{{ __('ui.passengers') }}</span>

                    <div class="d-flex align-items-center justify-content-center">
                        <!-- Bottone per decrementare i passeggeri -->
                        <button wire:click="decrementPassengers" type="button" id="removePassenger"
                            class="btn passenger_button" @if ($transferPassengers == 1) disabled @endif><i
                                class="bi bi-dash-lg"></i></button>

                        <!-- Input per il numero di passeggeri -->
                        <input wire:model.live="transferPassengers" type="number"
                            class="form-control form_input rounded-0 input_size text-center" id="transferPassengers"
                            min="1" max="16" value="1" readonly>

                        <!-- Bottone per incrementare i passeggeri -->
                        <button wire:click="incrementPassengers" type="button" id="addPassenger"
                            class="btn passenger_button" @if ($transferPassengers == 16) disabled @endif><i
                                class="bi bi-plus-lg"></i></button>

                    </div>

                    <x-error-message field='transferPassengers' />
                </div>

                <div class="col-12 mb-3 p-0">
                    <label>{{ __('ui.totalPrice') }}</label>
                    <div class="d-flex justify-content-start align-items-center bg-c rounded px-2">
                        <img src="{{ url('/media/svg/currency-euro.svg') }}" alt="">
                        <input wire:model.live="transferPrice" readonly type="text"
                            class="form-control form_input input_size" id="transferPrice">
                    </div>
                </div>

                <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                    <button wire:click="goToStep(1)" type="button" onclick="scrollToTop()"
                        class="btn w-custom input_size bg-dark rounded px-2 text-light me-3 text-uppercase">{{ __('ui.back') }}</button>
                    <!-- Pulsante Submit -->
                    <button type="submit"
                        class="btn w-custom input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.next') }}</button>
                </div>
            @endif
        </div>
    </form>

</div>
