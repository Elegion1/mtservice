<x-layout>
    <div class="container-fluid p-3">
        <div class="row">
            <div class="col-12 col-md-6">
                <div
                    class="container-fluid my-2 my-md-0 justify-content-center align-items-center d-flex d-block d-md-none">
                    @if ($excursion->images->isNotEmpty())
                        @foreach ($excursion->images as $image)
                            <x-responsive-image loading="lazy" image="{{ $image->path }}"
                                alt="img_{{ $excursion->{'slug_' . app()->getLocale()} }}" class="img-show m-1" />
                        @endforeach
                    @else
                        <x-responsive-image loading="lazy" image="https://picsum.photos/600/400" alt="placeholder"
                            class="img-show m-1" />
                    @endif
                </div>
                <div class="container-fluid justify-content-center d-flex flex-column">
                    <h2 class="text-d text-wrap">{{ __('ui.excursionAt') }} {!! $excursion->{'name_' . app()->getLocale()} !!}</h2>
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
                        <strong class="text-d">{{ $excursion->price }} €</strong>
                        <small>{{ __('ui.perPerson') }}</small>
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

                    <p>{!! __('ui.usefulLinks') !!}</p>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2 my-md-0 d-none d-md-block">
                    <div class="d-flex justify-content-center align-items-center">
                        @if ($excursion->images->isNotEmpty())
                            @foreach ($excursion->images as $image)
                                <x-responsive-image loading="lazy" image="{{ $image->path }}"
                                    alt="img_{{ $excursion->{'slug_' . app()->getLocale()} }}" class="img-show m-1" />
                            @endforeach
                        @else
                            <x-responsive-image loading="lazy" image="https://picsum.photos/600/400" alt="placeholder"
                                class="img-show m-1" />
                        @endif
                    </div>
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
