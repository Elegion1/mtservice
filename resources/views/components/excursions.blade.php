<div id="excursionCarousel" class="carousel excursion slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach ($excursions as $index => $excursion)
            <button type="button" data-bs-target="#excursionCarousel" data-bs-slide-to="{{ $index }}"
                class="bg-secondary {{ $index === 0 ? 'active' : '' }}" aria-current="true"
                aria-label="Slide {{ $index }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @foreach ($excursions as $index => $excursion)
            <div class="carousel-item excursion {{ $index === 0 ? 'active' : '' }}">
                <div class="container">
                    <div class="row">
                        <div
                            class="col-12 col-md-6 d-flex justify-content-center justify-content-md-end align-items-center">
                            @if ($excursion->images->isNotEmpty())
                                @foreach ($excursion->images->first() as $image)
                                    <img src="{{ Storage::url($image->path) }}" class="excursion-img rounded shadow"
                                        alt="...">
                                @endforeach
                            @else
                                <img class="excursion-img rounded shadow"
                                    src="https://picsum.photos/40{{ $excursion->id }}" alt="">
                            @endif
                        </div>
                        <div class="col-12 col-md-6 d-flex justify-content-center flex-column mt-3">
                            <h3>{{ $excursion->{'name_' . app()->getLocale()} }}</h3>
                            <small>{{ __('ui.duration') }} {{ $excursion->duration }} {{ __('ui.hours') }}
                                {{ __('ui.approx') }}</small>
                            <p>{!! $excursion->{'description_' . app()->getLocale()} !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="indicators_space"></div>
    {{-- <button class="carousel-control-prev" type="button" data-bs-target="#excursionCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#excursionCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button> --}}
</div>
