<div id="serviceCarousel" class="carousel service slide p-3" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach ($services as $index => $service)
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $service->id }}"
                class=" bg-secondary {{ $index === 0 ? 'active' : '' }}" aria-current="true"
                aria-label="Slide {{ $service->id }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @foreach ($services as $index => $service)
            <div class="carousel-item service {{ $index === 0 ? 'active' : '' }}">
                <a class="text-reset text-decoration-none" href="{{ route('service.show', ['id' => $service->id]) }}">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 my-3 d-flex justify-content-center align-items-center">
                                @if ($service->images->isNotEmpty())
                                    <div id="carouselImages{{ $service->id }}" class="carousel slide"
                                        data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach ($service->images as $imgIndex => $image)
                                                <div class="carousel-item {{ $imgIndex === 0 ? 'active' : '' }}">
                                                    <img src="{{ Storage::url($image->path) }}" class="d-block w-100"
                                                        alt="...">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carouselImages{{ $service->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carouselImages{{ $service->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                @else
                                    <img class="service-img rounded shadow"
                                        src="https://picsum.photos/40{{ $service->id }}" alt="">
                                @endif
                            </div>
                            <div
                                class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                                <p class="h3">{{ $service->{'title_' . app()->getLocale()} }}</p>
                                <p class="h6">{{ $service->{'subtitle_' . app()->getLocale()} }}</p>
                                <p class=" text-wrap ">{{ $service->{'abstract_' . app()->getLocale()} }}</p>
                                @if ($service->links)
                                    <a class="small" target="__blank" href="{{ $service->links }}">{{__('ui.clickLink')}}</a>
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
