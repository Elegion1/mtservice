<div>
    <div class="container ">
        <form wire:submit.prevent="submitBookingTransfer" id="transferForm">
            <h4 class="text-uppercase text-a"><strong>{{ __('ui.transferTitle') }}</strong></h4>

            <div class="row">
                <div class="col-12">
                    <select wire:model.live="departure" id="departureSelect" class="form-select form_input_focused"
                        aria-label="Default select example">
                        <option selected>{{ __('ui.selectDeparture') }}</option>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination->id }}">
                                {{ $destination->name }}</option>
                        @endforeach
                    </select>
                    <x-error-message :field='$departure' />
                </div>
                <div class="col-12">
                    <select wire:model.live="return" wire:change="calculatePrice" id="returnSelect"
                        class="form-select form_input_focused" aria-label="Default select example">
                        <option selected>{{ __('ui.selectDestination') }}</option>
                        @foreach ($routes as $route)
                            @if ($route->departure->id == $departure)
                                <option value="{{ $route->arrival->id }}">{{ $route->arrival->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <x-error-message :field='$return' />
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label for="transferPassengers" class="form-label">{{ __('ui.passengers') }}</label>
                    <input wire:model.live="transferPassengers" type="number" class="form-control form_input_focused"
                        id="transferPassengers" min="1" max="16" value="1">
                        <x-error-message :field='$transferPassengers' />
                </div>
                <div class="col-6 d-flex flex-column align-items-start justify-content-end ">
                    <div class="form-check">
                        <input wire:click="setSolaAndata" class="form-check-input form_input_focused" type="radio"
                            name="tripType" id="solaAndata" {{ $solaAndata ? 'checked' : '' }}>
                        <label class="form-check-label" for="solaAndata">
                            {{ __('ui.oneWay') }}
                        </label>
                    </div>
                    <div class="form-check">
                        <input wire:click="setAndataRitorno" class="form-check-input form_input_focused" type="radio"
                            name="tripType" id="andataRitorno" {{ $andataRitorno ? 'checked' : '' }}>
                        <label class="form-check-label" for="andataRitorno">
                            {{ __('ui.returnTrip') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="dateDeparture">{{ __('ui.departure') }}</label>
                    <input wire:model.live="dateDeparture" type="datetime-local" class="form-control form_input_focused"
                        id="dateDeparture">
                        <x-error-message :field='$dateDeparture' />
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="dateReturn">{{ __('ui.return') }}</label>
                    <input wire:model.live="dateReturn" type="datetime-local" class="form-control form_input_focused"
                        id="dateReturn" {{ $solaAndata ? 'disabled' : '' }}>
                        <x-error-message :field='$dateReturn' />
                </div>
            </div>
            <div class="row mb-3 align-items-end">
                <div class="col-4">
                    <label for="transferPrice" class="form-label">{{ __('ui.totalPrice') }}</label>
                    <input wire:model.live="transferPrice" readonly type="text"
                        class="form-control form_input_focused" id="transferPrice" value="€">
                </div>
                <div class="col-2 ms-0 ps-0 d-flex justify-content-start align-items-center">
                    <span class="fs-4">€</span>
                </div>
                <div class="col-6 d-grid">
                    <button type="submit" class=" btn bg-a text-white">{{ __('ui.submit') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
