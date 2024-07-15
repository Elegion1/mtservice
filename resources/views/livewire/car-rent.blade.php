<div>
    <div class="container">
        <form wire:submit.prevent="submitBookingRent">
            <h4 class="text-uppercase text-a"><strong>{{__('ui.rentTitle')}}</strong></h4>
            <div class="row bg-b p-2 rounded">
                <div class="col-12 col-md-5">
                    <label class="form-label" for="dateStart">{{__('ui.rentStartDate')}}</label>
                    <input wire:model.live="dateStart" type="date" class="form-control form_input_focused" id="dateStart">
                    {{-- <div class="error-message">
                                @error('dateStart')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div> --}}
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label" for="dateEnd">{{__('ui.rentEndDate')}}</label>
                    <input wire:model.live="dateEnd" type="date" class="form-control form_input_focused" id="dateEnd">
                    {{-- <div class="error-message">
                                @error('dateEnd')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div> --}}
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label" for="quantity">{{__('ui.quantity')}}</label>
                    <input wire:model.live="quantity" type="number" class="form-control form_input_focused" id="quantity" min="1"
                        max="1">
                    {{-- <div class="error-message">
                                @error('quantity')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div> --}}
                </div>
                {{-- <div class="col-12 d-flex justify-content-center align-items-center flex-wrap flex-column">
                            <div class="error-message">
                                @error('dateStart')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="error-message">
                                @error('dateEnd')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="error-message">
                                @error('quantity')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}
            </div>

            <div class="container-fluid message d-flex flex-column align-items-center justify-content-center mx-auto my-2">
                <div class="error-message">
                    @error('dateStart')
                        <span class="text-danger error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="error-message">
                    @error('dateEnd')
                        <span class="text-danger error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="error-message">
                    @error('quantity')
                        <span class="text-danger error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="container p-md-3">
                <p><strong class="text-uppercase">{{__('ui.rentSelectCar')}}</strong></p>
                @foreach ($cars as $car)
                    <div
                        class="form-check border rounded mb-3 @if (!$car->isAvailable) bg-c @endif row d-flex justify-content-between align-items-center">
                        <div class="col-1">
                            <input wire:model.live="carID" value="{{ $car->id }}" class="form-check-input form_input_focused"
                                type="radio" name="flexRadioDefault" id="car{{ $car->id }}"
                                @if (!$car->isAvailable) disabled @endif>
                        </div>
                        <div class="col-3 p-1 my-auto">
                            <img width="80px" src="{{ Storage::url($car->images[0]->path) }}" alt="">
                        </div>
                        <div class="col-4 text-center text-md-start d-flex flex-column">
                            <p class="m-1 h6">{{ $car->name }}</p>
                            <p><small>{{ $car->description }}</small></p>
                        </div>
                        <div class="col-4 d-flex align-items-end justify-content-center flex-column">
                            @if ($car->isAvailable)
                                <p class="m-1">{{__('ui.priceStartingFrom')}}</p>
                                <p class="h3">{{ $car->price }} €</p>
                            @else
                                <p class="h6 text-danger"><strong class="text-uppercase">{{__('ui.notAvailable')}}</strong></p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="container-fluid">
                <p><strong class="text-uppercase">{{__('ui.totalPrice')}}</strong></p>
                <div class="d-flex justify-content-between mb-3">
                    <input wire:model.live="rentPrice" class="form-control form_input_focused mx-1" type="text"
                        aria-label="readonly input example" readonly>
                    <button class="btn bg-a text-white mx-1" type="submit">{{__('ui.submit')}}</button>
                </div>
            </div>
        </form>
    </div>

</div>
