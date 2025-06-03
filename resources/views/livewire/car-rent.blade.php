<div>

    <form wire:submit.prevent="submitBookingRent">

        <div class="row">
            @php
                $priceSummaryItems = [
                    [
                        'label' => __('ui.rentPrice'),
                        'icon' => '/media/svg/currency-euro.svg',
                        'value' => $carRentPrice ?? 0,
                    ],
                ];
                if ($kaskoEnabled && isset($selectedCar) && $selectedCar->kasko) {
                    $priceSummaryItems[] = [
                        'label' => __('ui.kaskoPrice'),
                        'icon' => '/media/svg/currency-euro.svg',
                        'value' => $kaskoPrice ?? 0,
                    ];
                }
                $priceSummaryItems[] = [
                    'label' => __('ui.handOffCost'),
                    'icon' => '/media/svg/currency-euro.svg',
                    'value' => $handOffCost ?? 0,
                ];
                // $currentStep = 2;
            @endphp

            @if ($currentStep == 1)
                <div class="d-flex justify-content-between align-items-center p-0">
                    <h6 class="p-0 mb-3 text-uppercase">{{ ucfirst(__('ui.selectDate')) }}</h6>
                    @if ($minimumDays > 0)
                        <span class="mb-3 text-danger small">{{ __('ui.minimumRent') }}: {{ $minimumDays }}
                            {{ __('ui.days') }}
                        </span>
                    @endif
                </div>

                <!-- Data e Ora di Inizio -->
                <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                    <div class="w-custom me-3">
                        <span>{{ __('ui.rentStartDate') }}</span>
                        <input wire:model.live="dateStart" type="date" placeholder="gg/mm/aaaa"
                            min="{{ $startDateMin }}" class="form-control form_input input_size" id="dateStart">
                        <x-error-message field='dateStart' />
                    </div>

                    <div class="w-custom">
                        <span>{{ __('ui.time') }}</span>
                        <input wire:model.live="timeStart" type="time" placeholder="hh:mm" min="{{ $startTimeMin }}"
                            step="900" class="form-control form_input input_size" id="timeStart">
                        <x-error-message field='timeStart' />
                    </div>
                </div>

                <!-- Data e Ora di Fine -->
                <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                    <div class="w-custom me-3">
                        <span>{{ __('ui.rentEndDate') }}</span>
                        <input wire:model.live="dateEnd" type="date" placeholder="gg/mm/aaaa"
                            min="{{ $endDateMin }}" class="form-control form_input input_size" id="dateEnd">
                        <x-error-message field='dateEnd' />
                    </div>

                    <div class="w-custom">
                        <span>{{ __('ui.time') }}</span>
                        <input wire:model.live="timeEnd" type="time" placeholder="hh:mm" min="{{ $endTimeMin }}"
                            step="900" class="form-control form_input input_size" id="timeEnd">
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
                            @if ($car->show)
                                <div class="col-5 col-sm-4 {{ $loop->iteration > 3 ? 'mb-0' : 'mb-3' }}">
                                    <x-car-card :car="$car" :selected="$carID == $car->id" />
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <x-error-message field='carID' />
                </div>

                @if ($dateStart && $timeStart && $dateEnd && $timeEnd)
                    <div class="bg-c text-center fw-light rounded mb-3 d-flex justify-content-evenly align-items-center"
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


                @if (isset($selectedCar) && $selectedCar->kasko)
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="kaskoEnabled"
                            wire:model.live="kaskoEnabled">
                        <label class="form-check-label" for="kaskoEnabled">
                            {{ __('ui.enableKasko') }}
                        </label>
                    </div>
                @endif


                <x-price-summary :totalPrice="$rentPrice" :items="$priceSummaryItems" />

                <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                    <button wire:click="goToStep(1)" type="button" onclick="scrollToTop()"
                        class="btn w-custom input_size bg-dark rounded px-2 text-light me-3 text-uppercase">{{ __('ui.back') }}</button>

                    <button wire:click="goToStep(3)" type="button" onclick="scrollToTop()"
                        class="btn w-custom input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.next') }}</button>
                </div>
            @endif

            @if ($currentStep == 3)
                @php
                    $locations = ['pickup', 'delivery'];
                @endphp

                @foreach ($locations as $key)
                    <div class="col-12 p-0 m-0">

                        <label for="{{ $key }}Location">{{ __('ui.' . $key . 'Location') }}</label>

                        <select wire:model.live="{{ $key }}Location" id="{{ $key }}Location"
                            class="form-select form_input input_size"
                            aria-label="{{ __('ui.select') . ' ' . strtolower(__('ui.' . $key . 'Location')) }}">
                            <option value="">
                                {{ __('ui.select') . ' ' . strtolower(__('ui.' . $key . 'Location')) }}</option>
                            @foreach ($handOffOptions as $optionKey => $option)
                                @if ($optionKey == 'airport')
                                    @foreach ($option as $city => $details)
                                        <option value="{{ $optionKey }}_{{ $city }}">
                                            {{ ucfirst(__('ui.airport')) }} - {{ ucfirst(__($city)) }}
                                        </option>
                                    @endforeach
                                @elseif ($optionKey == 'custom_address')
                                    <option value="{{ $optionKey }}">{{ ucfirst(__('ui.customAddress')) }}</option>
                                @else
                                    <option value="{{ $optionKey }}">{{ ucfirst(__('ui.' . $optionKey)) }}</option>
                                @endif
                            @endforeach
                        </select>

                        <x-error-message field="{{ $key }}Location" />

                    </div>

                    @if ($this->{$key . 'Location'} == 'custom_address')
                        <div class="col-12 p-0 m-0">
                            <label for="{{ $key }}CustomAddress"
                                class="text-capitalize">{{ __('ui.customAddress') }}</label>
                            <input type="text" wire:model.live="{{ $key }}CustomAddress"
                                class="form-control form_input input_size" id="{{ $key }}CustomAddress"
                                placeholder="{{ ucfirst(__('ui.insertAddress')) }}">

                            <!-- Mostra i suggerimenti corretti -->
                            @foreach ($this->{$key . 'correctedCustomAddress'} as $item)
                                <input type="text"
                                    wire:click="selectAddress('{{ $key }}', '{{ $item }}')"
                                    class="shadow p-1 formattedCustomAddress"
                                    id="{{ $key }}formattedCustomAddress" value="{{ $item }}"
                                    placeholder="{{ __('ui.insertAddress') }}" readonly>
                            @endforeach

                            <x-error-message field="{{ $key }}CustomAddress" />
                        </div>
                    @endif
                @endforeach


                {{-- @php
                    $variables = [
                        'pickupLocation' => $pickupLocation,
                        'pickupCustomAddress' => $pickupCustomAddress,
                        'deliveryLocation' => $deliveryLocation,
                        'deliveryCustomAddress' => $deliveryCustomAddress,
                        'pickupCost' => $pickupCost,
                        'deliveryCost' => $deliveryCost,
                        'handOffCost' => $handOffCost,
                        'pickuphandOffDistance' => $pickuphandOffDistance,
                        'delivaryhandOffDistance' => $deliveryhandOffDistance,
                        'rentPrice' => $rentPrice,
                        'totalPrice' => $totalPrice,
                    ];
                @endphp

                @foreach ($variables as $key => $variable)
                    <p>{{ $key }}: {{ $variable }}</p>
                @endforeach --}}

                <x-price-summary :totalPrice="$rentPrice" :items="$priceSummaryItems" />

                <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                    <button wire:click="goToStep(2)" type="button" onclick="scrollToTop()"
                        class="btn w-custom input_size bg-dark rounded px-2 text-light me-3 text-uppercase">{{ __('ui.back') }}</button>
                    <!-- Pulsante Submit -->
                    <button type="submit"
                        class="btn w-custom input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.next') }}</button>
                </div>
            @endif
        </div>
    </form>

</div>
