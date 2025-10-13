<div>

    <form wire:submit.prevent="submitBookingExcursion" id="excursionForm">

        <div class="row">
            <div class="col-12 p-0 m-0">
                <span>{{ __('ui.excursionTitle') }}</span>
                <select wire:model.live="excursionSelect" wire:change="calculatePriceExcursion" id="excursionSelect"
                    class="form-select form_input input_size" aria-label="seleziona escursione">
                    <option value="">{{ __('ui.excursionSelect') }}</option>
                    @foreach ($excursions as $excursion)
                        <option value="{{ $excursion->id }}" data-price="{{ $excursion->price }}">
                            {{ $excursion->{'name_' . app()->getLocale()} }}</option>
                    @endforeach
                </select>
                <x-error-message field='excursionSelect' />
            </div>

            <div class="col-12 p-0 m-0 d-flex justify-content-between align-items-center">
                <div class="w-custom me-3">
                    <span>{{ __('ui.date') }}</span>
                    <input wire:model.live="excursionDate" type="date" placeholder="gg/mm/aaaa"
                        min="{{ date('Y-m-d') }}" class="form-control form_input input_size" id="dateExcursion">
                    <x-error-message field='excursionDate' />
                </div>

                <div class="w-custom">
                    <span>{{ __('ui.time') }}</span>
                    <input wire:model.live="excursionTime" type="time" placeholder="hh:mm"
                        class="form-control form_input input_size" id="timeExcursion">
                    <x-error-message field='excursionTime' />
                </div>
            </div>

            <div class="col-12 p-0 m-0">
                <span>{{ __('ui.passengers') }}</span>

                <div class="d-flex align-items-center justify-content-center">
                    <!-- Bottone per decrementare i passeggeri -->
                    <button wire:click="updatePassengers(-1)" type="button" id="removePassenger"
                        class="btn passenger_button" @if ($excursionPassengers == 1) disabled @endif><i
                            class="bi bi-dash-lg"></i></button>

                    <!-- Input per il numero di passeggeri -->
                    <input wire:model.live="excursionPassengers" type="number"
                        class="form-control form_input rounded-0 input_size text-center" id="excursionPassengers"
                        min="1" max="16" value="1" readonly>

                    <!-- Bottone per incrementare i passeggeri -->
                    <button wire:click="updatePassengers(1)" type="button" id="addPassenger"
                        class="btn passenger_button " @if ($excursionPassengers == 16) disabled @endif><i
                            class="bi bi-plus-lg"></i></button>

                </div>

                <x-error-message field='excursionPassengers' />
            </div>

            <div class="col-12 mb-3 p-0">
                <label>{{ __('ui.totalPrice') }}</label>
                <div class="d-flex justify-content-between align-items-center bg-c rounded px-2">
                    <span class="fw-semibold">€</span>
                    <input wire:model.live="excursionPrice" readonly type="text"
                        class="form-control form_input input_size" id="excursionPrice">
                </div>
            </div>

            <button type="submit"
                class="btn col-12 input_size bg-dark rounded px-2 text-light text-uppercase">{{ __('ui.next') }}</button>
        </div>
    </form>

</div>
