<div>

    <form wire:submit.prevent="submitBookingRent">

        <div class="row">

            @if ($currentStep == 1)
                <h6 class="p-0 mb-3 text-uppercase">{{ ucfirst(__('ui.selectDate')) }}</h6>
                <!-- Data e Ora di Inizio -->
                <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                    <div class="w-custom me-3">
                        <span>{{ __('ui.rentStartDate') }}</span>
                        <input wire:model.live="dateStart" type="date" class="form-control form_input input_size"
                            id="dateStart">
                        <x-error-message field='dateStart' />
                    </div>

                    <div class="w-custom">
                        <span>{{ __('ui.time') }}</span>
                        <input wire:model.live="timeStart" type="time" class="form-control form_input input_size"
                            id="timeStart">
                        <x-error-message field='timeStart' />
                    </div>
                </div>

                <!-- Data e Ora di Fine -->
                <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                    <div class="w-custom me-3">
                        <span>{{ __('ui.rentEndDate') }}</span>
                        <input wire:model.live="dateEnd" type="date" class="form-control form_input input_size"
                            id="dateEnd">
                        <x-error-message field='dateEnd' />
                    </div>

                    <div class="w-custom">
                        <span>{{ __('ui.time') }}</span>
                        <input wire:model.live="timeEnd" type="time" class="form-control form_input input_size"
                            id="timeEnd">
                        <x-error-message field='timeEnd' />
                    </div>
                </div>
                <button wire:click="submitDateSelection" type="button"
                    class="btn col-12 input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.next') }}</button>
            @endif

            @if ($currentStep == 2)
                <!-- Selezione Auto -->
                <span class="p-0 mb-3 text-uppercase">{{ ucfirst(__('ui.rentSelectCar')) }}</span>

                <div class="col-12 p-0 m-0">
                    <div class="row d-flex justify-content-evenly">
                        @foreach ($cars as $car)
                            <div class="col-5 col-sm-4 {{ $loop->iteration > 3 ? 'mb-0' : 'mb-3' }}">
                                <x-car-card :car="$car" :selected="$carID == $car->id" />
                            </div>
                        @endforeach
                    </div>
                    <x-error-message field='carID' />
                </div>

                @if ($dateStart && $timeStart && $dateEnd && $timeEnd)
                    <div
                        class="bg-c text-center fw-light rounded mb-3 d-flex justify-content-evenly align-items-center"
                        id="rentDatesSummary">

                        <!-- Colonna di partenza -->
                        <div class="d-flex flex-column text-primary text-start small">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-calendar-event me-2 fs-6"></i>
                                <span>
                                    {{ \Carbon\Carbon::parse($dateStart)->translatedFormat('l j M Y') }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock me-2 fs-6"></i>
                                <span>{{ $timeStart }}</span>
                            </div>
                        </div>

                        <!-- Freccia al centro -->
                        <div class="mx-3">
                            <i class="bi bi-arrow-right fs-6"></i>
                        </div>

                        <!-- Colonna di arrivo -->
                        <div class="d-flex flex-column text-danger text-start small">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-calendar-check me-2 fs-6"></i>
                                <span>
                                    {{ \Carbon\Carbon::parse($dateEnd)->translatedFormat('l j M Y') }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock me-2 fs-6"></i>
                                <span>{{ $timeEnd }}</span>
                            </div>
                        </div>

                    </div>
                @endif

                <!-- Prezzo Totale -->
                <div class="col-12 mb-3 p-0">
                    <span>{{ __('ui.totalPrice') }}</span>
                    <div class="d-flex justify-content-start align-items-center bg-c rounded px-2">
                        <img src="{{ url('/media/svg/currency-euro.svg') }}" alt="">
                        <input wire:model.live="rentPrice" type="text" class="form-control form_input input_size"
                            id="rentPrice" readonly>
                    </div>
                </div>

                <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                    <button wire:click="goToStep(1)" type="button" onclick="scrollToTop()"
                        class="btn w-custom input_size bg-dark rounded px-2 text-light me-3 text-uppercase">{{ __('ui.back') }}</button>
                    <!-- Pulsante Submit -->
                    <button type="submit"
                        class="btn w-custom input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.submit') }}</button>
                </div>
            @endif
        </div>
    </form>

</div>
