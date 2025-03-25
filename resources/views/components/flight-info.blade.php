<div class="row mb-3">

    <!-- Numero volo -->
    <div class="col-12">
        <label for="flight_number" class="form-label">{{ __('ui.flight_number') }}</label>
        <input type="text" class="form-control form_input" id="flight_number" wire:model="flightNumber"
            placeholder="{{ __('ui.flight_number_placeholder') }}">
    </div>

    <!-- Aeroporto di partenza -->
    <div class="col-6">
        <label for="departure_airport" class="form-label">{{ __('ui.departure_airport') }}</label>
        <input type="text" class="form-control form_input" id="departure_airport" wire:model="departureAirport"
            placeholder="{{ __('ui.departure_airport_placeholder') }}">
    </div>
    <!-- Orario di partenza -->
    <div class="col-6">
        <label for="departure_time" class="form-label">{{ __('ui.departure_time') }}</label>
        <input type="time" placeholder="hh:mm" class="form-control form_input" id="departure_time" wire:model="departureTime"
            placeholder="{{ __('ui.departure_time_placeholder') }}">
    </div>



    <!-- Aeroporto di arrivo -->
    <div class="col-6">
        <label for="arrival_airport" class="form-label">{{ __('ui.arrival_airport') }}</label>
        <input type="text" class="form-control form_input" id="arrival_airport" wire:model="arrivalAirport"
            placeholder="{{ __('ui.arrival_airport_placeholder') }}">
    </div>

    <!-- Orario di arrivo -->
    <div class="col-6">
        <label for="arrival_time" class="form-label">{{ __('ui.arrival_time') }}</label>
        <input type="time" placeholder="hh:mm" class="form-control form_input" id="arrival_time" wire:model="arrivalTime"
            placeholder="{{ __('ui.arrival_time_placeholder') }}">
    </div>

</div>
