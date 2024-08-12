<x-layout>
    <div class="container-fluid bg-white rounded p-3">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="container-fluid my-2 my-md-0 justify-content-center align-items-center d-flex d-block d-md-none">
                    @if ($excursion->images->isNotEmpty())
                        @foreach ($excursion->images as $image)
                            <img src="{{ Storage::url($image->path) }}" class="img-show m-1" alt="...">
                        @endforeach
                    @else
                        <img class="img-show" src="https://picsum.photos/600/400" alt="">
                    @endif
                </div>
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
                    <p>
                        {{ __('ui.priceStartingFrom') }}
                        <strong class="text-d">{{ $excursion->price }} €</strong> <small>{{ __('ui.perPerson') }}</small>
                    </p>
                    <br>
                    {!! $excursion->{'abstract_' . app()->getLocale()} !!}
                    <br>
                    <br>
                    {!! $excursion->{'description_' . app()->getLocale()} !!}
                    @php
                        $duration = $excursion->duration;
                    @endphp
                    <p>{!! __('ui.excursionNotes', ['duration' => $duration]) !!}</p>
                    <br>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="container-fluid my-2 my-md-0 justify-content-center align-items-center d-flex d-none d-md-block">
                    @if ($excursion->images->isNotEmpty())
                        @foreach ($excursion->images as $image)
                            <img src="{{ Storage::url($image->path) }}" class="img-show m-1" alt="...">
                        @endforeach
                    @else
                        <img class="img-show" src="https://picsum.photos/600/400" alt="">
                    @endif
                </div>
                <div class="container-fluid">
                    <x-contact-link />
                </div>
            </div>
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
    </div>
</x-layout>
