<div>
    <div class="container">
        <form wire:submit.prevent="submitBookingExcursion">
            <h4 class="text-uppercase text-a"><strong>{{ __('ui.excursionTitle') }}</strong></h4>
            <div class="row">
                <div class="col-12">
                    <select wire:model.live="excursionSelect" wire:change="calculatePriceExcursion" id="excursionSelect"
                        class="form-select form_input_focused">
                        <option selected>{{ __('ui.excursionSelect') }}</option>
                        @foreach ($excursions as $excursion)
                            <option value="{{ $excursion->id }}" data-price="{{ $excursion->price }}">
                                {{ $excursion->{'name_' . app()->getLocale()} }}</option>
                        @endforeach
                    </select>
                    <div class="error-message">
                        @error('excursionSelect')
                            <span class="text-danger error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label for="excursionPassengers" class="form-label">{{ __('ui.passengers') }}</label>
                    <input wire:model.live="excursionPassengers" type="number" class="form-control form_input_focused"
                        id="excursionPassengers" min="1" max="16" value="1">
                    <div class="error-message">
                        @error('excursionPassengers')
                            <span class="text-danger error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label" for="dateExcursion">{{ __('ui.departure') }}</label>
                    <input wire:model.live="excursionDate" type="datetime-local" class="form-control form_input_focused"
                        id="dateExcursion">
                    <div class="error-message">
                        @error('excursionDate')
                            <span class="text-danger error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3 align-items-end">
                <div class="col-6">
                    <label for="excursionPrice" class="form-label">{{ __('ui.totalPrice') }}</label>
                    <input wire:model.live="excursionPrice" readonly type="text"
                        class="form-control form_input_focused" id="excursionPrice" value="">
                </div>
                <div class="col-6 d-grid">
                    <button type="submit" class="btn bg-a text-white">{{ __('ui.submit') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
