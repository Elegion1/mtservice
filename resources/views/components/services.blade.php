<div id="serviceCarousel" class="carousel service slide p-3" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach ($services as $index => $service)
            <button type="button" data-bs-target="#serviceCarousel" data-bs-slide-to="{{ $index }}"
                class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @foreach ($services as $index => $service)
            <div class="carousel-item service {{ $index === 0 ? 'active' : '' }}">
                <a class="text-reset text-decoration-none" href="{{ route('service.show', ['title_it' => $service->title_it, 'id' => $service->id]) }}">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 my-3 d-flex justify-content-center align-items-center">
                                @if ($service->images->isNotEmpty())
                                    @foreach ($service->images as $image)
                                        <img src="{{ Storage::url($image->path) }}" class="service-img" alt="img_{{$service->title_en}}">
                                    @endforeach
                                @else
                                    <img class="service-img " src="https://picsum.photos/1920/108{{$service->id}}" alt="placeholder">
                                @endif
                            </div>
                            <div
                                class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                                <p class="h3 text-d">{!! $service->{'title_' . app()->getLocale()} !!}</p>
                                <p class="h6">{!! $service->{'subtitle_' . app()->getLocale()} !!}</p>
                                <p class=" text-wrap ">{!! $service->{'abstract_' . app()->getLocale()} !!}</p>
                                @if ($service->links)
                                    <a class="small" target="__blank"
                                        href="{{ $service->links }}">{{ __('ui.clickLink') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
    <div class="indicators_space"></div>
</div>
