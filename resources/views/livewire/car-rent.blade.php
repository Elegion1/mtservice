<div>
    <div class="container-md">
        <form wire:submit.prevent="submitBookingRent">
            <h4 class="text-uppercase text-a"><strong>{{ __('ui.rentTitle') }}</strong></h4>
            <div class="row rounded">
                <div class="col-12 col-md-6 mb-2">
                    <label class="form-label" for="dateStart">{{ __('ui.rentStartDate') }}</label>
                    <input wire:model.live="dateStart" type="datetime-local" class="form-control form_input_focused"
                        id="dateStart">
                </div>
                <div class="col-12 col-md-6 mb-2">
                    <label class="form-label" for="dateEnd">{{ __('ui.rentEndDate') }}</label>
                    <input wire:model.live="dateEnd" type="datetime-local" class="form-control form_input_focused"
                        id="dateEnd">
                </div>
                <div class="col-12 col-md-2 mb-2 d-none">
                    <label class="form-label" for="quantity">{{ __('ui.quantity') }}</label>
                    <input wire:model.live="quantity" type="number" class="form-control form_input_focused"
                        id="quantity" min="1" max="1">
                </div>
            </div>

            <div class="container-md message d-flex flex-column align-items-center justify-content-center mx-auto">
                <x-error-message field='dateStart' />
                <x-error-message field='dateEnd' />
                <x-error-message field='quantity' />
            </div>

            <div class="container-md">
                <p><strong class="text-uppercase">{{ __('ui.rentSelectCar') }}</strong></p>
                <div class="row d-flex justify-content-center">
                    @foreach ($cars as $car)
                        <div
                            class="col-12 col-lg-3 m-2 fixed_height border rounded bg-white @if (!$car->isAvailable) bg-secondary-subtle @endif @if ($carID == $car->id) bg-primary-subtle @endif">
                            <label id="labelRent" for="car{{ $car->id }}"
                                class="form-check m-0 row d-flex justify-content-around align-items-center flex-column flex-nowrap">
                                <input wire:model.live="carID" value="{{ $car->id }}"
                                    class="form-check-input_custom form_input_focused" type="radio"
                                    name="flexRadioDefault" id="car{{ $car->id }}"
                                    @if (!$car->isAvailable) disabled @endif>
                                <div class="p-1 col-12 d-flex justify-content-center align-items-center">
                                    <img class="img_carRent" src="{{ Storage::url($car->images[0]->path) }}"
                                        alt="{{$car->name}}-{{$car->description}}">
                                </div>
                                <div class="p-0 col-12 d-flex flex-column justify-content-center align-items-center">
                                    <p class="h6 m-0 text-nowrap text-a">{{ $car->name }}</p>
                                    <p class="m-0"><small>{{ $car->description }}</small></p>
                                </div>
                                <div class="p-0 col-12 text-center my-auto text-small">
                                    @if ($car->isAvailable)
                                        <p class="m-1">{{ __('ui.priceStartingFrom') }}</p>
                                        <p class="h3 text-d">{{ $car->price }} €</p>
                                    @else
                                        <p class="h6 text-danger"><strong
                                                class="text-uppercase">{{ __('ui.notAvailable') }}</strong></p>
                                    @endif
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            
            <div class="row mb-3 justify-content-center align-items-end">
                <div class="col-4">
                        <label for="rentPrice" class="form-label">{{ __('ui.totalPrice') }}</label>
                        <input wire:model.live="rentPrice" class="form-control form_input_focused" id="rentPrice"
                            type="text" aria-label="readonly input example" readonly>
                    </div>
                    <div class="col-2">
                        <span class="fs-4">€</span>
                    </div>
                    <div class="col-6 d-grid">
                        <button class="btn bg-a text-white mx-1" type="submit">{{ __('ui.submit') }}</button>
                    </div>
                </div>
        
        </form>
    </div>

</div>
