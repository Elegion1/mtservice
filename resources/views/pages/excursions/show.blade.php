<x-layout>
    <div class="container-fluid bg-white rounded p-3">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="container-fluid justify-content-center d-flex flex-column">
                    <p class="h2">{{ $excursion->{'name_' . app()->getLocale()} }}</p>
                    <p>
                        <small class="text-black">{{ __('ui.priceStartingFrom') }}</small>
                        <strong>{{ $excursion->price }} €</strong> <small>{{__('ui.perPerson')}}</small>
                    </p>

                    <small>{{ __('ui.duration') }} {{ $excursion->duration }}
                        @if ($excursion->duration == 1)
                            {{ __('ui.hour') }}
                        @else
                            {{ __('ui.hours') }}
                        @endif
                        {{ __('ui.approx') }}
                    </small>
                    {!! $excursion->{'abstract_' . app()->getLocale()} !!}
                    {!! $excursion->{'description_' . app()->getLocale()} !!}

                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="container-fluid my-2 my-md-0 justify-content-center align-items-center d-flex">
                    @if ($excursion->images->isNotEmpty())
                        @foreach ($excursion->images as $image)
                            <img src="{{ Storage::url($image->path) }}" class="rounded shadow m-1" alt="...">
                        @endforeach
                    @else
                        <img class="rounded shadow" src="https://picsum.photos/600/400" alt="">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <x-contact-link />
    </div>
</x-layout>
