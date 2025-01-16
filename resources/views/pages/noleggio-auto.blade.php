<x-layout>
    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="container bg-white rounded p-3">
                <x-show-content :pagine="$pagine" />
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <h3 class="text-center mt-5">{{ __('ui.carsDisplay') }}</h3>
            <div class="d-flex justify-content-center align-items-center mt-3 flex-wrap">
                @foreach ($cars as $car)
                    <div class="card me-lg-5 p-1 mt-3 bg-b shadow-sm" style="width: 13rem;">
                        <img src="{{ Storage::url($car->images[0]->path) }}" class="img_carRent"
                            alt="{{ $car->name }}">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $car->name }}</h5>
                            <p class="card-text">{{ $car->description }}</p>
                            <p class="card-text text-wrap"><strong>{{ __('ui.priceStartingFrom') }}:</strong> <br>
                                {{ $car->price }} € {{ __('ui.perDay') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="container my-5">
                <x-contact-link />
            </div>
            <div class="container my-5">
                <h2 class="text-center">{{ __('ui.title2') }}</h2>
                <x-services />
            </div>
        </div>
        <div class="col-12 mt-5">
            <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
            <x-excursions />
        </div>
    </div>

</x-layout>
