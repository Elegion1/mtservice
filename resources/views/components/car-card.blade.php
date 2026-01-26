<label id="labelRent" for="car{{ $car->id }}"
    class="m-0 p-1 border rounded-3 w-100 {{ !$car->isAvailable ? 'bg-secondary-subtle' : '' }} 
    {{ $selected ? 'bg-success-subtle' : '' }}">
    <input wire:model.live="carID" value="{{ $car->id }}" class="form-check-input_custom " type="radio"
        name="flexRadioDefault" id="car{{ $car->id }}" {{ !$car->isAvailable ? 'disabled' : '' }}>
    <div class="row m-0 p-0">
        <div class="col-6 d-flex align-items-center justify-content-center">
            <x-responsive-image loading="lazy" image="{{ $car->images[0]->path }}"
                alt="{{ $car->name }}-{{ $car->description }}" class="img_carRent" />
        </div>

        <div class="col-6">
            <div class="d-flex flex-column justify-content-center h-100">
                <p class="h6 m-0 text-nowrap">{{ $car->name }}</p>
                <p class="m-0"><small>{{ $car->description }}</small></p>

                @if ($car->kasko && $car->isAvailable)
                    <p class="m-0"><strong class="text-uppercase text-a text-small">{{ __('ui.kasko') }} <br>
                            {{ $car->kasko_price }} €/giorno</strong></p>
                @endif
                @if (!$car->isAvailable)
                    <h6 class="text-danger"><strong class="text-uppercase">{{ __('ui.notAvailable') }}</strong></h6>
                @endif
            </div>
        </div>
    </div>
</label>
