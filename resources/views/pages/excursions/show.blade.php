<x-layout>
    <div class="container-fluid bg-white rounded p-3">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="container-fluid justify-content-center d-flex flex-column">
                    <h2 class="text-d">{{ $excursion->{'name_' . app()->getLocale()} }}</h2>
                    <small>{{ __('ui.duration') }} {{ $excursion->duration }}
                        @if ($excursion->duration == 1)
                            {{ __('ui.hour') }}
                        @else
                            {{ __('ui.hours') }}
                        @endif
                        {{ __('ui.approx') }}
                    </small>
                    <br>
                    {!! $excursion->{'abstract_' . app()->getLocale()} !!}
                    {!! $excursion->{'description_' . app()->getLocale()} !!}
                    <br>
                    <br>
                    <p>
                        {{ __('ui.priceStartingFrom') }}
                        <strong class="text-d">{{ $excursion->price }} €</strong> <small>{{ __('ui.perPerson') }}</small>
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="container-fluid my-2 my-md-0 justify-content-center align-items-center d-flex">
                    @if ($excursion->images->isNotEmpty())
                        @foreach ($excursion->images as $image)
                            <img src="{{ Storage::url($image->path) }}" class="img-show m-1" alt="...">
                        @endforeach
                    @else
                        <img class="img-show" src="https://picsum.photos/600/400" alt="">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <x-contact-link />
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <h2 class="text-center">{{ __('ui.title2') }}</h2>
            <x-services />
        </div>
        <div class="col-12 mt-5">
            <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
            <x-excursions />
        </div>
    </div>
</x-layout>
