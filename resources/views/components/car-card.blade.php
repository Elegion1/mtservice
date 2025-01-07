<label id="labelRent" for="car{{ $car->id }}"
    class="fixed_height fixed_width m-0 p-0 d-flex justify-content-around align-items-center flex-column flex-nowrap border rounded-3 {{ !$car->isAvailable ? 'bg-secondary-subtle' : '' }} 
    {{ $selected ? 'bg-success-subtle' : '' }}">
    <input wire:model.live="carID" value="{{ $car->id }}" class="form-check-input_custom" type="radio"
        name="flexRadioDefault" id="car{{ $car->id }}" {{ !$car->isAvailable ? 'disabled' : '' }}>

    <img class="img_carRent" src="{{ Storage::url($car->images[0]->path) }}"
        alt="{{ $car->name }}-{{ $car->description }}">

    <p class="h6 m-0 text-nowrap">{{ $car->name }}</p>
    <p class="m-0"><small>{{ $car->description }}</small></p>

    <div class="p-0 text-center my-auto text-small">
        @if (!$car->isAvailable)
            <h6 class="text-danger"><strong class="text-uppercase">{{ __('ui.notAvailable') }}</strong></h6>
        @endif
    </div>
</label>
